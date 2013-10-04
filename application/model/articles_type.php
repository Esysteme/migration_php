<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class articles_type extends Model
{

	var $schema = "CREATE TABLE `articles_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(64) NOT NULL,
  `libelle` varchar(64) NOT NULL,
  `etat` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	var $field = array("id", "image", "libelle", "etat");
	var $validate = array(
		'image' => array(
			'not_empty' => array('This field is requiered.')
		),
		'libelle' => array(
			'not_empty' => array('This field is requiered.')
		),
		'etat' => array(
			'numeric' => array('This must be an int.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
