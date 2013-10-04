<?php

echo "<div class=\"photo\">";
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

		
		echo '<div class="cool left ' . $selection . '">';
		echo "<a href=\"" . $var['url'] . "\">";

		echo '<div class="bigleaderpix">
<div class="caption">';
//echo '<p>' . $var['name2'] . '</p>';
echo '</div>
<div class="bigleaderlien"></div>
<img width="250" height="250" alt="' . $var['name'] . '" title="' . $var['name'] . '" src="' . $var['photo'] . '">
</div>';



		echo '</a></div>';
	}
}
echo "</div>";  //id=photo end

echo "<div class=\"clear\"></div>";