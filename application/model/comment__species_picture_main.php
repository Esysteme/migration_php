F<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class comment__species_picture_main extends Model
{

	var $schema = "CREATE TABLE `comment__species_picture_main` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_species_picture_main` int(11) NOT NULL,
  `id_user_main` int(11) NOT NULL,
  `id_parent` int(11) NOT NULL,
  `id_language` char(5) NOT NULL,
  `date` datetime NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	var $field = array("id", "id_species_picture_main", "id_user_main", "id_parent", "id_language", "date", "text");
	var $validate = array(
		'id_species_picture_main' => array(
			'reference_to' => array('The constraint to species_picture_main.id isn\'t respected.', 'species_picture_in_wait', 'id')
		),
		'id_user_main' => array(
			'reference_to' => array('The constraint to user_main.id isn\'t respected.', 'user_main', 'id')
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
