function D3_NodeLinkTreeRepresentation(){}

D3_NodeLinkTreeRepresentation.prototype.show = function(data) {

	// data is file path
	if(typeof data === "string"){
		d3.json(data, function(error, root) {
			if (error) alert(error);
			D3_NodeLinkTreeRepresentation.load(root);
		});
	}
	// data is json
	else {
		D3_NodeLinkTreeRepresentation.load(data);
	}
}
	
D3_NodeLinkTreeRepresentation.load = function(json) {


	/***************************************************/
	/*		Transformation du json generique 		   */
	/***************************************************/
	
	// On transforme le fichier generique json au bon format 
	// pour la representation concernee
	var formatter = new D3_Formatter();
	var json = formatter.to_tree(json);
	
	/***************************************************/
	/*					Outils						   */
	/***************************************************/
	var d3_utils = new D3_Utils();


	/***************************/
	/*		Relations 		   */
	/**************************/
	
	var data = json.relationsUsed;
	var paragraphs = d3.select('.selectRelation')
		.on("change",change)
		.selectAll(".relation")
			.data(data)
				.enter()
				.append("option")
				.attr("class", "relation");

	// On configure le texte
	paragraphs
		.attr("value", function (d) { return d;})
		.text(function (d) { return d; });
		
	/***************************/
	/*		Graphe	 		   */
	/**************************/
	
	zoom = d3.behavior.zoom()
			.scaleExtent([1, 10])
			.on("zoom", zoomed);
			
	var width = $("#contentCenter").width(),
    height = $("#contentCenter").height();
	
	var diameter = width;
	
	var color = d3.scale.category20();
	var colorLink = d3.scale.category20();

	var tree = d3.layout.tree()
		.size([360, diameter / 2 - 200])
		.separation(function(a, b) { return (a.parent == b.parent ? 1 : 2) / a.depth; });

	var diagonal = d3.svg.diagonal.radial()
		.projection(function(d) { return [d.y, d.x / 180 * Math.PI]; });
		
	// On cree un nouveau noeud <svg>
	var svg = d3.select("#contentCenter").append("svg")
		.attr("width", diameter)
		.attr("height", diameter - 150)
		.attr("class", "svgContainer");
		
	// On specifie une origine
	var d = [{ x: 20, y: 20 }];
	// On cree un nouveau noeud <g>
	var container = d3.select('.svgContainer')
		.data(d)
		.append("g")
		.attr("class", "representationContainer")
		.attr("transform", "translate(" + diameter / 2 + "," + diameter / 2 + ")");
		
	/*var container = svg.append("g")
		.attr("class", "representationContainer")
		.attr("transform", "translate(" + diameter / 2 + "," + diameter / 2 + ")");*/
		
	d3.select(self.frameElement).style("height", diameter - 150 + "px");
	
	var nodes = tree.nodes(json),
		links = json.links;
			
	var link = container.selectAll(".link")
		.data(links)
		.enter()
			.append("path")
			.attr("class", "link")
			.attr("id", function(d) { return d.name; })
			.style("stroke-width", function(d) { return Math.sqrt(d.value); })
			.style("stroke", "#999")
			.attr("d", diagonal);
		
	// Quand on clique sur une relation on affiche
	// les liens en couleur
	function change(){
		// On recupere ce que l'utilisateur a choisi
		nameRelation = this.options[this.selectedIndex].value;
		// On redessine les liens en couleur de base
		d3.selectAll("path")
				.style("stroke-width", function(d) { return Math.sqrt(d.value); })
				.style("stroke", "#999");
		// Pour tous les liens du graphe
		links.forEach(
			function(d){
				// Si le lien a la relation selectionnee alors on met en couleur
				if(d.name.localeCompare(nameRelation) == 0){
					d3.selectAll('#' + d.name)
						.style("stroke-width", 3)
						.style("stroke",  colorLink(d.value));
				}
			}
		);
	};

	var node = container.selectAll(".node")
		.data(nodes)
		.enter()
			.append("g")
			.attr("class", "node")
			.attr("transform", function(d) { return "rotate(" + (d.x - 90) + ")translate(" + d.y + ")"; });

	node.append("circle")
		.attr("r", 5)
		.style("fill", function(d) { return color(d.group); });
		
	// On affiche un titre lorsqu'on passe la souris
	node.append("title")
		.text(function(d) { return d.name; });

	node.append("text")
		.attr("dy", ".31em")
		.attr("text-anchor", function(d) { return d.x < 180 ? "start" : "end"; })
		.attr("transform", function(d) { return d.x < 180 ? "translate(8)" : "rotate(180)translate(-8)"; })
		.text(function(d) { 
			var sansEspace = new RegExp(/\s/); 
			if(sansEspace.test(d.name.toString()) == false) return d.name; 
		})
		.attr("cursor","pointer")
		.on("click", function(d) {
			d3_utils.show_wikipedia(d.name);
		})
		.on("dblclick", function(d) {
			d3_utils.load_json(d);
		});

	d3.selectAll('.zoom').on('click', zoomClick);
	
	// Si on clique sur le bouton ayant la classe
	// dragAndDrop on appelle la fonction dragAndDrop
	d3.selectAll('.dragAndDrop')
		.attr("value", "0")
		.on('click', d3_utils.dragAndDrop);
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
		scale = 0;
		
	d3.event.preventDefault();
	
	// On revient sur la taille initiale
	if(this.id === 'intial_scale'){
		scale = 1;
	}
	// Zoom / Dezoom
	else {
		direction = (this.id === 'zoom_in') ? 1 : -1;
		target_zoom = zoom.scale() * (1 + factor * direction);
		scale = target_zoom;
	}

	interpolateZoom(center, scale);
}

function zoomed(center) {
	var container = d3.select(".representationContainer");
	container.attr("transform",
		"translate(" + center[0] + "," + center[1] + ")"  +
		"scale(" + zoom.scale() + ")"
	);
}

function interpolateZoom (translate, scale) {
	var self = this;
	return d3.transition().duration(350).tween("zoom", function () {
		var iScale = d3.interpolate(zoom.scale(), scale);
		return function (t) {
		zoom
			.scale(iScale(t))
		zoomed(translate);
		};
	});
}
