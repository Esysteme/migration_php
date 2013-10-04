<?php
use \glial\synapse\Controller;

class Ibc extends Controller {

	function import_species() {

		
		$this->view = false;
		$this->layout_name = false;
		
		include_once(LIBRARY . "Glial/parser/ibc/ibc.php");
		include_once (LIB . "wlHtmlDom.php");

		

		$data = glial\parser\ibc\ibc::get_species_from_family();

		
		$i=0;

		foreach ($data as $line)
		{
			$i++;
			$sql = "SELECT * FROM species_main WHERE scientific_name = '" . $line['scientific_name'] . "'";

			$res = $this->db['mysql_write']->sql_query($sql);

			if ($this->db['mysql_write']->sql_num_rows($res) == 1)
			{
				echo $i . " [" . date("Y-m-d H:i:s") . "] species : ".$line['scientific_name']."\n";
			
				$ob = $this->db['mysql_write']->sql_fetch_object($res);
				
				$source = array();
				$source['species_source_detail']['id_species_main'] = $ob->id;
				$source['species_source_detail']['id_species_sub'] = 0;
				$source['species_source_detail']['id_species_source_main'] = 5;
				$source['species_source_detail']['reference_url'] = $line['url'];
				$source['species_source_detail']['reference_id'] = $line['reference_id'];
				$source['species_source_detail']['date_created'] = date("c");
				$source['species_source_detail']['date_updated'] = date("c");

				/*
				$out = $this->db['mysql_write']->sql_save($source);
				
				if (! $out)
				{
					debug($source);
					debug($this->db['mysql_write']->sql_error());
					die();
				}*/
			}
			else
			{
				echo "Spceis not found : ".$line['scientific_name']."\n";
			}
		}





		//debug($data);
		
	}
	
	
	function get_order_and_family()
	{
		$this->view = false;
		$this->layout_name = false;
		
		include_once(LIBRARY . "Glial/parser/ibc/ibc.php");
		include_once (LIB . "wlHtmlDom.php");

		

		$data = glial\parser\ibc\ibc::get_order_and_family();
		
		debug($data);
		
	}
	
	
	function get_pic()
	{
		$this->view = false;
		$this->layout_name = false;
		
		include_once(LIBRARY . "Glial/parser/ibc/ibc.php");
		include_once (LIB . "wlHtmlDom.php");

		
		
		//$data = glial\parser\ibc\ibc::get_photo_and_infos('grey-shrike-thrush-colluricincla-harmonica/male-singing');
		$data = glial\parser\ibc\ibc::get_photo_and_infos('brown-kiwi-apteryx-australis/northern-brown-kiwi-now-considered-distinct-species-photographed-');
		
		
		//$data = glial\parser\ibc\ibc::get_video_and_infos('great-white-pelican-pelecanus-onocrotalus/large-group-flight');
		
		
		
		debug($data);
		
	}

}