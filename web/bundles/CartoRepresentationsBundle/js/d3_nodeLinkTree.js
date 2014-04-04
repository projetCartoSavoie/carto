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
	var diameter = 960;

	var tree = d3.layout.tree()
		.size([360, diameter / 2 - 120])
		.separation(function(a, b) { return (a.parent == b.parent ? 1 : 2) / a.depth; });

	var diagonal = d3.svg.diagonal.radial()
		.projection(function(d) { return [d.y, d.x / 180 * Math.PI]; });

	var svg = d3.select("#contentCenter").append("svg")
		.attr("width", diameter)
		.attr("height", diameter - 150)
		.attr("transform", "translate(" + diameter / 2 + "," + diameter / 2 + ")")
		.call(d3.behavior.zoom().scaleExtent([1, 8]).on("zoom", zoom))
		.append("g");

	d3.select(self.frameElement).style("height", diameter - 150 + "px");
	
	// On transforme le fichier generique json au bon format pour la representation concernee
	var formatter = new D3_Formatter();
	var json = formatter.to_tree(json);
	
	var nodes = tree.nodes(json),
		links = tree.links(nodes);

	var link = svg.selectAll(".link")
		.data(links)
		.enter().append("path")
		.attr("class", "link")
		.attr("d", diagonal);

	var node = svg.selectAll(".node")
		.data(nodes)
		.enter()
			.append("g")
			.attr("class", "node")
			.attr("transform", function(d) { return "rotate(" + (d.x - 90) + ")translate(" + d.y + ")"; });

	node.append("circle")
		.attr("r", 4.5);

	node.append("text")
		.attr("dy", ".31em")
		.attr("text-anchor", function(d) { return d.x < 180 ? "start" : "end"; })
		.attr("transform", function(d) { return d.x < 180 ? "translate(8)" : "rotate(180)translate(-8)"; })
		.style("stroke", "black")
		.text(function(d) { return d.name; })
		.attr("cursor","pointer")
		.on("click", function(d) {
				var d3_utils = new D3_Utils();
				d3_utils.show_wikipedia(d.name);
			});
			
	function zoomClick() {
		svg.call(d3.behavior.zoom().scaleExtent([1, 8]).on("zoom", zoom));
		svg.append("g");
	}
	
	function zoom(){
		svg.attr("transform", "translate(" + d3.event.translate + ")scale(" + d3.event.scale + ")");
	}

	d3.selectAll('#zoomIn').on('click', zoomClick);
}