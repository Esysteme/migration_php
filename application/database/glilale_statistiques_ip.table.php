<?php
$_TABLE['create'] ="CREATE TABLE `glilale_statistiques_ip` (
  `id` int(11) NOT NULL auto_increment,
  `ip` char(15) NOT NULL,
  `hostname` varchar(150) NOT NULL,
  `isp` varchar(150) NOT NULL,
  `organization` varchar(150) NOT NULL,
  `proxy` varchar(150) NOT NULL,
  `type` varchar(150) NOT NULL,
  `assignment` varchar(150) NOT NULL,
  `country` char(2) NOT NULL,
  `area` varchar(150) NOT NULL,
  `city` varchar(150) NOT NULL,
  `latitude` varchar(150) NOT NULL,
  `longitude` varchar(150) NOT NULL,
  `area_code` varchar(150) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `IP` (`ip`)
) ENGINE=MyISAM AUTO_INCREMENT=842 DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","ip","hostname","isp","organization","proxy","type","assignment","country","area","city","latitude","longitude","area_code");
?>