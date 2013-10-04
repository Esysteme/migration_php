<?php
$_TABLE['create'] ="CREATE TABLE `species_order` (
  `id` int(11) NOT NULL auto_increment,
  `id_species_class` int(11) NOT NULL,
  `is_valid` int(11) NOT NULL,
  `scientific_name` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `scientific_name` (`scientific_name`),
  KEY `FK_id_species_class` (`id_species_class`),
  CONSTRAINT `FK_id_species_class` FOREIGN KEY (`id_species_class`) REFERENCES `species_class` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=593 DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","id_species_class","is_valid","scientific_name","date_created","date_updated");
?>