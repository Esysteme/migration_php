<?php
$_TABLE['create'] ="CREATE TABLE `geolocalisation_city` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `id_geolocalisation_country` int(11) NOT NULL,
  `id_country` varchar(10) NOT NULL default '0',
  `libelle` varchar(250) NOT NULL,
  `capital` enum('0','1') NOT NULL default '0',
  `population` int(10) unsigned NOT NULL default '0',
  `latitude` float NOT NULL default '0',
  `longitude` float NOT NULL default '0',
  `code_zip` varchar(100) NOT NULL,
  `code_insee` varchar(100) NOT NULL,
  `distance` float NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `capital` (`capital`),
  KEY `countryid` (`id_country`),
  KEY `id_geolocalisation_country` (`id_geolocalisation_country`)
) ENGINE=InnoDB AUTO_INCREMENT=198798 DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","id_geolocalisation_country","id_country","libelle","capital","population","latitude","longitude","code_zip","code_insee","distance");
?>