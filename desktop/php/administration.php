<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJS('ldapEnable', config::byKey('ldap::enable'));
?>
<div id="div_administration">
    <ul class="nav nav-tabs" id="admin_tab">
        <li class="active"><a href="#config">{{Configuration}}</a></li>
        <li><a href="#user">{{Utilisateurs}}</a></li>
        <li><a href="#backup">{{Sauvegardes}}</a></li>
    </ul>


    <div class="tab-content">
        <!--********************Onglet mise à jour********************************-->
        <div class="tab-pane" id="backup">
            <div class="row">
                <div class="col-sm-6">
                    <legend>{{Sauvegardes}}</legend>
                    <form class="form-horizontal">
                        <fieldset>
                            <div class="form-group">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{Fréquence des sauvegardes}}</label>
                                    <div class="col-sm-3">
                                        <input type="text"  class="configKey form-control" data-l1key="backup::cron" />
                                    </div>
                                    <div class="col-sm-1">
                                        <i class="fa fa-question-circle cursor bt_pageHelp" data-name='cronSyntaxe'></i>
                                    </div>
                                </div>
                                <label class="col-sm-4 control-label">{{Sauvegardes}}</label>
                                <div class="col-sm-4">
                                    <a class="btn btn-default" id="bt_backupJeedom"><i class="fa fa-refresh fa-spin" style="display : none;"></i> <i class="fa fa-floppy-o"></i> Sauvegarder</a>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">{{Emplacement des sauvegardes}}</label>
                                <div class="col-sm-4">
                                    <input type="text" class="configKey form-control" data-l1key="backup::path" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">{{Nombre de jour(s) de mémorisation des sauvegardes}}</label>
                                <div class="col-sm-4">
                                    <input type="text" class="configKey form-control" data-l1key="backup::keepDays" />
                                </div>
                            </div>
                            <?php if (config::byKey('market::apikey') != '') { ?>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{Envoyer les sauvegardes dans le cloud}}</label>
                                    <div class="col-sm-4">
                                        <input type="checkbox" class="configKey" data-l1key="backup::cloudUpload" />
                                    </div>
                                </div>
                            <?php } ?>
                        </fieldset>
                    </form>
                    <legend>{{Sauvegardes locales}}</legend>
                    <form class="form-horizontal">
                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">{{Sauvegardes disponibles}}</label>
                                <div class="col-sm-4">
                                    <select class="form-control" id="sel_restoreBackup">

                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">{{Restaurer la sauvegarde}}</label>
                                <div class="col-sm-4">
                                    <a class="btn btn-warning" id="bt_restoreJeedom"><i class="fa fa-refresh fa-spin" style="display : none;"></i> <i class="fa fa-file"></i> {{Restaurer}}</a>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">{{Supprimer la sauvegarde}}</label>
                                <div class="col-sm-4">
                                    <a class="btn btn-danger" id="bt_removeBackup"><i class="fa fa-trash-o"></i> {{Supprimer}}</a>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">{{Télécharger la sauvegarde}}</label>
                                <div class="col-sm-4">
                                    <a class="btn btn-success" id="bt_downloadBackup"><i class="fa fa-cloud-download"></i> {{Télécharger}}</a>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                    <?php if (config::byKey('market::apikey') != '') { ?>
                        <legend>{{Sauvegardes cloud}}</legend>
                        <form class="form-horizontal">
                            <fieldset>
                                <?php
                                try {
                                    $listeCloudBackup = market::listeBackup();
                                } catch (Exception $e) {
                                    echo '<div class="alert alert-danger">' . $e->getMessage() . '</div>';
                                }
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{Sauvegardes disponibles}}</label>
                                    <div class="col-sm-4">
                                        <select class="form-control" id="sel_restoreCloudBackup">
                                            <?php
                                            try {
                                                foreach ($listeCloudBackup as $backup) {
                                                    echo '<option>' . $backup . '</option>';
                                                }
                                            } catch (Exception $e) {
                                                
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{Restaurer la sauvegarde}}</label>
                                    <div class="col-sm-4">
                                        <a class="btn btn-warning" id="bt_restoreCloudJeedom"><i class="fa fa-refresh fa-spin" style="display : none;"></i> <i class="fa fa-file"></i> {{Restaurer}}</a>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    <?php } ?>
                </div>

                <div class="col-sm-6">
                    <legend>{{Informations sauvegardes}}</legend>
                    <pre id="pre_backupInfo"></pre>
                    <legend>{{Informations restaurations}}</legend>
                    <pre id="pre_restoreInfo">

                    </pre>
                </div>
            </div>

            <div class="form-actions" style="height: 20px;">
                <a class="btn btn-success" id="bt_saveBackup"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
            </div>
        </div>

        <!--********************Onglet utilisateur********************************-->
        <div class="tab-pane" id="user">
            <legend>{{Liste des utilisateurs :}}</legend>
            <?php if (config::byKey('ldap::enable') != '1') { ?>
                <a class="btn btn-success pull-right" id="bt_addUser"><i class="fa fa-plus-circle"></i> {{Ajouter un utilisateur}}</a><br/><br/>
            <?php } ?>
            <table class="table table-condensed table-bordered" id="table_user">
                <thead><th>{{Nom d'utilisateur}}</th><th>{{Actions}}</th><th>{{Droits}}</th></thead>
                <tbody></tbody>
            </table>
            <div class="form-actions" style="height: 20px;">
                <a class="btn btn-success" id="bt_saveUser"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
            </div>
        </div>

        <!--********************Onglet configuration********************************-->
        <div class="tab-pane active" id="config">
            <div class="panel-group" id="accordionConfiguration">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#config_generale">
                                {{Configuration générale}}
                            </a>
                        </h3>
                    </div>
                    <div id="config_generale" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <form class="form-horizontal">
                                <fieldset>
                                    <div class="form-group expertModeVisible">
                                        <label class="col-sm-2 control-label">{{Clef api}}</label>
                                        <div class="col-sm-2"> 
                                            <p class="form-control-static" id="in_keyAPI"><?php echo config::byKey('api'); ?></p>
                                        </div>
                                        <div class="col-sm-1"> 
                                            <a class="btn btn-default form-control" id="bt_genKeyAPI">{{Générer}}</a>
                                        </div>
                                        <div class="alert-info col-sm-6" style="padding: 10px;">
                                            {{Activation du cron : ajouter <em>* * * * * su --shell=/bin/bash - www-data -c "/usr/bin/php #PATH_TO_JEEDOM#/jeedom/core/php/jeeCron.php" >> /dev/null 2>&1</em> à la crontab}}
                                        </div>
                                    </div>
                                    <div class="form-group expertModeVisible">
                                        <label class="col-sm-2 control-label">{{Clef nodeJS}}</label>
                                        <div class="col-sm-2"> 
                                            <p class="form-control-static" id="in_nodeJsKey"><?php echo config::byKey('nodeJsKey'); ?></p>
                                        </div>
                                        <div class="col-sm-1"> 
                                            <a class="btn btn-default form-control" id="bt_nodeJsKey" >{{Générer}}</a>
                                        </div>
                                    </div>
                                    <div class="form-group expertModeVisible">
                                        <label class="col-sm-2 control-label">{{Hardware key}}</label>
                                        <div class="col-sm-2"> 
                                            <p class="form-control-static"><?php echo jeedom::getHardwareKey(); ?></p>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{Email admin}}</label>
                                        <div class="col-sm-3">
                                            <input type="text"  class="configKey form-control" data-l1key="emailAdmin" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{Langue}}</label>
                                        <div class="col-sm-2">
                                            <select class="configKey form-control" data-l1key="language">
                                                <option value="fr_FR">{{Français}}</option>
                                                <option value="en_US">{{Anglais}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>

                    </div>
                </div>
                <div class="panel panel-default expertModeVisible">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#config_memcache">
                                {{Configuration cache}}
                            </a>
                        </h3>
                    </div>
                    <div id="config_memcache" class="panel-collapse collapse">
                        <div class="panel-body">
                            <form class="form-horizontal">
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{Durée de vie memcache (secondes)}}</label>
                                        <div class="col-sm-3">
                                            <input type="text"  class="configKey form-control" data-l1key="lifetimeMemCache" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{Vider toutes les données en cache}}</label>
                                        <div class="col-sm-3">
                                            <a class="btn btn-warning" id="bt_flushMemcache">{{Vider}}</a>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{Cron persistance du cache}}</label>
                                        <div class="col-sm-3">
                                            <input type="text"  class="configKey form-control" data-l1key="persist::cron" />
                                        </div>
                                        <div class="col-sm-1">
                                            <i class="fa fa-question-circle cursor bt_pageHelp" data-name='cronSyntaxe'></i>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#config_history">
                                {{Configuration historique}}
                            </a>
                        </h3>
                    </div>
                    <div id="config_history" class="panel-collapse collapse">
                        <div class="panel-body">
                            <form class="form-horizontal">
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{Période de calcul pour min, max, moyenne (en heure)}}</label>
                                        <div class="col-sm-3">
                                            <input type="text"  class="configKey form-control" data-l1key="historyCalculPeriod" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{Période de calcul pour la tendance (en heure)}}</label>
                                        <div class="col-sm-3">
                                            <input type="text"  class="configKey form-control" data-l1key="historyCalculTendance" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{Délai avant archivage (heure)}}</label>
                                        <div class="col-sm-3">
                                            <input type="text"  class="configKey form-control" data-l1key="historyArchiveTime" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{Archiver par paquet de (heure)}}</label>
                                        <div class="col-sm-3">
                                            <input type="text"  class="configKey form-control" data-l1key="historyArchivePackage" />
                                        </div>
                                    </div>
                                    <div class="form-group alert alert-danger">
                                        <label class="col-sm-2 control-label">{{Seuil de calcul de tendance}}</label>
                                        <label class="col-sm-1 control-label">{{Min}}</label>
                                        <div class="col-sm-1">
                                            <input type="text"  class="configKey form-control" data-l1key="historyCalculTendanceThresholddMin" />
                                        </div>
                                        <label class="col-sm-1 control-label">{{Max}}</label>
                                        <div class="col-sm-1">
                                            <input type="text"  class="configKey form-control" data-l1key="historyCalculTendanceThresholddMax" />
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="panel panel-default expertModeVisible">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#configuration_cron">
                                {{Configuration crontask, scripts & deamons}}
                            </a>
                        </h3>
                    </div>
                    <div id="configuration_cron" class="panel-collapse collapse">
                        <div class="panel-body">
                            <form class="form-horizontal">
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{Rattrapage maximum autorisé (min, -1 pour infini)}}</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="configKey form-control" data-l1key="maxCatchAllow"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{Crontask : temps exécution max (min)}}</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="configKey form-control" data-l1key="maxExecTimeCrontask"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{Script : temps exécution max (min)}}</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="configKey form-control" data-l1key="maxExecTimeScript"/>
                                        </div>
                                    </div>
                                    <div class="form-group alert alert-danger">
                                        <label class="col-sm-2 control-label">{{Jeecron sleep time}}</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="configKey form-control" data-l1key="cronSleepTime"/>
                                        </div>
                                    </div>
                                    <div class="form-group alert alert-danger">
                                        <label class="col-sm-2 control-label">{{Deamons sleep time}}</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="configKey form-control" data-l1key="deamonsSleepTime"/>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default expertModeVisible">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#configuration_logMessage">
                                {{Configuration des logs & messages}}
                            </a>
                        </h3>
                    </div>
                    <div id="configuration_logMessage" class="panel-collapse collapse">
                        <div class="panel-body">
                            <form class="form-horizontal">
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{Ajouter un message à chaque erreur dans les logs}}</label>
                                        <div class="col-sm-1">
                                            <input type="checkbox" class="configKey" data-l1key="addMessageForErrorLog" checked/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{Nombre de lignes maximum dans un fichier de log}}</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="configKey form-control" data-l1key="maxLineLog"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{Logs actifs}}</label>
                                        <div class="col-sm-2">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" class="configKey" data-l1key="logLevel" data-l2key="debug" checked /> Debug
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" class="configKey" data-l1key="logLevel" data-l2key="info" checked /> Info
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" class="configKey" data-l1key="logLevel" data-l2key="event" checked /> Event
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" class="configKey" data-l1key="logLevel" data-l2key="error" checked /> Error
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default expertModeVisible">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#config_ldap">
                                {{Configuration LDAP}}
                            </a>
                        </h3>
                    </div>
                    <div id="config_ldap" class="panel-collapse collapse">
                        <div class="panel-body">
                            <form class="form-horizontal">
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{Activer l'authentification LDAP}}</label>
                                        <div class="col-sm-1">
                                            <input type="checkbox" class="configKey" data-l1key="ldap:enable"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{Hôte}}</label>
                                        <div class="col-sm-3">
                                            <input type="text"  class="configKey" data-l1key="ldap:host" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{Port}}</label>
                                        <div class="col-sm-3">
                                            <input type="text"  class="configKey form-control" data-l1key="ldap:port" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{Domaine}}</label>
                                        <div class="col-sm-3">
                                            <input type="text"  class="configKey form-control" data-l1key="ldap:domain" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{Base DN}}</label>
                                        <div class="col-sm-3">
                                            <input type="text"  class="configKey form-control" data-l1key="ldap:basedn" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{Nom d'utilisateur}}</label>
                                        <div class="col-sm-3">
                                            <input type="text"  class="configKey form-control" data-l1key="ldap:username" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{Mot de passe}}</label>
                                        <div class="col-sm-3">
                                            <input type="text"  class="configKey form-control" data-l1key="ldap:password" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{Filtre (optionnel)}}</label>
                                        <div class="col-sm-3">
                                            <input type="text"  class="configKey form-control" data-l1key="ldap:filter" />
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <a class='btn btn-default' id='bt_testLdapConnection'>{{Tester la connexion}}</a>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#configuration_convertColor">
                                {{Conversion des couleurs en html}}
                            </a>
                        </h3>
                    </div>
                    <div id="configuration_convertColor" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <i class="fa fa-plus-circle pull-right" id="bt_addColorConvert" style="font-size: 1.8em;"></i>
                                    <table class="table table-condensed table-bordered" id="table_convertColor" >
                                        <thead>
                                            <tr>
                                                <th>{{Nom}}</th><th>{{Code HTML}}</th>
                                            </tr>
                                            <tr class="filter" style="display : none;">
                                                <td class="color"><input class="filter form-control" filterOn="color" /></td>
                                                <td class="codeHtml"><input class="filter form-control" filterOn="codeHtml" /></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="panel panel-default expertModeVisible">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#configuration_commandeEqlogic">
                                {{Commandes & Equipements}}
                            </a>
                        </h3>
                    </div>
                    <div id="configuration_commandeEqlogic" class="panel-collapse collapse">
                        <div class="panel-body">
                            <form class="form-horizontal">
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{Nombre d'échec avant désactivation de l'équipement}}</label>
                                        <div class="col-sm-3">
                                            <input type="text"  class="configKey form-control" data-l1key="numberOfTryBeforeEqLogicDisable" />
                                        </div>
                                    </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#configuration_nodeJS">
                                    {{NodeJS}}
                                </a>
                            </h3>
                        </div>
                        <div id="configuration_nodeJS" class="panel-collapse collapse">
                            <div class="panel-body">
                                <form class="form-horizontal">
                                    <fieldset>
                                        <div class="form-group expertModeVisible">
                                            <label class="col-sm-2 control-label">{{Activer NodeJS}}</label>
                                            <div class="col-sm-1">
                                                <input type="checkbox" class="configKey" data-l1key="enableNodeJs"/>
                                            </div>
                                        </div>
                                        <div class="form-group expertModeVisible">
                                            <label class="col-sm-2 control-label">{{Port interne NodeJS}}</label>
                                            <div class="col-sm-3">
                                                <input type="text"  class="configKey" data-l1key="nodeJsInternalPort" />
                                            </div>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default expertModeVisible">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#configuration_market">
                                    {{Market}}
                                </a>
                            </h3>
                        </div>
                        <div id="configuration_market" class="panel-collapse collapse">
                            <div class="panel-body">
                                <form class="form-horizontal">
                                    <fieldset>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">{{Adresse}}</label>
                                            <div class="col-sm-3">
                                                <input class="configKey form-control" data-l1key="market::address"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">{{Clef API}}</label>
                                            <div class="col-sm-3">
                                                <input type="text"  class="configKey form-control" data-l1key="market::apikey" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">{{Installer automatiquement les widgets manquants}}</label>
                                            <div class="col-sm-3">
                                                <input type="checkbox"  class="configKey" data-l1key="market::autoInstallMissingWidget" />
                                            </div>
                                        </div>
                                        <div class="form-group alert alert-danger">
                                            <label class="col-sm-2 control-label">{{Voir modules non validés (à vos risques et périls)}}</label>
                                            <div class="col-sm-3">
                                                <input type="checkbox"  class="configKey" data-l1key="market::showToValidateMarket" />
                                            </div>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default expertModeVisible">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#configuration_update">
                                    {{Mise à jour}}
                                </a>
                            </h3>
                        </div>
                        <div id="configuration_update" class="panel-collapse collapse">
                            <div class="panel-body">
                                <form class="form-horizontal">
                                    <fieldset>
                                        <div class="form-group expertModeVisible">
                                            <label class="col-sm-2 control-label">{{Faire une sauvegarde avant la mise à jour}}</label>
                                            <div class="col-sm-1">
                                                <input type="checkbox" class="configKey" data-l1key="update::backupBefore"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">{{Branche}}</label>
                                            <div class="col-sm-2">
                                                <select class="configKey form-control" data-l1key="market::branch">
                                                    <option value="stable">Stable</option>
                                                    <option value="master">Developpement</option>
                                                </select>
                                            </div>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions" style="height: 20px;">
                        <a class="btn btn-success" id="bt_saveGeneraleConfig"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="md_newUser">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">×</button>
                <h3>{{Ajouter un utilisateur}}</h3>
            </div>
            <div class="modal-body">
                <div style="display: none;" id="div_newUserAlert"></div>
                <center>
                    <input class="form-control" type="text"  id="in_newUserLogin" placeholder="{{Login}}"/><br/><br/>
                    <input class="form-control" type="password"  id="in_newUserMdp" placeholder="{{Mot de passe}}"/>
                </center>
            </div>
            <div class="modal-footer">
                <a class="btn" data-dismiss="modal">{{Annuler}}</a>
                <a class="btn btn-primary" id="bt_newUserSave"><i class="fa fa-check-circle"></i> {{Enregistrer}}</a>
            </div>
        </div>
    </div>
</div>

<?php include_file("desktop", "administration", "js"); ?>
