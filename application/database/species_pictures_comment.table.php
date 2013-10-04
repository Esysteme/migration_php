<?php
$_TABLE['create'] ="CREATE TABLE `species_pictures_comment` (
  `id` int(11) NOT NULL auto_increment,
  `id_species_pictures_main` int(11) NOT NULL,
  `id_user_main` int(11) NOT NULL,
  `comment` text collate utf8_unicode_ci NOT NULL,
  `date_inserted` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `IdUserMain` (`id_user_main`),
  KEY `IdSpeciesPicturesMain` (`id_species_pictures_main`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
$_TABLE['field'] = array("id","id_species_pictures_main","id_user_main","comment","date_inserted");
?>