<?php

// id_species_picture_main:118538
use glial\synapse\singleton;
use \glial\synapse\Controller;


class Photo extends Controller
{

	public $module_group = "Media";

	function test()
	{

		include_once(LIBRARY . "Glial/parser/flickr/flickr.php");
		include_once (LIB . "wlHtmlDom.php");
		debug(flickr::get_photo_info("http://www.flickr.com/photos/43256133@N06/4778518108/"));
		exit;
	}

	function index()
	{
		$this->title = __("Members");
		$this->ariane = "> " . $this->title;

		


		$sql = "select * from user_main 
INNER JOIN geolocalisation_city ON geolocalisation_city.id = user_main.id_geolocalisation_city
where is_valid ='1' order by points DESC LIMIT 50";
		$res = $this->db['mysql_write']->sql_query($sql);

		$data = $this->db['mysql_write']->sql_to_array($res);

		$this->javascript = array("jquery-1.4.2.min.js");
		$this->set("data", $data);
	}

	function admin_import()
	{


		if ( from() == "administration.controller.php" )
		{
			$module['picture'] = "administration/photo.gif";
			$module['name'] = __("Pictures");
			$module['description'] = __("Upload, convert and edit a picture");

			return $module;
		}

		$this->layout_name = "admin";
		

		$this->title = __("Import a picture");
		$this->ariane = '> <a href="' . LINK . 'administration/">' . __("Administration") . '</a> > ' . $this->title;


		$url = "http://www.birdquest-tours.com/gallery.cfm?TourTitle=&GalleryRegionID=0&GalleryCategoryID=0&Country=Type+here+to+search..&Photographer=Type+here+to+search..&Species=Hunstein%27s+Mannikin";

		$this->javascript = array("jquery.1.3.2.js", "jquery.autocomplete.min.js");


		$table = "species_picture_in_wait";
		$field = "id_species_main";

		$this->code_javascript[] = '$("#' . $table . '-' . $field . '-auto").autocomplete("' . LINK . 'species/get_species_id_by_scientific/", {
					
					mustMatch: true,
					autoFill: true,
					max: 100,
					scrollHeight: 302,
					delay:1
					});
					$("#' . $table . '-' . $field . '-auto").result(function(event, data, formatted) {
						if (data)
							$("#' . $table . '-' . $field . '").val(data[1]);
					});';

		$table = "species_picture_in_wait";
		$field = "id_species_main";

		$this->code_javascript[] = '$("#' . $table . '-' . $field . '-auto").autocomplete("' . LINK . 'species/get_species_id_by_scientific/", {
					
					mustMatch: true,
					autoFill: true,
					max: 100,
					scrollHeight: 302,
					delay:1
					});
					$("#' . $table . '-' . $field . '-auto").result(function(event, data, formatted) {
						if (data)
							$("#' . $table . '-' . $field . '").val(data[1]);
					});';
	}

	function admin_crop()
	{

		$this->layout_name = "admin";
		

		if ( from() == "administration.controller.php" )
		{

			$sql = "select count(1) as cpt from species_picture_in_wait where id_history_etat=1";

			$res = $this->db['mysql_write']->sql_query($sql);
			$data = $this->db['mysql_write']->sql_to_array($res);

			$module['count'] = $data[0]['cpt'];
			$module['picture'] = "administration/crop2.png";
			$module['name'] = __("Crop a picture");
			$module['description'] = __("Resize an image");

			$this->title = __($module['name']);
			$this->ariane = "> " . __("Administration") . " > " . $this->title;

			return $module;
		}



		$this->title = __("Crop a picture");
		$this->ariane = "> <a href=\"" . LINK . "administration/\">" . __("Administration") . "</a> > " . $this->title;



		/*		 * ****************************************************************************************************** */

//118570
//id_photo // id_species_picture_main

		if ( !empty($_GET['id_species_picture_main']) )
		{
			if ( !stristr($_SERVER['HTTP_REFERER'], 'admin_crop') )
			{
				$_SESSION['HTTP_REFERER'] = $_SERVER['HTTP_REFERER'];
			}
		}

		if ( $_SERVER['REQUEST_METHOD'] == "POST" )
		{
			debug($_POST);
			//exit;

			if ( !empty($_POST['comment']) )
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


				if ( $this->db['mysql_write']->sql_save($comment) )
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

			//utilisé par l'ajout et le delete

			if ( !empty($_GET['id_species_picture_main']) )
			{
				$table = 'species_picture_main';
			}
			else
			{
				$table = 'species_picture_in_wait';
			}

			$species_picture_in_wait[$table]['id'] = $_POST['species_picture_main']['id'];
			$species_picture_in_wait[$table]['id_species_picture_info'] = $_POST['species_picture_main']['id_species_picture_info2'];

			if ( !empty($_POST['idontknow']) )
			{
//we don't know so we require a new picture
				if ( !empty($_GET['id_species_picture_main']) )
				{

					header("location: " . $_SESSION['HTTP_REFERER']);
					unset($_SESSION['HTTP_REFERER']);
				}
				else
				{

					if ( !empty($_GET['id_species_main']) )
					{
						header("location: " . LINK . "photo/admin_crop/id_species_main:" . $_GET['id_species_main'] . '/');
					}
					else
					{
						header("location: " . LINK . "photo/admin_crop/");
					}
				}
				die();
			}



			if ( !empty($_POST['irefuse']) )
			{

				$this->db['mysql_write']->set_history_type(9);
				if ( !$this->db['mysql_write']->sql_save($species_picture_in_wait) )
				{

					$error = $this->db['mysql_write']->sql_error();

					if ( is_array($_SESSION['ERROR']) )
					{

						$_SESSION['ERROR'] = array_merge($_SESSION['ERROR'], $error);
					}
					else
					{
						$_SESSION = $error;
					}


					if ( !empty($_SESSION['ERROR'][$table]['id_species_picture_info']) )
					{
						$_SESSION['ERROR'][$table]['id_species_picture_info2'] = $_SESSION['ERROR'][$table]['id_species_picture_info'];
						unset($_SESSION['ERROR'][$table]['id_species_picture_info']);
					}


					$title = I18n::getTranslation(__("Error"));
					$msg = I18n::getTranslation(__("Please verify, you have selected the reason of your choice."));

					set_flash("error", $title, $msg);

					$ret = array();
					foreach ( $_POST['species_picture_main'] as $var => $val )
					{
						$ret[] = "species_picture_main:" . $var . ":" . $val;
					}

					$param = implode("/", $ret);

//die();
					if ( !empty($_GET['id_species_picture_main']) )
					{
						header("location: " . LINK . "photo/admin_crop/" . $param . "/id_species_picture_main:" . $_POST['species_picture_main']['id']);
					}
					else
					{

						if ( !empty($_GET['id_species_main']) )
						{
							header("location: " . LINK . "photo/admin_crop/" . $param . "/id_species_main:" . $_GET['id_species_main']);
						}
						else
						{
							header("location: " . LINK . "photo/admin_crop/" . $param . "/id_photo:" . $_POST['species_picture_main']['id']);
						}
					}

					die();
				}
				else
				{
					$this->db['mysql_write']->set_history_type(8);
					$this->db['mysql_write']->sql_delete($species_picture_in_wait);

					$title = I18n::getTranslation(__("Success"));
					$msg = I18n::getTranslation(__("The photo has been successfully deleted"));

					set_flash("success", $title, $msg);
				}


				if ( !empty($_GET['id_species_picture_main']) )
				{
					header("location: " . $_SESSION['HTTP_REFERER']);
				}
				else
				{
					if ( !empty($_GET['id_species_main']) )
					{
						header("location: " . LINK . "photo/admin_crop/id_species_main:" . $_GET['id_species_main']);
					}
					else
					{
						header("location: " . LINK . "photo/admin_crop/");
					}
				}

				exit;
			}

			// clicked on accepted !!!!!!!!!!!!!!!!!!!!!!!!!

			if ( !empty($_GET['id_species_picture_main']) )
			{

				$species_picture_main['species_picture_main']['id'] = $_POST['species_picture_main']['id'];
				$sql = "SELECT * FROM species_picture_main where id='" . $_POST['species_picture_main']['id'] . "'";
			}
			else
			{
				$sql = "SELECT * FROM species_picture_in_wait where id='" . $_POST['species_picture_main']['id'] . "'";
			}

			$res = $this->db['mysql_write']->sql_query($sql);
			$this->data['species_picture_main'] = $this->db['mysql_write']->sql_to_array($res);

			$species_picture_main['species_picture_main'] = $this->data['species_picture_main'][0];
			$species_picture_main['species_picture_main']['id_species_picture_info'] = $_POST['species_picture_main']['id_species_picture_info'];
			$species_picture_main['species_picture_main']['id_species_sub'] = $_POST['species_picture_main']['id_species_sub'];
			$species_picture_main['species_picture_main']['id_species_main'] = $_POST['species_picture_main']['id_species_main'];

			( $_POST['species_picture_main']['id_species_sub'] === NULL ) ? $_POST['species_picture_main']['id_species_sub'] = 0 : NULL;
			(empty($species_picture_main['species_picture_main']['id_species_sub'])) ? 0 : $_POST['species_picture_main']['id_species_sub'];
			$species_picture_main['species_picture_main']['crop'] = $_POST['species_picture_main']['crop_x1'] . ";" . $_POST['species_picture_main']['crop_y1'] . ";" . $_POST['species_picture_main']['crop_x2'] . ";" . $_POST['species_picture_main']['crop_y2'];



			if ( empty($_GET['id_species_picture_main']) )
			{
				$species_picture_main['species_picture_main']['date_validated'] = Date("Y-m-d H:i:s");
				$glob = unserialize(base64_decode($_POST['img']));

				switch ( $glob['license']['text'] )
				{
					case "Tous droits réservés":
						$species_picture_main['species_picture_main']['id_licence'] = 1;
						break;

					case "Certains droits réservés (licence Creative Commons)":
						switch ( $glob['license']['url'] )
						{
							case "http://creativecommons.org/licenses/by/2.0/":
								$species_picture_main['species_picture_main']['id_licence'] = 5;
								break;
							case "http://creativecommons.org/licenses/by-sa/2.0/":
								$species_picture_main['species_picture_main']['id_licence'] = 6;
								break;
							case "http://creativecommons.org/licenses/by-nd/2.0/":
								$species_picture_main['species_picture_main']['id_licence'] = 7;
								break;
							case "http://creativecommons.org/licenses/by-nc/2.0/":
								$species_picture_main['species_picture_main']['id_licence'] = 8;
								break;
							case "http://creativecommons.org/licenses/by-nc-sa/2.0/":
								$species_picture_main['species_picture_main']['id_licence'] = 9;
								break;
							case "http://creativecommons.org/licenses/by-nc-nd/2.0/":
								$species_picture_main['species_picture_main']['id_licence'] = 10;
								break;
							default:
								die("need to add a new license CC");
								break;
						}
						break;

					default:
						$species_picture_main['species_picture_main']['id_licence'] = 11;
						break;
				}


				if ( empty($glob['author']) )
				{
					$glob['author'] = $species_picture_main['species_picture_main']['author'];
				}


				if ( !empty($glob['author']) )
				{
					$author["species_author"]["surname"] = $glob['author'];

					$sql = "SELECT id from species_author where surname ='" . $this->db['mysql_write']->sql_real_escape_string($glob['author']) . "'";
					$res = $this->db['mysql_write']->sql_query($sql);

					if ( $this->db['mysql_write']->sql_num_rows($res) == 1 )
					{
						$ob = $this->db['mysql_write']->sql_fetch_object($res);

						$species_picture_main['species_picture_main']['id_species_author'] = $ob->id;
					}
					else
					{

						if ( !$this->db['mysql_write']->sql_save($author) )
						{
							die("problem insertion author");
						}
						else
						{
							$species_picture_main['species_picture_main']['id_species_author'] = $this->db['mysql_write']->sql_insert_id();
						}
					}
				}
				else
				{
					$species_picture_main['species_picture_main']['id_species_author'] = 1;
				}
			}

			if ( empty($_GET['id_species_picture_main']) )
			{
				$this->db['mysql_write']->set_history_type(1);
			}
			else
			{
				$this->db['mysql_write']->set_history_type(12);
			}



			if ( $this->db['mysql_write']->sql_save($species_picture_main) )
			{


//effacement de la ligne dans la table species_picture_in_wait
//todo pb avec delete SELECT count(1) as cpt FROM history_action WHERE `id` = ''
				if ( empty($_GET['id_species_picture_main']) )
				{

					$this->db['mysql_write']->set_history_type(11);
					$this->db['mysql_write']->sql_delete($species_picture_in_wait);


// traitement des tag

					$tag = array();
					if ( !empty($glob['tag']) )
					{
						$link = array();

						foreach ( $glob['tag'] as $value )
						{
							unset($tag);
							$tag['species_picture_tag']['tag'] = trim(mb_strtolower($value, 'UTF-8'));
							$id_species_picture_tag = $this->db['mysql_write']->sql_save($tag);

							if ( $id_species_picture_tag )
							{
								unset($link);
								$link['link__species_picture__species_picture_tag']['id_species_picture_main'] = $species_picture_main['species_picture_main']['id'];
								$link['link__species_picture__species_picture_tag']['id_species_picture_tag'] = $id_species_picture_tag;

								if ( !$this->db['mysql_write']->sql_save($link) )
								{
									debug($link);
									debug($this->db['mysql_write']->sql_error());
									die("problem insertion link tag picture");
								}
							}
							else
							{
								debug($this->db['mysql_write']->sql_error());

								debug($this->db['mysql_write']->query);
								die("problem insertion tag");
							}
						}
					}

// traitement des tag
				}

				$sql = "SELECT * FROM species_tree_name where id = '" . $species_picture_main['species_picture_main']['id_species_main'] . "'";
				$res = $this->db['mysql_write']->sql_query($sql);
				$ob = $this->db['mysql_write']->sql_fetch_object($res);

				$species_name = str_replace(" ", "_", $ob->species_);
				$path = "Eukaryota/{$ob->kingdom}/{$ob->phylum}/{$ob->class}/{$ob->order2}/{$ob->family}/{$ob->genus}/" . $species_name;
				$picture_name = $species_picture_main['species_picture_main']['id'] . "-" . $species_name . ".jpg";

				exec("mkdir -p " . TMP . "crop/" . SIZE_SITE_MAX . "x/" . $path);

				$path_890 = TMP . "crop/" . SIZE_SITE_MAX . "x/" . $path . DS . $picture_name;
				$cmd = "mv " . TMP . "picture/" . SIZE_SITE_MAX . "/" . $species_picture_main['species_picture_main']["name"] . " " . $path_890;
				shell_exec($cmd);

//pour le backup
				$url_dest = DATA . "img/" . $path;

				exec("mkdir -p " . $url_dest);

				$path_1024 = $url_dest . DS . $picture_name;

				if ( $this->data['species_picture_main'][0]["width"] > SIZE_BACKUP )
				{
					include_once LIB . 'imageprocessor.lib.php';
					$ImageProcessor = new ImageProcessor();
					$ImageProcessor->Load(TMP . "photos_in_wait/" . $this->data['species_picture_main'][0]["name"]);
					$ImageProcessor->Resize(SIZE_BACKUP, null, RESIZE_STRETCH);
					$ImageProcessor->Save($path_1024, 100);
				}
				else
				{
					$cmd = "cp " . TMP . "photos_in_wait/" . $species_picture_main['species_picture_main']["name"] . " " . $path_1024;
					shell_exec($cmd);
				}

//end backup
//generation miniature 250px
				$url_dest_pic_big = TMP . "crop/" . SIZE_MINIATURE_BIG . "x" . SIZE_MINIATURE_BIG . "/" . $path;
				$url_dest_pic_min = TMP . "crop/" . SIZE_MINIATURE_SMALL . "x" . SIZE_MINIATURE_SMALL . "/" . $path;

				exec("mkdir -p " . $url_dest_pic_big);
				exec("mkdir -p " . $url_dest_pic_min);

				include_once LIB . 'imageprocessor.lib.php';
				$ImageProcessor = new ImageProcessor();
				$ImageProcessor->Load($path_890);
				$ImageProcessor->Crop($_POST['species_picture_main']['crop_x1'], $_POST['species_picture_main']['crop_y1'], $_POST['species_picture_main']['crop_x2'], $_POST['species_picture_main']['crop_y2']);
				$ImageProcessor->Resize(SIZE_MINIATURE_BIG, SIZE_MINIATURE_BIG, RESIZE_STRETCH);
				$ImageProcessor->Save($url_dest_pic_big . "/" . $picture_name, 100);

//generation miniature 158px
				$ImageProcessor->Resize(SIZE_MINIATURE_SMALL, SIZE_MINIATURE_SMALL, RESIZE_STRETCH);
				$ImageProcessor->Save($url_dest_pic_min . "/" . $picture_name, 100);


				if ( empty($_GET['id_species_picture_main']) )
				{

					$title = I18n::getTranslation(__("Picture croped"));
					$msg = I18n::getTranslation(__("The picture has been croped with success"));
				}
				else
				{
					$title = I18n::getTranslation(__("Picture updated"));
					$msg = I18n::getTranslation(__("The picture has been updated with success"));
				}
				set_flash("success", $title, $msg);



				if ( empty($_GET['id_species_picture_main']) )
				{

					if ( empty($_GET['id_species_main']) )
					{
						header("location: " . LINK . "photo/admin_crop/");
					}
					else
					{
						header("location: " . LINK . "photo/admin_crop/id_species_main:" . $_GET['id_species_main'] . "/");
					}
				}
				else
				{
					if ( !empty($_SESSION['HTTP_REFERER']) )
					{
						header("location: " . $_SESSION['HTTP_REFERER']);
					}
					else
					{
						header("location: " . LINK . "photo/admin_crop/");
					}
				}
				exit;
			}
			else
			{
//error

				$error = $this->db['mysql_write']->sql_error();
				$_SESSION['ERROR'] = $error;

				if ( is_array($_SESSION['ERROR']) )
				{

					$_SESSION['ERROR'] = array_merge($_SESSION['ERROR'], $error);
				}
				else
				{
					$_SESSION = $error;
				}

				if ( count($_SESSION['ERROR']['species_picture_main']) != 0 )
				{
					$li = "invalid field :<br /><ul>";

					foreach ( $_SESSION['ERROR']['species_picture_main'] as $key => $value )
					{
						$li .= "<li>" . $key . " : " . __($value) . "</li>";
					}
					$li .= "</ul>";
				}


				$title = I18n::getTranslation(__("Error"));
				$msg = I18n::getTranslation($li);

				set_flash("error", $title, $msg);

				$ret = array();
				foreach ( $_POST['species_picture_main'] as $var => $val )
				{
					$ret[] = "species_picture_main:" . $var . ":" . $val;
				}

				$param = implode("/", $ret);


				if ( empty($_GET['id_species_picture_main']) )
				{

					if ( empty($_GET['id_species_main']) )
					{
						header("location: " . LINK . "photo/admin_crop/" . $param . "/id_photo:" . $_POST['species_picture_main']['id']);
					}
					else
					{
						header("location: " . LINK . "photo/admin_crop/" . $param . "/id_species_main:" . $_GET['id_species_main'] . "/id_photo:" . $_POST['species_picture_main']['id']);
					}
				}
				else
				{

					header("location: " . LINK . "photo/admin_crop/" . $param . "/id_species_picture_main:" . $_POST['species_picture_main']['id']);
				}
//die();
				exit;
			}
		}
		/*
		 * 
		 * ################################## FIN DU POST ##################################
		 */

		/*


		  SELECT a.id_species_main, a.*, count(1) as cpt, c.`scientific_name`,b.id as id_photo, b.*, c.scientific_name,e.*,z.*
		  FROM `species_tree_id` a
		  INNER JOIN species_picture_in_wait b ON b.id_species_main = a.id_species_main
		  INNER JOIN species_main c ON c.id = a.id_species_main
		  INNER JOIN species_tree_name e ON e.id = a.id_species_main
		  LEFT JOIN species_translation z ON z.id_row = a.id_species_main and id_table = 7
		  WHERE b.id_history_etat = '1'
		  AND id_species_family = 438
		  group by a.id_species_main


		 */
		if ( !empty($_GET['id_species_picture_main']) )
		{

			$sql = "SELECT a.id_species_main, a.*, c.`scientific_name`,b.id as id_photo, b.*, c.scientific_name,e.*,z.*
			FROM `species_tree_id` a
			INNER JOIN species_picture_main b ON b.id_species_main = a.id_species_main
			INNER JOIN species_main c ON c.id = a.id_species_main
			INNER JOIN species_tree_name e ON e.id = a.id_species_main
			LEFT JOIN species_translation z ON z.id_row = a.id_species_main and id_table = 7
			WHERE b.id = '" . mysql_real_escape_string($_GET['id_species_picture_main']) . "'";

			$id_species_picture_main = $_GET['id_species_picture_main'];
		}
		else
		{
			$contrainte = "";

			if ( !empty($_GET['id_photo']) )
			{
				$contrainte .= " and b.id = '" . mysql_real_escape_string($_GET['id_photo']) . "' ";
			}


			if ( !empty($_GET['id_species_main']) )
			{
				$contrainte .= " AND b.id_species_main = '" . $_GET['id_species_main'] . "' ";
			}
			else
			{
				//$contrainte .= " AND id_species_family = 438 ";
			}

			/*
			  $sql = "SELECT count(1) FROM `species_tree_id` a
			  INNER JOIN species_picture_in_wait b ON b.id_species_main = a.id_species_main
			  where id_history_etat = '1' " . $contrainte;

			  //echo $sql;

			  $r = $this->db['mysql_write']->sql_query($sql);
			  $d = mysql_fetch_row($r);

			  $rand = rand(0, $d[0]);
			  if (empty($rand))
			  $rand = 0;
			  if ($d[0] == 1)
			  $rand = 0;
			 */
			$sql = "SELECT a.id_species_main, a.*, c.`scientific_name`,b.id as id_photo, b.*, c.scientific_name,e.*,z.*
			FROM `species_tree_id` a
			INNER JOIN species_picture_in_wait b ON b.id_species_main = a.id_species_main
			INNER JOIN species_main c ON c.id = a.id_species_main
			INNER JOIN species_tree_name e ON e.id = a.id_species_main
			LEFT JOIN species_translation z ON z.id_row = a.id_species_main and id_table = 7
			WHERE b.id_history_etat = '1'
			" . $contrainte . " 
			ORDER BY RAND()
			LIMIT 1";

//echo $sql;
		}


		$res = $this->db['mysql_write']->sql_query($sql);



		if ( mysql_num_rows($res) > 0 )
		{
			$this->data['species'] = $this->db['mysql_write']->sql_to_array($res);



			$sql = "SELECT a.date, c.title, c.point,b.name, b.id, b.firstname,d.iso 
FROM history_main a
INNER JOIN user_main b ON a.id_user_main = b.id
INNER JOIN history_action c ON c.id = a.id_history_action
INNER JOIN geolocalisation_country d ON b.id_geolocalisation_country = d.id
WHERE line = " . $this->data['species'][0]['id_photo'] . " AND id_history_table in(9,10)
ORDER BY a.id asc";
			$res22 = $this->db['mysql_write']->sql_query($sql);
			$this->data['history'] = $this->db['mysql_write']->sql_to_array($res22);

			$_LG = singleton::getInstance("Language");
			$lg = explode(",", LANGUAGE_AVAILABLE);
			$nbchoice = count($lg);

			for ( $i = 0; $i < $nbchoice; $i++ )
			{
				$this->data['geolocalisation_country'][$i]['libelle'] = $_LG->languagesUTF8[$lg[$i]];
				$this->data['geolocalisation_country'][$i]['id'] = $lg[$i];
			}
			$this->data['default_lg'] = $_LG->Get();

//commentaire

			$sql = "SELECT * FROM comment__species_picture_main a
INNER JOIN user_main b ON a.id_user_main = b.id
INNER JOIN 	geolocalisation_country c ON b.id_geolocalisation_country = c.id
WHERE a.id_species_picture_main = '" . $this->data['species'][0]['id_photo'] . "'";
			$res22 = $this->db['mysql_write']->sql_query($sql);
			$this->data['comment'] = $this->db['mysql_write']->sql_to_array($res22);
		}
		else
		{
			if ( !empty($id_species_picture_main) || !empty($_GET['id_species_main']) )
			{
				$title = I18n::getTranslation(__("Warning"));
				$msg = I18n::getTranslation(__("The stock of photos is now empty!"));
				set_flash("caution", $title, $msg);
			}
			else
			{
				$title = I18n::getTranslation(__("Error"));
				$msg = I18n::getTranslation(__("The photo doesn't exist!"));
				set_flash("error", $title, $msg);
			}


			if ( !empty($_GET['id_species_main']) )
			{
				$sql = "SELECT scientific_name from species_main where id ='" . $this->db['mysql_write']->sql_real_escape_string($_GET['id_species_main']) . "'";
				$res = $this->db['mysql_write']->sql_query($sql);

				while ( $ob = $this->db['mysql_write']->sql_fetch_object($res) )
				{
					$name = str_replace(' ', '_', $ob->scientific_name);

					//$_SESSION['HTTP_REFERER']
					header("location: " . LINK . "species/nominal/" . $name . "/");
					die();
				}

				die("problem redirection not good");
			}
			else
			{
				header("location: " . LINK . "administration/");
			}

			exit;
		}


		if ( !empty($this->data['species']['0']['crop']) )
		{
			$crop = explode(";", $this->data['species']['0']['crop']);
		}
		else
		{
			$crop = array(0, 0, 250, 250);
		}



		$this->javascript = array("jquery-1.6.4.min.js",
			"http://maps.googleapis.com/maps/api/js?language=en&sensor=false&region=US",
			"jquery.imgareaselect.pack.js",
			"jquery.autocomplete.min.js",
			"setmap.js");





		$this->code_javascript[] = '$("#species_picture_main-id_author-auto").autocomplete("' . LINK . 'user/author/", {
mustMatch: true,
autoFill: true,
max: 100,
scrollHeight: 302,
delay:0}
);';




		$sql = "SELECT id, scientific_name as libelle FROM species_kingdom order By scientific_name";
		$res = $this->db['mysql_write']->sql_query($sql);
		$this->data['species_kingdom'] = $this->db['mysql_write']->sql_to_array($res);


		$sql = "SELECT id, scientific_name as libelle FROM species_phylum WHERE id_species_kingdom = " . $this->data['species']['0']['id_species_kingdom'] . " order By scientific_name";
		$res = $this->db['mysql_write']->sql_query($sql);
		$this->data['species_phylum'] = $this->db['mysql_write']->sql_to_array($res);


		$sql = "SELECT id, scientific_name as libelle FROM species_class WHERE id_species_phylum = " . $this->data['species']['0']['id_species_phylum'] . " order By scientific_name";
		$res = $this->db['mysql_write']->sql_query($sql);
		$this->data['species_class'] = $this->db['mysql_write']->sql_to_array($res);


		$sql = "SELECT id, scientific_name as libelle FROM species_order WHERE id_species_class = " . $this->data['species']['0']['id_species_class'] . " order By scientific_name";
		$res = $this->db['mysql_write']->sql_query($sql);
		$this->data['species_order'] = $this->db['mysql_write']->sql_to_array($res);


		$sql = "SELECT id, scientific_name as libelle FROM species_family WHERE id_species_order = " . $this->data['species']['0']['id_species_order'] . " order By scientific_name";
		$res = $this->db['mysql_write']->sql_query($sql);
		$this->data['species_family'] = $this->db['mysql_write']->sql_to_array($res);


		$sql = "SELECT id, scientific_name as libelle FROM species_genus WHERE id_species_family = " . $this->data['species']['0']['id_species_family'] . " order By scientific_name";
		$res = $this->db['mysql_write']->sql_query($sql);
		$this->data['species_genus'] = $this->db['mysql_write']->sql_to_array($res);


		$sql = "SELECT id, scientific_name as libelle FROM species_main WHERE id_species_genus = " . $this->data['species']['0']['id_species_genus'] . " order By scientific_name";
		$res = $this->db['mysql_write']->sql_query($sql);
		$this->data['species_main'] = $this->db['mysql_write']->sql_to_array($res);


		$sql = "SELECT id, scientific_name as libelle FROM species_sub WHERE id_species_main = " . $this->data['species']['0']['id_species_main'] . " order By scientific_name";
		$res = $this->db['mysql_write']->sql_query($sql);
		$this->data['species_sub'] = $this->db['mysql_write']->sql_to_array($res);



		$sql = "SELECT id, libelle as libelle FROM licence order By id";
		$res = $this->db['mysql_write']->sql_query($sql);
		$this->data['licence'] = $this->db['mysql_write']->sql_to_array($res);




//accept
		$sql = "SELECT id, libelle as libelle, type FROM species_picture_info where `type` = 1 order BY type, cf_order";
		$res = $this->db['mysql_write']->sql_query($sql);

		$i = 0;
		while ( $ob = mysql_fetch_object($res) )
		{
			$i++;

			$this->data['pic_info'][$i]['id'] = $ob->id;
			$this->data['pic_info'][$i]['libelle'] = __($ob->libelle);

			if ( empty($this->data['species']['0']['id_species_picture_info']) )
			{
				$this->data['species']['0']['id_species_picture_info'] = 0;
			}
		}


//refuse
		$sql = "SELECT id, libelle as libelle, type FROM species_picture_info where `type` = 3 order BY type, cf_order";
		$res = $this->db['mysql_write']->sql_query($sql);

		$i = 0;
		while ( $ob = mysql_fetch_object($res) )
		{
			$i++;

			$this->data['pic_info2'][$i]['id'] = $ob->id;
			$this->data['pic_info2'][$i]['libelle'] = __($ob->libelle);

			if ( empty($this->data['species']['0']['id_species_picture_info2']) )
			{
				$this->data['species']['0']['id_species_picture_info2'] = 0;
			}
		}


// should be removed
		if ( empty($this->data['species']['0']['data']) )
		{

			include_once(LIBRARY . "Glial/parser/flickr/flickr.php");
			include_once (LIB . "wlHtmlDom.php");



//use gliale\flickr;
			$this->data['img'] = flickr::get_photo_info($this->data['species']['0']['url_context']);

			if ( $this->data['img'] )
			{

				unset($tmp);
				$tmp['species_picture_in_wait']['id'] = $this->data['species']['0']['id_photo'];
				$tmp['species_picture_in_wait']['data'] = base64_encode(serialize($this->data['img']));

				$this->db['mysql_write']->set_history_user(9);
				$this->db['mysql_write']->set_history_type(10);
				if ( !$this->db['mysql_write']->sql_save($tmp) )
				{
					die("Problem insertion data dans species_picture_in_wait");
					set_flash("error", "Error", "Hum really strange !");
				}
			}
		}
		else
		{
			$this->data['img'] = unserialize(base64_decode($this->data['species']['0']['data']));
		}

//debug(TMP);

		$file = TMP . "photos_in_wait/" . $this->data['species'][0]["name"];

		if ( $this->data['img'] )
		{
			if ( file_exists($file) )
			{


				$size = getimagesize($file);

				switch ( $size['mime'] )
				{
					case "image/gif":

						$cmd = "rm " . TMP . "photos_in_wait/" . $this->data['species'][0]["name"];
						shell_exec($cmd);

						$cmd = "cd " . TMP . "photos_in_wait/; wget -nc " . $this->data['img']['photo'] . "";

						shell_exec($cmd);

						$elem = explode("/", $this->data['img']['photo']);

						$this->data['species'][0]["name"] = $elem[count($elem) - 1];

						$file = TMP . "photos_in_wait/" . $this->data['species'][0]["name"];
//die();

						break;
					case "image/jpeg":
//echo "Image is a jpeg";
						break;
					case "image/png":
//echo "Image is a png";
						break;
					case "image/bmp":
//echo "Image is a bmp";
						break;
				}
			}
		}

		if ( !file_exists($file) )
		{
			if ( !file_exists($this->data['species']['0']['url_found']) )
			{


				$title = I18n::getTranslation(__("Warning"));
				$msg = I18n::getTranslation(__("This photo doesn't exist on server, we downloaded a new !"));
				set_flash("caution", $title, $msg);

				$cmd = "cd " . TMP . "photos_in_wait/; wget -nc " . $this->data['species']['0']['url_found'] . "";
				shell_exec($cmd);
			}
			else
			{
				die("not found : " . $this->data['species']['0']['url_found']);

				header("location: " . LINK . "photo/admin_crop/");
			}
		}

		if ( $this->data['species'][0]["width"] > SIZE_SITE_MAX )
		{
			include_once LIB . 'imageprocessor.lib.php';

			$ImageProcessor = new ImageProcessor();
			$ImageProcessor->Load(TMP . "photos_in_wait/" . $this->data['species'][0]["name"]);
			$ImageProcessor->Resize(SIZE_SITE_MAX, null, RESIZE_STRETCH);
//$ImageProcessor->Rotate(90);
			$ImageProcessor->Save(TMP . "picture/" . SIZE_SITE_MAX . "/" . $this->data['species'][0]["name"], 100);
		}
		else
		{
			$cmd = "cp " . TMP . "photos_in_wait/" . $this->data['species'][0]["name"] . " " . TMP . "picture/" . SIZE_SITE_MAX . "/" . $this->data['species'][0]["name"];
			shell_exec($cmd);
		}




//debug($this->data['img']);
//debug($this->data['species']);
		$this->set('data', $this->data);



		$size = getimagesize(TMP . "picture/" . SIZE_SITE_MAX . "/" . $this->data['species'][0]["name"]);

		$this->code_javascript[] = "$('#forum_post-id_language').change(function() {
$('#flag').removeAttr('class').addClass($('#forum_post-id_language').val());
});";

		$this->code_javascript[] = "
function preview(img, selection) {
if (!selection.width || !selection.height)
return;

var scaleX = " . SIZE_MINIATURE_SMALL . " / selection.width;
var scaleY = " . SIZE_MINIATURE_SMALL . " / selection.height;

$('#preview img').css({
width: Math.round(scaleX * " . $size[0] . "),
height: Math.round(scaleY * " . $size[1] . "),
marginLeft: -Math.round(scaleX * selection.x1),
marginTop: -Math.round(scaleY * selection.y1)
});

$('#species_picture_main-crop_x1').val(selection.x1);
$('#species_picture_main-crop_y1').val(selection.y1);
$('#species_picture_main-crop_x2').val(selection.x2);
$('#species_picture_main-crop_y2').val(selection.y2);
$('#species_picture_main-crop_weight').val(selection.width);
$('#species_picture_main-crop_height').val(selection.height);    
}

$('#none-id_species_kingdom').change(function() {

$('#none-id_species_phylum').load('" . LINK . "photo/get_options/species_phylum/'+$('#none-id_species_kingdom').val());
$('#none-id_species_class').html('');
$('#none-id_species_order').html('');
$('#none-id_species_family').html('');
$('#none-id_species_genus').html('');
$('#species_picture_main-id_species_main').html('');
$('#species_picture_main-id_species_sub').html('');
});

$('#none-id_species_phylum').change(function() {
$('#none-id_species_class').load('" . LINK . "photo/get_options/species_class/'+$('#none-id_species_phylum').val());
$('#none-id_species_order').html('');
$('#none-id_species_family').html('');
$('#none-id_species_genus').html('');
$('#species_picture_main-id_species_main').html('');
$('#species_picture_main-id_species_sub').html('');
});


$('#none-id_species_class').change(function() {
$('#none-id_species_order').load('" . LINK . "photo/get_options/species_order/'+$('#none-id_species_class').val());
$('#none-id_species_family').html('');
$('#none-id_species_genus').html('');
$('#species_picture_main-id_species_main').html('');
$('#species_picture_main-id_species_sub').html('');
});

$('#none-id_species_order').change(function() {
$('#none-id_species_family').load('" . LINK . "photo/get_options/species_family/'+$('#none-id_species_order').val());
$('#none-id_species_genus').html('');
$('#species_picture_main-id_species_main').html('');
$('#species_picture_main-id_species_sub').html('');
});

$('#none-id_species_family').change(function() {
$('#none-id_species_genus').load('" . LINK . "photo/get_options/species_genus/'+$('#none-id_species_family').val());
$('#species_picture_main-id_species_main').html('');
$('#species_picture_main-id_species_sub').html('');
});

$('#none-id_species_genus').change(function() {
$('#species_picture_main-id_species_main').load('" . LINK . "photo/get_options/species_main/'+$('#none-id_species_genus').val());
$('#species_picture_main-id_species_sub').html('');
});

$('#species_picture_main-id_species_main').change(function() {
$('#species_picture_main-id_species_sub').load('" . LINK . "photo/get_options/species_sub/'+$('#species_picture_main-id_species_main').val());
});

$(function () {
var ias = $('#photo').imgAreaSelect({ aspectRatio: '1:1', 
onSelectChange: preview,
x1: " . $crop[0] . ", y1: " . $crop[1] . ", x2: " . $crop[2] . ", y2: " . $crop[3] . ",
onInit: preview,
minHeight: 250,
minWidth: 250,
handles: true
});

});";
		/*
		  $this->code_javascript[] = "$(function () {




		  /*
		  $('#form-map').setmap({

		  drop: function ( latitude, longitude, fullAddress ) {
		  $('#lat').val(latitude);
		  $('#lng').val(longitude);
		  $('#location').val(fullAddress);
		  },

		  map: {
		  zoom: 4, // zoom level: from 0 to 20; higher is closer
		  zoomControls: 'small', // 'small' or 'large' - defines whether a zoom slider will be present
		  type: 'roadmap', // map type: hybrid, terrain, roadmap, satellite
		  draggable: true, // should the users be able to drag the map
		  disabled: true, // creates a static map with no visual, mouse or keyboard controls
		  streetView: false // streetview controls

		  }


		  });


		  $('#searchLoc').bind('click', function () {
		  $('#form-map').setmap('setAddress', $('#location').val(), function ( latitude, longitude, fullAddress ) {
		  $('#lat').val(latitude);
		  $('#lng').val(longitude);
		  $('#location').val(fullAddress);
		  });
		  });

		  $('#location').bind('keyup', function ( e ) {
		  if( e.keyCode === 13 ) {
		  $('#searchLoc').trigger('click');
		  }
		  });




		  });





		  "; */

		$this->code_javascript[] = "
$('#form-map').setmap().setmap('setAddress', 'Meru District, Eastern, Kenya', function ( lat, lng, address ) {
$('#lat').val(lat);
$('#lng').val(lng);
$('#location').val(adress);
});



$('#searchLoc').bind('click', function () {
$('#form-map').setmap('setAddress', $('#location').val(), function ( latitude, longitude, fullAddress ) {
$('#lat').val(latitude);
$('#lng').val(longitude);
$('#location').val(fullAddress);
});
});

$('#location').bind('keyup', function ( e ) {
if( e.keyCode === 13 ) {
$('#searchLoc').trigger('click');
}
});



";
	}

	function get_options($param)
	{
//debug($param);

		$table = $param[0];
		$id = $param[1];

		$id_table['species_phylum'] = "id_species_kingdom";
		$id_table['species_class'] = "id_species_phylum";
		$id_table['species_order'] = "id_species_class";
		$id_table['species_family'] = "id_species_order";
		$id_table['species_genus'] = "id_species_family";
		$id_table['species_main'] = "id_species_genus";
		$id_table['species_sub'] = "id_species_main";



		$this->layout_name = false;
		

		$sql = "SELECT id, scientific_name as libelle FROM `" . mysql_real_escape_string($table) . "` WHERE `" . $id_table[$table] . "` = " . mysql_real_escape_string($id) . " order By scientific_name";
		$res = $this->db['mysql_write']->sql_query($sql);

		if ( $table == "species_sub" )
		{
			$select = array();
			$i = 0;
			while ( $ob = $this->db['mysql_write']->sql_fetch_object($res) )
			{
				$select[$i]['id'] = $ob->id;

				$split_sub = explode(' ', $ob->libelle);
				$select[$i]['libelle'] = $split_sub[2];

				$i++;
			}

			$this->data['elem'] = $select;
		}
		else
		{
			$this->data['elem'] = $this->db['mysql_write']->sql_to_array($res);
		}



		$this->data['id'] = $id;
		$this->set('data', $this->data);
	}

	function menu()
	{
		
	}

	function admin_movie()
	{

		$module['picture'] = "administration/movie2.gif";
		$module['name'] = __("Movies");
		$module['description'] = __("Import, edit and resize a movie");

		return $module;
	}

	function dl_picture()
	{
		$this->layout_name = false;

		
		$sql = "SELECT * from species_picture_in_wait";


		$sql = "SELECT * from species_picture_in_wait a
inner join species_tree_id b on a.id_species_main = b.id_species_main where b.id_species_family = 438";

		$res = $this->db['mysql_write']->sql_query($sql);


		$i = 0;


		while ( $ob = mysql_fetch_object($res) )
		{

			$file = TMP . "photos_in_wait/" . $ob->name;

			if ( !file_exists($file) )
			{
				$cmd = "cd " . TMP . "photos_in_wait/; wget -nc" . $ob->url_found . "";
				shell_exec($cmd);

				sleep(3);

				$i++;
				echo "image numero : " . $i . "\n";
			}
		}
	}

	function moderate_photo()
	{

		$this->layout_name = "admin";

		$sql = "SELECT  * 
FROM species_picture_main a
INNER JOIN species_tree_nominal b ON a.id_species_main = b.id_nominal
WHERE b.id_family =  '438' and id_species_main=10098
AND a.id_history_etat =1";

		
		$res = $this->db['mysql_write']->sql_query($sql);

		$data['photo'] = $this->db['mysql_write']->sql_to_array($res);


		$this->set('data', $data);


		debug($data);
	}

	function one_shoot_update_photo_id()
	{
		$this->layout_name = false;
		$sql = "select id,name from species_picture_in_wait order by id";

		
		$res = $this->db['mysql_write']->sql_query($sql);


		$i = 0;
		while ( $ob = $this->db['mysql_write']->sql_fetch_object($res) )
		{

			$tab_id = explode("_", $ob->name);

			$photo_id = "flickr_" . $tab_id[0];

			$sql = "UPDATE species_picture_in_wait SET photo_id ='" . $this->db['mysql_write']->sql_real_escape_string($photo_id) . "' WHERE id ='" . $ob->id . "'";
			$this->db['mysql_write']->sql_query($sql);

			if ( $i % 1000 == 0 )
			{
				echo "line : " . $i . "\n";
			}
			$i++;
		}

		exit;

		/*
		 * photo_id
		 * insert into doublons  select photo_id from species_picture_in_wait group by photo_id having count(1) > 1 order by count(1) desc
		 * 
		 * ALTER IGNORE TABLE species_picture_in_wait ADD UNIQUE INDEX(photo_id);
		 * 
		 * 		SELECT c.id ,a . * 
		  FROM species_picture_in_wait a
		  INNER JOIN doublons b ON a.photo_id = b.photo_id
		  LEFT JOIN species_picture_main c ON c.id = a.id
		  where c.id is not null
		 */
	}

}

