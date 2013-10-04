<?php
$_TABLE['create'] ="CREATE TABLE `species_iucn` (
  `id` int(11) NOT NULL auto_increment,
  `code_iucn` char(2) NOT NULL,
  `libelle` varchar(50) NOT NULL,
  `cf_order` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `code_iucn` (`code_iucn`,`libelle`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","code_iucn","libelle","cf_order");
?>