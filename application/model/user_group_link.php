<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class user_group_link extends Model
{

	var $schema = "CREATE TABLE `user_group_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user_group_main` int(11) NOT NULL,
  `id_user_main` int(11) NOT NULL,
  `joined` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `IdUserGroupMain` (`id_user_group_main`,`id_user_main`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8";
	var $field = array("id", "id_user_group_main", "id_user_main", "joined");
	var $validate = array(
		'id_user_group_main' => array(
			'reference_to' => array('The constraint to user_group_main.id isn\'t respected.', 'user_group_main', 'id')
		),
		'id_user_main' => array(
			'reference_to' => array('The constraint to user_main.id isn\'t respected.', 'user_main', 'id')
		),
		'joined' => array(
			'time' => array('This must be a time.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
