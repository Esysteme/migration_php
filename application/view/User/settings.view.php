<?php

echo "<div class=\"menu\">";
echo "<ul id=\"onglet\" class=\"menu_tab\" style=\"padding-left: 3px;\">
<li id=\"general\" class=\"selected\"><a href=\"" . LINK . "user/settings/main/\">" . __("Main") . "</a></li>
<li><a href=\"\">" . __("My historical") . "</a></li>
<li><a href=\"\">" . __("My photos") . "</a></li>
<li><a href=\"\">" . __("My videos") . "</a></li>
<li><a href=\"\">" . __("My birds") . "</a></li>
<li><a href=\"\">" . __("My Ads") . "</a></li>
</ul>";
echo "</div>";




switch ($data['request'])
{
	case 'main':
		echo '<div class="menu_main right">';

		echo '<div class="title">';
		echo __('Photo');
		echo '</div>';
		echo '<div>';

		echo '<a href="'.LINK.'user/settings/main/photo">' . __('Add a new photo') . '</a><br />';
		echo '<a href="">' . __('Resize your photo') . '</a><br />';
		echo '<a href="">' . __('Delete your photo') . '</a><br />';
		echo '</div>';


		echo '<div class="title">';
		echo __('General');
		echo '</div>';
		echo '<div>';

		echo '<a href="">' . __('Add your gender') . '</a><br />';
		echo '<a href="">' . __('Add your nationality') . '</a><br />';
		echo '<a href="">' . __('Update your location') . '</a><br />';

		echo '</div>';

		echo '</div>';
		break;
}




switch ($data['request'])
{
	case 'main':
		switch ($data['item'])
		{
			case 'photo':

				echo "dd";
				$login = new controller("user", "photo", "gg");
				$login->recursive = true;
				$login->get_controller();



				break;
		}
		break;
}



echo '<div class="clear"></div>';