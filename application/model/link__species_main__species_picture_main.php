<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class link__species_main__species_picture_main extends Model
{

	var $schema = "CREATE TABLE `link__species_main__species_picture_main` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_species_main` int(11) NOT NULL,
  `id_species_picture_main` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_species_main` (`id_species_main`,`id_species_picture_main`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	var $field = array("id", "id_species_main", "id_species_picture_main");
	var $validate = array(
		'id_species_main' => array(
			'reference_to' => array('The constraint to species_main.id isn\'t respected.', 'species_main', 'id')
		),
		'id_species_picture_main' => array(
			'reference_to' => array('The constraint to species_picture_main.id isn\'t respected.', 'species_picture_main', 'id')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
