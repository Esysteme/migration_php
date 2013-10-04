<?php
$_TABLE['create'] ="CREATE TABLE `gliale_audit_query` (
  `id` int(11) NOT NULL auto_increment,
  `date` datetime NOT NULL,
  `query` varchar(255) NOT NULL,
  `time_execution` float NOT NULL,
  `user` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=318918 DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","date","query","time_execution","user");
?>