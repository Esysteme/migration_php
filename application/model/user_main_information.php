<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class user_main_information extends Model
{

	var $schema = "CREATE TABLE `user_main_information` (
  `id` int(11) NOT NULL,
  `id_user_main` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `street` varchar(50) NOT NULL,
  `zipcode` varchar(15) NOT NULL,
  `city` varchar(50) NOT NULL,
  `country` char(2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_user_main` (`id_user_main`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	var $field = array("id", "id_user_main", "number", "street", "zipcode", "city", "country");
	var $validate = array(
		'id_user_main' => array(
			'reference_to' => array('The constraint to user_main.id isn\'t respected.', 'user_main', 'id')
		),
		'number' => array(
			'numeric' => array('This must be an int.')
		),
		'street' => array(
			'not_empty' => array('This field is requiered.')
		),
		'zipcode' => array(
			'not_empty' => array('This field is requiered.')
		),
		'city' => array(
			'not_empty' => array('This field is requiered.')
		),
		'country' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
