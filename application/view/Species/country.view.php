<?php


$libelle = "";


foreach ($data['species'] as $var)
{

            
	if ($var['libelle'] != $libelle)
	{
		if ($libelle != "")
		{
			echo "</ul>";
            echo '<div class="clear"></div>';
		}
		
        
		
		echo "<h3>".$var['libelle']."</h3>";
		
		$libelle = $var['libelle'];
		echo '<ul class="onglet_pic">';
	}

	
	$url = LINK . '/species/nominal/' . str_replace(" ", "_", $var['scientific_name']) . '/';
	
	
	if ($var['id_species_picture_main'] == "")
	{
		$img = IMG . 'main/nopictureavailable.png';
	}
	else
	{
		$img = FARM1 . 'crop/158x158/Eukaryota/'.$var['kingdom'] .'/'.$var['phylum'].'/'.$var['class'].'/'
			.$var['order'].'/'.$var['family'].'/'.$var['genus'].'/'.str_replace(' ','_',$var['nominal']).'/'.$var['id_species_picture_main'].'-'.str_replace(' ','_',$var['nominal']).".jpg";
	}
	
    
            
        $pic['data-link'] = '';
        $pic['data-target'] = "";
        $pic['photo'] =  $img;
        $pic['url'] = $url;
        $pic['display-name'] = $var['nominal'];
        $pic['name'] = $var['nominal'];
        
        
        \Glial\Species\Species::miniature($pic);

	//Species::html_pic($url, $img, $var['scientific_name'], $var['scientific_name']);
}

