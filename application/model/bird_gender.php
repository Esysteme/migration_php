<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class bird_gender extends Model
{

	var $schema = "CREATE TABLE `bird_gender` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
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
