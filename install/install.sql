-- phpMyAdmin SQL Dump
-- version 4.0.6
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Jeu 08 Mai 2014 à 16:17
-- Version du serveur: 5.6.16-1~exp1
-- Version de PHP: 5.5.9-1ubuntu4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `jeedom`
--

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(127) NOT NULL,
  `datetime` datetime DEFAULT NULL,
  `lifetime` varchar(127) NOT NULL,
  `value` varchar(5119) DEFAULT NULL,
  `options` varchar(5119) DEFAULT NULL,
  PRIMARY KEY (`key`),
  UNIQUE KEY `key_UNIQUE` (`key`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `cache_persist`
--

CREATE TABLE IF NOT EXISTS `cache_persist` (
  `key` varchar(127) NOT NULL,
  `datetime` datetime DEFAULT NULL,
  `lifetime` varchar(127) NOT NULL,
  `value` varchar(5119) DEFAULT NULL,
  `options` varchar(5119) DEFAULT NULL,
  PRIMARY KEY (`key`),
  UNIQUE KEY `key_UNIQUE` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `calendar_event`
--

CREATE TABLE IF NOT EXISTS `calendar_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eqLogic_id` int(11) NOT NULL,
  `cmd_param` text,
  `value` varchar(127) DEFAULT NULL,
  `startDate` datetime DEFAULT NULL,
  `endDate` datetime DEFAULT NULL,
  `until` datetime DEFAULT NULL,
  `repeat` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Structure de la table `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MEMORY  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `chatHistory`
--

CREATE TABLE IF NOT EXISTS `chatHistory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` int(11) DEFAULT NULL,
  `to` int(11) DEFAULT NULL,
  `message` varchar(1023) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `cmd`
--

CREATE TABLE IF NOT EXISTS `cmd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eqLogic_id` int(11) NOT NULL,
  `order` int(11) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `configuration` text,
  `template` text,
  `isHistorized` varchar(45) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `subType` varchar(45) DEFAULT NULL,
  `cache` text,
  `unite` varchar(45) DEFAULT NULL,
  `eventOnly` tinyint(1) DEFAULT '0',
  `display` text,
  `isVisible` int(11) DEFAULT '1',
  `value` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`eqLogic_id`,`name`),
  KEY `isHistorized` (`isHistorized`),
  KEY `type` (`type`),
  KEY `eventOnly` (`eventOnly`),
  KEY `name` (`name`),
  KEY `subtype` (`subType`),
  KEY `eqLogic_id` (`eqLogic_id`),
  KEY `value` (`value`),
  KEY `order` (`order`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1482 ;

-- --------------------------------------------------------

--
-- Structure de la table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `plugin` varchar(127) NOT NULL DEFAULT 'core',
  `key` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`key`,`plugin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `cron`
--

CREATE TABLE IF NOT EXISTS `cron` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server` varchar(127) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `enable` int(11) DEFAULT NULL,
  `class` varchar(127) DEFAULT NULL,
  `function` varchar(127) NOT NULL,
  `lastrun` datetime DEFAULT NULL,
  `duration` varchar(127) DEFAULT NULL,
  `state` varchar(127) DEFAULT NULL,
  `schedule` varchar(127) DEFAULT NULL,
  `timeout` int(11) DEFAULT NULL,
  `deamon` int(11) DEFAULT '0',
  `deamonSleepTime` int(11) DEFAULT NULL,
  `option` varchar(255) DEFAULT NULL,
  `once` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `class_function` (`class`,`function`,`option`),
  KEY `type` (`class`),
  KEY `logicalId_Type` (`class`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=371 ;

-- --------------------------------------------------------

--
-- Structure de la table `dataStore`
--

CREATE TABLE IF NOT EXISTS `dataStore` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(127) NOT NULL,
  `link_id` int(11) NOT NULL,
  `key` varchar(127) NOT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`type`,`link_id`,`key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Structure de la table `energy`
--

CREATE TABLE IF NOT EXISTS `energy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eqLogic_id` int(11) NOT NULL,
  `category` varchar(127) DEFAULT NULL,
  `consumption` varchar(255) DEFAULT NULL,
  `power` varchar(255) DEFAULT NULL,
  `options` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `eqLogic_id` (`eqLogic_id`),
  KEY `category` (`category`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Structure de la table `eqLogic`
--

CREATE TABLE IF NOT EXISTS `eqLogic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(127) NOT NULL,
  `logicalId` varchar(127) DEFAULT NULL,
  `object_id` int(11) DEFAULT NULL,
  `eqType_name` varchar(127) NOT NULL,
  `specificCapatibilities` text,
  `configuration` text,
  `isVisible` tinyint(1) DEFAULT NULL,
  `eqReal_id` int(11) DEFAULT NULL,
  `isEnable` tinyint(1) DEFAULT NULL,
  `status` text,
  `timeout` int(11) DEFAULT NULL,
  `category` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`name`,`object_id`),
  KEY `eqTypeName` (`eqType_name`),
  KEY `name` (`name`),
  KEY `logical_id` (`logicalId`),
  KEY `logica_id_eqTypeName` (`logicalId`,`eqType_name`),
  KEY `object_id` (`object_id`),
  KEY `eqReal_id` (`eqReal_id`),
  KEY `timeout` (`timeout`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=382 ;

-- --------------------------------------------------------

--
-- Structure de la table `eqReal`
--

CREATE TABLE IF NOT EXISTS `eqReal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `logicalId` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `type` varchar(45) NOT NULL,
  `configuration` text,
  `cat` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  KEY `logicalId` (`logicalId`),
  KEY `type` (`type`),
  KEY `logicalId_Type` (`logicalId`,`type`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `history`
--

CREATE TABLE IF NOT EXISTS `history` (
  `cmd_id` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `value` float DEFAULT NULL,
  PRIMARY KEY (`cmd_id`,`datetime`),
  KEY `fk_history5min_commands1_idx` (`cmd_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `historyArch`
--

CREATE TABLE IF NOT EXISTS `historyArch` (
  `cmd_id` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `value` float DEFAULT NULL,
  PRIMARY KEY (`cmd_id`,`datetime`),
  KEY `fk_history5min_commands1` (`cmd_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `interactDef`
--

CREATE TABLE IF NOT EXISTS `interactDef` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enable` int(11) DEFAULT '1',
  `query` text,
  `reply` text,
  `link_type` varchar(127) DEFAULT NULL,
  `link_id` int(11) DEFAULT NULL,
  `person` varchar(255) DEFAULT NULL,
  `options` text,
  `filtres` text,
  `position` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Structure de la table `interactQuery`
--

CREATE TABLE IF NOT EXISTS `interactQuery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `interactDef_id` int(11) NOT NULL,
  `enable` int(11) DEFAULT '1',
  `query` text,
  `link_type` varchar(127) DEFAULT NULL,
  `link_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sarahQuery_sarahDef1_idx` (`interactDef_id`),
  FULLTEXT KEY `query` (`query`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4076 ;

-- --------------------------------------------------------

--
-- Structure de la table `internalEvent`
--

CREATE TABLE IF NOT EXISTS `internalEvent` (
  `datetime` datetime DEFAULT NULL,
  `event` varchar(127) DEFAULT NULL,
  `options` varchar(511) DEFAULT NULL
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `logicalId` varchar(127) DEFAULT NULL,
  `plugin` varchar(127) NOT NULL,
  `message` text,
  `action` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=48 ;

-- --------------------------------------------------------

--
-- Structure de la table `object`
--

CREATE TABLE IF NOT EXISTS `object` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `father_id` int(11) DEFAULT NULL,
  `isVisible` tinyint(1) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `configuration` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  KEY `fk_object_object1_idx1` (`father_id`),
  KEY `position` (`position`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- Structure de la table `scenario`
--

CREATE TABLE IF NOT EXISTS `scenario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(127) DEFAULT NULL,
  `group` varchar(127) DEFAULT NULL,
  `state` varchar(127) DEFAULT NULL,
  `isActive` tinyint(1) DEFAULT '1',
  `lastLaunch` datetime DEFAULT NULL,
  `mode` varchar(127) DEFAULT NULL,
  `schedule` text,
  `pid` int(11) DEFAULT NULL,
  `scenarioElement` text,
  `trigger` text,
  `log` text,
  `timeout` int(11) DEFAULT NULL,
  `isVisible` tinyint(1) DEFAULT '1',
  `object_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`group`,`object_id`),
  KEY `group` (`group`),
  KEY `fk_scenario_object1_idx` (`object_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=64 ;

-- --------------------------------------------------------

--
-- Structure de la table `scenarioElement`
--

CREATE TABLE IF NOT EXISTS `scenarioElement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(11) NOT NULL,
  `type` varchar(127) DEFAULT NULL,
  `name` varchar(127) DEFAULT NULL,
  `options` text,
  `log` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=195 ;

-- --------------------------------------------------------

--
-- Structure de la table `scenarioExpression`
--

CREATE TABLE IF NOT EXISTS `scenarioExpression` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(11) DEFAULT NULL,
  `scenarioSubElement_id` int(11) NOT NULL,
  `type` varchar(127) DEFAULT NULL,
  `subtype` varchar(127) DEFAULT NULL,
  `expression` text,
  `options` text,
  `log` text,
  PRIMARY KEY (`id`),
  KEY `fk_scenarioExpression_scenarioSubElement1_idx` (`scenarioSubElement_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=433 ;

-- --------------------------------------------------------

--
-- Structure de la table `scenarioSubElement`
--

CREATE TABLE IF NOT EXISTS `scenarioSubElement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(11) DEFAULT NULL,
  `scenarioElement_id` int(11) NOT NULL,
  `type` varchar(127) DEFAULT NULL,
  `subtype` varchar(127) DEFAULT NULL,
  `name` varchar(127) DEFAULT NULL,
  `options` text,
  `log` text,
  PRIMARY KEY (`id`),
  KEY `fk_scenarioSubElement_scenarioElement1_idx` (`scenarioElement_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=408 ;

-- --------------------------------------------------------

--
-- Structure de la table `update`
--

CREATE TABLE IF NOT EXISTS `update` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(127) DEFAULT NULL,
  `name` varchar(127) DEFAULT NULL,
  `logicalId` varchar(127) DEFAULT NULL,
  `localVersion` varchar(127) DEFAULT NULL,
  `remoteVersion` varchar(127) DEFAULT NULL,
  `status` varchar(127) DEFAULT NULL,
  `configuration` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=81 ;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(45) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `options` text,
  `hash` varchar(255) DEFAULT NULL,
  `rights` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Structure de la table `view`
--

CREATE TABLE IF NOT EXISTS `view` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(127) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='<double-click to overwrite multiple objects>' AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Structure de la table `viewData`
--

CREATE TABLE IF NOT EXISTS `viewData` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `viewZone_id` int(11) NOT NULL,
  `type` varchar(127) DEFAULT NULL,
  `link_id` int(11) NOT NULL,
  `configuration` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`viewZone_id`,`link_id`,`type`),
  KEY `fk_data_zone1_idx` (`viewZone_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='<double-click to overwrite multiple objects>' AUTO_INCREMENT=489 ;

-- --------------------------------------------------------

--
-- Structure de la table `viewZone`
--

CREATE TABLE IF NOT EXISTS `viewZone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `view_id` int(11) NOT NULL,
  `type` varchar(127) DEFAULT NULL,
  `name` varchar(127) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `configuration` text,
  PRIMARY KEY (`id`),
  KEY `fk_zone_view1` (`view_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='<double-click to overwrite multiple objects>' AUTO_INCREMENT=199 ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `cmd`
--
ALTER TABLE `cmd`
  ADD CONSTRAINT `fk_cmd_eqLogic1` FOREIGN KEY (`eqLogic_id`) REFERENCES `eqLogic` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `eqLogic`
--
ALTER TABLE `eqLogic`
  ADD CONSTRAINT `fk_eqLogic_jeenode1` FOREIGN KEY (`eqReal_id`) REFERENCES `eqReal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_eqLogic_object1` FOREIGN KEY (`object_id`) REFERENCES `object` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `history`
--
ALTER TABLE `history`
  ADD CONSTRAINT `fk_history_cmd1` FOREIGN KEY (`cmd_id`) REFERENCES `cmd` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `historyArch`
--
ALTER TABLE `historyArch`
  ADD CONSTRAINT `fk_historyArch_cmd1` FOREIGN KEY (`cmd_id`) REFERENCES `cmd` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `object`
--
ALTER TABLE `object`
  ADD CONSTRAINT `fk_object_object1` FOREIGN KEY (`father_id`) REFERENCES `object` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `scenario`
--
ALTER TABLE `scenario`
  ADD CONSTRAINT `fk_scenario_object1` FOREIGN KEY (`object_id`) REFERENCES `object` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `scenarioExpression`
--
ALTER TABLE `scenarioExpression`
  ADD CONSTRAINT `fk_scenarioExpression_scenarioSubElement1` FOREIGN KEY (`scenarioSubElement_id`) REFERENCES `scenarioSubElement` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `scenarioSubElement`
--
ALTER TABLE `scenarioSubElement`
  ADD CONSTRAINT `fk_scenarioSubElement_scenarioElement1` FOREIGN KEY (`scenarioElement_id`) REFERENCES `scenarioElement` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `viewData`
--
ALTER TABLE `viewData`
  ADD CONSTRAINT `fk_data_zone1` FOREIGN KEY (`viewZone_id`) REFERENCES `viewZone` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `viewZone`
--
ALTER TABLE `viewZone`
  ADD CONSTRAINT `fk_zone_view1` FOREIGN KEY (`view_id`) REFERENCES `view` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
