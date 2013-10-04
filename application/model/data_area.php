<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class data_area extends Model
{

	var $schema = "CREATE TABLE `data_area` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_area` int(11) NOT NULL,
  `id_country` varchar(2) NOT NULL DEFAULT '0',
  `name` varchar(250) DEFAULT NULL,
  `name_clean` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8";
	var $field = array("id", "id_area", "id_country", "name", "name_clean");
	var $validate = array(
		'id_area' => array(
			'reference_to' => array('The constraint to area.id isn\'t respected.', 'area', 'id')
		),
		'id_country' => array(
			'reference_to' => array('The constraint to country.id isn\'t respected.', 'country', 'id')
		),
		'name' => array(
			'not_empty' => array('This field is requiered.')
		),
		'name_clean' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
