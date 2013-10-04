	// PageLoad function
	// This function is called when:
	// 1. after calling $.historyInit();
	// 2. after calling $.historyLoad();
	// 3. after pushing "Go Back" button of a browser
	function pageload(hash) {
		// alert("pageload: " + hash);
		// hash doesn't contain the first # character.
		if(hash) {
			// restore ajax loaded state
			if($.browser.msie) {
				// jquery's $.load() function does't work when hash include special characters like aao.
				hash = encodeURIComponent(hash);
			}
			
			var jqueryTab;
			jqueryTab = hash.split('-');
			//alert(hash);
			
			var name = jqueryTab[0];
			
			name = jqueryTab[0].split('_');
			document.title = name[0]+' '+name[1];
			document.title = hash;
			//this.href+' WWW ';
			
			$("#species_detail").load(hash);
			
			var test = this.referrer;
			//alert(location.hash);
			//$("div[a:contains('n.1.1.1.9.101.438.2517.21115')]").addClass("x-tree-selected");
			// 
			//$("#result_1").load("index.php?page=species&ajax=yes&section=detail_ssp&id_species="+jqueryTab[1]);
			//$("#langage > a").each(function(){ this.href = this.href+"#"+hash; });
		} else {
			// start page
			$("#species_detail").empty();
		}
	}

	$(document).ready(function(){
		// Initialize history plugin.
		// The callback is called at once by present location.hash. 
		
		
		$.historyInit(pageload, "jquery_history.html");
		
		// set onlick event for buttons
		$("a[rel='history']").click(function(){
			// 
			var hash = this.href;
			
			//alert('fghxfgh');
			hash = hash.replace(/^.*#/, '');

			// moves to a new page. 
			// pageload is called at once. 
			// hash don't contain "#", "?"
			$.historyLoad(hash);
			return false;
		});
	
	
	});