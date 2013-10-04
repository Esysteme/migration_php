<?php

use \Glial\I18n\I18n;

if (! defined('SIZE_MINIATURE_BIG'))
{
	define("SIZE_MINIATURE_BIG", 250);
}

	
echo "<div class=\"title_box\">".__("The last photos")."</div>";

		echo '<div id="slider-holder"> 
			<div id="slider" class="jdslider">';
			
foreach($data as $pic)
{
	$pic['species'] = str_replace(" ","_",$pic['species_']);
	$url = "crop/".SIZE_MINIATURE_BIG."x".SIZE_MINIATURE_BIG."/Eukaryota/".$pic['kingdom']."/".$pic['phylum']."/".$pic['class']."/".$pic['order2']."/".$pic['family']."/".$pic['genus']."/".$pic['species']."/".$pic['id_photo']."-".$pic['species'].".jpg";
	
	if (! file_exists(TMP.$url))
	{
		if (generate_crop($pic,SIZE_MINIATURE_BIG,DATA, TMP."crop/"))
		{
			echo "not good";
		}
		
	}
	
	///				thecus/www/species/image/crop/158x158/Eukaryota/Animalia/Chordata/Aves/Passeriformes/Estrildidae/Lonchura/Lonchura_striata/281-Lonchura_striata.jpg
	//<img src="/thecus/www/species/image/crop/250x250/Eukaryota/Animalia/Chordata/Aves/Passeriformes/Estrildidae/Lonchura/Lonchura striata/281-Lonchura striata.jpg" width="250" height="250">
	echo '<a href="'.LINK.'species/nominal/'.$pic['species'].'/photo/photo_detail/'.$pic['id_photo'].'/"> 
	<img src="'.FARM1.''.$url.'" width="250" height="250" alt="'.$pic[I18n::Get()].' ('.$pic['species_'].')" title="'.$pic[I18n::Get()].' ('.$pic['species_'].')" /> 
	<span>'.$pic[I18n::Get()].'</span> 
	</a>';
}


echo '</div></div>';
/*
foreach($data as $ob)
{

	echo "<a href=\"\"><img alt=\"".species::GetSpeciesNameById($ob->IdSpeciesMain,$Language->Get())."\" class=\"thumb\" height=\"74\" id=\"thumb{$i}\" src=\"{$url}\" title=\"".species::GetSpeciesNameById($ob->IdSpeciesMain,$Language->Get())."\" width=\"74\" /></a>";
	$i++;
}
*/
