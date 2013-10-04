F<?php
namespace Application\Model;
use \Glial\Synapse\Model;

class user_group_type extends Model
{
var $schema = "CREATE TABLE `user_group_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `detail` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `date_insterted` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

var $field = array("id","name","detail","date_insterted");

var $validate = array(
	'name' => array(
		'not_empty' => array('This field is requiered.')
	),
	'detail' => array(
		'not_empty' => array('This field is requiered.')
	),
	'date_insterted' => array(
		'time' => array('This must be a time.')
	),
);

function get_validate()
{
return $this->validate;
}
}
