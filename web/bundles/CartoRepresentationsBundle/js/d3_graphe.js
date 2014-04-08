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

	zoom = d3.behavior.zoom()
			.scaleExtent([1, 10])
			.on("zoom", zoomed);


	var width = $("#contentCenter").width(),
    height = $("#contentCenter").height();

	var color = d3.scale.category20();

	var force = d3.layout.force()
		.charge(-400)
		.linkDistance(20)
		.size([width, height]);

	var svg = d3.select("#contentCenter").append("svg")
		.attr("width", width)
		.attr("height", height);
	
	var container = svg.append("g")
		.attr("class", "representationContainer")

	var formatter = new D3_Formatter();
	var graph = formatter.to_graph(json);
		
	force
		.nodes(graph.nodes)
		.links(graph.links)
		.start();
		
	/* Define the data for the circles */

	var link = container.selectAll(".link")
		.data(graph.links)
		.enter()
			.append("line")
			.attr("class", "link")
			.style("stroke-width", function(d) { return Math.sqrt(d.value); });
			
	var node = container.selectAll(".node")
		.data(graph.nodes)
		.enter()
			.append("g")
			.attr("class", "node")
			.call(force.drag);
	
	node.append("circle")
		.attr("r", 5)
		.style("fill", function(d) { return color(d.group); });
		
		
	// On affiche les mots associe aux noeuds
	node.append("text")
		.attr("x", 12)
		.attr("dy", ".35em")
		.style("stroke", "black")
		.text(function(d) { 
			var sansEspace = new RegExp(/\s/); 
			if(sansEspace.test(d.name.toString()) == false) return d.name; 
		})
		.attr("cursor","pointer")
		.on("click", function(d) {
			var d3_utils = new D3_Utils();
			d3_utils.show_wikipedia(d.name);
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