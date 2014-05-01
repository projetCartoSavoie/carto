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
	
	// On recupere la taille de la div pour mettre le svg
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

	//Le layout de D3 permet d'agencer sous forme d'arbre
	var tree = d3.layout.tree()
		.size([0, 100]);

	var diagonal = d3.svg.diagonal()
		.projection(function(d) { return [d.y, d.x]; });
		
	// On cree un nouveau noeud <svg>
	//On configure le svg qui contiendra toute la figure
	var svg = d3.select("#contentCenter").append("svg")
		.attr("width", widthContentCenter)
		.attr("class", "svgContainer");
		
	// On specifie une origine
	var d = [{ x: 20, y: 30 }];
	// On cree un nouveau noeud <g> pour mettre plusieurs attributs
	var container = d3.select('.svgContainer')
		.data(d)
		.append("g")
		.attr("class", "representationContainer")
		.attr("id","representationContainer")
		.attr("transform", "translate(" + margin.left + "," + margin.top + ")")
		.attr("tx",  20)
		.attr("ty", 30)
		.attr("sc", 1);
		
	/***************************************************/
	/*		Transformation du json generique 		   */
	/***************************************************/

	// On transforme le fichier generique json au bon format pour la representation concernee
	var formatter = new D3_Formatter();
	var treeJson = formatter.to_tree(json);
	treeJson.x0 = 0;
	treeJson.y0 = 0;
	
	/***************************************************/
	/*					Outils						   */
	/***************************************************/
	var d3_utils = new D3_Utils();
	
	/***************************/
	/*		Relations 		   */
	/**************************/
	
	d3_utils.showRelation(treeJson, "tree");
	
	/***************************/
	/*		Update	 		   */
	/**************************/
	update(root = treeJson);

function update(source) {
		
	/***************************/
	/*		Graphe	 		   */
	/**************************/
	
	zoom = d3.behavior.zoom()
			.scaleExtent([1, 10])
			.on("zoom", zoomed);

	var colorLink = d3.scale.category20();

	// On recupere les noeuds du json grace a la fonction de d3
	var nodes = tree.nodes(root);
	
	var height = Math.max(500, nodes.length * barHeight + margin.top + margin.bottom);

	d3.select("svg")
		.attr("height", height);

	d3.select(self.frameElement)
		.style("height", height + "px");

	nodes.forEach(function(n, i) {
	n.x = i * barHeight;
	});

	// On recupere les noeuds de nodes
	var node = container.selectAll("g.node")
	  .data(nodes, function(d) { return d.id || (d.id = ++i); });

	// On cree les noeuds de la representation
	var nodeEnter = node.enter().append("g")
	  .attr("class", "node")
	  .attr("transform", function(d) { return "translate(" + source.y0 + "," + source.x0 + ")"; })
	  .style("opacity", 1e-6);

	// Les noeuds sont representes par des rectangles
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
			d3_utils.show_wikipedia(d.name);
		})
		.on("dblclick", function(d) {
			d3_utils.load_json(d);
		});
		  
	// On affiche un titre lorsqu'on passe la souris
	node.append("title")
		.text(function(d) { return d.name; });

	// Nouvelle position du noeud si on clique dessus
	node.transition()
	  .duration(duration)
	  .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; })
	  .style("opacity", 1)
	.select("rect")
	  .style("fill", color);

	// On enleve les noeuds si le parent a ete clique
	// On les place a la meme position que le parent
	node.exit().transition()
	  .duration(duration)
	  .attr("transform", function(d) { return "translate(" + source.y + "," + source.x + ")"; })
	  .style("opacity", 1e-6)
	  .remove();

	// On recupere tous les liens du json
	var links = getLinks(nodes);
	// On met a jour les liens
	var link = container.selectAll("path.link")
	  .data(links, function(d) { return d.target.id; });

	// On ajoute les liens
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

	// On met les liens a leur nouvelle position
	link.transition()
		.duration(duration)
		.attr("d", diagonal);

	// On place les liens a ne pas afficher a la position du parent
	link.exit().transition()
		.duration(duration)
		.attr("d", function(d) {
			var o = {x: source.x, y: source.y};
			return diagonal({source: o, target: o});
		})
		.remove();

	// Pour tous les noeuds on affecte la nouvelle position
	nodes.forEach(function(d) {
	d.x0 = d.x;
	d.y0 = d.y;
	});
	}

	// Lorsqu'on clique sur un noeud
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
	
	// On recupere le liens du json mis a jour a chaque fois 
	// qu'on clique sur un noeud pour avoir le nom de la relation
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
	
	d3.selectAll('.zoom').on('click', d3_utils.zoomClick);
	
	// Si on clique sur le bouton ayant la classe
	// dragAndDrop on appelle la fonction dragAndDrop
	d3.selectAll('.dragAndDrop')
		.attr("value", "0")
		.on('click', d3_utils.dragAndDrop);
}
