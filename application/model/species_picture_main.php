<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class species_picture_main extends Model
{

	// Nous donnons donc à Gliale la structure d'un enregistrement
	var $schema = "CREATE TABLE `UserMain` (
 `Id` int(11) NOT NULL auto_increment,
 `IsValid` int(11) NOT NULL,
 `Login` varchar(50) NOT NULL,
 `Email` varchar(50) NOT NULL,
 `Password` varchar(20) NOT NULL,
 `Name` varchar(40) NOT NULL,
 `Firstname` varchar(40) NOT NULL,
 `IP` varchar(15) NOT NULL,
 `CountryIP` char(2) NOT NULL,
 `Points` int(11) NOT NULL,
 `LastLogin` datetime NOT NULL,
 `LastConnected` datetime NOT NULL,
 `LastJoined` datetime NOT NULL,
 `KeyAuth` char(32) NOT NULL,
 PRIMARY KEY  (`Id`),
 UNIQUE KEY `email` (`Email`),
 UNIQUE KEY `login` (`Login`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8";
	// Règles de validation des données

	var $field = array();
	var $validate = array(
		'md5' => array(
			'is_unique' => array('This photo already exist in the database.'),
			'not_empty' => array("The md5 of picture must be set.")
		),
		'crop' => array(
			'regex' => array("The crop on picture is not valid", "/^[0-9]{1,4};[0-9]{1,4};[0-9]{1,4};[0-9]{1,4}$/"),
		),
		'id_author' => array(
			'reference_to' => array("Please select the name of the author", "species_author", "id")
		),
		'url_found' => array(
			'not_empty' => array("URL of picture must be requested.")
		),
		'url_context' => array(
			'not_empty' => array("URL of webpage must be requested.")
		),
		'id_species_main' => array(
			'reference_to' => array("Please select the species", "species_main", "id")
		),
		'id_species_picture_info' => array(
			'reference_to' => array("Please select the type of image", "species_picture_info", "id")
		),
		'id_species_picture_type' => array(
			'reference_to' => array("Please select the type of picrure", "species_picture_type", "id")
		),
		'id_licence' => array(
			'reference_to' => array("Please select the license", "licence", "id")
		)
	);

}

?>