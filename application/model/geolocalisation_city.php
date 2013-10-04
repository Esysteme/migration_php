<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class geolocalisation_city extends Model
{

	var $schema = "CREATE TABLE `geolocalisation_city` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_geolocalisation_country` int(11) NOT NULL,
  `id_country` varchar(10) NOT NULL DEFAULT '0',
  `libelle` varchar(250) NOT NULL,
  `capital` enum('0','1') NOT NULL DEFAULT '0',
  `population` int(10) unsigned NOT NULL DEFAULT '0',
  `latitude` float NOT NULL DEFAULT '0',
  `longitude` float NOT NULL DEFAULT '0',
  `code_zip` varchar(100) NOT NULL,
  `code_insee` varchar(100) NOT NULL,
  `distance` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_geolocalisation_country` (`id_geolocalisation_country`),
  KEY `libelle` (`libelle`)
) ENGINE=InnoDB AUTO_INCREMENT=198798 DEFAULT CHARSET=utf8";
	var $field = array("id", "id_geolocalisation_country", "id_country", "libelle", "capital", "population", "latitude", "longitude", "code_zip", "code_insee", "distance");
	var $validate = array(
		'id_geolocalisation_country' => array(
			'reference_to' => array('The constraint to geolocalisation_country.id isn\'t respected.', 'geolocalisation_country', 'id')
		),
		'id_country' => array(
			'reference_to' => array('The constraint to country.id isn\'t respected.', 'country', 'id')
		),
		'libelle' => array(
			'not_empty' => array('This field is requiered.')
		),
		'capital' => array(
			'not_empty' => array('This field is requiered.')
		),
		'population' => array(
			'numeric' => array('This must be an int.')
		),
		'latitude' => array(
			'decimal' => array('This must be a float.')
		),
		'longitude' => array(
			'decimal' => array('This must be a float.')
		),
		'code_zip' => array(
			'not_empty' => array('This field is requiered.')
		),
		'code_insee' => array(
			'not_empty' => array('This field is requiered.')
		),
		'distance' => array(
			'decimal' => array('This must be a float.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
