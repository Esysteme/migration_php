<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class user_news_letter extends Model
{

	var $schema = "CREATE TABLE `user_news_letter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `user_agent` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `date_inserted` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
	var $field = array("id", "email", "ip", "user_agent", "date_inserted");
	var $validate = array(
		'email' => array(
			'email' => array('your email is not valid')
		),
		'ip' => array(
			'ip' => array('your IP is not valid')
		),
		'user_agent' => array(
			'not_empty' => array('This field is requiered.')
		),
		'date_inserted' => array(
			'time' => array('This must be a time.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
