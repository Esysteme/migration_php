<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class user_group_main extends Model
{

	var $schema = "CREATE TABLE `user_group_main` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user_group_type` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `tag` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `country` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `logo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
	var $field = array("id", "id_user_group_type", "name", "tag", "country", "logo", "url");
	var $validate = array(
		'id_user_group_type' => array(
			'reference_to' => array('The constraint to user_group_type.id isn\'t respected.', 'user_group_type', 'id')
		),
		'name' => array(
			'not_empty' => array('This field is requiered.')
		),
		'tag' => array(
			'not_empty' => array('This field is requiered.')
		),
		'country' => array(
			'not_empty' => array('This field is requiered.')
		),
		'logo' => array(
			'not_empty' => array('This field is requiered.')
		),
		'url' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
