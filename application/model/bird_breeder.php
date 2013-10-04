<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class bird_breeder extends Model
{

	var $schema = "CREATE TABLE `bird_breeder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user_main` int(11) NOT NULL,
  `id_bird_federation` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_bird_federation` (`id_bird_federation`,`number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
	var $field = array("id", "id_user_main", "id_bird_federation", "number");
	var $validate = array(
		'id_user_main' => array(
			'reference_to' => array('The constraint to user_main.id isn\'t respected.', 'user_main', 'id')
		),
		'id_bird_federation' => array(
			'reference_to' => array('The constraint to bird_federation.id isn\'t respected.', 'bird_federation', 'id')
		),
		'number' => array(
			'numeric' => array('This must be an int.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
