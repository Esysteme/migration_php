<?php
$_TABLE['create'] ="CREATE TABLE `gliale_module_main` (
  `id` int(11) NOT NULL auto_increment,
  `directory_name` varchar(35) NOT NULL,
  `description` varchar(255) NOT NULL,
  `is_active` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","directory_name","description","is_active");
?>