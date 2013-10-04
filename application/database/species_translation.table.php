<?php
$_TABLE['create'] ="CREATE TABLE `species_translation` (
  `id` int(11) NOT NULL auto_increment,
  `id_table` int(11) NOT NULL,
  `id_row` int(11) NOT NULL,
  `scientific_name` varchar(50) NOT NULL,
  `fr` varchar(50) NOT NULL,
  `en` varchar(50) NOT NULL,
  `de` varchar(50) NOT NULL,
  `es` varchar(50) NOT NULL,
  `nl` varchar(50) NOT NULL,
  `it` varchar(50) NOT NULL,
  `ja` varchar(50) NOT NULL,
  `cs` varchar(50) NOT NULL,
  `pl` varchar(50) NOT NULL,
  `cn` varchar(50) NOT NULL,
  `ru` varchar(50) NOT NULL,
  `fi` varchar(50) NOT NULL,
  `pt` varchar(50) NOT NULL,
  `dk` varchar(50) NOT NULL,
  `no` varchar(50) NOT NULL,
  `sk` varchar(50) NOT NULL,
  `se` varchar(50) NOT NULL,
  `is` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `IdTable` (`id_table`,`id_row`)
) ENGINE=MyISAM AUTO_INCREMENT=9713 DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","id_table","id_row","scientific_name","fr","en","de","es","nl","it","ja","cs","pl","cn","ru","fi","pt","dk","no","sk","se","is");
?>