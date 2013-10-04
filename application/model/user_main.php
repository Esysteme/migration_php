<?php

namespace Application\Model;

use \Glial\Synapse\Model;

class user_main extends Model
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
		'login' => array(
			'is_unique' => array('This email has already been taken.'),
			'email' => array("You must enter a valid email address."),
			'not_empty' => array("L'email doit être renseigné.")
		),
		'email' => array(
			'is_unique' => array('This email has already been taken.'),
			'email' => array("You must enter a valid email address."),
			'not_empty' => array("L'email doit être renseigné.")
		),
		'name' => array(
			'no_numeric' => array('pas de chiffre !'),
			'name_firstname' => array('lettres uniquement !'),
			'between' => array("Le nom d'utilisateur doit être entre 2 et 15 caractères", 2, 50),
			'not_empty' => array("Le nom doit être renseigné.")
		),
		'firstname' => array(
			'no_numeric' => array('pas de chiffre !'),
			'name_firstname' => array('lettres uniquement !'),
			'between' => array("Le prénom doit être entre 2 et 50 caractères", 2, 50),
			'not_empty' => array("Le prénom doit être renseigné.")
		),
		'ip' => array(
			'ip' => array("your IP is not valid")
		),
		'password' => array(
			'equal_to' => array("Password must be the same", "password2"),
			'between' => array("Your password must contain between 8 and 40 characters", 8, 40)
		),
		'id_geolocalisation_country' => array(
			'reference_to' => array("Please select your country", "geolocalisation_country", "id")
		),
		'id_geolocalisation_city' => array(
			'reference_to' => array("Please select your city", "geolocalisation_city", "id")
		),
		'points' => array(
			'numeric' => array("chiffre uniquement")
		)
	);

	function get_validate()
	{
		return $this->validate;
	}

}

