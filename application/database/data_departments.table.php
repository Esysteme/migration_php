<?php
$_TABLE['create'] ="CREATE TABLE `data_departments` (
  `id` int(11) NOT NULL auto_increment,
  `id_country` varchar(2) NOT NULL,
  `id_department` varchar(2) NOT NULL,
  `id_region` int(11) NOT NULL default '0',
  `name` varchar(250) default NULL,
  `name_clean` varchar(250) NOT NULL,
  `code` varchar(10) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=106 DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","id_country","id_department","id_region","name","name_clean","code");
?>