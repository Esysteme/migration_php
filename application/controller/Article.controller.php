<?php

use \glial\synapse\Controller;

class Article extends Controller
{

    public $module_group = "Articles";

    function index()
    {
        $this->layout_name = 'home';

        $this->title = __("Home");
        $this->ariane = " > " . __("The encyclopedia that you can improve !");
    }

    function block_article()
    {
        
    }

    function detail($param)
    {
        $this->title = __("Experience breeding : [Spermophaga_haematina]");
        $this->ariane = " > " . __("Articles") . " > " . $this->title;
    }

    function admin_create_articles()
    {
        if (from() === "administration.controller.php") {
            $module = array();
            $module['picture'] = "administration/icon-document.png";
            $module['name'] = __("Articles");
            $module['description'] = __("Create an article");
            return $module;
        }

        $this->title = __("Articles");
        $this->ariane = "> " . $this->title;
    }

}