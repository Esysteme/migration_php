<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class species_picture_in_wait2 extends Model
{

	var $schema = "CREATE TABLE `species_picture_in_wait2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_species_main` int(11) NOT NULL,
  `id_species_sub` int(11) NOT NULL,
  `id_user_main` int(11) NOT NULL,
  `id_species_picture_info` int(11) NOT NULL,
  `id_species_author` int(11) NOT NULL,
  `id_licence` int(11) NOT NULL,
  `md5` varchar(32) NOT NULL,
  `url_md5` varchar(32) NOT NULL,
  `url_found` varchar(100) NOT NULL,
  `url_context` varchar(100) NOT NULL,
  `author` varchar(50) NOT NULL,
  `copyright` varchar(100) NOT NULL,
  `is_linked` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `crop` char(20) NOT NULL,
  `date_created` datetime NOT NULL,
  `name` varchar(50) NOT NULL,
  `date_validated` datetime NOT NULL,
  `id_species_pictures_type` int(11) NOT NULL,
  `id_history_etat` int(11) NOT NULL,
  `data` text NOT NULL,
  `title` varchar(100) NOT NULL,
  `legend` text NOT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  `id_camera` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `URLMD5` (`url_md5`),
  KEY `IdSpeciesMain` (`id_species_main`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	var $field = array("id", "id_species_main", "id_species_sub", "id_user_main", "id_species_picture_info", "id_species_author", "id_licence", "md5", "url_md5", "url_found", "url_context", "author", "copyright", "is_linked", "height", "width", "crop", "date_created", "name", "date_validated", "id_species_pictures_type", "id_history_etat", "data", "title", "legend", "latitude", "longitude", "id_camera");
	var $validate = array(
		'id_species_main' => array(
			'reference_to' => array('The constraint to species_main.id isn\'t respected.', 'species_main', 'id')
		),
		'id_species_sub' => array(
			'reference_to' => array('The constraint to species_sub.id isn\'t respected.', 'species_sub', 'id')
		),
		'id_user_main' => array(
			'reference_to' => array('The constraint to user_main.id isn\'t respected.', 'user_main', 'id')
		),
		'id_species_picture_info' => array(
			'reference_to' => array('The constraint to species_picture_info.id isn\'t respected.', 'species_picture_info', 'id')
		),
		'id_species_author' => array(
			'reference_to' => array('The constraint to species_author.id isn\'t respected.', 'species_author', 'id')
		),
		'id_licence' => array(
			'reference_to' => array('The constraint to licence.id isn\'t respected.', 'licence', 'id')
		),
		'md5' => array(
			'not_empty' => array('This field is requiered.')
		),
		'url_md5' => array(
			'not_empty' => array('This field is requiered.')
		),
		'url_found' => array(
			'not_empty' => array('This field is requiered.')
		),
		'url_context' => array(
			'not_empty' => array('This field is requiered.')
		),
		'author' => array(
			'not_empty' => array('This field is requiered.')
		),
		'copyright' => array(
			'not_empty' => array('This field is requiered.')
		),
		'is_linked' => array(
			'numeric' => array('This must be an int.')
		),
		'height' => array(
			'numeric' => array('This must be an int.')
		),
		'width' => array(
			'numeric' => array('This must be an int.')
		),
		'crop' => array(
			'not_empty' => array('This field is requiered.')
		),
		'date_created' => array(
			'time' => array('This must be a time.')
		),
		'name' => array(
			'not_empty' => array('This field is requiered.')
		),
		'date_validated' => array(
			'time' => array('This must be a time.')
		),
		'id_species_pictures_type' => array(
			'reference_to' => array('The constraint to species_pictures_type.id isn\'t respected.', 'species_pictures_type', 'id')
		),
		'id_history_etat' => array(
			'reference_to' => array('The constraint to history_etat.id isn\'t respected.', 'history_etat', 'id')
		),
		'data' => array(
			'not_empty' => array('This field is requiered.')
		),
		'title' => array(
			'not_empty' => array('This field is requiered.')
		),
		'legend' => array(
			'not_empty' => array('This field is requiered.')
		),
		'latitude' => array(
			'decimal' => array('This must be a float.')
		),
		'longitude' => array(
			'decimal' => array('This must be a float.')
		),
		'id_camera' => array(
			'reference_to' => array('The constraint to camera.id isn\'t respected.', 'camera', 'id')
		),
	);

	function get_validate()
	{
		return $this->validate;
	}

}
