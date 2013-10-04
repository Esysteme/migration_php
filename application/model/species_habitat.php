<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class species_habitat extends Model
{

	var $schema = "CREATE TABLE `species_habitat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rank` char(10) NOT NULL,
  `libelle` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rank` (`rank`,`libelle`(50))
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	var $field = array("id", "rank", "libelle");
	var $validate = array(
		'rank' => array(
			'not_empty' => array('This field is requiered.')
		),
		'libelle' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
