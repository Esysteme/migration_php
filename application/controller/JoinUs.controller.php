<?php
use \glial\synapse\Controller;


class JoinUs extends Controller
{
	
	function index()
	{
	
	
	}
	
	
	
	function login()
	{
		global $_SITE;
		$_SQL = Singleton::getInstance("mysql");
		
		$_SITE['IdUser'] = -1;

		if ($_SERVER['REQUEST_METHOD'] == "POST")
		{ 
			if (!empty($_POST['login']) && !empty($_POST['password']))
			{		
				$sql = "select * from UserMain where Login = '".mysql_real_escape_string($_POST['login'])."' and Password ='".mysql_real_escape_string($_POST['password'])."'";
				$res = $this->db['mysql_write']->sql_query($sql);
				
					
				if (mysql_num_rows($res) == 1)
				{
					$ob = mysql_fetch_object($res);
					SetCookie ("IdUser",$ob->Id,time()+60*60*24*365, '/',$_SERVER['SERVER_NAME'],false, true);
					SetCookie ("Passwd",md5(md5($ob->Password)),time()+60*60*24*365,'/',$_SERVER['SERVER_NAME'],false, true);
					
					$sql = "UPDATE UserMain SET LastLogin = now() where Id='".mysql_real_escape_string($_SITE['IdUser'])."'";
					$this->db['mysql_write']->sql_query($sql);
					
					
					header("location: ".$_SERVER['REQUEST_URI']);
				}
				else
				{
					$_SITE['erreur'] = "login";
					//header("location: index.php?erreur={$_SITE['erreur']}&".$_SERVER['QUERY_STRING']);
				}
				
			}
			
			if (!empty($_POST['logout']))
			{
				SetCookie ("Passwd","",time()+60*60*24*365,'/',$_SERVER['SERVER_NAME'],false, true);
				header("location: ".WWW_ROOT);
			}
		}
		
		

		if (!empty($_COOKIE['IdUser']) && !empty($_COOKIE['Passwd']))
		{
			$sql = "select * from UserMain where Id = '".mysql_real_escape_string($_COOKIE['IdUser'])."'";
			$res = $this->db['mysql_write']->sql_query($sql);
			
			if (mysql_num_rows($res) == 1)
			{
				
				$ob = mysql_fetch_object($res);
				
				if (md5(md5($ob->Password)) == $_COOKIE['Passwd'])
				{
					$_SITE['IdUser'] = $_COOKIE['IdUser'];
					$_SITE['Name'] = $ob->Name;
					$_SITE['FirstName'] = $ob->Firstname;
					
					
					
					
					$sql = "UPDATE UserMain SET LastConnected = now() where Id='".mysql_real_escape_string($_SITE['IdUser'])."'";
					$this->db['mysql_write']->sql_query($sql);
					
				}
			}
		}
		
		
		$this->set("_SITE",$_SITE);
	
	}
	
	
	function Register()
	{
	
	
	}
	
	
	function LostPassword()
	{
	
	
	}
	


}












?>