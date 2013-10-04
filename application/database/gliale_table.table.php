<?php
$_TABLE['create'] ="CREATE TABLE `gliale_table` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) collate utf8_unicode_ci NOT NULL,
  `date_insterted` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `Name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
$_TABLE['field'] = array("id","name","date_insterted");
?>