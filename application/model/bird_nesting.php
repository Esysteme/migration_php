<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class bird_nesting extends Model
{

	var $schema = "CREATE TABLE `bird_nesting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_bird_ring_father` int(11) DEFAULT NULL,
  `id_bird_ring_mother` int(11) DEFAULT NULL,
  `id_bird_nest` int(11) NOT NULL,
  `id_housing` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
	var $field = array("id", "id_bird_ring_father", "id_bird_ring_mother", "id_bird_nest", "id_housing");
	var $validate = array(
		'id_bird_ring_father' => array(
			'reference_to' => array('The constraint to bird_ring_father.id isn\'t respected.', 'bird_ring_father', 'id')
		),
		'id_bird_ring_mother' => array(
			'reference_to' => array('The constraint to bird_ring_mother.id isn\'t respected.', 'bird_ring_mother', 'id')
		),
		'id_bird_nest' => array(
			'reference_to' => array('The constraint to bird_nest.id isn\'t respected.', 'bird_nest', 'id')
		),
		'id_housing' => array(
			'reference_to' => array('The constraint to housing.id isn\'t respected.', 'housing', 'id')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
