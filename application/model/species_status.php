<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class species_status extends Model
{

	var $schema = "CREATE TABLE `species_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Libelle` (`libelle`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8";
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
