<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class user_settings extends Model
{

	var $schema = "CREATE TABLE `user_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user_main` int(11) NOT NULL,
  `photo` varchar(100) NOT NULL,
  `gender` int(11) NOT NULL,
  `id_geolocalisation_country__nationality` int(11) NOT NULL,
  `avatar` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_user_main` (`id_user_main`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	var $field = array("id", "id_user_main", "photo", "gender", "id_geolocalisation_country__nationality", "avatar");
	var $validate = array(
		'id_user_main' => array(
			'reference_to' => array('The constraint to user_main.id isn\'t respected.', 'user_main', 'id')
		),
		'photo' => array(
			'not_empty' => array('This field is requiered.')
		),
		'gender' => array(
			'numeric' => array('This must be an int.')
		),
		'id_geolocalisation_country__nationality' => array(
			'reference_to' => array('The constraint to geolocalisation_country__nationality.id isn\'t respected.', 'geolocalisation_country__nationality', 'id')
		),
		'avatar' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
