<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class species_distribution_information extends Model
{

	var $schema = "CREATE TABLE `species_distribution_information` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8";
	var $field = array("id", "libelle");
	var $validate = array(
		'libelle' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
