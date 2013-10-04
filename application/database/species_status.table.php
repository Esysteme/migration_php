<?php
$_TABLE['create'] ="CREATE TABLE `species_status` (
  `id` int(11) NOT NULL auto_increment,
  `libelle` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `Libelle` (`libelle`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","libelle");
?>