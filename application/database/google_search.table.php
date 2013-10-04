<?php
$_TABLE['create'] ="CREATE TABLE `geolocalisation_country` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name_fr` varchar(250) NOT NULL,
  `name_eng` varchar(200) NOT NULL,
  `libelle` varchar(200) NOT NULL,
  `name_webtitle` varchar(255) NOT NULL,
  `adj` varchar(200) NOT NULL,
  `iso` char(2) NOT NULL,
  `iso3` varchar(3) default NULL,
  `population` int(10) unsigned NOT NULL default '0',
  `latitude` float NOT NULL default '0',
  `longitude` float NOT NULL default '0',
  `description` text NOT NULL,
  `num_code` varchar(10) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `iso` (`iso`)
) ENGINE=InnoDB AUTO_INCREMENT=253 DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","id_user_main","ip","date","key_words","referer");
?>