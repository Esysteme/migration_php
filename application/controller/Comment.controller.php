<?php


use \Glial\I18n\I18n;
use \Glial\Synapse\Controller;


class Comment extends Controller {

	public $module_group = "Articles";

	function index() {
		
	}

	function image($param) {

		

		if ($_SERVER['REQUEST_METHOD'] == "POST")
		{
			debug($_POST);
			//exit;

			if (!empty($_POST['comment']))
			{

				$comment = array();
				$comment['comment__species_picture_main']['id_user_main'] = $GLOBALS['_SITE']['IdUser'];
				$comment['comment__species_picture_main']['id_species_picture_main'] = $_POST['comment']['id'];
				$comment['comment__species_picture_main']['id_parent'] = NULL;
				$comment['comment__species_picture_main']['id_language'] = $_POST['comment']['id_language'];
				$comment['comment__species_picture_main']['date'] = date('c');
				$comment['comment__species_picture_main']['text'] = $_POST['comment']['text'];

				(empty($_POST['comment']['subscribe'])) ? $subscribe = 0 : $subscribe = 1;
				$comment['comment__species_picture_main']['subscribe'] = $subscribe;

				if ($this->db['mysql_write']->sql_save($comment))
				{
					$title = I18n::getTranslation(__("Success"));
					$msg = I18n::getTranslation(__("Your comment has been added."));

					set_flash("success", $title, $msg);
					header("location: " . LINK . "photo/admin_crop/id_photo:" . $_POST['comment']['id'] . '/');
					exit;
				}
				else
				{
					$title = I18n::getTranslation(__("Error"));
					$msg = I18n::getTranslation(__("Please review the following issues that occurred"));

					set_flash("error", $title, $msg);
					header("location: " . LINK . "photo/admin_crop/id_photo:" . $_POST['comment']['id'] . '/');
					exit;
				}

				debug($comment);
				debug($this->db['mysql_write']->sql_error());
				die();
			}
		}

		$this->layout = false;

		$sql = "SELECT * FROM comment__species_picture_main a
			INNER JOIN user_main b ON a.id_user_main = b.id
			INNER JOIN 	geolocalisation_country c ON b.id_geolocalisation_country = c.id
			WHERE a.id_species_picture_main = '" . $this->db['mysql_write']->sql_real_escape_string($param[0]) . "'";
		$res = $this->db['mysql_write']->sql_query($sql);
		$data['comment'] = $this->db['mysql_write']->sql_to_array($res);



		
		$lg = explode(",", LANGUAGE_AVAILABLE);
		$nbchoice = count($lg);

        
		for ($i = 0; $i < $nbchoice; $i++)
		{
			$data['geolocalisation_country'][$i]['libelle'] = I18n::$languagesUTF8[$lg[$i]];
			$data['geolocalisation_country'][$i]['id'] = $lg[$i];
		}
		$data['default_lg'] = I18n::Get();

		$data['id_photo'] = $param[0];

		$this->set("data", $data);
	}

}