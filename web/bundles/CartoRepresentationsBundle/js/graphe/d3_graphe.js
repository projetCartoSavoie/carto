function D3_GrapheRepresentation(){}

var zoom = null;

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

D3_GrapheRepresentation.load = function(json) {

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
	
	d3_utils.showRelation(json, "graph", json.links);
		
	/***************************/
	/*		Graphe	 		   */
	/**************************/
	
	//Zoom sert aux fonctions de zoom communes � toutes les repr�sentations
	zoom = d3.behavior.zoom()
			.scaleExtent([1, 10])
			.on("zoom", zoomed);

	var width = $("#contentCenter").width(),
    height = $("#contentCenter").height();

	var color = d3.scale.category20();
	var colorLink = d3.scale.category20();

	var force = d3.layout.force()
		.charge(-400)
		.linkDistance(20)
		.size([width, height]);
		
	force
		.nodes(graph.nodes)
		.links(graph.links)
		.start();
		
	// On cree un nouveau noeud <svg>
	var svg = d3.select("#contentCenter").append("svg")
		.attr("width", width)
		.attr("height", height)
		.attr("class", "svgContainer");
		
	// On specifie une origine
	var d = [{ x: 20, y: 20 }];
	// On cree un nouveau noeud <g>
	var container = d3.select('.svgContainer')
		.data(d)
		.append("g")
		.attr("class", "representationContainer")
		.attr("id","representationContainer")
		.attr("transform", function (d) { return "translate(" + d.x + "," + d.y + ")"; })
		.attr("tx", 20)
		.attr("ty", 20)
		.attr("sc", 1);
		
	/* Define the data for the circles */
	// Pour tous les �l�ments .link on cr�e un noeud <line>
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
	
	var drag = d3.behavior.drag()
		.on("drag", function(d, i) {
			d.x += d3.event.dx;
			d.y += d3.event.dy;
			d3.select(this).moveToFront().attr("transform", function(d, i) {
			   return "translate(" + [d.x, d.y] + ")";
			});
		})
		.on("dragend", function(d, i){
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
		});
		
	// Pour tous les �l�ments .node on cr�e un noeud <g>
	var node = container.selectAll(".node")
		.data(graph.nodes)
		.enter()
			.append("g")
			.attr("class", "node")
			.attr("transform", function(d) { return "rotate(" + d.x + ")translate(" + d.y + ")"; })
			.call(force.drag);
			
	// A chaque node <g> on cr�e un noeud <circle>
	node.append("circle")
		.attr("r", 5)
		.style("stroke", "#fff")
		.style("stroke-width", 1.5)
		.style("fill", function(d) { return color(d.group); });
		
		
	// A chaque noeud on affiche son nom
	node.append("text")
		.attr("x", 12)
		.attr("dy", ".35em")
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
		
	// On affiche un titre lorsqu'on passe la souris
	node.append("title")
		.text(function(d) { return d.name; });
	
	force.on("tick", function() {
		link.attr("x1", function(d) { return d.source.x; })
			.attr("y1", function(d) { return d.source.y; })
			.attr("x2", function(d) { return d.target.x; })
			.attr("y2", function(d) { return d.target.y; });
			
		node.attr("transform", function(d) { 
			return "translate(" + d.x + "," + d.y + ")"; 
		});
	});

	// Si on clique sur le bouton ayant la classe
	// zoom on appelle la fonction zoomClick
	d3.selectAll('.zoom').on('click', zoomClick);
	
	// Si on clique sur le bouton ayant la classe
	// dragAndDrop on appelle la fonction dragAndDrop
	d3.selectAll('.dragAndDrop')
		.attr("value", "0")
		.on('click', d3_utils.dragAndDrop);

	// On d�sactive les boutons inutiles pour cette vue
	d3.selectAll('.rotate').attr("value","0").attr("class","inactif");

}

function zoomClick() {

	var width = $("#contentCenter").width();
	var height = $("#contentCenter").height();

	var clicked = d3.event.target,
		direction = 1,
		factor = 0.2,
		target_zoom = 1,
		center = [width / 2, height / 2],
		extent = zoom.scaleExtent(),
		translate = zoom.translate(),
		translate0 = [],
		l = [],
		view = {x: translate[0], y: translate[1], k: zoom.scale()};

	d3.event.preventDefault();
	
	translate0 = [(center[0] - view.x) / view.k, (center[1] - view.y) / view.k];
	
	// On revient sur la taille initiale
	if(this.id === 'intial_scale'){
		view.k = 1;
	}
	// Zoom / Dezoom
	else {
		direction = (this.id === 'zoom_in') ? 1 : -1;
		target_zoom = zoom.scale() * (1 + factor * direction);
		view.k = target_zoom;
	}
	l = [translate0[0] * view.k + view.x, translate0[1] * view.k + view.y];

	view.x += center[0] - l[0];
	view.y += center[1] - l[1];

	interpolateZoom([view.x, view.y], view.k);
}

function zoomed() {
	var container = d3.select(".representationContainer");
	var tx = Number($("#representationContainer").attr('tx'));
	var ty = Number($("#representationContainer").attr('ty'));
	var sc = zoom.scale();
	var rotation = Number($("#rotate").attr('value'))
	container.attr("transform",
		"translate(" + tx + "," + ty + ")"  +
		"scale(" + sc + ")" +
		"rotate(" + rotation + ")"
	);
	container.attr("sc",sc);
}

function interpolateZoom (translate, scale) {
	var self = this;
	return d3.transition().duration(350).tween("zoom", function () {
		var iTranslate = d3.interpolate(zoom.translate(), translate),
			iScale = d3.interpolate(zoom.scale(), scale);
		return function (t) {
		zoom
			.scale(iScale(t))
			.translate(iTranslate(t));
		zoomed();
		};
	});
}
