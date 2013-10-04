<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class species_picture_info extends Model
{

	var $schema = "CREATE TABLE `species_picture_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(35) NOT NULL,
  `type` int(11) NOT NULL,
  `detail` varchar(80) NOT NULL,
  `cf_order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8";
	var $field = array("id", "libelle", "type", "detail", "cf_order");
	var $validate = array(
		'libelle' => array(
			'not_empty' => array('This field is requiered.')
		),
		'type' => array(
			'numeric' => array('This must be an int.')
		),
		'detail' => array(
			'not_empty' => array('This field is requiered.')
		),
		'cf_order' => array(
			'numeric' => array('This must be an int.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
