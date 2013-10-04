<?php
$_TABLE['create'] ="CREATE TABLE `species_to_introduce` (
  `id` int(11) NOT NULL auto_increment,
  `species_kingdom` varchar(50) NOT NULL,
  `species_phylum` varchar(50) NOT NULL,
  `species_class` varchar(50) NOT NULL,
  `species_order` varchar(50) NOT NULL,
  `species_family` varchar(50) NOT NULL,
  `species_main` varchar(50) NOT NULL,
  `species_sub` varchar(50) NOT NULL,
  `is_valid` int(11) NOT NULL,
  `id_reference` varchar(32) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","species_kingdom","species_phylum","species_class","species_order","species_family","species_main","species_sub","is_valid","id_reference");
?>