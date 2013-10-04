<?php

use \glial\synapse\Controller;


class BotIucn extends Controller {

	public $module_group = "BOT";

	function index() {
		
	}

	function admin_iucn() {

		if (from() == "administration.controller.php")
		{
			$module['picture'] = "administration/iucn.jpg";
			$module['name'] = "IUCN red list"; //iucn red list.jpg
			$module['description'] = __("Manage IUCN's bot");
			return $module;
		}
	}

	function get_species_iucn() {

		
		include_once(LIBRARY . "Glial/parser/iucn/iucn.php");
		include_once(LIBRARY . "Glial/species/species.php");
		include_once (LIB . "wlHtmlDom.php");


		$k = 0;
		for ($i = 1277; $i > 500; $i--) // 1277 pages is a max of IUCN
		{
			$data = iucn::get_all_species($i);

			foreach ($data as $species)
			{
				$sql = "SELECT * FROM species_main where scientific_name = '" . $this->db['mysql_write']->sql_real_escape_string($species['scientific_name']) . "'";
				$res = $this->db['mysql_write']->sql_query($sql);


				$summary = iucn::get_species_summary($species['reference_id']);
				//print_r($summary);


				if ($this->db['mysql_write']->sql_num_rows($res) == 1)
				{
					$ob = $this->db['mysql_write']->sql_fetch_object($res);
					$id_species = $ob->id;
				}
				else
				{
					$taxo = $summary['taxo'];
					$id_species = species_tree::add_species($taxo['Kingdom'], $taxo['Phylum'], $taxo['Class'], $taxo['Order'], $taxo['Family'], $taxo['Genus'], $taxo['scientific_name']);
				}

				$sql = "select * FROM species_source_detail WHERE reference_id ='" . $species['reference_id'] . "' AND id_species_source_main=1";
				$res2 = $this->db['mysql_write']->sql_query($sql);

				$save = array();
				if ($this->db['mysql_write']->sql_num_rows($res2) == 0)
				{
					$save['species_source_detail']['date_created'] = $species['date'];
				}
				else
				{
					$ob2 = $this->db['mysql_write']->sql_fetch_object($res2);
					$save['species_source_detail']['id'] = $ob2->id;
				}

				$save['species_source_detail']['reference_url'] = $species['url'];
				$save['species_source_detail']['reference_id'] = $species['reference_id'];
				$save['species_source_detail']['date_updated'] = $species['date'];
				$save['species_source_detail']['id_species_source_main'] = 1;
				$save['species_source_detail']['id_species_main'] = $id_species;

				$id_species_source_detail = $this->db['mysql_write']->sql_save($save);

				if (!$id_species_source_detail)
				{
					debug($this->db['mysql_write']->sql_error());
					debug($save);
					die();
				}
				else
				{
					$species_source_data = array();
					$species_source_data['species_source_data']['id_species_source_detail'] = $id_species_source_detail;
					$species_source_data['species_source_data']['type'] = "summary";
					$species_source_data['species_source_data']['date'] = date("c");
					$species_source_data['species_source_data']['data'] = base64_encode(gzencode(json_encode($summary), 9));


					/*
					  $sql = "INSERT INTO species_source_data SET
					  id_species_source_detail = '".$id_species_source_detail."',
					  id_species_source_detail = 'summary',
					  id_species_source_detail = now(),
					  id_species_source_detail = '".gzencode(json_encode($summary),9)."';

					  "
					 */

					if (!$this->db['mysql_write']->sql_save($species_source_data))
					{
						debug($this->db['mysql_write']->sql_error());
						debug($species_source_data);
						die();
					}
				}

				sleep(2);

				$k++;
				echo $k . " [" . date("Y-m-d H:i:s") . "] " . $species['scientific_name'] . " added !\n";
			}

			sleep(5);
		}


		exit;
	}

	function get_species() {
		//
		include_once(LIBRARY . "Glial/parser/iucn/iucn.php");
		include_once (LIB . "wlHtmlDom.php");

		$data = iucn::get_species_summary('106008709');
		//$data = iucn::get_species_bibliography('106008709');

		print_r($data);

		exit;
	}

	function test_import() {
		
		include_once(LIBRARY . "Glial/parser/iucn/iucn.php");
		include_once(LIBRARY . "Glial/species/species.php");

		include_once (LIB . "wlHtmlDom.php");



		$data = iucn::get_species_summary('178043');
		print_r($data);

		$taxo = $data['taxo'];

		$id_species = species_tree::add_species($taxo['Kingdom'], $taxo['Phylum'], $taxo['Class'], $taxo['Order'], $taxo['Family'], $taxo['Genus'], $taxo['scientific_name']);
		//$id_species = species_tree::add_species("WWW", "WWW", "WWW", "WWW", "WWW", "WWW", "WWW");

		debug($id_species);
		//debug($id_phylum);
		exit;
	}

	function get_classification_schemes() {

		$this->layout_name = false;

		include_once(LIBRARY . "Glial/parser/iucn/iucn.php");
		include_once(LIBRARY . "Glial/species/species.php");
		include_once (LIB . "wlHtmlDom.php");

		


		$sql = "select id, id_species_main, reference_id FROM species_source_detail WHERE id_species_source_main=1 order by reference_id";
		$res = $this->db['mysql_write']->sql_query($sql);


		$i = 1;
		while ($ob = $this->db['mysql_write']->sql_fetch_object($res)) {

			$habitat = iucn::get_species_classification($ob->reference_id);

			$species_source_data = array();
			$species_source_data['species_source_data']['id_species_source_detail'] = $ob->id;
			$species_source_data['species_source_data']['type'] = "classification";
			$species_source_data['species_source_data']['date'] = date("c");
			$species_source_data['species_source_data']['data'] = base64_encode(gzencode(json_encode($habitat), 9));

			if (!$this->db['mysql_write']->sql_save($species_source_data))
			{
				debug($this->db['mysql_write']->sql_error());
				debug($species_source_data);
				die();
			}

			echo $i . " reference_id : " . $ob->reference_id . "\n";

			sleep(1);
			$i++;
		}

		exit;
	}

	function get_bibliography() {

		$this->layout_name = false;

		include_once(LIBRARY . "Glial/parser/iucn/iucn.php");
		include_once(LIBRARY . "Glial/species/species.php");
		include_once (LIB . "wlHtmlDom.php");

		


		$sql = "select a.id, id_species_main, reference_id FROM species_source_detail a
			inner join species_source_data b on b.id_species_source_detail = a.id
			WHERE id_species_source_main=1
			GROUP BY reference_id
			HAVING count(1) =1 
order by FLOOR(`reference_id`)";
		$res = $this->db['mysql_write']->sql_query($sql);


		$i = 1;
		while ($ob = $this->db['mysql_write']->sql_fetch_object($res)) {

			$habitat = iucn::get_species_bibliography($ob->reference_id);

			$species_source_data = array();
			$species_source_data['species_source_data']['id_species_source_detail'] = $ob->id;
			$species_source_data['species_source_data']['type'] = "bibliography";
			$species_source_data['species_source_data']['date'] = date("c");
			$species_source_data['species_source_data']['data'] = base64_encode(gzencode(json_encode($habitat), 9));

			if (!$this->db['mysql_write']->sql_save($species_source_data))
			{
				debug($this->db['mysql_write']->sql_error());
				debug($species_source_data);
				die();
			}

			echo $i . " reference_id : " . $ob->reference_id . "\n";

			sleep(1);
			$i++;
		}

		exit;
	}

	function import_habitat() {
		echo "Start !\n";


		$this->layout_name = false;

		
		$sql = "SELECT * FROM species_source_data where type ='classifica'";
		$res = $this->db['mysql_write']->sql_query($sql);

		$i =0;
		while ($ob = $this->db['mysql_write']->sql_fetch_object($res)) {
			$i++;

			//echo $ob->data."\n";


			$data = json_decode(gzinflate(substr(base64_decode($ob->data), 10, -8)),true);

			if (!empty($data['Habitats']))
			{
				foreach ($data['Habitats'] as $line)
				{
					$habitat = array();
					$habitat['species_habitat']['rank'] = $line['code'];
					$habitat['species_habitat']['libelle'] = trim($line['libelle']);
					
					if (! $this->db['mysql_write']->sql_save($habitat))
					{
						debug($habitat);
						exit;
						
					}
				}
				
				echo $i."[".date("Y-m-d H:i:s")."] {$line['code']} {$line['libelle']}\n";
			}

			//debug($data);
		}
		exit;
	}

	
	
	function import_bibliography() {
		echo "Start !\n";


		$this->layout_name = false;

		
		$sql = "SELECT * FROM species_source_data where type ='bibliograp'";
		$res = $this->db['mysql_write']->sql_query($sql);

		$i =0;
		while ($ob = $this->db['mysql_write']->sql_fetch_object($res)) {
			$i++;

			//echo $ob->data."\n";


			$data = json_decode(gzinflate(substr(base64_decode($ob->data), 10, -8)),true);


			
			if (!empty($data['book']))
			{
				foreach ($data['book'] as $line)
				{
					
					if (empty($line))
					{
						continue;
					}
					
					$book = array();
					
					$book['bibliography']['text'] = $this->db['mysql_write']->sql_real_escape_string(trim($line));
					$book['bibliography']['crc32'] = crc32($this->db['mysql_write']->sql_real_escape_string(trim($line)));
					echo $i."[".date("Y-m-d H:i:s")."] {$line}\n";
					if (! $this->db['mysql_write']->sql_save($book))
					{
						debug($book);
						debug($this->db['mysql_write']->sql_error());
						exit;
						
					}
				}
				
				//echo $i."[".date("Y-m-d H:i:s")."] {$line}\n";
			}

			//debug($data);
		}
		exit;
	}
}

//260177
//