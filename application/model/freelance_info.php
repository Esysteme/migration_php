<?php

namespace Application\Model;
use \Glial\Synapse\Model;

class freelance_info extends Model
{
var $schema = "CREATE TABLE `freelance_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref_freelance_info` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `date` date NOT NULL,
  `author` int(200) NOT NULL,
  `location` int(200) NOT NULL,
  `duration` int(200) NOT NULL,
  `TJM` int(200) NOT NULL,
  `start` int(200) NOT NULL,
  `description` text NOT NULL,
  `inserted` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

var $field = array("id","ref_freelance_info","title","date","author","location","duration","TJM","start","description","inserted");

var $validate = array(
	'ref_freelance_info' => array(
		'numeric' => array('This must be an int.')
	),
	'title' => array(
		'not_empty' => array('This field is requiered.')
	),

	'description' => array(
		'not_empty' => array('This field is requiered.')
	),

);

function get_validate()
{
return $this->validate;
}
}
