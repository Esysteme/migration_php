<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
use \glial\synapse\Controller;


class BotGeoNews extends Controller
{

	public $module_group = "species";

	/*
	 * get all kmz from directory
	 * unzip kmz to kml 
	 * post kml to get are by polygon
	 */

	function test()
	{
		$this->view = false;
		$this->layout_name = false;
		include_once LIBRARY . 'Glial/kml/kml.php';
		
		
		$path_kml = "/home/www/species/data/range_map/kml/Lonchura_caniceps.kml";
		$path_kml = "/home/www/species/data/range_map/kml/Lonchura_hunsteini.kml";
		
		$data_kml = \glial\kml\kml::extract_data($path_kml);
		debug($data_kml);
	}
	
	function get_area()
	{

		$this->view = false;
		$this->layout_name = false;

		include_once LIBRARY . 'Glial/extract/html_dom.php';
		include_once LIBRARY . 'Glial/parser/geo_news/geo_news.php';

		$sql = "SELECT * FROM species_tree_nominal where class='Aves'";
		

		$res = $this->db['mysql_write']->sql_query($sql);

		$dir = "/home/www/species/data/range_map/kmz/";

		if ( is_dir($dir) )
		{
			$dh = opendir($dir);
			if ( $dh )
			{
				while ( ($file = readdir($dh)) !== false )
				{
					if ( $file == "." || $file == ".." )
					{
						continue;
					}

					if ( filetype($dir . $file) == "file" )
					{

						//unzip file
						$path_parts = pathinfo($file);
						$name = $path_parts['filename'];
						\glial\parser\geo_news\geo_news::kmz_to_kml($name);


						//ask geo_news to get area in meter squared
						$path_file = "/home/www/species/data/range_map/kml/" . $name . ".kml";
						\glial\parser\geo_news\geo_news::get_area_kml($path_file);

						echo "\n---------------------------\n";
						echo "Scientific name : " . $name . " \n";
						echo "------------------------------\n";
						sleep(1);
					}
				}
				closedir($dh);
			}
		}
	}

	function import_to_db()
	{

		$this->view = false;
		$this->layout_name = false;

		include_once LIBRARY . 'Glial/kml/kml.php';
		

		$species = "Lonchura_hunsteini";

		$path_kml = "/home/www/species/data/range_map/kml/" . $species . ".kml";
		$path_area = "/home/www/species/data/range_map/area/" . $species . ".kml";


		$data_kml = \glial\kml\kml::extract_data($path_kml);
		$data_area = \glial\kml\kml::extract_data($path_area);


		$squared_meter = array();
		foreach ( $data_area['Document']['Folder']['Placemark'] as $polygon )
		{
			$elem = explode(" ", $polygon['description']);

			$area = (float) substr($elem[5], 1);

			$squared_meter[] = round($area / 1000000, 2);
		}
		
		debug($squared_meter);
		
		$total = array_sum($squared_meter);
		$nb_polygon = count($squared_meter);

		$species_name = str_replace("_", " ", $species);
		$sub_species = explode(" ", $species_name);

		$sql = "SELECT a.id as id_species, b.id as id_species_sub FROM species_main a
			INNER JOIN species_sub b ON a.id = b.id_species_main
			where b.scientific_name = '" . $species_name . " " . $sub_species[1] . "'";
		$res = $this->db['mysql_write']->sql_query($sql);

		while ( $ob = $this->db['mysql_write']->sql_fetch_object($res) )
		{

			$sql2 = "INSERT INTO range_map_main (id_species_main, km_squared) VALUES (" . $ob->id_species . "," . $total . ")";
			$this->db['mysql_write']->sql_query($sql2);


			$sql10 = "SELECT id from range_map_main where id_species_main =" . $ob->id_species . "";
			$res10 = $this->db['mysql_write']->sql_query($sql10);


			while ( $ob10 = $this->db['mysql_write']->sql_fetch_object($res10) )
			{
				$id_range_map_main = $ob10->id;

				$i = 0;
				foreach ( $data_kml['Document']['Folder']['Placemark'] as $Placemark )
				{
					$i++;

					// rowcount of polygon to get area
					$j = -1;
					foreach ( $Placemark['MultiGeometry']['Polygon'] as $Polygon )
					{
						$j++;


						$sql3 = "INSERT INTO range_map_polygon (id_species_main,id_species_sub,id_range_map_main,id_range_map_legend, km_squared, placemark) 
					VALUES (" . $ob->id_species . "," . $ob->id_species_sub . "," . $id_range_map_main . ",1," . $squared_meter[$j] . "," . $i . ")";
						$this->db['mysql_write']->sql_query($sql3);
						

						$coordinates = explode(' ',trim( $Polygon['outerBoundaryIs']['LinearRing']['coordinates']));

						
						$dd = count($Polygon['innerBoundaryIs']);
						
						
						
						debug($coordinates);

						$sql11 = "SELECT max(id) as id from range_map_polygon";
						$res11 = $this->db['mysql_write']->sql_query($sql11);


						while ( $ob11 = $this->db['mysql_write']->sql_fetch_object($res11) )
						{
							$id_range_map_polygon = $ob11->id;
							
							foreach ( $coordinates as $point )
							{
								$coord = explode(',', trim($point));

								$sql4 = "INSERT INTO range_map_coordinates (id_range_map_polygon, latitude, longitude) 
								VALUES (" . $id_range_map_polygon . "," . $coord[0] . "," . $coord[1] . ")";
								$this->db['mysql_write']->sql_query($sql4);
							}
						}
					}
				}
				
				if ($nb_polygon != $j+1)
				{
					echo "Nb polygon : ".$nb_polygon."\n";
					echo "Nb found : ".($j+1)."\n";
					
					
					die("forgot some figure");
				}
		
				
			}
			
			
		}
	}

}