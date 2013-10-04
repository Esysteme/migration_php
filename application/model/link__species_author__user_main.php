<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class link__species_author__user_main extends Model
{

	var $schema = "CREATE TABLE `link__species_author__user_main` (
  `id` int(11) NOT NULL,
  `id_user_main` int(11) NOT NULL,
  `id_species_author` int(11) NOT NULL,
  UNIQUE KEY `id_user_main` (`id_user_main`,`id_species_author`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
	var $field = array("id", "id_user_main", "id_species_author");
	var $validate = array(
		'id_user_main' => array(
			'reference_to' => array('The constraint to user_main.id isn\'t respected.', 'user_main', 'id')
		),
		'id_species_author' => array(
			'reference_to' => array('The constraint to species_author.id isn\'t respected.', 'species_author', 'id')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
