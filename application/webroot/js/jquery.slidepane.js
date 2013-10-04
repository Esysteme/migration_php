// Item Name: SlidePane - jQuery Sliding Panel
// Author: Mapalla
// Author URI: http://codecanyon.net/user/Mapalla
// Version: 1.0

(function($){

var is_animation_running = false ;

  //start of plugin
  $.fn.slidepane = function(options) {
  
  var defaults = {slideWidth:700, slideSpeed:500, autoPlay:false, autoPlayInterval:5000, cycle:false, 
                 keysControl:true, mouseControl:true, startSlide:1}; // default value
  
  var o = jQuery.extend(defaults, options);
  
  return this.each(function(){
    // variables
    var e = $(this);
    e.effect_duration = o.slideSpeed; 
	e.menu_slider_width = o.slideWidth + 'px';
	e.autoPlay = o.autoPlay;
	e.autoPlayInterval = o.autoPlayInterval;
	e.autoPlayCycle = o.cycle;
	e.keysControl = o.keysControl;
	e.mouseControl = o.mouseControl;
	slideHeading = e.children('dt');
	slideContent = e.children('dd');
	e.active_slide = e.children('dd:eq(0)');
	e.slideLength = e.children('dt').length;
	e.start = o.startSlide;
	e.css({'overflow':'hidden'});
			
	stateFirstTimeRun(slideContent);
			
	slideHeading.bind('click', e, slide_on_click); // binding heading click
	
	//rotate title
    rotateTitle(e);
	
	//open slide
	openSlideItem(e, (e.start - 1));
			 
	//cross slide
    var numberOfCrossSlide = slideContent.children('.cross_slide').length;
    for (var x = 0; x < numberOfCrossSlide; x++)
    {
    createCrossSlide(e, x);
    }
    
    //vertical slide
    var numberOfVerticalSlide = slideContent.children('.vertical_slide').length;
    for (var i = 0; i < numberOfVerticalSlide; i++)
    {
    e.verticalSlidePosition = 0;
    createVerticalSlide(e, o.slideWidth, i);
    }
    
    
    // Autoplay
    var slide_number = e.start;
    var top_slide_number = e.slideLength;
    if (e.autoPlay){
    autoplaySlide(e, slide_number, top_slide_number); // run autoplay
    
    //stop autoplay when heading is clicked
    e.children('dt').click(function(){
        clearInterval(intervalProcess);
    });
    
    //stop autoplay when key is pressed
    $(document).keydown(function(){
        clearInterval(intervalProcess);
    });
    
    //stop autoplay when user using mouse wheel
    e.mousewheel(function(){
        clearInterval(intervalProcess);
    });
    }
        
    //keyboard navigation
    if (e.keysControl){
    $(document).keydown(function(event){
        if (event.keyCode == '39')
        {
            keyNext(e, e.slideLength);
        }
        if (event.keyCode == '37')
        {
            keyPrev(e, e.slideLength);
        }
    });
    }
            
    // mouse wheel navigation
    var isOverVerticalSlide = false;
    e.children('dd').children('.vertical_slide').hover(
    function(){
        isOverVerticalSlide = true ;
    },
    function(){
        isOverVerticalSlide = false ;
        if (e.mouseControl){
        e.mousewheel(function(event, delta){
            if (isOverVerticalSlide == false){
                if (delta > 0){keyPrev(e, e.slideLength)} 
                if (delta < 0) { keyNext(e, e.slideLength)}
                }
            });
        }
    }
    );
    
    if (e.mouseControl){
        e.mousewheel(function(event, delta){
            if (isOverVerticalSlide == false){
                if (delta > 0){keyPrev(e, e.slideLength)} 
                if (delta < 0) { keyNext(e, e.slideLength)}
                }
            });
    }
    
   
  }); // end of return
    
  }; //end of plugin
  
  //function here
  
  function stateFirstTimeRun(slideContent){
    slideContent.css({'overflow':'hidden', 'width':'0px'});
	slideContent.addClass('not_active');
  }
  
  //Autoplay
  function autoplaySlide(e, slide_number, top_slide_number){
    intervalProcess = setInterval(function(){
    
        if (slide_number == top_slide_number )
        {
            if (e.autoPlayCycle==false)
            {
                return ;
            }
            else {slide_number = 0;}
        }
               
        var currentSlideHeading = e.children('dd:eq(' + slide_number + ')').prev();
        
        if ( currentSlideHeading.next() == e.active_slide)
	    {
	        return;
	    }
	    
	   	    	    	
	    var activeSlide = e.active_slide;
	    var nextActiveSlide = currentSlideHeading.next();
	    
	    	    	    
	    //slideinout
	    slideInOut(e, activeSlide, nextActiveSlide);
	
        // stop autoplay if cycle is true
        // or back to first slide if cycle is false
        slide_number = slide_number + 1;
        if (slide_number == top_slide_number )
        {
            if (e.autoPlayCycle==false)
            {
                clearInterval(intervalProcess);
            }
            else {slide_number = 0;}
        }
        }, e.autoPlayInterval);  
        
  }
  
  //slide to open when first time running 
  function openSlideItem(e, slideNumber){
    e.active_slide = e.children('dd:eq(' + slideNumber + ')');
    var activeSlide = e.active_slide;
    var slideHeading = activeSlide.prev();
    
    slide(activeSlide, e.menu_slider_width, e.effect_duration);
    activeSlide.removeClass('not_active');
	slideHeading.css({'cursor':'default'});
    slideHeading.append('<div class=triangle-right></div>');
    slideHeading.addClass('active');
    slideHeading.children('.index').addClass('selected_index');
  }
  
  //sliding content
  function slide(slide_content, width, duration)
  {
    is_animation_running = true ;
	slide_content.animate({'width' : width}, duration, function(){
	is_animation_running = false ;
	});
  }
  
  //slide click
  function slide_on_click(event)
  {
    var e = $(this);
    var activeSlide = event.data.active_slide;
    var nextActiveSlide = e.next();
    
    // First check if the active_menu button was clicked. If yes, we do nothing ( return )
    if ( e.next()[0] == activeSlide[0] )
	{
	    return;
	}
	
	// Check if animation is running. If it is, we interrupt
	if (is_animation_running)
	{
	    return;
	}
	
	//slideInOut
    slideInOut(event.data, activeSlide, nextActiveSlide);
  } // end slide click
  
  // rotate title
  function rotateTitle(element){
    var headingCount = element.children('dt').length ;
    var headingHeight = element.children('dt').height() ;
    var headingWidth = element.children('dt').width() ;


    for (var i = 0; i < headingCount; i++ )
    {
    var currentSlideHeading = element.children('dt:eq(' + i + ')');
    var title = currentSlideHeading.text() ;
    currentSlideHeading.html('<span>' + title + '</span>');
    currentSlideHeading.children('span').addClass('title');
    var currentTitle = currentSlideHeading.children('span.title');
    currentTitle.css({'display':'block', 'paddingLeft':'10px', 'paddingRight':'10px','width':(headingHeight-20) + 'px', 
                    'height':headingWidth + 'px', 'display':'block', 
                    '-moz-transform': 'rotate(-90deg) translate(-' + headingHeight + 'px,0px)', '-moz-transform-origin': 'left top', //mozilla
                    '-webkit-transform': 'rotate(-90deg) translate(-' + headingHeight + 'px,0px)', '-webkit-transform-origin': 'left top', //webkit, safari, chrome
                    '-o-transform': 'rotate(-90deg) translate(-' + headingHeight + 'px,0px)', '-o-transform-origin': 'left top', //opera
                    'transform': 'rotate(-90deg) translate(-' + headingHeight + 'px,0px)', 'transform-origin': 'left top', //w3c standard
                    'filter': 'progid:DXImageTransform.Microsoft.BasicImage(rotation=3)', //ie7
                    '-ms-filter': 'progid:DXImageTransform.Microsoft.BasicImage(rotation=3)'}); //ie8+
                                                
    // adding slide index
    currentSlideHeading.append('<div class=index>' + (i+1) + '</div>');
    }

  } //end of rotate title
  
  //cross slide
  function createCrossSlide(element, x)
  {
    var currentCrossFading = element.children('dd').children('.cross_slide:eq(' + x + ')');
    var slideHeight = currentCrossFading.parent().height();
    var currentCrossSlide = currentCrossFading.children('li');
     var slideLength = currentCrossSlide.length;
    var firstCurrentCrossSlide = currentCrossFading.children('li:first');
    
    //styling crossfading
    currentCrossFading.css({'list-style-type':'none', 'margin':'0', 'padding':'0'});
    currentCrossSlide.css({'list-style-type':'none', 'margin':'0',
        'padding':'0', 'overflow':'hidden', 'width':element.menu_slider_width, 'height':slideHeight + 'px', 'position':'absolute'});
    
    // create cross-slide navigation controls
    createCrossSlideControls(currentCrossFading, slideLength);
    
    //fadeout all slide when first time open, except first slide
    currentCrossSlide.fadeOut('fast');
    firstCurrentCrossSlide.fadeIn('fast');

  }//end of cross-slide
  
  //create cross-slide controls
  function createCrossSlideControls(currentCrossFading, slideLength)
  {
    //adding controls container
    var crossSlideContainer = currentCrossFading.parent();
    crossSlideContainer.append('<div class=cross_slide_bullet_container></div>');
    var controlsContainer = crossSlideContainer.children('.cross_slide_bullet_container');
    
    // creating bullet controls
    for (var i = 0; i < slideLength ; i++)
    {
        controlsContainer.append('<div></div>');
    }
    
    // styling first control when first time run
    var firstControl = controlsContainer.children('div:first');
    firstControl.addClass('active_bullet');
    firstControl.css('cursor', 'default');
    
    //binding click event on bullet button
    var activeSlide = 0;
    for (var i = 0; i < slideLength ; i++){
        var crossSlideControl = controlsContainer.children('div:eq(' + i + ')');
        crossSlideControl.bind('click', {index:i} , function(event){
        var e = $(this) ;
        if (activeSlide == event.data.index)
        {
            return ;
        }
        //fadeout active slide
        //fadein selected slide
        var crossSlideItem = e.parent('.cross_slide_bullet_container').parent('dd').children('.cross_slide').children('li:eq(' + activeSlide + ')');
        crossSlideItem.fadeOut(1000);
        // styling active_bullet
        var slideControl = e.parent('.cross_slide_bullet_container').children('div');
        slideControl.removeClass('active_bullet');
        
        activeSlide = event.data.index;
        crossSlideItem = e.parent('.cross_slide_bullet_container').parent('dd').children('.cross_slide').children('li:eq(' + activeSlide + ')');
        crossSlideItem.fadeIn(1000);
        e.addClass('active_bullet');
        slideControl.css('cursor', 'pointer');
        e.css('cursor', 'default');
        });
    }   
    
  }// end
  
  //vertical slide
  function createVerticalSlide(element,width,i)
  {
    var currentVerticalSlide = element.children('dd').children('.vertical_slide:eq(' + i +')');
    var slideHeight = currentVerticalSlide.parent().height();
    var leftPosition = (width/2)-15;
    var verticalSlideItem = currentVerticalSlide.children('li');
    var numberOfSlides = verticalSlideItem.length;
    
    //styling vertical slide
    currentVerticalSlide.css({'list-style-type':'none', 'margin':'0', 'padding':'0'});
    verticalSlideItem.css({'list-style-type':'none', 'margin':'0',
                    'padding':'0', 'overflow':'hidden', 'width':element.menu_slider_width, 'height':slideHeight + 'px'});
                    
    createVerticalSlideControls(currentVerticalSlide, leftPosition, numberOfSlides, slideHeight, element.verticalSlidePosition, element.mouseControl); //creating navigation controls
    
   
  } //end of createVerticalSlide
  
  //creating vertical slide navigation controls
  function createVerticalSlideControls(currentVerticalSlide, leftPosition, numberOfSlides, slideHeight, currentPosition, mouseWheelControl)
  {
    //insert up and down control inside container
    var verticalSlideParent = currentVerticalSlide.parent() ;
    verticalSlideParent.append('<div id="up_control"><div id="triangle-up"></div></div>');
    verticalSlideParent.append('<div id="down_control"><div id="triangle-down"></div></div>');
    
    //styling controls
    var upControl = verticalSlideParent.children('#up_control');
    var downControl = verticalSlideParent.children('#down_control');
    var triangleUp = upControl.children('#triangle-up');
    var triangleDown = downControl.children('#triangle-down');
    upControl.css({'width':'30px', 'height':'30px', 'backgroundColor':'#000', '-moz-border-radius':'5px', '-webkit-border-radius':'5px', 
        'border-radius': '5px', 'position':'absolute', 'display':'block','cursor':'pointer','z-index':'100', 'top':'5px', 
        'left':leftPosition + 'px', 'opacity': '0.5', '-moz-opacity': '0.5', '-webkit-opacity': '0.5', '-khtml-opacity': '0.5',
        '-ms-filter': 'progid:DXImageTransform.Microsoft.Alpha(Opacity=50)', 'filter':'alpha(opacity=50)'});
    triangleUp.css({'margin':'6px 0 0 8px', 'width':'0', 'height':'0', 'borderLeft':'7px solid transparent', 'borderRight':'7px solid transparent', 
        'borderBottom':'14px solid white'});
    downControl.css({'width':'30px', 'height':'30px', 'backgroundColor':'#000', '-moz-border-radius':'5px', '-webkit-border-radius':'5px', 
        'border-radius': '5px', 'position':'absolute', 'display':'block','cursor':'pointer','z-index':'100', 'bottom':'5px', 
        'left':leftPosition + 'px', 'opacity': '0.5', '-moz-opacity': '0.5', '-webkit-opacity': '0.5', '-khtml-opacity': '0.5',
        '-ms-filter': 'progid:DXImageTransform.Microsoft.Alpha(Opacity=50)', 'filter':'alpha(opacity=50)'});
    triangleDown.css({'margin':'10px 0 0 8px', 'width':'0', 'height':'0', 'borderLeft':'7px solid transparent', 
        'borderRight':'7px solid transparent', 'borderTop':'14px solid white'});
        
    createVerticalSlideIndicator(currentVerticalSlide, numberOfSlides);
                                                        
    //hiding navigation controls,
    //but display them on mouseover
    upControl.hide();
    downControl.hide();
   
    //mouse hover
    verticalSlideParent.hover(
        //mouseover
        function(){
        var e = $(this);
        var currentUpControl = e.children('#up_control');
        var currentDownControl = e.children('#down_control');
        manageVerticalSlideControls(currentPosition, numberOfSlides, currentUpControl, currentDownControl);}
        ,
        // mouseout
        function(){
            var e = $(this);
            var currentUpControl = e.children('#up_control');
            var currentDownControl = e.children('#down_control');
            currentUpControl.hide();
            currentDownControl.hide();}
    ); //end hover
    
    // down control click event
    downControl.click(function(){
        
        if (is_animation_running)
	    {
	        return;
	    }
	    
	    is_animation_running = true ;
        currentPosition = currentPosition + 1;
        // manage vertical slide control
        var controlsContainer = $(this).parent();
        var currentUpControl = controlsContainer.children('#up_control');
        var currentDownControl = controlsContainer.children('#down_control');
        manageVerticalSlideControls(currentPosition, numberOfSlides, currentUpControl, currentDownControl);
        // move slide
        var marginTop = (-currentPosition*slideHeight) ;
        var verticalSlide = controlsContainer.children('.vertical_slide');
        verticalSlide.animate({'marginTop': marginTop +'px'}, 'slow', function(){is_animation_running = false ;}); 
        
        stylingVerticalSlideIndicator(currentVerticalSlide, currentPosition);        
         
    }); // end click event
    
    
    // up control click event
    upControl.click(function(){
        if (is_animation_running)
	    {
	        return;
	    }
	    
	    is_animation_running = true ;
        currentPosition = currentPosition - 1;
        // manage vertical slide controls
        var controlsContainer = $(this).parent();
        var currentUpControl = controlsContainer.children('#up_control');
        var currentDownControl = controlsContainer.children('#down_control');
        manageVerticalSlideControls(currentPosition, numberOfSlides, currentUpControl, currentDownControl);
        // move slide
        var marginTop = (-currentPosition*slideHeight) ;
        var verticalSlide = controlsContainer.children('.vertical_slide');
        verticalSlide.animate({'marginTop': marginTop + 'px'}, 'slow', function(){is_animation_running = false ;});
        
        stylingVerticalSlideIndicator(currentVerticalSlide, currentPosition);
    }); // end click event
    
    // vertical slide indicator click event
    for (var i = 0; i < numberOfSlides ; i++){
    var control = currentVerticalSlide.parent().prev().children('.verticalSlideIndicatorContainer').children('div:eq(' + i + ')');
    control.bind('click', {index:i}, function(event){
               
        if (is_animation_running)
	    {
	        return;
	    }
	    
	    is_animation_running = true ;
	    currentPosition = event.data.index;
	    	    
	    var controlsContainer = $(this).parent().parent().next();
	    //move slide
	    var marginTop = (-currentPosition*slideHeight) ;
        var verticalSlide = controlsContainer.children('.vertical_slide');
        verticalSlide.animate({'marginTop': marginTop + 'px'}, 'slow', function(){is_animation_running = false ;});
        
        stylingVerticalSlideIndicator(currentVerticalSlide, currentPosition);
    });
    } // end for
    
    
    //mouse wheel event
    if (mouseWheelControl){
    verticalSlideParent.mousewheel(function(event,delta){
        var controlsContainer = $(this);
        if (delta > 0){
            if (is_animation_running)
	        {
	            return;
	        }
            //up
            if (currentPosition == 0)
            { return ;}
            
            is_animation_running = true ;
            currentPosition = currentPosition - 1;
            // manage vertical slide controls
            
            var currentUpControl = controlsContainer.children('#up_control');
            var currentDownControl = controlsContainer.children('#down_control');
            manageVerticalSlideControls(currentPosition, numberOfSlides, currentUpControl, currentDownControl);
            // move slide
            var marginTop = (-currentPosition*slideHeight) ;
            var verticalSlide = controlsContainer.children('.vertical_slide');
            verticalSlide.animate({'marginTop': marginTop + 'px'}, 'slow', function(){is_animation_running = false ;});
            stylingVerticalSlideIndicator(currentVerticalSlide, currentPosition);
        } 
        if (delta < 0){
            if (is_animation_running)
	        {
	            return;
	        }
	        
            //down
            if (currentPosition == (numberOfSlides - 1))
            { return ;}
            
            is_animation_running = true ;
            currentPosition = currentPosition + 1;
            
            // manage vertical slide control
            var currentUpControl = controlsContainer.children('#up_control');
            var currentDownControl = controlsContainer.children('#down_control');
            manageVerticalSlideControls(currentPosition, numberOfSlides, currentUpControl, currentDownControl);
            // move slide
            var marginTop = (-currentPosition*slideHeight) ;
            var verticalSlide = controlsContainer.children('.vertical_slide');
            verticalSlide.animate({'marginTop': marginTop +'px'}, 'slow', function(){is_animation_running = false ;});
            stylingVerticalSlideIndicator(currentVerticalSlide, currentPosition);
        }
    }); //end mousewheel
    }  //end if    
    
  } //end
  
    
  // manage vertical slide control
  function manageVerticalSlideControls(position, numberOfSlides, upControl, downControl)
  {
    // position==0 is first slide
        if(position==0)  { upControl.hide(); }
        else { upControl.show(); }
        // numberOfSlides-1 is last slides
        if(position==numberOfSlides-1) { 
        downControl.hide(); }
        else { downControl.show(); }
  } // end
  
  function stylingVerticalSlideIndicator(currentVerticalSlide, position){
     var headingVerticalSlide = currentVerticalSlide.parent().prev() ;
     var controlsContainer = headingVerticalSlide.children('.verticalSlideIndicatorContainer');
     var controls = controlsContainer.children('div');
     var currentControl = controlsContainer.children('div:eq(' + position + ')');
     
     controls.removeClass('active');
     currentControl.addClass('active');
     
  }
  
  // creating vertical slide indicator
  function createVerticalSlideIndicator(currentVerticalSlide, verticalSlideLength){
    var headingVerticalSlide = currentVerticalSlide.parent().prev() ;
    //adding controls container
    headingVerticalSlide.append('<div class=verticalSlideIndicatorContainer></div>');
    var controlsContainer = headingVerticalSlide.children('.verticalSlideIndicatorContainer');
    
    // creating bullet controls
    for (var i = 0; i < verticalSlideLength ; i++)
    {
        controlsContainer.append('<div></div>');
    }
    
    var controls = controlsContainer.children('div');
    
    // styling first control when first time run
    var firstControl = controlsContainer.children('div:first');
    firstControl.addClass('active');    
  }
  
  // function to run when user press right arrow key
  function keyNext(e, slideLength){
    // Check if animation is running. If it is, we interrupt
	if (is_animation_running)
	{
	    return;
	}
	
	
  
    var activeSlide = e.active_slide;
    var nextActiveSlide = activeSlide.next().next() ;
       
    //back to the first slide
    var slideIndex = $('dd').index(activeSlide);
    if (slideIndex == (slideLength - 1))
    {
        var firstSlide = e.children('dd:eq(0)');
        nextActiveSlide = firstSlide;
    }
    
    // slide   
    slideInOut(e, activeSlide, nextActiveSlide);
        
  }//end keyNext
    
  //function to run when user click left arrow key
  function keyPrev(e, slideLength){
    // Check if animation is running. If it is, we interrupt
	if (is_animation_running)
	{
	    return;
	}
	
	var activeSlide = e.active_slide;
	var nextActiveSlide = activeSlide.prev().prev() ;
	
	// back to the last slide
	var slideIndex = $('dd').index(activeSlide);
    if (slideIndex == 0)
    {
        var lastSlide = e.children('dd:eq(' + (slideLength -1) + ')');
        nextActiveSlide = lastSlide ;
    }
    
    
    //slide
    slideInOut(e, activeSlide, nextActiveSlide)    
    
  } //end keyPrev
  
  //slide in active slide and slide out the next slide
  function slideInOut(e, activeSlide, nextActiveSlide){
    activeSlideHeading = activeSlide.prev();
    //slide in the active slide
	slide(activeSlide, 0, e.effect_duration); //slide in
	activeSlide.addClass('not_active'); 
	activeSlideHeading.children('.triangle-right').remove();//right arrow
	activeSlideHeading.removeClass('active');
	activeSlideHeading.children('.index').removeClass('selected_index'); //index
	activeSlideHeading.css({'cursor':'pointer'}); // cursor
	    
	// slide out the next slide		
	e.active_slide = nextActiveSlide;
	activeSlide = e.active_slide;
	activeSlideHeading = activeSlide.prev(); 
	slide(activeSlide, e.menu_slider_width, e.effect_duration);
	activeSlide.removeClass('not_active');
	activeSlideHeading.append('<div class=triangle-right></div>');
    activeSlideHeading.addClass('active');
    activeSlideHeading.children('.index').addClass('selected_index'); // index
    activeSlideHeading.css({'cursor':'default'}); // cursor
  }
  
		
})( jQuery );
