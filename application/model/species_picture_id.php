<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class species_picture_id extends Model
{

	var $schema = "CREATE TABLE `species_picture_id` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_species_picture_search` int(11) NOT NULL,
  `photo_id` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `photo_id` (`photo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	var $field = array("id", "id_species_author", "photo_id");
	var $validate = array(
		'id_species_author' => array(
			'reference_to' => array('The constraint to species_author.id isn\'t respected.', 'species_author', 'id')
		),
		
		'photo_id' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
