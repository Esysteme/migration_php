/*
$(function(){
	$('.photo_link').live('click', function(){
		$('.photo_link').removeClass('active');
		$('.photo_link').addClass('passive');
		$(this).removeClass('passive');
		$(this).addClass('active');
	});
});

*/
		

$(document).ready(function(){
   $('.menu_tab > li').live('click', function(){
   
				$(this).parent().children('li').siblings().removeClass('selected');
				$(this).addClass('selected');

				
				var page2 = $(this).children('a').attr('href');
				var id_a = $(this).attr('id');
				
				
				// var destination = $(this).parents("ul:first").next();
				
				var controller = $(this).children('a').attr('data-link');
				var target = $(this).children('a').attr('data-target');
				
				//target = "content_1";
				
				url_ajax = page2+controller+'>'+target+'/';
				
	
				//alert(url_ajax);

	
       			$("#"+target).hide(0, function(){
				$(this).load(url_ajax, 'data', function(){
					$(this).fadeIn(200);
					
					var data = $(this).html();
					
					var etat = {data: data, id_a: id_a, url_ajax: url_ajax, type_link: 'onglet', target :target};
					history.pushState(etat, document.title, page2);

				});

			});
	   
       return false;
   });
});

onpopstate = function(event) {
	if (event.state.type_link)
	{
		switch(event.state.type_link)
		{
			case "onglet":
				$("#"+event.state.id_a).parent().children('li').siblings().removeClass('selected');
				$("#"+event.state.id_a).addClass('selected');
			break;
		}
	}
	$("#"+event.state.target).html(event.state.data);

}



	

