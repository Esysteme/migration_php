<?php
$_TABLE['create'] ="CREATE TABLE `user_main_web` (
  `id` int(11) NOT NULL,
  `id_user_main` int(11) NOT NULL,
  `ip_creation` varchar(15) NOT NULL,
  `hostname` varchar(50) NOT NULL,
  `localisation_country` char(2) NOT NULL,
  `localisation_area` varchar(50) NOT NULL,
  `navigateur` varchar(50) NOT NULL,
  `language` char(2) NOT NULL,
  `longitude` varchar(20) NOT NULL,
  `latitude` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id_user_main` (`id_user_main`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","id_user_main","ip_creation","hostname","localisation_country","localisation_area","navigateur","language","longitude","latitude");
?>