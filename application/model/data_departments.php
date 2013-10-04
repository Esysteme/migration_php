<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class data_departments extends Model
{

	var $schema = "CREATE TABLE `data_departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_country` varchar(2) NOT NULL,
  `id_department` varchar(2) NOT NULL,
  `id_region` int(11) NOT NULL DEFAULT '0',
  `name` varchar(250) DEFAULT NULL,
  `name_clean` varchar(250) NOT NULL,
  `code` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=106 DEFAULT CHARSET=utf8";
	var $field = array("id", "id_country", "id_department", "id_region", "name", "name_clean", "code");
	var $validate = array(
		'id_country' => array(
			'reference_to' => array('The constraint to country.id isn\'t respected.', 'country', 'id')
		),
		'id_department' => array(
			'reference_to' => array('The constraint to department.id isn\'t respected.', 'department', 'id')
		),
		'id_region' => array(
			'reference_to' => array('The constraint to region.id isn\'t respected.', 'region', 'id')
		),
		'name' => array(
			'not_empty' => array('This field is requiered.')
		),
		'name_clean' => array(
			'not_empty' => array('This field is requiered.')
		),
		'code' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
