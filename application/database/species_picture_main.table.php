<?php
$_TABLE['create'] ="CREATE TABLE `species_picture_main` (
  `id` int(11) NOT NULL auto_increment,
  `id_species_main` int(11) NOT NULL,
  `id_species_sub` int(11) NOT NULL,
  `id_changes` int(11) NOT NULL,
  `id_from` int(11) NOT NULL,
  `id_user_main` int(11) NOT NULL,
  `md5` varchar(32) NOT NULL,
  `url_md5` varchar(32) NOT NULL,
  `url_found` varchar(100) NOT NULL,
  `url_context` varchar(100) NOT NULL,
  `id_species_action_detail` int(11) NOT NULL,
  `author` varchar(50) NOT NULL,
  `copyright` varchar(100) NOT NULL,
  `is_linked` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `crop` char(20) NOT NULL,
  `date_created` datetime NOT NULL,
  `name` varchar(50) NOT NULL,
  `date_validated` datetime NOT NULL,
  `id_species_pictures_type` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `URLMD5` (`url_md5`),
  KEY `IdSpeciesMain` (`id_species_main`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","id_species_main","id_species_picture_info","id_species_sub","id_changes","id_from","id_user_main","md5","url_md5","url_found","url_context","id_species_action_detail","author","copyright","is_linked","height","width","crop","date_created","name","date_validated","id_species_pictures_type","id_licence");
?>