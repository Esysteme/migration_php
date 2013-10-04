<?php

use \Glial\Synapse\Singleton;
use \Glial\Synapse\Controller;

class Author extends Controller {

	public $module_group = "Species";

	function index() {
		
		$this->title = __("Authors");
		$this->ariane = "> " . $this->title;

		$this->layout_name ="admin";
		
	
		
		$sql = "SELECT a.id, a.surname, 
			(SELECT COUNT( 1 ) FROM species_picture_main b WHERE b.id_species_author = a.id) as valid,
			(SELECT count(1) FROM species_picture_in_wait c WHERE c.author = a.surname and c.id_history_etat = 1) as in_wait,
			(SELECT count(1) FROM species_picture_in_wait e 
			INNER JOIN species_picture_info f ON f.id = e.id_species_picture_info and f.type = 3
			WHERE e.author = a.surname and e.id_history_etat = 3) as refused
			FROM species_author a
			order by valid desc LIMIT 100";
		
		
		$res = $this->db['mysql_write']->sql_query($sql);
		$data = $this->db['mysql_write']->sql_to_array($res);

		$this->set("data", $data);
		

	}

	function image($param) {
		
		
		$this->layout_name ="admin";
		
	
		$sql = "SELECT * FROM species_author a
			WHERE id = '".$param[0]."'";
		$res = $this->db['mysql_write']->sql_query($sql);
		$data['author'] = $this->db['mysql_write']->sql_to_array($res);
		
		$this->title = $data['author'][0]['surname'];
        $this->ariane = '> <a href="'.LINK.'author/">'.__("Author").'</a> > ' . $this->title;
		
		
		$sql = "SELECT *,a.id as id_photo,c.libelle as info_photo FROM species_picture_main a
			inner join species_tree_nominal b on a.id_species_main = b.id_nominal
			INNER JOIN species_picture_info c ON c.id = a.id_species_picture_info
			INNER JOIN species_author d ON d.id = a.id_species_author
			WHERE a.id_species_author = '".$param[0]."'
			order by id_species_main,id_species_sub, id_species_picture_info";
		
		$res = $this->db['mysql_write']->sql_query($sql);
		$data['photo'] = $this->db['mysql_write']->sql_to_array($res);
		
		//--INNER JOIN species_author d ON d.id = a.id_species_author
		$sql = "SELECT distinct a.photo_id,e.miniature,a.id as id_photo FROM species_picture_in_wait a
			inner join species_tree_nominal b on a.id_species_main = b.id_nominal
			inner join species_picture_id e ON e.photo_id = a.photo_id
			
			WHERE a.author = '".$this->db['mysql_write']->sql_real_escape_string($data['author'][0]['surname'])."' AND id_history_etat =1
			order by id_species_main, id_species_picture_info";
		
		$res = $this->db['mysql_write']->sql_query($sql);
		$data['to_valid'] = $this->db['mysql_write']->sql_to_array($res);

		
		
		$sql = "SELECT distinct a.photo_id,e.miniature,a.id as id_photo FROM species_picture_in_wait a
			inner join species_tree_nominal b on a.id_species_main = b.id_nominal
			inner join species_picture_id e ON e.photo_id = a.photo_id
			inner join species_picture_info f ON f.id = a.id_species_picture_info
			WHERE a.author = '".$this->db['mysql_write']->sql_real_escape_string($data['author'][0]['surname'])."'  AND id_history_etat =3 and f.type=3
			order by id_species_main, id_species_picture_info";
		
		$res = $this->db['mysql_write']->sql_query($sql);
		$data['removed'] = $this->db['mysql_write']->sql_to_array($res);
		
		
		
		
		
		
		
		$this->set("data", $data);
		
	}
	
	
	function admin_manage()
	{
		
		if (from() == "administration.controller.php")
		{
			$module = array();
			$module['picture'] = "administration/author-icon.png";
			$module['name'] = __("Authors");
			$module['description'] = __("Upload, convert and edit a picture");

			return $module;
		}
		
		$this->layout_name = "admin";
		$_SQL = singleton::getInstance(SQL_DRIVER);

		$this->title = __("Manage authors");
		$this->ariane = '> <a href="'.LINK.'administration/">'.__("Administration").'</a> > ' . $this->title;
		
		
		
		
		$sql = "SELECT a.id, a.surname, 
			(SELECT COUNT( 1 ) FROM species_picture_main b WHERE b.id_species_author = a.id) as valid,
			
			FROM species_author a
			order by valid desc";
		
		
		$res = $this->db['mysql_write']->sql_query($sql);
		$data = $this->db['mysql_write']->sql_to_array($res);

		$this->set("data", $data);
		
	}

}