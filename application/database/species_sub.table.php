<?php
$_TABLE['create'] ="CREATE TABLE `species_sub` (
  `id` int(11) NOT NULL auto_increment,
  `id_species_main` int(11) NOT NULL,
  `id_species_pictures` int(11) NOT NULL,
  `is_valid` int(11) NOT NULL,
  `scientific_name` varchar(200) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `ScientificName` (`scientific_name`),
  UNIQUE KEY `Id` (`id`,`id_species_main`),
  KEY `FK_IdSpeciesMain` (`id_species_main`),
  CONSTRAINT `FK_id_species_main` FOREIGN KEY (`id_species_main`) REFERENCES `species_main` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=260271 DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","id_species_main","id_species_pictures","is_valid","scientific_name","date_created","date_updated");
?>