<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class species_authorities extends Model
{

	var $schema = "CREATE TABLE `species_authorities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(200) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `libelle` (`libelle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	var $field = array("id", "libelle", "date");
	var $validate = array(
		'libelle' => array(
			'not_empty' => array('This field is requiered.')
		),
		'date' => array(
			'date' => array('This must be a date.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
