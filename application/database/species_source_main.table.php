<?php
$_TABLE['create'] ="CREATE TABLE `species_source_main` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL,
  `u_r_l` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","name","u_r_l","date_created","date_updated");
?>