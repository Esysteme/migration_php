/*!
 * Ext JS Library 3.0.0
 * Copyright(c) 2006-2009 Ext JS, LLC
 * licensing@extjs.com
 * http://www.extjs.com/license
 */

Ext.onReady
(
	function()
	{
		
		Ext.Direct.addProvider(Ext.app.REMOTING_API);

		var tree = new Ext.tree.TreePanel
		(
			{
				autoScroll: true,
				renderTo: document.getElementById('menu_cadre_arbre'),
				animate: false, //lol
				rootVisible:true,
				enableDD:false,
				
				root: {
					expanded:true,
					useArrows: false,
					id: 'n.1.1.1.9.101.438', //n.1.1.1.9.101.438
					text: '<b>Estrildidae</b>',
				},
				preloadChilren: true,
				
				listeners:
				{
					click: function(n)
					{

						var a,b,c;
						
						a = n.attributes.id.split('.');

						if (a.length == 9)
						{
							b = a[a.length-1];
							
							var  reg=new  RegExp("( )", "g");
							
							
							//window.location = '#'+n.attributes.text.replace(reg,"_")+"-"+b
							
							$(function() {
								$.History.trigger('/thecus/www/species/en/species/nominal/Heteromunia_pectoralis'); 
							 });
							
							
							/*
							
							//Ext.Msg.alert('Trop content lol', 'LOL You clicked: "' + n.attributes.text + '"');
							function file(fichier)
							{
								if(window.XMLHttpRequest) // FIREFOX
								xhr_object = new XMLHttpRequest();
								else if(window.ActiveXObject) // IE
								xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
								else
								return(false);
								xhr_object.open("GET", fichier, false);
								xhr_object.send(null);
								if(xhr_object.readyState == 4) return(xhr_object.responseText);
								else return(false);
							}

							c = file("index.php?page=species&ajax=yes&section=detail_species&id_species="+b);
							document.getElementById('species_detail').innerHTML=c;
							*/
							//Ext.Msg.alert('Trop content lol', 'LOL You clicked: "' + a.length + '"');
							
						}
					}
				},
				

				
				loader: 
				new Ext.tree.TreeLoader
				(
					{
						
						directFn: TestAction.getTree,

					}
					
				),
				fbar:[
					{
					text: 'Load one part of tree',
					handler: function(){
						//tree.selectPath("/n.1/n.1.1/n.1.1.1/n.1.1.1.9/n.1.1.1.9.101/n.1.1.1.9.101.438/n.1.1.1.9.101.438.2517/n.1.1.1.9.101.438.2517.9207");
						tree.selectPath("/n.1.1.1.9.101.438/n.1.1.1.9.101.438.2517/n.1.1.1.9.101.438.2517.9207");
					}},
					{
					text: 'Collapse all',
					handler: function(){
						tree.collapseAll();
					}},
				{
					text: 'Expand all',
					handler: function(){
						tree.expandAll();
					}
				}]

			}
			
		);
		
		
		//tree.getRootNode().reload();
		//createNode: 'n.1.1.1.9.101.438',
	

	
	return true;

	}

);


/*

  height: 800,
        fbar: [{
            text: 'Reload root',
            handler: function(){
                tree.getRootNode().reload();
            }
        }]
*/