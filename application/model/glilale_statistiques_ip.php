<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class glilale_statistiques_ip extends Model
{

	var $schema = "CREATE TABLE `glilale_statistiques_ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` char(15) NOT NULL,
  `hostname` varchar(150) NOT NULL,
  `isp` varchar(150) NOT NULL,
  `organization` varchar(150) NOT NULL,
  `proxy` varchar(150) NOT NULL,
  `type` varchar(150) NOT NULL,
  `assignment` varchar(150) NOT NULL,
  `country` char(2) NOT NULL,
  `area` varchar(150) NOT NULL,
  `city` varchar(150) NOT NULL,
  `latitude` varchar(150) NOT NULL,
  `longitude` varchar(150) NOT NULL,
  `area_code` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `IP` (`ip`)
) ENGINE=MyISAM AUTO_INCREMENT=842 DEFAULT CHARSET=utf8";
	var $field = array("id", "ip", "hostname", "isp", "organization", "proxy", "type", "assignment", "country", "area", "city", "latitude", "longitude", "area_code");
	var $validate = array(
		'ip' => array(
			'ip' => array('your IP is not valid')
		),
		'hostname' => array(
			'not_empty' => array('This field is requiered.')
		),
		'isp' => array(
			'not_empty' => array('This field is requiered.')
		),
		'organization' => array(
			'not_empty' => array('This field is requiered.')
		),
		'proxy' => array(
			'not_empty' => array('This field is requiered.')
		),
		'type' => array(
			'not_empty' => array('This field is requiered.')
		),
		'assignment' => array(
			'not_empty' => array('This field is requiered.')
		),
		'country' => array(
			'not_empty' => array('This field is requiered.')
		),
		'area' => array(
			'not_empty' => array('This field is requiered.')
		),
		'city' => array(
			'not_empty' => array('This field is requiered.')
		),
		'latitude' => array(
			'not_empty' => array('This field is requiered.')
		),
		'longitude' => array(
			'not_empty' => array('This field is requiered.')
		),
		'area_code' => array(
			'not_empty' => array('This field is requiered.')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
