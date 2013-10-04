<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class language2 extends Model
{

	var $schema = "CREATE TABLE `language2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iso` char(2) NOT NULL,
  `iso3` char(3) NOT NULL,
  `iso5` char(5) NOT NULL,
  `print_name` varchar(200) NOT NULL,
  `english` varchar(50) NOT NULL,
  `french` varchar(100) NOT NULL,
  `language` varchar(50) NOT NULL,
  `inverted_name` varchar(100) NOT NULL,
  `charset` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `iso3` (`iso3`)
) ENGINE=InnoDB AUTO_INCREMENT=8180 DEFAULT CHARSET=utf8";
	var $field = array("id", "iso", "iso3", "iso5", "print_name", "english", "french", "language", "inverted_name", "charset");
	var $validate = array(
		'iso' => array(
			'not_empty' => array('This field is requiered.')
		),
		'iso3' => array(
			'not_empty' => array('This field is requiered.')
		),
		'iso5' => array(
			'not_empty' => array('This field is requiered.')
		),
		'print_name' => array(
			'not_empty' => array('This field is requiered.')
		),
		'english' => array(
			'not_empty' => array('This field is requiered.')
		),
		'french' => array(
			'not_empty' => array('This field is requiered.')
		),
		'language' => array(
			'not_empty' => array('This field is requiered.')
		),
		'inverted_name' => array(
			'not_empty' => array('This field is requiered.')
		),
		'charset' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
