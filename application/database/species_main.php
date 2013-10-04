<?php
$table ="CREATE TABLE `species_main` (
  `id` int(11) NOT NULL auto_increment,
  `id_species_genus` int(11) NOT NULL,
  `id_species_iucn` int(11) NOT NULL,
  `id_species_taxon_author` int(11) NOT NULL,
  `date_author` char(4) NOT NULL,
  `is_valid` int(11) NOT NULL,
  `scientific_name` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `scientific_name` (`scientific_name`),
  KEY `id_genus` (`id_species_genus`),
  CONSTRAINT `FK_id_species_genus` FOREIGN KEY (`id_species_genus`) REFERENCES `species_genus` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=260174 DEFAULT CHARSET=utf8";
$field = array("id","id_species_genus","id_species_iucn","id_species_taxon_author","date_author","is_valid","scientific_name","date_created","date_updated");
?>
