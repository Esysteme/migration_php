<?php
$_TABLE['create'] ="CREATE TABLE `user_group_link` (
  `id` int(11) NOT NULL auto_increment,
  `id_user_group_main` int(11) NOT NULL,
  `id_user_main` int(11) NOT NULL,
  `joined` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `IdUserGroupMain` (`id_user_group_main`,`id_user_main`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8";
$_TABLE['field'] = array("id","id_user_group_main","id_user_main","joined");
?>