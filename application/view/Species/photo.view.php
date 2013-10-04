<?php


use \Glial\Species\Species;

//include_once(LIBRARY . "glial/species/species.php");

$scientific_name = "";



echo '<div id="photo">';
foreach ($data['photo'] as $var)
{

	if (!empty($var['scientific_name']))
	{
		if ($scientific_name != $var['scientific_name'])
		{
			echo "</ul>";
            echo '<div class="clear"></div>';
            
            
			echo '<h3>' . $var['scientific_name'] . '</h3>';
            echo '<ul class="onglet_pic">';
			$scientific_name = $var['scientific_name'];
		}
	}

	$species_name = str_replace(" ", "_", $var['nominal']);
	$path = "Eukaryota/{$var['kingdom']}/{$var['phylum']}/{$var['class']}/{$var['order']}/{$var['family']}/{$var['genus']}/" . $species_name;
	$picture_name = $var['id_photo'] . "-" . $species_name . ".jpg";
	$img = FARM1 . "crop/" . SIZE_MINIATURE_SMALL . "x" . SIZE_MINIATURE_SMALL . "/" . $path . DS . $picture_name;
	$url = LINK . "species/nominal/" . $species_name . "/photo/photo_detail/" . $var['id_photo'] . "/";


        $pic['data-link'] = '';
        $pic['data-target'] = "";
        $pic['photo'] =  $img;
        $pic['url'] = $url;
        $pic['display-name'] = $var['nominal'];
        $pic['name'] = $scientific_name;
        
        
        \Glial\Species\Species::miniature($pic);

	//Species::html_pic($url, $img, $var['info_photo'], $species_name);
}

echo "</div>";
