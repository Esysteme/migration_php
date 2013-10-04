<?php
$_TABLE['create'] ="CREATE TABLE `species_source_detail` (
  `id` int(11) NOT NULL auto_increment,
  `id_species_main` int(11) NOT NULL,
  `id_species_sub` int(11) NOT NULL,
  `id_species_source_main` int(11) NOT NULL,
  `reference_url` varchar(200) NOT NULL,
  `reference_id` varchar(20) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `IdSource` (`id_species_source_main`,`reference_id`)
) ENGINE=MyISAM AUTO_INCREMENT=654 DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","id_species_main","id_species_sub","id_species_source_main","reference_url","reference_id","date_created","date_updated");
?>