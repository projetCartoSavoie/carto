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

	var formatter = new D3_Formatter();
	var graph = formatter.to_graph(json);

	/***************************/
	/*		Relations 		   */
	/**************************/

	var widthRelation = $("#relations").width();

	// On met toutes les relations sur le contentLeft
	// en selectionnant la classe relations
	var data = json.relationsUsed;
	
	// On cree une nouvelle balise div dans la partie gauche
	var svgRelation = d3.select("#relations").append("div")
		.attr("width", widthRelation)
		.attr("class", "relations");
		
	var paragraphs = svgRelation.selectAll(".relations")
        .data(data)
			.enter()
			.append("p");

	// On configure le texte
	paragraphs.append("text")
		.style("color", "black")
		.text(function (d) { return d; });
		
		
	/***************************/
	/*		Graphe	 		   */
	/**************************/

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

	// On cree un nouveau noeud <svg>
	var svg = d3.select("#contentCenter").append("svg")
		.attr("width", width)
		.attr("height", height);
		
	// On cree un nouveau noeud <g>
	var container = svg.append("g")
		.attr("class", "representationContainer")
			
	force
		.nodes(graph.nodes)
		.links(graph.links)
		.start();
	
	/* Define the data for the circles */
	// Pour tous les éléments .link on crée un noeud <line>
	var link = container.selectAll(".link")
		.data(graph.links)
		.enter()
			.append("line")
			.attr("class", "link")
			.attr("class", function(d) { return d.name; })
			.style("stroke-width", function(d) { return Math.sqrt(d.value); })
			.style("stroke", "#999");
		
	var linkColor;
	// Quand on clique sur une relation on affiche
	// les liens en couleur
	paragraphs
		.on("click", function(nameRelation){
			var linkColor = [];
			//d3.select('.' + 
			// Pour tous les liens du graphe
			graph.links.forEach(
				function(d){
					// On redessine les liens en couleur de base
					d3.selectAll('.' + d.name)
							.style("stroke-width", function(d) { return Math.sqrt(d.value); })
							.style("stroke", "#999");
					// Si le lien a la relation selectionnee alors on met en couleur
					if(d.name.localeCompare(nameRelation) == 0){
						linkColor.push(d);
						d3.selectAll('.' + d.name)
							.style("stroke-width", 3)
							.style("stroke",  "red");
					}
				}
			);
		});
		
	// Pour tous les éléments .node on crée un noeud <g>
	var node = container.selectAll(".node")
		.data(graph.nodes)
		.enter()
			.append("g")
			.attr("class", "node")
			.call(force.drag);
			
	// A chaque node <g> on crée un noeud <circle>
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
		/*.on("click", function(d) {
			var d3_utils = new D3_Utils();
			d3_utils.show_wikipedia(d.name);
		});*/
		.on("click", function(d){
			var url = "http://localhost/CartoSavoie/carto/web/bundles/CartoRepresentationsBundle/action/main_action.php"; // Juliana
			//var url = "http://carto.localhost/bundles/CartoRepresentationsBundle/action/main_action.php"; // CÃ©line
			$.ajax({
				type: "POST",
				url: url,
				data: {
					cmd: 'search_action',
					search: d.name
				},
				cache: false,
				success: function(response) {
					var result = $.parseJSON(response);
					if(result.success){
						var data = result.data;
						if(representation){
							$('svg').remove();
							$('.relations').remove();
						}
						representation.show(data);
					}
				}
			});
			return false;
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

	d3.selectAll('.zoom').on('click', zoomClick);
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
	direction = (this.id === 'zoom_in') ? 1 : -1;
	target_zoom = zoom.scale() * (1 + factor * direction);

	if (target_zoom < extent[0] || target_zoom > extent[1]) { return false; }

	translate0 = [(center[0] - view.x) / view.k, (center[1] - view.y) / view.k];
	view.k = target_zoom;
	l = [translate0[0] * view.k + view.x, translate0[1] * view.k + view.y];

	view.x += center[0] - l[0];
	view.y += center[1] - l[1];

	interpolateZoom([view.x, view.y], view.k);
}

function zoomed() {
	var container = d3.select(".representationContainer");
	container.attr("transform",
		"translate(" + zoom.translate() + ")" +
		"scale(" + zoom.scale() + ")"
	);
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
