<?php
use \glial\synapse\Controller;

class Home extends Controller
{
	function index()
	{
		$this->layout_name = 'home';
		
		$this->title = __("Home");
		$this->ariane = " > ".__("The encyclopedia that you can improve !");
		
		
		$this->javascript = array("jquery-1.4.4.min.js","jquery.mousewheel.min.js","jquery.slidepane.min.js", "jawdropper_slider.js");
		
		/*
		$this->code_javascript[] = "
		$('.slidepane').slidepane({
        slideWidth:676, 
        slideSpeed:500, 
        autoPlay:false, 
        autoPlayInterval:5000, 
        cycle:true, 
        keysControl:true, 
        mouseControl:true, 
        startSlide:1});";*/
		
		$this->code_javascript[] = "
		
		$(document).ready(function() {
			$('#slider').jdSlider({ 
				showSelectors : false,
				showNavigation     : false,
				showCaption        : true,
				width  : 250,
				height : 250,
				transitions : 'blocksDiagonalIn, randomBlocks, randomSlicesVertical, randomSlicesHorizontal,sliceSlideVertical, sliceSlideHorizontal,stretchOut, lightBeam, fade, sliceFade, lightBeam,shrink, sliceFade,slide, slideOver',
				randomTransitions : true,
				pauseOnHover       : true
			});
		});
		";

	}


	function gabari()
	{
	
	
	}
}
