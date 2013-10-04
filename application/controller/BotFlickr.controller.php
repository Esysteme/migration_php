<?php

use \glial\parser\flickr\Flickr;
use \glial\synapse\Singleton;
use \glial\shell\Color;


use \glial\synapse\Controller;

class BotFlickr extends Controller
{

	public $module_group = "BOT";

	function index()
	{
		
	}

	function admin_flickr()
	{


		if ( from() == "administration.controller.php" )
		{
			$module['picture'] = "administration/flickr.png";
			$module['name'] = "Flickr";
			$module['description'] = __("Manage picture importation from Flickr's bot");
			return $module;
		}
		$_SQL = singleton::getInstance(SQL_DRIVER);
		$this->layout_name = 'admin';


		$sql = "SELECT *,(select count(1) from species_picture_id d where d.photo_id = b.photo_id group by d.photo_id) as cpt
			FROM species_picture_search a 
			inner join species_picture_id b ON a.id = b.id_species_picture_search
			WHERE a.id_species_main = 9222";

		$res = $this->db['mysql_write']->sql_query($sql);
		$data['img'] = $this->db['mysql_write']->sql_to_array($res);

		$this->set('data', $data);
	}

	function family()
	{

		$res = $this->db['mysql_write']->sql_query($sql);


		while ( $ob = $this->db['mysql_write']->sql_fetch_object($res) )
		{
			
		}
	}

	private function get_licence($licence)
	{
		switch ( $licence['text'] )
		{
			case "Tous droits réservés":
			case "All Rights Reserved":
				$id_licence = 1;
				break;

			case "Certains droits réservés (licence Creative Commons)":
				switch ( $licence['url'] )
				{
					case "http://creativecommons.org/licenses/by/2.0/": $id_licence = 5;
						break;
					case "http://creativecommons.org/licenses/by-sa/2.0/": $id_licence = 6;
						break;
					case "http://creativecommons.org/licenses/by-nd/2.0/": $id_licence = 7;
						break;
					case "http://creativecommons.org/licenses/by-nc/2.0/": $id_licence = 8;
						break;
					case "http://creativecommons.org/licenses/by-nc-sa/2.0/": $id_licence = 9;
						break;
					case "http://creativecommons.org/licenses/by-nc-nd/2.0/": $id_licence = 10;
						break;
					default:
						echo "- " . $licence['url'] . " - ";
						die("need to add a new license CC");
						break;
				}
				break;

			default:
				$id_licence = 11;
				break;
		}

		return $id_licence;
	}

	function looking_for_family()
	{
		
		include_once (LIB . "wlHtmlDom.php");

		$_SQL = singleton::getInstance(SQL_DRIVER);

		$sql = "SELECT id_nominal , nominal,b.*  from species_tree_nominal a
			LEFT JOIN species_translation b ON a.id_nominal = b.id_row AND b.id_table = 7
where a.id_family = 438";

		$res = $this->db['mysql_write']->sql_query($sql);

		while ( $ob = $this->db['mysql_write']->sql_fetch_object($res) )
		{
			$tab_name = array($ob->nominal);

			//, $ob->fr, $ob->en, $ob->de, $ob->es, $ob->nl, $ob->it, $ob->ja, $ob->cs, $ob->pl, $ob->fi, $ob->da, $ob->no, $ob->sk);

			foreach ( $tab_name as $name )
			{
				if ( !empty($name) )
				{
					$data['link_photo'] = flickr::getLinksToPhotos($name);

					foreach ( $data['link_photo'] as $url_to_get )
					{
						$data['img'] = flickr::getPhotoInfo($url_to_get);

						if ( $data['img'] )
						{
							$tmp = array();
							$sql = "SELECT count(1) as cpt, id FROM species_picture_in_wait where photo_id = '" . $data['img']['id'] . "'";
							$res2 = $this->db['mysql_write']->sql_query($sql);
							$ob2 = $this->db['mysql_write']->sql_fetch_object($res2);

							if ( $ob2->cpt != 0 )
							{
								echo "New photo found on : " . $url_to_get . "\n";
								$tmp['species_picture_in_wait']['id'] = $ob2->id;
								$this->db['mysql_write']->set_history_type(13);
							}
							else
							{
								echo "Update photo found on : " . $url_to_get . "\n";
								$tmp['species_picture_in_wait']['id_history_etat'] = 1;
								$this->db['mysql_write']->set_history_type(3);
							}

							$tmp['species_picture_in_wait']['photo_id'] = $data['img']['id'];
							$tmp['species_picture_in_wait']['data'] = base64_encode(serialize($data['img']));
							$tmp['species_picture_in_wait']['id_licence'] = $this->get_licence($data['img']['license']);
							$tmp['species_picture_in_wait']['id_species_main'] = $ob->id_nominal;
							$tmp['species_picture_in_wait']['md5'] = $data['img']['image']['md5'];
							$tmp['species_picture_in_wait']['url_md5'] = md5($url_to_get);
							$tmp['species_picture_in_wait']['url_found'] = $data['img']['photo'];
							$tmp['species_picture_in_wait']['url_context'] = $url_to_get;
							$tmp['species_picture_in_wait']['date_created'] = date('c');


							$tmp['species_picture_in_wait']['author'] = $data['img']['author'];
							$tmp['species_picture_in_wait']['legend'] = $data['img']['legend'];
							$tmp['species_picture_in_wait']['title'] = $data['img']['title'];
							$tmp['species_picture_in_wait']['height'] = intval($data['img']['image']['height']);
							$tmp['species_picture_in_wait']['width'] = intval($data['img']['image']['width']);
							$tmp['species_picture_in_wait']['name'] = $data['img']['name'];

							$tmp['species_picture_in_wait']['location'] = $data['img']['location'];
							$tmp['species_picture_in_wait']['latitude'] = $data['img']['latitude'];
							$tmp['species_picture_in_wait']['longitude'] = $data['img']['longitude'];


							if ( trim($data['img']['image']['mime']) === "image/jpeg" )
							{
								$this->db['mysql_write']->set_history_user(9);


								if ( !$this->db['mysql_write']->sql_save($tmp) )
								{
									echo "#####################";
									debug($this->db['mysql_write']->sql_error());
									//die("Problem insertion data dans species_picture_in_wait");
									sleep(5);
								}
							}
							else
							{
								echo 'mine not good :' . $data['img']['image']['mime'] . "\n";
								sleep(5);
							}
						}
						else
						{
							echo "problem to get img !\n";
						}
						sleep(3);
					}
				}
			}
			
			exit;
		}
// select * from 
	}

	function update_search()
	{


		

		

		$sql = "SELECT id_nominal , a.nominal,b.id_species_sub,   b.language, b.text as name
		from species_tree_nominal a
		INNER JOIN scientific_name_translation b ON a.id_nominal= b.id_species_main
		INNER JOIN language c ON b.language = c.iso3
		LEFT JOIN species_picture_search d ON d.tag_search = b.text AND c.iso3 = d.language
		where a.id_family = 438 AND b.id_species_sub = 0 AND d.total_found is null
		order by rand()";

		$res = $this->db['mysql_write']->sql_query($sql);

		while ( $ob = $this->db['mysql_write']->sql_fetch_object($res) )
		{

			echo Color::getColoredString($ob->nominal, "black", "green") . "\n";


			$data['link_photo'] = Flickr::getLinksToPhotos($ob->name);

			$search = array();
			$search['species_picture_search']['id_species_main'] = $ob->id_nominal;
			$search['species_picture_search']['tag_search'] = $ob->name;
			$search['species_picture_search']['language'] = $ob->language;
			$search['species_picture_search']['total_found'] = (int) count($data['link_photo']);
			$search['species_picture_search']['id_user_main'] = 9;
			$search['species_picture_search']['date'] = date('Y-m-d H:i:s');
			$search['species_picture_search']['id_species_source_main'] = 7;

			$id_search = $this->db['mysql_write']->sql_save($search);

			if ( $id_search )
			{
				echo '[';
				$i=1;
				foreach ( $data['link_photo'] as $url_to_get )
				{
					//debug( $url_to_get);
					echo $i.", ";
					$i++;
					
					$author = array();
					$author['species_author']['surname'] = $url_to_get['author'];
					$author['species_author']['date'] = date('Y-m-d H:i:s');

					$id_author = $this->db['mysql_write']->sql_save($author);

					if (! $id_author )
					{
						//debug($author);
						debug($this->db['mysql_write']->sql_error());
						echo Color::getColoredString("Impossible to insert this author", "white", "red") . "\n";
					}
					
					//delete from species_author where id > 1600
					
					
					if ( empty($url_to_get['img']['url']) )
					{
						print_r($url_to_get);
						die("pb pic");
					}

					$pic_id = array();
	
					$pic_id['species_picture_id']['photo_id'] = flickr::get_photo_id($url_to_get['url']);
					$pic_id['species_picture_id']['id_species_author'] = $id_author;
					$pic_id['species_picture_id']['link'] = $url_to_get['url'];
					$pic_id['species_picture_id']['miniature'] = $url_to_get['img']['url'];
					$pic_id['species_picture_id']['date'] = date('Y-m-d H:i:s');

					//debug($pic_id);
					
					$id_picture = $this->db['mysql_write']->sql_save($pic_id);
					
					if ( ! $id_picture)
					{
						//debug($pic_id);
						debug($this->db['mysql_write']->sql_error());
						
						echo Color::getColoredString("Impossible to insert picture", "white", "red") . "\n";
					}
					else
					{
						$pic_id = array();
						$pic_id['link__species_picture_id__species_picture_search']['id_species_picture_id'] = $id_picture;
						$pic_id['link__species_picture_id__species_picture_search']['id_species_picture_search'] = $id_search;
						$pic_id['link__species_picture_id__species_picture_search']['date'] = date('Y-m-d H:i:s');

						if ( !$this->db['mysql_write']->sql_save($pic_id) )
						{
							//debug($pic_id);
							debug($this->db['mysql_write']->sql_error());
							echo Color::getColoredString("ERROR INSERTION link__species_picture_id__species_picture_search", "white", "red") . "\n";
							
						}
					}
				}
				echo "]\n";
			}
			else
			{
				
				debug($this->db['mysql_write']->sql_error());
				echo Color::getColoredString("ERROR INSERTION species_picture_search", "white", "red") . "\n";
			}
			
		}

		exit;
	}

	function test()
	{

		$this->layout_name = false;

		include_once (LIB . "wlHtmlDom.php");


		
		
		
		print_r($data);

		//http://farm8.staticflickr.com/7022/6657652857_34d38960ab_z.jpg
		//http://farm8.staticflickr.com/7022/6657652857_34d38960ab_b.jpg

		exit;
	}

	function test2()
	{

		$this->layout_name = false;



		$url = "http://www.flickr.com/photos/75299599@N00/6657652857/";
		$url = "http://www.flickr.com/photos/81609886@N05/9304372638/in/photostream/";
		$url2 = "http://www.flickr.com/photos/gregbm/map/?photo=6657652857";

				

		$res = Flickr::getPhotoInfo("http://www.flickr.com/photos/gregbm/9570385391/");
        $data = '{"id":"flickr_9570385391","id_photo":"9570385391","url":{"main":"http:\/\/www.flickr.com\/photos\/gregbm\/9570385391\/","img_z":"http:\/\/farm4.staticflickr.com\/3782\/9570385391_9eae844e46_z.jpg","exif":"http:\/\/www.flickr.com\/photos\/gregbm\/9570385391\/meta\/","all_size":"http:\/\/www.flickr.com\/photos\/gregbm\/9570385391\/sizes\/sq\/"},"id_author":"gregbm","legend":"The most common parrot around Rubio Plantation. Quite noisy too.","author":"Greg Miles","date-taken":"August 10, 2013","camera":"Canon EOS-1D X","tag":["Purple-bellied Lory","Lorius hypoinochrous","Rubio Plantation Retreat","Karu","New Ireland","Papua New Guinea"],"license":{"text":"All Rights Reserved","url":"\/help\/general\/#147"},"img":{"size_available":["q","t","s","n","m","z","c","l"],"best":"l","url":{"img":"http:\/\/farm4.staticflickr.com\/3782\/9570385391_9eae844e46_b.jpg"}},"exif":{"Dates":{"Taken on":"August 10, 2013 at 2.54PM PDT","Posted to Flickr":"August 22, 2013 at 2.06PM PDT"},"Exif data":{"Camera":"Canon EOS-1D X","Exposure":"0.003 sec (1\/400)","Aperture":"f\/7.1","Focal Length":"700 mm","ISO Speed":"1000","Exposure Bias":"+2\/3 EV","Flash":"Off, Did not fire","Image Width":"4608","Image Height":"3072","Bits Per Sample":"8 8 8","Photometric Interpretation":"RGB","Orientation":"Horizontal (normal)","Samples Per Pixel":"3","X-Resolution":"72 dpi","Y-Resolution":"72 dpi","Software":"Adobe Photoshop CS6 (Macintosh)","Date and Time (Modified)":"2013:08:20 21:56:17","Artist":"Greg B Miles","YCbCr Positioning":"Co-sited","Copyright":"Greg B Miles All rights reserved","Exposure Program":"Program AE","Sensitivity Type":"Recommended Exposure Index","Recommended Exposure Index":"1000","Date and Time (Original)":"2013:08:10 14:54:53","Date and Time (Digitized)":"2013:08:10 14:54:53","Max Aperture Value":"5.7","Metering Mode":"Multi-segment","Sub Sec Time":"81","Sub Sec Time Original":"81","Sub Sec Time Digitized":"81","Color Space":"sRGB","Focal Plane X-Resolution":"5091.712707 dpi","Focal Plane Y-Resolution":"5069.306931 dpi","Custom Rendered":"Normal","Exposure Mode":"Auto","White Balance":"Auto","Scene Capture Type":"Standard","Lens Info":"700mm f\/0","Lens Model":"EF500mm f\/4L IS USM +1.4x","Lens Serial Number":"0000000000","Compression":"JPEG (old-style)","Coded Character Set":"UTF8","By-line":"Greg B Miles","Object Name":"Purple-bellied Lory","Date Created":"2013:08:10","Time Created":"14:54:53+00:00","Copyright Notice":"Greg B Miles All rights reserved","Global Angle":"30","Global Altitude":"30","Copyright Flag":"True","Photoshop Quality":"12","Photoshop Format":"Standard","Progressive Scans":"3 Scans","XMPToolkit":"Adobe XMP Core 5.3-c011 66.145661, 2012\/02\/06-14:56:27","Rating":"0","Metadata Date":"2013:08:20 21:56:17+10:00","Format":"image\/jpeg","Rights":"Greg B Miles All rights reserved","Title":"Purple-bellied Lory","Creator":"Greg B Miles","Lens":"EF500mm f\/4L IS USM +1.4x","Lens ID":"143","Image Number":"0","Approximate Focus Distance":"79.9","Flash Compensation":"0","Color Mode":"RGB","ICCProfile Name":"sRGB IEC61966-2.1","Original Document ID":"49CA919DBD1556E13D81536C5D7E9D47","History Action":"saved","History Instance ID":"xmp.iid:2C0005261F206811822AD1F812E82D80","History When":"2013:08:20 21:56:17+10:00","History Software Agent":"Adobe Photoshop CS6 (Macintosh)","History Changed":"\/","Marked":"True","Viewing Conditions Illuminant Type":"D50","Measurement Observer":"CIE 1931","Measurement Flare":"0.999%","Measurement Illuminant":"D65","Color Transform":"YCbCr"}}}';
       
		
		//print_r(json_decode($data,true));
		
	
		echo json_encode($res);
		//$diff = array_diff(, $res);
		
		//print_r($diff);


		exit;
	}

	function import_geolocalisation()
	{
		$this->layout_name = false;
		include_once(LIBRARY . "Glial/parser/flickr/flickr.php");
		include_once (LIB . "wlHtmlDom.php");
		

		$sql = "SELECT * FROM species_picture_main where data != ''";

		$res = $this->db['mysql_write']->sql_query($sql);

		$i = 0;
		while ( $ob = $this->db['mysql_write']->sql_fetch_object($res) )
		{

			$data = unserialize(base64_decode($ob->data));


			if ( !empty($data['gps']['latitude']) && $data['gps']['latitude'] != 0 )
			{
				$i++;
				echo $i . " [" . date("Y-m-d H:i:s") . "] photo : " . $data['url'] . "\n";

				$sql = "UPDATE species_picture_main SET latitude = '" . $data['gps']['latitude'] . "', longitude = '" . $data['gps']['longitude'] . "' WHERE id = '" . $ob->id . "'";
				$this->db['mysql_write']->sql_query($sql);

				/*
				  $pic = array();

				  $pic['species_picture_main']['id'] = $ob->id;
				  $pic['species_picture_main']['latitude'] = $data['gps']['latitude'];
				  $pic['species_picture_main']['longitude'] = $data['gps']['longitude'];

				  echo $i . " [" . date("Y-m-d H:i:s") . "] photo : ".$data['url']."\n";

				  if (! $this->db['mysql_write']->sql_save($pic))
				  {
				  debug($pic);
				  debug($this->db['mysql_write']->sql_error());
				  die();
				  }

				  unset($pic);
				 */
			}
		}
	}
	
	
	function displayPicture($id_species)
	{
		$sql = "SELECT * FROM ";
		
	}

}

