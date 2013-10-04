<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class species_author extends Model
{

	var $schema = "CREATE TABLE `species_author` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user_main` int(11) NOT NULL,
  `firstname` varchar(35) NOT NULL,
  `name` varchar(35) NOT NULL,
  `email` varchar(80) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `main_page` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8";
	var $field = array("id", "id_user_main", "firstname", "name", "email", "surname", "main_page");
	var $validate = array(
		'id_user_main' => array(
			'reference_to' => array('The constraint to user_main.id isn\'t respected.', 'user_main', 'id')
		),
		'firstname' => array(
			'not_empty' => array('This field is requiered.')
		),
		'name' => array(
			'not_empty' => array('This field is requiered.')
		),
		'email' => array(
			'email' => array('your email is not valid')
		),
		'surname' => array(
			'not_empty' => array('This field is requiered.')
		),
		'main_page' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
