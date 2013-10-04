<?php
$_TABLE['create'] ="CREATE TABLE `species_kingdom` (
  `id` int(11) NOT NULL auto_increment,
  `scientific_name` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `scientific_name` (`scientific_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","scientific_name","date_created","date_updated");
?>