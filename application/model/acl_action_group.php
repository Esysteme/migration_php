<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class acl_action_group extends Model
{

	var $schema = "CREATE TABLE `acl_action_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_acl_action` int(11) NOT NULL,
  `id_group` int(11) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `acl` (`id_acl_action`,`id_group`)
) ENGINE=InnoDB AUTO_INCREMENT=245 DEFAULT CHARSET=utf8";
	var $field = array("id", "id_acl_action", "id_group", "description");
	var $validate = array(
		'id_acl_action' => array(
			'reference_to' => array('The constraint to acl_action.id isn\'t respected.', 'acl_action', 'id')
		),
		'id_group' => array(
			'reference_to' => array('The constraint to group.id isn\'t respected.', 'group', 'id')
		),
		'description' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
