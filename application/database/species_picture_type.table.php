<?php
$_TABLE['create'] ="CREATE TABLE `species_picture_type` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","title","date_created");
?>