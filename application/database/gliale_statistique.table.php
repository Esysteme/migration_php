<?php
$_TABLE['create'] ="CREATE TABLE `gliale_statistique` (
  `id` int(11) NOT NULL auto_increment,
  `id_user_main` int(11) NOT NULL,
  `query_string` varchar(255) NOT NULL,
  `referer` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  `ip` varchar(15) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `accept_language` varchar(20) NOT NULL,
  `country` char(2) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12359 DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","id_user_main","query_string","referer","date_created","ip","user_agent","accept_language","country");
?>