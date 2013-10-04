<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class gliale_module_main extends Model
{

	var $schema = "CREATE TABLE `gliale_module_main` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `directory_name` varchar(35) NOT NULL,
  `description` varchar(255) NOT NULL,
  `is_active` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8";
	var $field = array("id", "directory_name", "description", "is_active");
	var $validate = array(
		'directory_name' => array(
			'not_empty' => array('This field is requiered.')
		),
		'description' => array(
			'not_empty' => array('This field is requiered.')
		),
		'is_active' => array(
			'numeric' => array('This must be an int.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
