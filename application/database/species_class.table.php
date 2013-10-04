<?php
$_TABLE['create'] ="CREATE TABLE `species_class` (
  `id` int(11) NOT NULL auto_increment,
  `id_species_phylum` int(11) NOT NULL,
  `scientific_name` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `scientific_name` (`scientific_name`),
  KEY `FK_IdSpeciesPhylum` (`id_species_phylum`),
  CONSTRAINT `FK_id_species_phylum` FOREIGN KEY (`id_species_phylum`) REFERENCES `species_phylum` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","id_species_phylum","scientific_name","date_created","date_updated");
?>