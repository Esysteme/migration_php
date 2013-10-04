<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class user_main_web extends Model
{

	var $schema = "CREATE TABLE `user_main_web` (
  `id` int(11) NOT NULL,
  `id_user_main` int(11) NOT NULL,
  `ip_creation` varchar(15) NOT NULL,
  `hostname` varchar(50) NOT NULL,
  `localisation_country` char(2) NOT NULL,
  `localisation_area` varchar(50) NOT NULL,
  `navigateur` varchar(50) NOT NULL,
  `language` char(2) NOT NULL,
  `longitude` varchar(20) NOT NULL,
  `latitude` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_user_main` (`id_user_main`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	var $field = array("id", "id_user_main", "ip_creation", "hostname", "localisation_country", "localisation_area", "navigateur", "language", "longitude", "latitude");
	var $validate = array(
		'id_user_main' => array(
			'reference_to' => array('The constraint to user_main.id isn\'t respected.', 'user_main', 'id')
		),
		'ip_creation' => array(
			'ip' => array('your IP is not valid')
		),
		'hostname' => array(
			'not_empty' => array('This field is requiered.')
		),
		'localisation_country' => array(
			'not_empty' => array('This field is requiered.')
		),
		'localisation_area' => array(
			'not_empty' => array('This field is requiered.')
		),
		'navigateur' => array(
			'not_empty' => array('This field is requiered.')
		),
		'language' => array(
			'not_empty' => array('This field is requiered.')
		),
		'longitude' => array(
			'not_empty' => array('This field is requiered.')
		),
		'latitude' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
