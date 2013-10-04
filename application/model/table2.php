<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class table2 extends Model
{

	var $schema = "CREATE TABLE `table2` (
  `Id` varchar(200) NOT NULL,
  `Part2B` varchar(200) NOT NULL,
  `Part2T` varchar(200) NOT NULL,
  `Part1` varchar(200) NOT NULL,
  `Scope` varchar(200) NOT NULL,
  `Language_Type` varchar(200) NOT NULL,
  `Ref_Name` varchar(200) NOT NULL,
  `Comment` varchar(200) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	var $field = array("Id", "Part2B", "Part2T", "Part1", "Scope", "Language_Type", "Ref_Name", "Comment");
	var $validate = array(
		'Id' => array(
			'not_empty' => array('This field is requiered.')
		),
		'Part2B' => array(
			'not_empty' => array('This field is requiered.')
		),
		'Part2T' => array(
			'not_empty' => array('This field is requiered.')
		),
		'Part1' => array(
			'not_empty' => array('This field is requiered.')
		),
		'Scope' => array(
			'not_empty' => array('This field is requiered.')
		),
		'Language_Type' => array(
			'not_empty' => array('This field is requiered.')
		),
		'Ref_Name' => array(
			'not_empty' => array('This field is requiered.')
		),
		'Comment' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
