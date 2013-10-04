<?php


use \glial\synapse\Controller;


class ContactUs extends Controller
{

	function index()
	{





		if ( $_SERVER['REQUEST_METHOD'] == "POST" )
		{
			$contact_us = array();
			$contact_us['contact_us'] = $_POST['contact_us'];
			$contact_us['contact_us']['date'] = date('c');
			$contact_us['contact_us']['ip'] = $_SERVER['REMOTE_ADDR'];

			if ( $this->db['mysql_write']->sql_save($contact_us) )
			{
				$msg = I18n::getTranslation(__('Your message has been sent'));
				$title = I18n::getTranslation(__("Success"));
				set_flash("success", $title, $msg);

				
				header("location: " . LINK . "contact_us/");
				exit;
			}
			else
			{
				
				$error = $this->db['mysql_write']->sql_error();
				$_SESSION['ERROR'] = $error;
				
				$msg = I18n::getTranslation(__('Please verify your informations'));
				$title = I18n::getTranslation(__("Error"));
				set_flash("error", $title, $msg);
				
				
				foreach ($_POST['contact_us'] as $var => $val)
				{
					$ret[] = "contact_us:" . $var . ":" . urlencode($val);
				}

				$param = implode("/", $ret);
				
				header("location: " . LINK . "contact_us/index/" . $param);
				
	
				exit;

			}
		}

		$this->title = __("Contact us");
		$this->ariane = "> " . $this->title;



		$this->javascript = array("jquery.1.3.2.js", "jquery.autocomplete.min.js");
		$this->code_javascript[] = '$("#contact_us-id_geolocalisation_city-auto").autocomplete("' . LINK . 'user/city/", {
		extraParams: {
			country: function() {return $("#contact_us-id_geolocalisation_country").val();}
		},
		mustMatch: true,
		autoFill: true,
		max: 100,
		scrollHeight: 302,
		delay:0
		});
		$("#contact_us-id_geolocalisation_city-auto").result(function(event, data, formatted) {
			if (data)
				$("#contact_us-id_geolocalisation_city").val(data[1]);
		});
		$("#contact_us-id_geolocalisation_country").change( function() 
		{
			$("#contact_us-id_geolocalisation_city-auto").val("");
			$("#contact_us-id_geolocalisation_city").val("");
		} ); 

		';


		$sql = "SELECT id, libelle from geolocalisation_country where libelle != '' order by libelle asc";
		$res = $this->db['mysql_write']->sql_query($sql);
		$this->data['geolocalisation_country'] = $this->db['mysql_write']->sql_to_array($res);

		$this->set('data', $this->data);
	}

}