<?php
$_TABLE['create'] ="CREATE TABLE `species_synonyms` (
  `id` int(11) NOT NULL auto_increment,
  `id_table` int(11) NOT NULL,
  `id_row` int(11) NOT NULL,
  `iso` char(2) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `IdRow` (`id_row`),
  KEY `ISO` (`iso`,`name`(40))
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","id_table","id_row","iso","name");
?>