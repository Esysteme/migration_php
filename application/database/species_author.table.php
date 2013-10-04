<?php
$_TABLE['create'] ="CREATE TABLE `species_author` (
  `id` int(11) NOT NULL auto_increment,
  `first_name` varchar(35) NOT NULL,
  `name` varchar(35) NOT NULL,
  `email` varchar(80) NOT NULL,
  `surname` varchar(35) NOT NULL,
  `main_page` varchar(150) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","first_name","name","email","surname","main_page");
?>