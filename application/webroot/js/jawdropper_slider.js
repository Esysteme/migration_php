/*
 * @name JawDropper Slider
 * @desc plugin for jQuery
 *
 * @author Holydog
 * @version 0.1
 * @requires jQuery v1.4+ 
 *
 */
;(function($) {

$.jdSlider = function(args, options) {
	var opts = $.extend({}, $.jdSlider.defaults, options);
	return window;
} // $.jdSlider

//
// Defaults 
//
$.jdSlider.defaults = {
	autoAdvance        : true,     // Change slides automatically
	delay              : 2000,     // Delay between slide change (in ms)
	transitionDuration : 1000,     // Duration of the transition in ms
	                               // note : Its hard to come up with a single value that suits well to every transition.
	                               //        so this value is not followed EXACTLIY by all transitions(*).
                                   // Trasitions available : 
 								   //   blinds*, blocksDiagonalIn*, clock, circle, diamond, exitStageLeft, exitStageRight, fade,
								   //   fountain*, lightBeam, randomBlocks*, randomSlicesVertical*, randomSlicesHorizontal*,
								   //   shrink, sliceFade, sliceSlideVertical*, sliceSlideHorizontal*, slide, slideOver, 
								   //   stretchOut, zipper**, slide, slideOver, stretchOut, zipper*
	transitions        : "all",    // List of the transitions used by the slider. for example "sliderIn, zipper, diamond, fade". default "all" for all transitions available
	randomTransitions  : false,    // If true, the slider will show a random transition listed in "transitions" option. If false, will cycle through the transitions sequentially
	pauseOnHover       : true,     // Pause slide show when mouse over the slider 
    showCaption        : "hover",  // Show image captions. Can be true, false or "hover", in this case the captions will be show on mouseover  
    showSelectors      : "hover",  // Show image selectors. Can be true, false or "hover", in this case the selectors will be show on mouseover  
    showNavigation     : "hover",  // Show previous and next buttons. Can be true, false or "hover", in this case the selectors will be show on mouseover  
	width              : 600,      // Width of the slider
	height             : 300       // Height of the slider
};

// Constants
var LIGHTBEAM_GLOW_WIDTH = 74;
var SCRIPT_PATH = "";



$.fn.jdSlider = function(options) {
	var	opts = $.extend({}, $.jdSlider.defaults, options);
	return this.each(function() {
		var $this = $(this);
		$this.data('jdSlider:options', opts);
		buildSliderFor(this, opts);
	});	
}; // fn.jdSlider

$.fn.jdSliderPrev = function(options) {
	var	opts = $.extend({}, $.jdSlider.defaults, options);
	return this.each(function() {
		prevSlide($(this), options);
	});	
}; // fn.jdSliderPrev

$.fn.jdSliderNext = function(options) {
	var	opts = $.extend({}, $.jdSlider.defaults, options);
	return this.each(function() {
		nextSlide($(this), options);
	});	
}; // fn.jdSliderNext

$.fn.jdSliderStop = function(options) {
	var	opts = $.extend({}, $.jdSlider.defaults, options);
	return this.each(function() {
		stopSlider( $(this) );
	});	
}; // fn.jdSliderStop

$.fn.jdSliderPlay = function(options) {
	var	opts = $.extend({}, $.jdSlider.defaults, options);
	return this.each(function() {
		playSlider( $(this) );
	});	
}; // fn.jdSliderPlay


function buildSliderFor(elem, options) {
	var slider = $(elem),
		slides = [];

	// adjust slider CSS
	if (slider.css('position')=='static') slider.css('position', 'relative');
	slider.css({
		overflow      : 'hidden',
		width         : options.width,
		height        : options.height
	})

	// find slides inside element
	var slide;
	slider.children('img, a').each(function(){
		var img, $this=$(this), next;
		img = $this.is("a") ? $this.children('img:first') : $this;
		slide = {
			elem  : $this,
			image : img,
			title : img.attr('title')
		}
		next = img.next();
		if ( next.length>0 && !next.is("a") && !next.is("img") ) {
			slide.caption = next.html();
			next.css('display', 'none');
		} else {
			slide.caption = img.attr('title');
		}
		
		slides.push(slide);
		img.css({ border : 'none' });
		$this.css({
			outline  : 0,
			display  : "none",
			position : 'absolute'
		})

	})
	slider.data('jdslider:slides', slides);

	// create slider caption
	slider.append( $('<div class="jdslider-caption"></div>').css({ display:'none' }) );

	// create prev, next, play & pause buttons
	$('<div class="jdslider-control jdslider-prev">Back</div>').css('display', 'none' )
		.click(function(){ prevSlide(slider) })
		.appendTo(slider);
	$('<div class="jdslider-control jdslider-next">Next</div>').css('display', 'none' )
		.click(function(){ nextSlide(slider) })
		.appendTo(slider);
	
	// create slide selector
	var selectors = $('<div class="jdslider-selectors"></div>').css('display','none').appendTo(slider),
	    selector;
	for(var i=0; i<slides.length; i++) {
		selector = $('<div class="jdslider-selector"></div>').html(i+1).appendTo(selectors);
		selector.data('jdslider:slide_number', i)
		        .click(function(){ showSlide(slider, $(this).data('jdslider:slide_number'));	});
	}
	slider.data("jdslider:selector", selectors);

	// pauseOnHover 
	var showCaptionIsHover = false;
	slider.hover(function(e){
		if (e.originalTarget!=slider && 
		    ( $(e.originalTarget).hasClass('jdslider_light_right') || $(e.originalTarget).hasClass('jdslider_light_left') ) ) return;
		if (options.pauseOnHover) pauseSlider(slider);
		if (options.showCaption=='hover') {
			showCaptionIsHover  = true;
			options.showCaption = true;
			showCaption(slider);	
		}
		if (options.showSelectors=='hover') showSelectors(slider);
		if (options.showNavigation=='hover') showNavigation(slider);
	}, function(){
		if (options.pauseOnHover) unpauseSlider(slider);

		if (showCaptionIsHover) {
			options.showCaption='hover';
			showCaptionIsHover = false;
			hideCaption(slider);
		}
		if (options.showSelectors=='hover') hideSelectors(slider);
		if (options.showNavigation=='hover') hideNavigation(slider);
	});
	

	// show first slide and set timeout to transition
	showSlide(slider, 0, 'fade');
	updateControlsVisibility(slider);
} // buildSliderFor

function prevSlide(slider, options) {
	var current_slide = slider.data('jdslider:current-slide'),
	    slides = slider.data('jdslider:slides'),
	    prev, 
	    transition;
	    
	if (options) {
		transition = options.transition;
	}

	if (current_slide > 0) { prev = current_slide-1 } else { prev = slides.length-1 };
	showSlide(slider, prev, transition);
} // prevSlide

function nextSlide(slider, options) {
	var current_slide = slider.data('jdslider:current-slide'),
	    slides = slider.data('jdslider:slides'),
	    next, 
	    transition;
	    
	if (options) {
		transition = options.transition;
	}

	if (current_slide < slides.length-1) { next = current_slide+1 } else { next = 0};
	showSlide(slider, next, transition);

} // nextSlide


function showSlide(slider, slideIndex, transition){
	if (slider.data('jdslider:current-slide')==slideIndex) return;
	var showing = slider.data('jdslider_showing');
	if (showing) return;
	if (inTransition(slider)) return;

	clearTimeout( slider.data('jdSlider:timer') );

	inTransition(slider,true);
	hideCaption(slider);
	slider.data('jdslider_showing', true);
	
	if (!transition) transition = getNextTransitionName(slider);
	if (!transition) transition = 'fade';

	var trans = $.jdSlider.transitions[transition];
	trans(slider, slideIndex);

} // showSlide

function slideShowed(slider, slideIndex) {
	var slides = slider.data('jdslider:slides');

	if ( slider.data('jdslider:current-slide')!=undefined ) {
		slides[slider.data('jdslider:current-slide')].elem.hide();
	}

	slider.css('background','url('+ $(slides[slideIndex].image).attr('src') +') no-repeat');
	slider.data('jdslider:current-slide', slideIndex);
	
	slides[slideIndex].elem.css({ left:0, top:0, display:'block' });
	slides[slideIndex].image.css({ display:'block' });
	
	$('.jdslider_block', slider).css('display', 'none');
	slider.data('jdslider_showing', false);
	var selector = slider.data("jdslider:selector");
	$('.jdslider-current', selector).removeClass('jdslider-current');
	$(selector[0].childNodes[slideIndex]).addClass('jdslider-current')

	inTransition(slider, false);
	showCaption(slider);
	setTimerForAutoAdvance(slider);
} // slideShowed

function inTransition(slider, value) {
	if (value==undefined) return slider.data('jdslider:inTransition'); 
	slider.data('jdslider:inTransition', value); 
} // inTransition


function setTimerForAutoAdvance(slider) {
	var	options = getOptions(slider);
	if (options.autoAdvance && (!options.paused)) {
		var timer = setTimeout(function(){ 
			nextSlide(slider);
		}, options.delay);
		slider.data('jdSlider:timer', timer);
	}
} // setTimerForAutoAdvance

function stopSlider(slider) {
	getOptions(slider).autoAdvance = false;
	clearTimeout( slider.data('jdSlider:timer') );
}; // stopSlider

function playSlider(slider) {
	getOptions(slider).autoAdvance = true;
	getOptions(slider).paused = false;
	setTimerForAutoAdvance(slider);
}; // playSlider

function pauseSlider(slider) {
	getOptions(slider).paused = true;
	clearTimeout( slider.data('jdSlider:timer') );
}; // pauseSlider

function unpauseSlider(slider) {
	getOptions(slider).paused = false;
	setTimerForAutoAdvance(slider);
}; // unpauseSlider

function getNextTransitionName(slider) {
	var options = getOptions(slider);
	var transitions = slider.data("jdslider:transitions");
	if (!transitions) {
		if (options.transitions && options.transitions!="all") {
			transitions = options.transitions.replace(/ /g, "").split(",");
		} else {
			transitions = [];
			for(i in $.jdSlider.transitions) transitions.push(i);
		}
		slider.data("jdslider:transitions", transitions);
	}
	if (options.randomTransitions) {
		return transitions[ random(transitions.length) ];
	} else {
		var i = slider.data("jdslider:last_transition_index");
		if ((i==undefined) || (++i>=transitions.length)) i=0;
		slider.data("jdslider:last_transition_index", i);
		return transitions[ i ];
	}
}; // getNextTransitionName

// 
// update controls visibility
//
function updateControlsVisibility(slider) {
	var options=getOptions(slider);
	if (options.showSelectors==true) showSelectors(slider)
	else hideSelectors(slider);
	if (options.showNavigation==true) showNavigation(slider)
	else hideNavigation(slider);
} // updateControlsVisibility

function hideCaption(slider) {
	$(".jdslider-caption", slider).fadeOut(300);
} // hideCaption

function showCaption(slider) {
	if (!(getOptions(slider).showCaption==true)) return;
	if ( inTransition(slider) ) return;
	var current_slide = slider.data('jdslider:current-slide'),
	    slides  = slider.data('jdslider:slides'),
	    caption = slides[current_slide].caption; 
	if (caption=="") return;
	$(".jdslider-caption", slider).html(caption).fadeIn();
} // showCaption

function hideSelectors(slider) {
	$(".jdslider-selectors", slider).fadeOut(300);
} // hideSelectors

function showSelectors(slider) {
	$(".jdslider-selectors", slider).fadeIn();
} // showSelectors

function hideNavigation(slider) {
	$(".jdslider-prev, .jdslider-next", slider).fadeOut(300);
} // hideNavigation

function showNavigation(slider) {
	$(".jdslider-prev, .jdslider-next", slider).fadeIn(300);
} // showNavigation


// *********************************
//
// Transitions
//
// *********************************
$.jdSlider.transitions = {

	//
	// Blinds
	//
	blinds : function(slider, slideIndex, direction) {
		var blocks;
		var number_of_slices = 10;
	
		var DIRECTIONS = ['l', 'r', 't', 'b']
		if (!direction) direction = DIRECTIONS[ random(4) ];
		var cols = isVertical(direction) ? 1 : number_of_slices;
		var rows = isVertical(direction) ? number_of_slices : 1;
	
		blocks = getBlocks(slider, cols, rows, {
			backgroundImage  : 'url('+getSlideUrl(slider, slideIndex)+')'
		});

		var finished = 0;
		function animation_finished(){ 
			finished++;
			if (finished==number_of_slices) slideShowed(slider, slideIndex); 
		}
	
		var property = (direction=='l' || direction=='r') ? 'width' : 'height' ;
		if (direction=='r' || direction=='b') blocks = blocks.toArray().reverse();

		var duration = getOptions(slider).transitionDuration;
		$.each(blocks, function(index, val){
			var block = $(val);
			var animated_prop = {};
			animated_prop[property] = block[property]();
			block.css('display', 'block')
				 .css(property, 0);
			setTimeout(function(){
				block.animate(animated_prop, duration*0.75, animation_finished);
			}, index*duration/blocks.length*0.75 );
		});

	}, // blinds

	//
	// Blocks Diagonal In
	//
	blocksDiagonalIn :function(slider, slideIndex, options) {
		var blocks, cols = 8, rows = 4, number_of_blocks = cols*rows;
	
		blocks = getBlocks(slider, cols, rows, {
			backgroundImage : 'url('+getSlideUrl(slider, slideIndex)+')',
			opacity : 0,
			display : 'block'
		});
	
		var finished = 0;
		function animation_finished(){ 
			finished++;
			if (finished==number_of_blocks) slideShowed(slider, slideIndex); 
		}

		var duration = getOptions(slider).transitionDuration;
		$.each(blocks, function(index, val){
			var block = $(val);
			var lefty = block.css('left');
			var topy  = block.css('top');
			block.css({ left: block.height()*1.5+parseInt(lefty), top: block.width()*1.5+parseInt(topy) });
			setTimeout(function(){
				block.animate({ left: lefty, top: topy, opacity: 1 }, duration*0.5, animation_finished);
			}, (val.slider_x+val.slider_y)*duration/(cols*(rows-1)) );
		});
	}, // blocks_diagonal_in


	//
	// Clock
	//
	clock : function(slider, slideIndex, direction) {
		slider = $(slider);
		var blocks, number_of_slices = Math.floor(slider.height()/5+0.5);
		blocks = getLayers(slider, slideIndex, number_of_slices);
		slider.data('jdslider:blocks', blocks);
		slider.data('jdslider:showing', slideIndex);
		slider.attr('jdslider:clock', 0);
		slider.animate({ 'jdslider:clock': 1 }, getOptions(slider).transitionDuration, 'linear');
	}, // clock


	//
	// Circle
	//
	circle : function(slider, slideIndex, direction) {
		slider = $(slider);
		var blocks, number_of_slices = Math.floor(slider.height()/5+0.5);
		blocks = getLayers(slider, slideIndex, number_of_slices);
		slider.data('jdslider:blocks', blocks);
		slider.data('jdslider:showing', slideIndex);
		slider.attr('jdslider:circle', 0);
		slider.animate({ 'jdslider:circle': 1 }, getOptions(slider).transitionDuration, 'linear');
	}, // circle


	//
	// Diamond
	//
	diamond : function(slider, slideIndex, direction) {
		slider = $(slider);
		var blocks, number_of_slices = Math.floor(slider.height()/4);
		blocks = getLayers(slider, slideIndex, number_of_slices);
		slider.data('jdslider:blocks', blocks);
		slider.data('jdslider:showing', slideIndex);
		slider.attr('jdslider:diamond', 0);
		slider.animate({ 'jdslider:diamond': 1 }, getOptions(slider).transitionDuration, 'linear');
	}, // diamond


	//
	// Exit_stage_left & Exit_stage_right
	//
	exitStageLeft : function(slider, slideIndex, direction) {
		if (direction!='left' && direction!='right') direction='left';
		var current_slide = slider.data('jdslider:current-slide'),
		slider = $(slider);
		var blocks, number_of_slices = Math.floor(slider.height()/5+0.5);
		blocks = getBlocks(slider, 1, number_of_slices, {
			backgroundImage : 'url('+getSlideUrl(slider, current_slide)+')',
			display: 'block'
		});

		slider.css('background','url('+ getSlideUrl(slider, slideIndex) +') no-repeat');
		var slides = slider.data('jdslider:slides');
		slides[slider.data('jdslider:current-slide')].elem.hide();

		slider.data('jdslider:blocks', blocks);
		slider.data('jdslider:showing', slideIndex);
		slider.attr('jdslider:exit_stage_'+direction, 0);
		if (direction=='left')  slider.animate({ 'jdslider:exit_stage_left'  : 1 }, getOptions(slider).transitionDuration, 'easeInOutBack');
		if (direction=='right') slider.animate({ 'jdslider:exit_stage_right' : 1 }, getOptions(slider).transitionDuration, 'easeInOutBack');
	}, // exit_stage

	//
	// Exit_stage_right
	//
	exitStageRight : function(slider, slideIndex, direction) {
		$.jdSlider.transitions.exitStageLeft(slider, slideIndex, 'right');
	}, // exit_stage_right

	
	//
	// Fade
	//
	fade : function(slider, slideIndex) {
		var block = getBlocks(slider, 1, 1, {
			background : 'url('+getSlideUrl(slider, slideIndex)+') no-repeat',
			display : 'none'
		});
		block.fadeIn( getOptions(slider).transitionDuration,  function(){ slideShowed(slider, slideIndex) } );
	}, // fade


	//
	// Fountain
	//
	fountain : function(slider, slideIndex, direction) {
		var blocks;
		var number_of_slices = 20;
	
		var DIRECTIONS = ['t', 'b'];
		if (!direction) direction = DIRECTIONS[ random(2) ];
		var cols = number_of_slices; rows = 1;
		blocks = getBlocks(slider, cols, rows, {
			backgroundImage : 'url('+getSlideUrl(slider, slideIndex)+')',
			opacity : 0
		});

		var finished = 0;
		function animation_finished(){ 
			finished++;
			if (finished==number_of_slices) slideShowed(slider, slideIndex); 
		}
	
		var value = (direction=='t') ? -slider.height() : slider.height();
		var duration = getOptions(slider).transitionDuration;
		var tmp, to_radians = Math.PI/blocks.length, 
	//		delay_sum = 50*blocks.length;
			delay_sum = duration*0.5;
		$.each(blocks, function(index, val) {
			var block = $(val) ,lefty;
			lefty = "-"+block.css('left');
			block.css({ display : 'block',
				        top     :  value,
				        backgroundPosition : lefty+" "+(-value)+"px"  });
			tmp = Math.sin(index*to_radians);
			setTimeout(function(){
				block.animate({ top : 0, opacity: 1, backgroundPosition : lefty+" 0px"  }, duration*0.750, animation_finished);
			}, delay_sum-(delay_sum * tmp));
		});

	}, // fountain

	//
	// LightBeam
	//
	lightBeam : function(slider, slideIndex, direction) {
		slider = $(slider);
		var blocks;
		blocks = getBlocks(slider, 1, 1, {
			background : 'url('+getSlideUrl(slider, slideIndex)+') no-repeat',
			display: 'block'
		});
		slider.data('jdslider:blocks', blocks);
		slider.data('jdslider:showing', slideIndex);
		slider.attr('jdslider:lightbeam', 0);
		slider.animate({ 'jdslider:lightbeam': 1 }, getOptions(slider).transitionDuration, 'linear');
	}, // lightBeam



	//
	// Random Blocks
	//
	randomBlocks : function(slider, slideIndex, acols, arows) {
		var blocks, cols = acols || 8, rows = arows || 4, number_of_blocks = cols*rows;
		
		blocks = getBlocks(slider, cols, rows, {
			backgroundImage : 'url('+getSlideUrl(slider, slideIndex)+')',
			opacity : 0,
			display : 'block'
		});
	
		var finished = 0;
		function animation_finished(){ 
			finished++;
			if (finished==number_of_blocks) slideShowed(slider, slideIndex); 
		}

		blocks = blocks.toArray();
		// shuffle array
		var i=blocks.length*2, tmp, r1, r2;
		while(i--) {
			r1 = random(number_of_blocks);
			r2 = random(number_of_blocks);
			tmp = blocks[r1]; blocks[r1]=blocks[r2]; blocks[r2]=tmp;
		}

		var duration = getOptions(slider).transitionDuration;
		$.each(blocks, function(index, val){
			var block = $(val);
			setTimeout(function(){
				block.animate({ opacity: 1 }, duration*0.4, animation_finished);
			//}, Math.random()*1000 );
			}, (index*duration/blocks.length*0.8));
		});

	}, // random_blocks

	//
	// Random Slices Vertical 
	//
	randomSlicesVertical :function(slider, slideIndex, options) {
		$.jdSlider.transitions.randomBlocks(slider, slideIndex, 10, 1);
	}, // random_slices_vertical

	//
	// Random Slices Horizontal 
	//
	randomSlicesHorizontal :function(slider, slideIndex, options) {
		$.jdSlider.transitions.randomBlocks(slider, slideIndex, 1, 5);
	}, // random_slices_horizontal
	

	//
	// Shrink
	//
	shrink : function(slider, slideIndex, direction) {
		var current_slide = slider.data('jdslider:current-slide'),
			width  = slider.width(), 
			height = slider.height();
		var block=$('.jdslider_img', slider).first();
		if (block.length==0) block=$('<img class="jdslider_img"/>').appendTo(slider);
		block.attr('src', getSlideUrl(slider, current_slide) );
		block.css({
			display    : 'block',
			width      : width,
			height     : height,
			left       : 0,
			top        : 0,
			opacity    : 1,
			position   : 'absolute'
		});
		slider.css('background','url('+ getSlideUrl(slider, slideIndex) +') no-repeat');

		var slides = slider.data('jdslider:slides');
		slides[slider.data('jdslider:current-slide')].elem.hide();

		block.animate({ 
			width   : 0,
			left    : width/2+"px",
			height  : 0,
			top     : height/2+"px",
			opacity : 1
		}, getOptions(slider).transitionDuration, "easeInBack", function(){ slideShowed(slider, slideIndex); });
	}, // shrink


	//
	// Slice Fade
	//
	sliceFade : function(slider, slideIndex, direction) {
		var blocks;
		var number_of_slices = 10;
	
		var DIRECTIONS = ['l', 'r', 't', 'b']
		if (!direction) direction = DIRECTIONS[ random(4) ];
		//console.log(direction)	
		var cols = isVertical(direction) ? 1 : number_of_slices;
		var rows = isVertical(direction) ? number_of_slices : 1;
		blocks = getBlocks(slider, cols, rows, {
			backgroundImage : 'url('+getSlideUrl(slider, slideIndex)+')',
			display : 'block',
			opacity : 0
		});

		var finished = 0;
		function animation_finished(){ 
			finished++;
			if (finished==number_of_slices) slideShowed(slider, slideIndex); 
		}
	
		if (direction=='r' || direction=='b') blocks = blocks.toArray().reverse();
		var duration = getOptions(slider).transitionDuration; 
		$.each(blocks, function(index, val){
			var block = $(val);
			setTimeout(function(){
				block.animate({ opacity: 1 }, duration*0.5, "linear", animation_finished);
		//	}, (50 + index*75));
			}, (index*duration/blocks.length*0.9));
		});

	}, // sliceFade


	//
	// Slice Slide
	//
	sliceSlideVertical : function(slider, slideIndex, direction) {
		var blocks;
		var number_of_slices = 10;
	
		var DIRECTIONS = ['l', 'r', 't', 'b']
		if (!direction) direction = DIRECTIONS[ random(4) ];
		//console.log(direction)	
		var cols = isVertical(direction) ? 1 : number_of_slices;
		var rows = isVertical(direction) ? number_of_slices : 1;
		cols = 10; rows = 1;
		blocks = getBlocks(slider, cols, rows, {
			backgroundImage : 'url('+getSlideUrl(slider, slideIndex)+')',
			opacity : 0
		});

		var finished = 0;
		function animation_finished(){ 
			finished++;
			if (finished==number_of_slices) slideShowed(slider, slideIndex); 
		}
	
		var property = (direction=='l' || direction=='r') ? 'width' : 'height' ;
		if (direction=='r' || direction=='b') blocks = blocks.toArray().reverse();
		var value = (direction=='l' || direction=='r') ? -slider.height() : slider.height();
		var duration = getOptions(slider).transitionDuration; 
		$.each(blocks, function(index, val){
			var block = $(val);
			block.css('display', 'block')
				 .css('top', value);
		//	     .css(property, 0);
			setTimeout(function(){
				block.animate({ top : 0, opacity: 1 }, duration*0.5, animation_finished);
		//	}, (50 + index*75));
			}, (index*duration/blocks.length*0.75));
		});

	}, // slice_slide_vertical

	sliceSlideHorizontal : function(slider, slideIndex, direction) {
		var blocks;
		var number_of_slices = 10;
	
		var DIRECTIONS = ['l', 'r', 't', 'b']
		if (!direction) direction = DIRECTIONS[ random(4) ];
		//console.log(direction)	
		var cols = isVertical(direction) ? 1 : number_of_slices;
		var rows = isVertical(direction) ? number_of_slices : 1;
		cols = 10; rows = 1;
		cols = 1; rows = 10;
		blocks = getBlocks(slider, cols, rows, {
			backgroundImage : 'url('+getSlideUrl(slider, slideIndex)+')',
			opacity : 0
		});

		var finished = 0;
		function animation_finished(){ 
			finished++;
			if (finished==number_of_slices) slideShowed(slider, slideIndex); 
		}
	
		var property = (direction=='l' || direction=='r') ? 'width' : 'height' ;
		if (direction=='r' || direction=='b') blocks = blocks.toArray().reverse();
		var value = (direction=='l' || direction=='r') ? -slider.height() : slider.height();
		var value = (direction=='l' || direction=='r') ? -slider.width() : slider.width();
		var duration = getOptions(slider).transitionDuration; 
		$.each(blocks, function(index, val) {
			var block = $(val);
			block.css('display', 'block')
				 .css('left', value);
		//	     .css(property, 0);
			setTimeout(function(){
				block.animate({ left : 0, opacity: 1 }, duration*0.5, animation_finished);
			//}, (index*75));
			}, (index*duration/blocks.length*0.75));
		});

	}, // slice_slide_horizontal


	//
	// Slide
	//
	slide : function(slider, slideIndex, direction) {
		DIRECTIONS = ['l', 'r', 't', 'b'];
		if (!direction) direction = DIRECTIONS[ random(4) ];

		var slides = slider.data('jdslider:slides'),
			current_slide = slides[slider.data('jdslider:current-slide')].elem,
			_left=0, _top=0;

		var block = getBlocks(slider, 1, 1, {
			backgroundImage : 'url('+getSlideUrl(slider, slideIndex)+')',
			display    : 'block'
		});

		if (direction=='l') _left =  slider.width();
		if (direction=='r') _left = -slider.width();
		if (direction=='t') _top  =  slider.height();
		if (direction=='b') _top  = -slider.height();
		block.css({ left: -_left, top: -_top })
			 .animate({ left: 0, top: 0 }, getOptions(slider).transitionDuration, function(){ slideShowed(slider, slideIndex) });
		current_slide.animate({ left: _left, top: _top }, getOptions(slider).transitionDuration );
	}, // slide


	//
	// SlideOver
	//
	slideOver : function(slider, slideIndex, direction) {
		DIRECTIONS = ['l', 'r', 't', 'b'];
		if (!direction) direction = DIRECTIONS[ random(4) ];
		var block = getBlocks(slider, 1, 1, {
			backgroundImage : 'url('+getSlideUrl(slider, slideIndex)+')',
			display    : 'block'
		});

		if (direction=='l') block.css('left', -slider.width() );
		if (direction=='r') block.css('left',  slider.width() );
		if (direction=='t') block.css('top',  -slider.height() );
		if (direction=='b') block.css('top',  slider.height() );
	
		block.animate({ left: 0, top: 0 }, getOptions(slider).transitionDuration, function(){ slideShowed(slider, slideIndex) });
	}, // slideOver



	//
	// Strech_out
	//
	stretchOut : function(slider, slideIndex, direction) {
		var current_slide = slider.data('jdslider:current-slide'),
			width  = slider.width(), 
			height = slider.height();
		var block=$('.jdslider_img', slider).first();
		if (block.length==0) block=$('<img class="jdslider_img"/>').appendTo(slider);
		block.attr('src', getSlideUrl(slider, current_slide) );
		block.css({
			display    : 'block',
			width      : width,
			height     : height,
			left       : 0,
			top        : 0,
			opacity    : 1,
			position   : 'absolute'
		});
		slider.css('background','url('+ getSlideUrl(slider, slideIndex) +') no-repeat');

		var slides = slider.data('jdslider:slides');
		slides[slider.data('jdslider:current-slide')].elem.hide();

		block.animate({ 
			width   : "+="+width*2,
			left    : "-"+width+"px",
			height  : "+="+height/2,
			top     : "-"+height/4+"px",
			opacity : 0
		}, getOptions(slider).transitionDuration, function(){ slideShowed(slider, slideIndex); });
	}, // stretch_out

	//
	// Wipe
	//
	wipe : function (slider, slideIndex, direction) {
		slider = $(slider);
		var blocks, number_of_slices = 20;
		blocks = getLayers(slider, slideIndex, number_of_slices);
		slider.data('jdslider:blocks', blocks);
		slider.data('jdslider:showing', slideIndex);
		slider.attr('jdslider:wipe', 0);
		slider.animate({ 'jdslider:wipe': 1 }, 1000, 'linear');
	}, // wipe
	
	//
	// Zipper
	//
	zipper : function(slider, slideIndex, direction) {
		var blocks, number_of_slices = 10;
	
		var DIRECTIONS = ['l', 'r', 't', 'b']
		if (!direction) direction = DIRECTIONS[ random(4) ];

		var cols = isVertical(direction) ? 1 : number_of_slices;
		var rows = isVertical(direction) ? number_of_slices : 1;
	
		blocks = getBlocks(slider, cols, rows, {
			backgroundImage : 'url('+getSlideUrl(slider, slideIndex)+')'
		});
	
		var finished = 0;
		function animation_finished(){ 
			finished++;
			if (finished==number_of_slices) slideShowed(slider, slideIndex); 
		}
	
		if (direction=='r' || direction=='b') blocks = blocks.toArray().reverse();
		var value    = isVertical(direction) ? slider.width() : slider.height();
		var property = isVertical(direction) ? 'left' : 'top';
		var animated_prop = {};
		animated_prop[property] = 0;
		var duration = getOptions(slider).transitionDuration;
		$.each(blocks, function(index, val){
			var block = $(val);
			block.css('display', 'block')
				 .css(property, value);
			value = -value;
			setTimeout(function(){
				block.animate(animated_prop, duration*0.75, animation_finished);
			//}, (100 + index*100));
			}, (index*duration/blocks.length));
		});

	} // zipper

}; // transitions



//
// Clock
//
$.fx.step['jdslider:clock'] = function slider_clock(fx) {
	var slider = $(fx.elem);
	var blocks = slider.data('jdslider:blocks');

	if (fx.state == 0 ) { // Initialisation
		fx.start = 0.0; fx.end   = 1.0;
		fx.half_width    = Math.round( slider.width()/2 );
		fx.half_height   = Math.round( slider.height()/2 );
		fx.clock_blocks  = $('.jdslider_clock', slider);
		if (fx.clock_blocks.length==0) {
			for(i=0;i<4;i++) $('<div class="jdslider_clock" style="display:none"></div>').appendTo(slider);
			fx.clock_blocks  = $('.jdslider_clock', slider);
		}
		fx.clock_blocks.css({ 
			background : blocks.css('background'),
			display:'none', width: fx.half_width, height: fx.half_height+1, position:'absolute' 
		});
		$(fx.clock_blocks[0]).css({ left: fx.half_width+'px', top: 0                   });
		$(fx.clock_blocks[1]).css({ left: fx.half_width+'px', top: fx.half_height+'px' });
		$(fx.clock_blocks[2]).css({ left: 0,                  top: fx.half_height+'px' });
		$(fx.clock_blocks[3]).css({ left: 0,                  top: 0                   });
		$(fx.clock_blocks[0]).css('background-position', -fx.half_width+'px 0px');
		$(fx.clock_blocks[1]).css('background-position', -fx.half_width+'px -'+fx.half_height+'px');
		$(fx.clock_blocks[2]).css('background-position', '0px -'+fx.half_height+'px');
		$(fx.clock_blocks[3]).css('background-position', '0px 0px');

		fx.increment  = fx.half_height / blocks.length;
	}

	//fx.pos = 0.2
	var angle = 2*Math.PI * fx.pos;
	var tg = Math.tan(angle);
	var rect;
	var height = fx.half_height*2;
	fx.clock_blocks.css('display', 'none');
	var top, left, bottom, right;
	
	// pos < 0.25
	if (fx.pos < 0.25) {
		left   = fx.half_width;
		top    = 0;
		bottom = 0;
		for (var i = 0; i < blocks.length; i++) {
			top = Math.round((i+1)*fx.increment);
			right = left+Math.round(tg * top);
			rect = 'rect('+ (fx.half_height-top) +'px '+ right+'px '+(fx.half_height-bottom)+'px '+ left+'px)';
			blocks[i].style.clip = rect;
			bottom = top;
		}
	
	} else if (fx.pos < 0.75) { 	
	// 0.25 <= pos < 0.75 (metade inferior)
		$(fx.clock_blocks[0]).css('display', 'block');
		if (fx.pos==0.25) return;
		if (fx.pos>=0.5) $(fx.clock_blocks[1]).css('display', 'block');
		if (fx.pos==0.5) return;
		
		right  = fx.half_width*2;
		top    = 0;
		bottom = top;
		for (var i = 0; i < blocks.length; i++) {
			top  = -Math.round((i+1)*fx.increment);
			left = fx.half_width+Math.round(tg * top);
			rect = 'rect('+ (fx.half_height-bottom) +'px '+ right+'px '+(fx.half_height-top)+'px '+ left+'px)';
			blocks[i].style.clip = rect;
			bottom = top;
		}
	
 	} else if (fx.pos < 1) { 
 	// 0.75 <= pos < 1
		$(fx.clock_blocks[0]).css('display', 'block');
		$(fx.clock_blocks[1]).css('display', 'block');
		$(fx.clock_blocks[2]).css('display', 'block');
		if (fx.pos==0.75) return;
		left   = 0;
		top    = 0;
		bottom = 0;
		for (var i = 0; i < blocks.length; i++) {
			top   = Math.round((i+1)*fx.increment);
			right = fx.half_width+Math.round(tg * top);
			rect = 'rect('+ (fx.half_height-top) +'px '+ right+'px '+(fx.half_height-bottom)+'px '+ left+'px)';
			blocks[i].style.clip = rect;
			bottom = top;
		}
 	
 	} else if (fx.pos==1) { 
		fx.clock_blocks.css('display', 'block');
	}
	
	if (fx.state == 1) { 
		slideShowed(slider, slider.data('jdslider:showing') );
		fx.clock_blocks.css('display', 'none');
	}
}; // clock

//
// Circle
//
$.fx.step['jdslider:circle'] = function slider_clock(fx) {
	var slider = $(fx.elem);
	var blocks = slider.data('jdslider:blocks');

	if (fx.state == 0 ) { // Initialisation
		fx.start = 0.0; fx.end   = 1.0;
		fx.half_width    = Math.round( slider.width()/2 );
		fx.half_height   = Math.round( slider.height()/2 );
		fx.max_radius    = Math.sqrt( fx.half_width*fx.half_width+fx.half_height*fx.half_height );
		fx.increment     = fx.half_height / blocks.length;
	}

	var radius  = fx.max_radius * fx.pos,
		radius2 = radius*radius,
	    rect,
	    top, left, bottom, right;
	for (var i = 0; i < blocks.length; i++) { 
		top    = Math.round((i+1)*fx.increment);
		left   = Math.sqrt( radius2-top*top );
		if (isNaN(left)) break;
		right  = fx.half_width+left;
		left   = fx.half_width-left;
		rect = 'rect('+ (fx.half_height-top) +'px '+ right+'px '+(fx.half_height+top)+'px '+ left+'px)';
		blocks[i].style.clip = rect;
	}
	
	if (fx.state == 1) { slideShowed(slider, slider.data('jdslider:showing') );	}
}; // circle


//
// Diamond
//
$.fx.step['jdslider:diamond'] = function(fx) {
	var slider = $(fx.elem);
	var blocks = slider.data('jdslider:blocks');

	if (fx.state == 0 ) { // Initialisation
		fx.start = 0.0;
		fx.end   = 1.0;
		fx.half_width    = slider.width()/2;
		fx.half_height   = slider.height()/2;
		fx.max_dimension = fx.half_height+fx.half_width;
		fx.increment     = fx.half_height / blocks.length;
		
		var top = fx.half_height, bottom = top;
		$.each(blocks, function(index, block){
			block.rect_top    = top+'px ';
			block.rect_bottom = bottom+'px ';
			top -= fx.increment; bottom += fx.increment;
		});
		blocks[blocks.length-1].rect_top    = '0px ';
		blocks[blocks.length-1].rect_bottom = slider.height()+'px ';
	}
	var inc   = fx.increment, 
	    x     = fx.max_dimension * fx.pos,
	    left  = fx.half_width - x,
	    right = fx.half_width + x;
	for (var i = 0; i < blocks.length; i++) {
		blocks[i].style.clip = 'rect('+blocks[i].rect_top+ right+'px '+ blocks[i].rect_bottom+ left+'px)';
		left += inc; right -= inc;
	}

	if (fx.state == 1) { slideShowed(slider, slider.data('jdslider:showing') );	}
}; // diamond

//
// Exit_stage left, right
//
function exit_stage(fx, direction) {
	var slider = $(fx.elem);
	var blocks = slider.data('jdslider:blocks');

	if (fx.state == 0) { // Initialisation
		fx.start = 0.0; fx.end   = 1.0;
		fx.skew      = 50;
		fx.width     = slider.width();
		blocks.css('display','block');
		if (direction=='right') blocks.css('left', '');
	}
	var prop      = (fx.width+fx.skew)*fx.pos,
		increment = Math.min(prop, fx.skew)/blocks.length;

	for (var i = 0; i < blocks.length; i++) {
		$(blocks[i]).css(direction, prop+'px');
		prop -= increment;
	}
	
	if (fx.state == 1) { 
		slideShowed(slider, slider.data('jdslider:showing') );
	}
} // exit stage

$.fx.step['jdslider:exit_stage_left'] = function(fx) {
	exit_stage(fx, 'left');
}; // exit_stage_left

$.fx.step['jdslider:exit_stage_right'] = function(fx) {
	exit_stage(fx, 'right');
}; // exit_stage_right



//
// LightBeam
//
$.fx.step['jdslider:lightbeam'] = function(fx) {
	var slider = $(fx.elem);
	var blocks = slider.data('jdslider:blocks');

	var light_left   = $('.jdslider_light_left', slider).first();
		light_right  = $('.jdslider_light_right', slider).first();
		light        = $('.jdslider_light', slider).first();

	if (fx.state == 0 ) { // Initialisation
		fx.start = 0.0;
		fx.end   = 1.0;
		fx.slider_width   = slider.width();
		fx.slider_height  = slider.height();
		slider.css('overflow', 'visible');

		if (light_left.length==0) {
			light_left=$('<img class="jdslider_light_left" src="'+scriptPath()+'"light_left.png"/>').appendTo(slider);
			light_left.load(function(){ 
				$(this).css('width', '');
				this.original_width = $(this).width();
			}).attr('src', scriptPath()+'light_left.png');
			fx.original_light_width  = light_left.width();
		}
		if (light_right.length==0) {
			light_right=$('<img class="jdslider_light_right" src="'+scriptPath()+'light_right.png"/>').appendTo(slider);
			light_right.load(function(){ this.original_width = $(this).width();}).attr('src', scriptPath()+'light_right.png');

		}
		if (light.length==0) {
			light=$('<img class="jdslider_light" src="'+scriptPath()+'light_v.png"/>').appendTo(slider);	
			light.load(function(){ 
				$(this).css('width', '');
				this.original_width = $(this).width();
				fx.light_width = $(this).width();
			}).attr('src', scriptPath()+'light_v.png');
			light.css({ 
				position : 'absolute', 
				width    : light.width(),
				top      : '-13px', 
				height   : 26+slider.height(),
				display  : 'block'
			});
		}
		fx.light_width = light.width();
		
		light_left.css({ 
			position : 'absolute', 
			right    : -10+slider.width(),
			width    : fx.original_light_width,
			top      : '-60px', 
			height   : 120+slider.height()
		});

		light_right.css({ 
			position : 'absolute', 
			width    : 10,
			top      : '-60px', 
			height   : 120+slider.height(),
			display  : 'none'
		});
		light.css({ 
			position : 'absolute', 
			left     : -light.width()/2,
			width    : light.width(),
			top      : '-13px', 
			height   : 26+slider.height(),
			display  : 'block'
		});

	}

	var _left, _right, _width;
	fx.original_light_width = light_left.attr('original_width') || LIGHTBEAM_GLOW_WIDTH;

	light.css('left', fx.slider_width*fx.pos-fx.light_width/2 );
	blocks.css('width', fx.slider_width*fx.pos );
	
	if (fx.pos<=0.5) {
		light_right.css('display', 'none');
		_width = fx.original_light_width-(fx.original_light_width-10)*fx.pos*2;
		_right = (-10+fx.slider_width);
		_right = _right - (_right-fx.slider_width/2)*fx.pos*2;
		light_left.css({
			right   : _right,
			width   : _width,
			display : 'block'
		});
	} else {
		light_left.css('display', 'none');
		fx.pos = (fx.pos-0.5)*2;
		_width = 10-(10-fx.original_light_width)*fx.pos;
		_left  = fx.slider_width/2;
		_left  = _left - (_left -(fx.slider_width-10) )*fx.pos;
		light_right.css({
			left    : _left+'px',
			width   : _width,
			display : 'block'
		});
	}

	if (fx.state == 1) {
		light_left.css('display', 'none');
		light_right.css('display', 'none');
		light.css('display', 'none');
		//  BUGFIX to Chrome and Safari
		// without the timeout, changing de overflow to hidden left some "trace" of the lightbeam image
		setTimeout(function(){ slider.css('overflow', 'hidden'); }, 10);
		slideShowed(slider, slider.data('jdslider:showing') );	
	}
}; // lightbeam


//
// Wipe
//
$.fx.step['jdslider:wipe'] = function(fx) {
	var slider = $(fx.elem),
	    blocks = slider.data('jdslider:blocks');

	if (fx.state == 0 ) { // Initialisation
		fx.start = 0.0;
		fx.end   = 1.0;
		fx.gradient_width = 10;
		fx.slider_width   = slider.width();
		fx.slider_height  = slider.height();
		$.each(blocks, function(index, block){
			$(block).css('opacity', 1-(index/blocks.length) );
		});
	}

	var top    = 0, 
	    bottom = fx.slider_height+"px ",
	    left   = -fx.gradient_width + (fx.gradient_width+fx.slider_width)* fx.pos;
	var right = left+1;
	blocks[0].style.clip = 'rect(0px '+(left+1)+'px '+bottom+'0px)';
	left++;
	for (var i = 1; i < blocks.length; i++) {
		// IE7< nao aceita virgula no clip
		blocks[i].style.clip = 'rect(0px '+ (left+1)+'px '+bottom+ left+'px)';
		left += 1;
	}

	if (fx.state == 1) { slideShowed(slider, slider.data('jdslider:showing') );	}
}; // wipe


// *********************
//
// Utilities
//
// *********************

function random(number) {
	return Math.floor(Math.random()*number);
} // random

function getOptions(slider) {
	return slider.data('jdSlider:options');
} // getOptions

function isVertical(direction) {
	return (direction=='t' || direction=='b');
} // isVertical

function getSlideUrl(slider, slideIndex) {
	var slides = slider.data('jdslider:slides');
	return $(slides[slideIndex].image).attr('src');
} // getSlideUrl

// retorna N "layers", divs que serao usados em transitions 
// que usam clip como diamon, clock e circle
function getLayers(slider, slideIndex, number_of_layers) {
	return getBlocks(slider, 1, number_of_layers, {
		background : 'url('+getSlideUrl(slider, slideIndex)+') no-repeat',
		left : 0, width: slider.width(),
		top  : 0, height: slider.height(), 
		clip: 'rect(0 0 0 0)', 
		display: 'block'
	});
} // getLayers


function getBlocks(slider, cols, rows, css) {
	if (!cols) cols = 1;
	if (!rows) rows = 1;
	if (!css) css = {};
	
	var number_of_blocks = rows*cols,
		block_width      = Math.floor(slider.width() / cols),
	    block_height     = slider.height() / rows,
	    blocks=$('.jdslider_block', slider).slice(0, number_of_blocks);
	var slider_width = slider.width(), slider_height = slider.height();

	// create blocks
	if (blocks.length<number_of_blocks) {
		for(var i=0; i<=(number_of_blocks-blocks.length); i++) {
			$('<div class="jdslider_block"></div>')
			      .css({ left:'10px', width: '10px', position:'absolute', backgroundRepeat : 'no-repeat' }) 
			      .appendTo(slider);
		}
	    blocks=$('.jdslider_block', slider).slice(0, number_of_blocks);
	}

	var heights = [], tops=[], t=0, h=0;
	for(y=0; y<rows; y++) {
		tops.push(Math.floor(t));
		t += block_height;
		if (y==rows-1) t=slider_height;
		heights.push( Math.floor( t-tops[y] ) );
	}	
	var i=0, w, h=block_height, left, top;
			
	w = block_width;
	for(x=0; x<cols;x++) { 
		if (x==cols-1) w=slider.width()-block_width*x;
		for(y=0; y<rows; y++) {

			left = block_width*x;
			top = tops[y]; // block_height*y;
			blocks[i].slider_x = x;
			blocks[i].slider_y = y;
			$(blocks[i]).css({
				width      : w, //block_width,
				height     : heights[y], // hblock_height,
				top        : top,
				left       : left,
				display    : 'none',
				opacity    : 1,
				backgroundPosition : "-"+left+"px -"+top+"px",
				clip      : 'rect(0px '+slider_width+'px '+slider.height()+'px 0px)'
			});
			i++;
		}
	}
	blocks.css(css);
	return blocks;
} // getBlocks

function scriptPath(){
	if (!SCRIPT_PATH) {
		var SCRIPT_NAME = "jawdropper_slider.js",
		    SCRIPT_NAME_2 = "jawdropper_slider.min.js";
		var src=$("script[src$="+SCRIPT_NAME+"]").attr("src");
		if (src) src = src.substring(0,src.lastIndexOf(SCRIPT_NAME))
		else {
			src=$("script[src$="+SCRIPT_NAME_2+"]").attr("src");		
			if (src) src = src.substring(0,src.lastIndexOf(SCRIPT_NAME_2));
		}
		SCRIPT_PATH = src || "";
	}
	return SCRIPT_PATH;
} // scriptPath

})(jQuery);

/* replace plugin_name with you plugin name */
/**
 * @author Alexander Farkas
 * v. 1.21
 */
 
(function($) {
	if(!document.defaultView || !document.defaultView.getComputedStyle){ // IE6-IE8
		var oldCurCSS = jQuery.curCSS;
		jQuery.curCSS = function(elem, name, force){
			if(name === 'background-position'){
				name = 'backgroundPosition';
			}
			if(name !== 'backgroundPosition' || !elem.currentStyle || elem.currentStyle[ name ]){
				return oldCurCSS.apply(this, arguments);
			}
			var style = elem.style;
			if ( !force && style && style[ name ] ){
				return style[ name ];
			}
			return oldCurCSS(elem, 'backgroundPositionX', force) +' '+ oldCurCSS(elem, 'backgroundPositionY', force);
		};
	}

	var oldAnim = $.fn.animate;
	$.fn.animate = function(prop){
		if('background-position' in prop){
			prop.backgroundPosition = prop['background-position'];
			delete prop['background-position'];
		}
		if('backgroundPosition' in prop){
			prop.backgroundPosition = '('+ prop.backgroundPosition;
		}
		return oldAnim.apply(this, arguments);
	};
	
	function toArray(strg){
		strg = strg.replace(/left|top/g,'0px');
		strg = strg.replace(/right|bottom/g,'100%');
		strg = strg.replace(/([0-9\.]+)(\s|\)|$)/g,"$1px$2");
		var res = strg.match(/(-?[0-9\.]+)(px|\%|em|pt)\s(-?[0-9\.]+)(px|\%|em|pt)/);
		return [parseFloat(res[1],10),res[2],parseFloat(res[3],10),res[4]];
	}
	
	$.fx.step.backgroundPosition = function(fx) {
		if (!fx.bgPosReady) {
			var start = $.curCSS(fx.elem,'backgroundPosition');
			
			if(!start){//FF2 no inline-style fallback
				start = '0px 0px';
			}
			
			start = toArray(start);
			
			fx.start = [start[0],start[2]];
			
			var end = toArray(fx.options.curAnim.backgroundPosition);
			fx.end = [end[0],end[2]];
			
			fx.unit = [end[1],end[3]];
			fx.bgPosReady = true;
		}
		//return;
		var nowPosX = [];
		nowPosX[0] = ((fx.end[0] - fx.start[0]) * fx.pos) + fx.start[0] + fx.unit[0];
		nowPosX[1] = ((fx.end[1] - fx.start[1]) * fx.pos) + fx.start[1] + fx.unit[1];           
		fx.elem.style.backgroundPosition = nowPosX[0]+' '+nowPosX[1];

	};
})(jQuery);


/*
 * jQuery Easing v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/
 *
 * Uses the built in easing capabilities added In jQuery 1.1
 * to offer multiple easing options
 *
 * TERMS OF USE - jQuery Easing
 * 
 * Open source under the BSD License. 
 * 
 * Copyright © 2008 George McGinley Smith
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, 
 * are permitted provided that the following conditions are met:
 * 
 * Redistributions of source code must retain the above copyright notice, this list of 
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list 
 * of conditions and the following disclaimer in the documentation and/or other materials 
 * provided with the distribution.
 * 
 * Neither the name of the author nor the names of contributors may be used to endorse 
 * or promote products derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY 
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED 
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
 * OF THE POSSIBILITY OF SUCH DAMAGE. 
 *
*/

// t: current time, b: begInnIng value, c: change In value, d: duration
jQuery.easing['jswing'] = jQuery.easing['swing'];

jQuery.extend( jQuery.easing,
{
	easeInBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*(t/=d)*t*((s+1)*t - s) + b;
	},
	easeInOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158; 
		if ((t/=d/2) < 1) return c/2*(t*t*(((s*=(1.525))+1)*t - s)) + b;
		return c/2*((t-=2)*t*(((s*=(1.525))+1)*t + s) + 2) + b;
	}
});

/*
 *
 * TERMS OF USE - EASING EQUATIONS
 * 
 * Open source under the BSD License. 
 * 
 * Copyright © 2001 Robert Penner
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, 
 * are permitted provided that the following conditions are met:
 * 
 * Redistributions of source code must retain the above copyright notice, this list of 
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list 
 * of conditions and the following disclaimer in the documentation and/or other materials 
 * provided with the distribution.
 * 
 * Neither the name of the author nor the names of contributors may be used to endorse 
 * or promote products derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY 
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED 
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
 * OF THE POSSIBILITY OF SUCH DAMAGE. 
 *
 */
