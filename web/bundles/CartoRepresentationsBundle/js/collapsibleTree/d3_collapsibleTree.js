function D3_TreeRepresentation(){}

D3_TreeRepresentation.prototype.show = function(data) {

	// data is file path
	if(typeof data === "string"){
		d3.json(data, function(error, root) {
			if (error) alert(error);
			D3_TreeRepresentation.load(root);
		});
	}
	// data is json
	else {
		D3_TreeRepresentation.load(data);
	}
}

D3_TreeRepresentation.load = function(json) {
		
	/***************************/
	/*		Graphe	 		   */
	/**************************/
	var widthContentCenter = $("#contentCenter").width(),
    heightContentCenter = $("#contentCenter").height();

	var margin = {top: 30, right: 20, bottom: 30, left: 20},
		width = widthContentCenter - margin.left - margin.right,
		height = heightContentCenter - margin.top - margin.bottom,
		barHeight = 20,
		barWidth = width * .8;

	var i = 0,
		duration = 400,
		root;

	var tree = d3.layout.tree()
		.size([0, 100]);

	var diagonal = d3.svg.diagonal()
		.projection(function(d) { return [d.y, d.x]; });

	var svg = d3.select("#contentCenter").append("svg")
		//.attr("height", heightContentCenter)
		.attr("width", widthContentCenter);
		
	var container = svg.append("g")
		.attr("class", "representationContainer")
		.attr("transform", "translate(" + margin.left + "," + margin.top + ")");
		
	/***************************************************/
	/*		Transformation du json generique 		   */
	/***************************************************/

	// On transforme le fichier generique json au bon format pour la representation concernee
	var formatter = new D3_Formatter();
	var treeJson = formatter.to_tree(json);
	treeJson.x0 = 0;
	treeJson.y0 = 0;
	update(root = treeJson);
	
	/***************************/
	/*		Update	 		   */
	/**************************/

function update(source) {

	/***************************/
	/*		Relations 		   */
	/**************************/
	
	var data = treeJson.relationsUsed;
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

	var colorLink = d3.scale.category20();

	// Compute the flattened node list. TODO use d3.layout.hierarchy.
	var nodes = tree.nodes(root);
	
	var height = Math.max(500, nodes.length * barHeight + margin.top + margin.bottom);

	d3.select("svg")
		.attr("height", height);

	d3.select(self.frameElement)
		.style("height", height + "px");

	// Compute the "layout".
	nodes.forEach(function(n, i) {
	n.x = i * barHeight;
	});

	// Update the nodes
	var node = container.selectAll("g.node")
	  .data(nodes, function(d) { return d.id || (d.id = ++i); });

	var nodeEnter = node.enter().append("g")
	  .attr("class", "node")
	  .attr("transform", function(d) { return "translate(" + source.y0 + "," + source.x0 + ")"; })
	  .style("opacity", 1e-6);

	// Enter any new nodes at the parent's previous position.
	nodeEnter.append("rect")
	  .attr("y", -barHeight / 2)
	  .attr("height", barHeight)
	  .attr("width", barWidth)
	  .style("fill", color)
	  .on("click", click);
		  
	// A chaque noeud on affiche son nom
	nodeEnter.append("text")
		.attr("dy", 3.5)
		.attr("dx", 5.5)
		.style("stroke", "black")
		.text(function(d) { 
			var sansEspace = new RegExp(/\s/); 
			if(sansEspace.test(d.name.toString()) == false) return d.name; 
		})
		.attr("cursor","pointer")
		.on("click", function(d) {
			var d3_utils = new D3_Utils();
			d3_utils.show_wikipedia(d.name);
		})
		.on("dblclick", function(d){
			var url = "http://localhost/CartoSavoie/carto/web/bundles/CartoRepresentationsBundle/action/main_action.php"; // Juliana
			//var url = "http://carto.dev/bundles/CartoRepresentationsBundle/action/main_action.php"; //Anthony
			//var url = "http://carto.localhost/bundles/CartoRepresentationsBundle/action/main_action.php"; //Céline
			$("#contentCenter").html('<img id="loading" src="/bundles/CartoRepresentationsBundle/images/ajax-loader.gif">');
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
						}
						representation.show(data);
						$("#loading").hide();
					}
				}
			});
			return false;
		});
		  
	// On affiche un titre lorsqu'on passe la souris
	node.append("title")
		.text(function(d) { return d.name; });

	// Transition nodes to their new position.
	nodeEnter.transition()
	  .duration(duration)
	  .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; })
	  .style("opacity", 1);

	node.transition()
	  .duration(duration)
	  .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; })
	  .style("opacity", 1)
	.select("rect")
	  .style("fill", color);

	// Transition exiting nodes to the parent's new position.
	node.exit().transition()
	  .duration(duration)
	  .attr("transform", function(d) { return "translate(" + source.y + "," + source.x + ")"; })
	  .style("opacity", 1e-6)
	  .remove();

	// On recupere tous les liens du json
	var links = getLinks(nodes);
	// Update the links
	var link = container.selectAll("path.link")
	  .data(links, function(d) { return d.target.id; });

	// Enter any new links at the parent's previous position.
	link.enter().insert("path", "g")
		.attr("class", "link")
		.attr("id", function(d) { return d.name; })
		.style("stroke-width", function(d) { return Math.sqrt(d.value); })
		.style("stroke", "#999")
		.attr("d", function(d) {
			var o = {x: source.x0, y: source.y0};
			return diagonal({source: o, target: o});
		})
		.transition()
		.duration(duration)
		.attr("d", diagonal);

	// Transition links to their new position.
	link.transition()
		.duration(duration)
		.attr("d", diagonal);

	// Transition exiting nodes to the parent's new position.
	link.exit().transition()
		.duration(duration)
		.attr("d", function(d) {
			var o = {x: source.x, y: source.y};
			return diagonal({source: o, target: o});
		})
		.remove();
	  
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

	// Stash the old positions for transition.
	nodes.forEach(function(d) {
	d.x0 = d.x;
	d.y0 = d.y;
	});
	}

	// Toggle children on click.
	function click(d) {
	  if (d.children) {
		d._children = d.children;
		d.children = null;
	  } else {
		d.children = d._children;
		d._children = null;
	  }
	  update(d);
	}

	function color(d) {
		var colorLink = d3.scale.category20();
		return d._children ? /*"#3182bd"*/colorLink(d.group) : d.children ? "#c6dbef" : "#fd8d3c";
	}
	function getLinks(nodes){
		var d3_links = tree.links(nodes);
		var allLinks = treeJson.links;
		var newLinks = new Array();
		for(var i = 0; i < allLinks.length; i++) {
			for(var j = 0; j < d3_links.length; j++) {
				if (allLinks[i].source.uid == d3_links[j].source.uid && allLinks[i].target.uid == d3_links[j].target.uid) {
					newLinks.push(allLinks[i]);
				}
			}
		}
		return newLinks;
	}
	
	d3.selectAll('.zoom').on('click', zoomClick);
}

function zoomClick() {
	
	var margin = {top: 30, right: 20, bottom: 30, left: 20};

	var clicked = d3.event.target,
		direction = 1,
		factor = 0.2,
		target_zoom = 1,
		center = [margin.left, margin.top],
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

