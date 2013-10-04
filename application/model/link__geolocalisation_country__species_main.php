<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class link__geolocalisation_country__species_main extends Model
{

	var $schema = "CREATE TABLE `link__geolocalisation_country__species_main` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_species_main` int(11) NOT NULL,
  `id_geolocalisation_country` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	var $field = array("id", "id_species_main", "id_geolocalisation_country");
	var $validate = array(
		'id_species_main' => array(
			'reference_to' => array('The constraint to species_main.id isn\'t respected.', 'species_main', 'id')
		),
		'id_geolocalisation_country' => array(
			'reference_to' => array('The constraint to geolocalisation_country.id isn\'t respected.', 'geolocalisation_country', 'id')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}
}
