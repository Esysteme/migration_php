<?php
$_TABLE['create'] ="CREATE TABLE `species_genus` (
  `id` int(11) NOT NULL auto_increment,
  `id_species_family` int(11) NOT NULL,
  `is_valid` int(11) NOT NULL,
  `scientific_name` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `scientific_name` (`scientific_name`),
  KEY `FK_IdSpeciesFamily` (`id_species_family`),
  CONSTRAINT `FK_id_species_family` FOREIGN KEY (`id_species_family`) REFERENCES `species_family` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38424 DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","id_species_family","is_valid","scientific_name","date_created","date_updated");
?>