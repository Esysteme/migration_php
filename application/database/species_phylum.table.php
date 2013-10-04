<?php
$_TABLE['create'] ="CREATE TABLE `species_phylum` (
  `id` int(11) NOT NULL auto_increment,
  `id_species_kingdom` int(11) NOT NULL,
  `scientific_name` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `scientific_name` (`scientific_name`),
  KEY `FK_id_species_kingdom` (`id_species_kingdom`),
  CONSTRAINT `FK_id_species_kingdom` FOREIGN KEY (`id_species_kingdom`) REFERENCES `species_kingdom` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","id_species_kingdom","scientific_name","date_created","date_updated");
?>