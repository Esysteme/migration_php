<?php

echo "<form action=\"\" method=\"post\">\n";

if ($GLOBALS['_SITE']['IdUser'] === -1)
{
	echo __("Email") . " : <input class=\"text\" type=\"text\" name=\"login\" /> ";
	echo __("Password") . " : <input class=\"text\" type=\"password\" name=\"password\" /> ";
	echo " <input class=\"boutton\" type=\"submit\" value=\"" . __("Login") . "\" /> ";
	echo " - <a class=\"underline\" href=\"" . LINK . "user/register/\">" . __("Sign up, it's free !") . "</a> (<a href=\"" . LINK . "user/lost_password/\">" . __("password forgotten") . "</a>)";
}
else
{
	echo __("Hello") . " <a href=\"" . LINK . "user/profil/" . $GLOBALS['_SITE']['IdUser'] . "\">{$GLOBALS['_SITE']['FirstName']} {$GLOBALS['_SITE']['Name']}</a> !&nbsp;&nbsp;&nbsp;";


	//echo " <a href=\"hhh\" class=\"boutton\" type=\"submit\">".__("Messaging")."</a>";
	//echo " <a href=\"hhh\" class=\"boutton\" type=\"submit\">".__("My account")."</a>";
	echo " <a href=\"" . LINK . "user/settings/main\" class=\"boutton\">" . __("Settings") . "</a>";

	if (intval($data["new_mail"]) === 0)
	{
		echo " <a href=\"" . LINK . "user/mailbox/inbox\" class=\"boutton\">" . __("Mailbox") . "</a>";
	}
	else
	{
		echo " <a href=\"" . LINK . "user/mailbox/inbox\" class=\"boutton\"><b>" . __("Mailbox") . " (" . $data["new_mail"] . ")</b></a>";
	}
	echo " <a href=\"" . LINK . "administration/\" class=\"boutton\">" . __("Administration") . "</a>";
	echo " <input class=\"boutton\" type=\"submit\" name=\"logout\" value=\"" . __("Logout") . "\" /> ";
}
echo "</form>\n";


