echo "Etez-vous sur de vouloir installer Jeedom ? Attention : ceci ecrasera la configuration par défaut de nginx s'il elle existe !"
while true
do
        echo -n "oui/non: "
        read ANSWER < /dev/tty
        case $ANSWER in
                oui)
                        break
                        ;;
                non)
                         echo "Annulation de l'installation"
                        exit 1
                        ;;
        esac
        echo "Répondez oui ou non"
done

echo "********************************************************"
echo "*             Installation des dépendances             *"
echo "********************************************************"
sudo apt-get update
sudo apt-get install -y ntp
sudo apt-get install -y unzip
sudo apt-get install -y nginx-common nginx-full
sudo apt-get install -y mysql-client mysql-common mysql-server mysql-server-core-5.5
echo "Quel mot de passe venez vous de taper (mot de passe root de la MySql) ?"
while true
do
        read MySQL_root < /dev/tty
        echo "Confirmez vous que le mot de passe est : "$MySQL_root
        while true
        do
            echo -n "oui/non: "
            read ANSWER < /dev/tty
            case $ANSWER in
			oui)
				break
				;;
			non)
				break
				;;
            esac
            echo "Répondez oui ou non"
        done    
        if [ $ANSWER == "oui" ]
        then
            break
        fi
done

sudo apt-get install -y nodejs
nodeJS=$?
sudo apt-get install -y php5-common php5-fpm php5-cli php5-curl php5-json php5-mysql
sudo apt-get install -y usb-modeswitch python-serial


echo "********************************************************"
echo "* Création des répertoire et mise en place des droits  *"
echo "********************************************************"
sudo mkdir -p /usr/share/nginx/www
cd /usr/share/nginx/www
chown www-data:www-data -R /usr/share/nginx/www

echo "********************************************************"
echo "*             Copie des fichiers de Jeedom             *"
echo "********************************************************"
if [ -d "jeedom" ] ; then
    rm -rf jeedom
fi
wget -O jeedom.zip http://market.jeedom.fr/jeedom/stable/jeedom.zip
if [  $? -ne 0 ] ; then
    wget -O jeedom.zip http://market.jeedom.fr/jeedom/stable/jeedom.zip
    if [  $? -ne 0 ] ; then
        echo "Impossible de télécharger le fichier";
        exit 0
    fi
fi
unzip jeedom.zip -d jeedom
sudo mkdir /usr/share/nginx/www/jeedom/tmp
sudo chmod 775 -R /usr/share/nginx/www
sudo chown -R www-data:www-data /usr/share/nginx/www
rm -rf jeedom.zip
cd jeedom

if [ ${nodeJS} -ne 0 ] ; then
    echo "********************************************************"
    echo "*          Installation de nodeJS manuellement         *"
    echo "********************************************************"
    sudo tar xJvf /usr/share/nginx/www/jeedom/install/node-v0.10.21-wheezy-armhf.tar.xz -C /usr/local --strip-components 1
    if [ ! -f '/usr/bin/nodejs' ] && [ -f '/usr/local/bin/node' ]; then
        sudo ln -s /usr/local/bin/node /usr/bin/nodejs
    fi
fi

echo "********************************************************"
echo "*          Configuration de la base de données         *"
echo "********************************************************"
bdd_password=$(cat /dev/urandom | tr -cd 'a-f0-9' | head -c 15)
echo "DROP USER 'jeedom'@'localhost'" | mysql -uroot -p${MySQL_root}
echo "CREATE USER 'jeedom'@'localhost' IDENTIFIED BY '${bdd_password}';" | mysql -uroot -p${MySQL_root}
echo "DROP DATABASE IF EXISTS jeedom;" | mysql -uroot -p${MySQL_root}
echo "CREATE DATABASE jeedom;" | mysql -uroot -p${MySQL_root}
echo "GRANT ALL PRIVILEGES ON jeedom.* TO 'jeedom'@'localhost';" | mysql -uroot -p${MySQL_root}


echo "********************************************************"
echo "*                Installation de Jeedom                *"
echo "********************************************************"
sudo cp core/config/common.config.sample.php core/config/common.config.php
sudo sed -i -e "s/#PASSWORD#/${bdd_password}/g" core/config/common.config.php 
sudo chown www-data:www-data core/config/common.config.php
sudo php install/install.php mode=force


echo "********************************************************"
echo "*                Mise en place du cron                 *"
echo "********************************************************"

croncmd="su --shell=/bin/bash - www-data -c '/usr/bin/php /usr/share/nginx/www/jeedom/core/php/jeeCron.php' >> /dev/null"
cronjob="* * * * * $croncmd"
( crontab -l | grep -v "$croncmd" ; echo "$cronjob" ) | crontab -


echo "********************************************************"
echo "*                Configuration de nginx                *"
echo "********************************************************"
sudo service nginx stop
if [ -f '/etc/nginx/sites-available/defaults' ]; then
    sudo rm /etc/nginx/sites-available/default
fi
sudo cp install/nginx_default /etc/nginx/sites-available/default
if [ ! -f '/etc/nginx/sites-enabled/default' ]; then
    sudo ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default
fi
sudo service nginx restart
sudo adduser www-data dialout
sudo sed -i 's/max_execution_time = 30/max_execution_time = 300/g' /etc/php5/fpm/php.ini
sudo service php5-fpm restart

echo "********************************************************"
echo "*             Mise en place service nodeJS             *"
echo "********************************************************"
sudo cp jeedom /etc/init.d/
sudo chmod +x /etc/init.d/jeedom
sudo update-rc.d jeedom defaults


echo "********************************************************"
echo "*             Démarrage du service nodeJS              *"
echo "********************************************************"
sudo service jeedom start

echo "********************************************************"
echo "*                 Installation finie                   *"
echo "********************************************************"
IP=$(ifconfig eth0 | grep 'inet adr:' | cut -d: -f2 | awk '{print $1}')
echo "Vous pouvez vous connecter sur jeedom en allant sur $IP/jeedom et en utilisant les identifiants admin/admin"