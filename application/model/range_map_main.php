<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class range_map_main extends Model
{

	var $schema = "CREATE TABLE `range_map_main` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_species_main` int(11) NOT NULL,
  `km_squared` float NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_species_main` (`id_species_main`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8";
	var $field = array("id", "id_species_main", "km_squared");
	var $validate = array(
		'id_species_main' => array(
			'reference_to' => array('The constraint to species_main.id isn\'t respected.', 'species_main', 'id')
		),
		'km_squared' => array(
			'decimal' => array('This must be a float.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
