<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class language extends Model
{

	var $schema = "CREATE TABLE `language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iso` char(2) NOT NULL,
  `iso3` char(3) NOT NULL,
  `print_name` varchar(200) NOT NULL,
  `language` varchar(50) NOT NULL,
  `charset` varchar(20) NOT NULL,
  `scope` char(1) NOT NULL,
  `language_type` char(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `iso3` (`iso3`),
  KEY `print_name` (`print_name`)
) ENGINE=InnoDB AUTO_INCREMENT=8192 DEFAULT CHARSET=utf8";
	var $field = array("id", "iso", "iso3", "print_name", "language", "charset", "scope", "language_type");
	var $validate = array(
		'iso' => array(
			'not_empty' => array('This field is requiered.')
		),
		'iso3' => array(
			'not_empty' => array('This field is requiered.')
		),
		'print_name' => array(
			'not_empty' => array('This field is requiered.')
		),
		'language' => array(
			'not_empty' => array('This field is requiered.')
		),
		'charset' => array(
			'not_empty' => array('This field is requiered.')
		),
		'scope' => array(
			'not_empty' => array('This field is requiered.')
		),
		'language_type' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
