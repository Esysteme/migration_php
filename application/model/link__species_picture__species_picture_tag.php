<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class link__species_picture__species_picture_tag extends Model
{

	var $schema = "CREATE TABLE `link__species_picture__species_picture_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_species_picture_main` int(11) NOT NULL,
  `id_species_picture_tag` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
	var $field = array("id", "id_species_picture_main", "id_species_picture_tag");
	var $validate = array(
		'id_species_picture_main' => array(
			'reference_to' => array('The constraint to species_picture_main.id isn\'t respected.', 'species_picture_main', 'id')
		),
		'id_species_picture_tag' => array(
			'reference_to' => array('The constraint to species_picture_tag.id isn\'t respected.', 'species_picture_tag', 'id')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
