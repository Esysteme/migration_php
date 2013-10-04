<?php
$_TABLE['create'] ="CREATE TABLE `data_area` (
  `id` int(11) NOT NULL auto_increment,
  `id_area` int(11) NOT NULL,
  `id_country` varchar(2) NOT NULL default '0',
  `name` varchar(250) default NULL,
  `name_clean` varchar(250) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","id_area","id_country","name","name_clean");
?>