function D3_GrapheRepresentation(){}

D3_GrapheRepresentation.prototype.show = function(fichier) {
	var width = 960,
    height = 500;

	var color = d3.scale.category20();

	var force = d3.layout.force()
		.charge(-120)
		.linkDistance(100)
		.size([width, height]);

	var svg = d3.select("#contentCenter").append("svg")
		.attr("width", width)
		.attr("height", height);
		
	d3.json(fichier, function(error, json) {

		if (error) alert(error);

		var formatter = new D3_Formatter();
		var graph = formatter.to_graph(json);
			
		force
			.nodes(graph.nodes)
			.links(graph.links)
			.start();
			
		/* Define the data for the circles */

		var link = svg.selectAll(".link")
			.data(graph.links)
			.enter()
				.append("line")
				.attr("class", "link")
				.style("stroke-width", function(d) { return Math.sqrt(d.value); });
				
		var node = svg.selectAll(".node")
			.data(graph.nodes)
			.enter()
				.append("g")
				.attr("class", "node")
				.call(force.drag)
				.attr("cursor","pointer")
				.on("click", function(d) {
					var d3_utils = new D3_Utils();
					d3_utils.show_wikipedia(d.name);
				});
		
		node.append("circle")
			.attr("r", 5)
			.style("fill", function(d) { return color(d.group); });
			
		node.append("text")
			.attr("x", 12)
			.attr("dy", ".35em")
			.style("stroke", "black")
			.text(function(d) { return d.name; });
			
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
	});
}