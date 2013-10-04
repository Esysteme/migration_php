<?php
$_TABLE['create'] ="CREATE TABLE `user_main` (
  `id` int(11) NOT NULL auto_increment,
  `is_valid` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(20) NOT NULL,
  `name` varchar(40) NOT NULL,
  `firstname` varchar(40) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `id_geolocalisation_country` int(11) NOT NULL,
  `id_geolocalisation_city` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `date_last_login` datetime NOT NULL,
  `date_last_connected` datetime NOT NULL,
  `date_created` datetime NOT NULL,
  `key_auth` char(32) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `login` (`login`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","is_valid","login","email","password","name","firstname","ip","id_geolocalisation_country","id_geolocalisation_city","points","date_last_login","date_last_connected","date_created","key_auth");
?>