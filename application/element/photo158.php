<?php
/*
echo "<div id=\"photo\">";
if (!empty($data['tab_img']))
{
	foreach ($data['tab_img'] as $var)
	{
		$var['name2'] = $var['name'];

		$selection = "passive";
		$tabssp = explode(' ', $var['name']);
		if (count($tabssp) === 3)
		{
			$var['name2'] = substr($tabssp[0], 0, 1) . ". " . substr($tabssp[1], 0, 1) . ". " . $tabssp[2];

			if ($tabssp[2] == $tabssp[1])
			{
				$selection = "active";
				//$_GET['id_ssp'] = $ob2->id;
			}
			else
				$selection = "passive";
		}

		echo "<span class=\"shadowImage\">";
		echo '<div class="photo_link ' . $selection . '">';
		echo '<a href="' . $var['url'] . '" data-target="'.$id_node.'" data-link="species-distribution_description">';

		echo '<div class="bigleaderpix">
<div class="caption">
<p>' . $var['name2'] . '</p>
</div>
<div class="bigleaderlien"></div>
<img width="158" height="158" alt="' . $var['name'] . '" title="' . $var['name'] . '" src="' . $var['photo'][0] . '">
</div>';

		echo '</a></div></span>';
	}
}
echo "</div>";  //id=photo end
echo "<div class=\"clear\"></div>";


*/

//debug($data);

$id_node = "node-".uniqid();

echo '<ul class="menu_tab onglet_pic">';
if (!empty($data['tab_img']))
{
	foreach ($data['tab_img'] as $var)
	{
		$pic = array();
        $pic['name'] = $var['name'];
        $pic['display-name'] = $var['name'];
		$pic['class'] = "";
		$tabssp = explode(' ', $var['name']);
		if (count($tabssp) === 3)
		{
			$pic['display-name'] = substr($tabssp[0], 0, 1) . ". " . substr($tabssp[1], 0, 1) . ". " . $tabssp[2];

			if ($tabssp[2] == $tabssp[1])
			{
				$pic['class'] = "selected";
				//$_GET['id_ssp'] = $ob2->id;
			}
			else
				$pic['class'] = "";
		}
        
        $pic['data-link'] = 'species-genus';
        $pic['data-target'] = 'page';
        $pic['photo'] = $var['photo'][0];
        $pic['url'] = $var['url'];
        
        
        \Glial\Species\Species::miniature($pic);
        
		
	}
}
echo "</ul>";  //id=photo end
echo "<div class=\"clear\"></div>";
