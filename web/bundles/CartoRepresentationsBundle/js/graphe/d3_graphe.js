function D3_GrapheRepresentation(){}

var zoom = null;

/** 
 * Fonction show : appelle la fonction load en lui passant le json
 *
 * @param data : le json sous forme d'objet json ou de chaine de caractères
 */
D3_GrapheRepresentation.prototype.show = function(data) {
		// data is file path
	if(typeof data === "string"){
		d3.json(data, function(error, root) {
			if (error) alert(error);
			D3_GrapheRepresentation.load(root);
		});
	}
	// data is json
	else {
		D3_GrapheRepresentation.load(data);
	}
}

/** 
 * Fonction load : ajoute les balises svg au conteneur pour afficher la vue graph pour le json donné
 *
 * @param json : fichier json rendu par la recherche
 */
D3_GrapheRepresentation.load = function(json) {
//d3.chart.dependencyWheel = function(options) {
	
	/***************************************************/
	/*		Transformation du json generique 		   */
	/***************************************************/

	var formatter = new D3_Formatter();
	var graph = formatter.to_graph(json);
	
	/***************************************************/
	/*					Outils						   */
	/***************************************************/
	var d3_utils = new D3_Utils();

	/***************************/
	/*		Relations 		   */
	/**************************/
	var colorLink = d3.scale.category20();
	d3_utils.showRelation(json, "graph", colorLink);
		
	/***************************/
	/*		Graphe	 		   */
	/**************************/
	
	//Zoom sert aux fonctions de zoom communes à toutes les représentations
	zoom = d3.behavior.zoom()
			.scaleExtent([1, 10])
			.on("zoom", zoomed);
	
	// On recupere la taille de la div pour mettre le svg
	var width = $("#contentCenter").width(),
    height = $("#contentCenter").height();
	
	var color = d3.scale.category20();

	//Le layout de D3 permet d'agencer sous forme de graphe
	var force = d3.layout.force()
		.charge(-400)
		.linkDistance(20)
		.size([width, height]);
	
	force
		.nodes(graph.nodes)
		.links(graph.links)
		.start();
		
	// On cree un nouveau noeud <svg>
	//On configure le svg qui contiendra toute la figure
	var svg = d3.select("#contentCenter").append("svg")
		.attr("width", width)
		.attr("height", height)
		.attr("class", "svgContainer");
		
	// On specifie une origine
	var d = [{ x: 20, y: 20 }];
	// On cree un nouveau noeud <g> pour mettre plusieurs attributs
	var container = d3.select('.svgContainer')
		.data(d)
		.append("g")
		.attr("class", "representationContainer")
		.attr("id","representationContainer")
		.attr("transform", function (d) { return "translate(" + d.x + "," + d.y + ")"; })
		.attr("tx", function (d) { return d.x; })
		.attr("ty", function (d) { return d.y; })
		.attr("sc", 1);
		
	// Pour tous les éléments .link on crée un noeud <line>
	// On cree les liens de la representation
	var link = container.selectAll(".link")
		.data(graph.links)
		.enter()
			.append("line")
			.attr("class", "link")
			.attr("class", function(d) {
				var nameClass = "";
				for(var i=0; i < d.name.length; i++){
					nameClass = nameClass + d.name[i] + " ";
				}
				return nameClass;
			})
			.style("stroke-width", function(d) { return Math.sqrt(d.value); })
			.style("stroke", "#999");
	
	/*var drag = d3.behavior.drag()
		.on("drag", function(d,i) {
			d.x += d3.event.dx;
			d.y += d3.event.dy;
			d3.select(this).moveToFront().attr("transform", function(d, i) {
			   return "translate(" + [d.x, d.y] + ")";
			});
		})
		.on("dragend", function(d,i) {
			orig_x = d.orig_x;
			new_x = d.x;

			// ugly hack to propagate click event on anchor tags in Firefox
			if ((orig_x == new_x) && $.browser.mozilla == true) { 
				if (d3.event.sourceEvent.target.parentNode.attributes.href){
				  href_raw = d3.event.sourceEvent.target.parentNode.attributes.href.value;
				  $(location).attr("href", href_raw);
				}
			}

			//set index of interval new_x falls into
			if (new_x <= 0) {
				new_pos_lower_bound = 0;
			} else if (new_x >= 300) {
				new_pos_lower_bound =  rangePoints[rangePoints.length-1]
			} else {
				$.each(rangePoints, function(i, rp){
				  if(new_x <= rp){
					new_pos_lower_bound = (new_x > orig_x) ? rangePoints[i-1] : rangePoints[i];
					return false
				  }
				})
			}
		});*/
		
	// Pour tous les elements .node on cree un noeud <g>
	// On cree les noeuds de la representation
	var node = container.selectAll(".node")
		.data(graph.nodes)
		.enter()
			.append("g")
			.attr("class", "node")
			.attr("transform", function(d) { return "rotate(" + d.x + ")translate(" + d.y + ")"; })
			.call(force.drag);
			
	// A chaque node <g> on cree un noeud <circle>
	// Les noeuds sont representes par des cercles
	node.append("circle")
		.attr("r", 5)
		.style("stroke", "#fff")
		.style("stroke-width", 1.5)
		.style("fill", function(d) { return color(d.group); });
		
		
	// A chaque noeud on affiche son nom
	node.append("text")
		.attr("x", 12)
		.attr("dy", ".35em")
		.attr("class", "text")
		.text(function(d) { 
			var sansEspace = new RegExp(/\s/); 
			if(sansEspace.test(d.name.toString()) == false) return d.name; 
		})
		.attr("cursor","pointer")
		
		// Quand on clique sur un mot on affiche l'information wikipedia
		.on("click", function(d) {
			d3_utils.show_wikipedia(d.name);
		})
		
		// Quand on double clique sur un mot on recharge son json
		.on("dblclick", function(d) {
			d3_utils.load_json(d);
		});
	
	force.on("tick", function() {
		link.attr("x1", function(d) { return d.source.x; })
			.attr("y1", function(d) { return d.source.y; })
			.attr("x2", function(d) { return d.target.x; })
			.attr("y2", function(d) { return d.target.y; });
			
		node.attr("transform", function(d) { 
			return "translate(" + d.x + "," + d.y + ")"; 
		});
	});
	
	// On ajoute des etiquettes sur les noeuds
	$('svg g circle').tipsy({ 
		gravity: 'w', 
		html: true, 
		title: function() {
			var d = this.__data__;
			if(d.type != null){
				return "<div>"+ d.type + "</div><div class='floatingp'>"+d.name+"</div>";
			}else{
				return "</div><div class='floatingp'>"+d.name+"</div>";
			}
		}
	});

	// Si on clique sur le bouton ayant la classe
	// zoom on appelle la fonction zoomClick
	d3.selectAll('.zoom').on('click', d3_utils.zoomClick);
	
	// Si on clique sur le bouton ayant la classe
	// dragAndDrop on appelle la fonction dragAndDrop
	d3.selectAll('.dragAndDrop')
		.attr("value", "0")
		.on('click', function(){
			d3_utils.dragAndDrop(force);
		});

	// On désactive les boutons inutiles pour cette vue
	d3.selectAll('.rotate').attr("value","0").attr("class","inactif");
}
