<?php
$_TABLE['create'] ="CREATE TABLE `user_main_information` (
  `id` int(11) NOT NULL,
  `id_user_main` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `srteet` varchar(50) NOT NULL,
  `zipcode` varchar(15) NOT NULL,
  `city` varchar(50) NOT NULL,
  `country` char(2) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id_user_main` (`id_user_main`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","id_user_main","number","srteet","zipcode","city","country");
?>