<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class scientific_name_translation extends Model
{

	var $schema = "CREATE TABLE `scientific_name_translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_species_main` int(11) NOT NULL,
  `language` char(5) NOT NULL,
  `text` text NOT NULL,
  `is_valid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	var $field = array("id", "id_species_main", "language", "text", "is_valid");
	var $validate = array(
		'id_species_main' => array(
			'reference_to' => array('The constraint to species_main.id isn\'t respected.', 'species_main', 'id')
		),
		'language' => array(
			'not_empty' => array('This field is requiered.')
		),
		'text' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
