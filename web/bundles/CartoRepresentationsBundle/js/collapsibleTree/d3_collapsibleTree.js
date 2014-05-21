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
	
	var color = d3.scale.category20();
	
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
	var colorLink = d3.scale.category20();
	d3_utils.showRelation(treeJson, "tree", colorLink);
	
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
		  .style("fill", function(d) { return color(d.group); })
		  .on("click", click);
			  
		// A chaque noeud on affiche son nom
		nodeEnter.append("text")
			.attr("dy", 3.5)
			.attr("dx", 5.5)
			.attr("id", function(d){
					// On met un id sans espace
					var nameWithoutSpace = d.name.replace(' ', '');
					return nameWithoutSpace; 
				})
			.text(function(d) { 
				// On met du texte seulement si c'est un mot sinon on se limite à 17 caracteres
				var sansEspace = new RegExp(/\s/);
				var name = "";
				if(d.children != null){
					name += "- ";
				}
				if(sansEspace.test(d.name.toString()) == false){
					name += d.name;
				}else{
					if (d.name.length > 20){
						name += d.name.substring(0,17) + '...';
					}else{
						name += d.name.substring(0,17);
					}
				}
				return name;
			})
			.attr("cursor","pointer")
			.on("click", function(d) {
				// On va chercher sur wikipedia seulement si on clique sur un mot et pas une phrase
				var sansEspace = new RegExp(/\s/); 
				if(sansEspace.test(d.name.toString()) == false){
					d3_utils.show_wikipedia(d.name);
				}
			})
			.on("dblclick", function(d) {
				d3_utils.load_json(d);
			});

		// Nouvelle position du noeud si on clique dessus
		node.transition()
		  .duration(duration)
		  .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; })
		  .style("opacity", 1)
		.select("rect")
		  .style("fill", function(d) { return color(d.group); });

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
			.style("stroke-width", function(d) {
				// On recupere ce que l'utilisateur a choisi
				nameRelation = $(".selectRelation option:selected").val();
				if(d.name === nameRelation){
					return 3;
				}
				else{
					return Math.sqrt(d.value); 
				}
			})
			.style("stroke", function(d) {
				// On recupere ce que l'utilisateur a choisi
				nameRelation = $(".selectRelation option:selected").val();
				if(d.name === nameRelation){
					return colorLink(d.value);
				}
				else{
					return "#999"; 
				}
			})
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
			
			
		// On ajoute des etiquettes sur les noeuds
		$('svg g .node').tipsy({ 
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
	}

	// Lorsqu'on clique sur un noeud
	function click(d) {
		// On recupere l'id egal au nom du noeud sans espace
		var nameWithoutSpace = d.name.replace(' ', '');
		
		// On recupere ce qu'il y a dans la balise text
		var text = document.getElementById(nameWithoutSpace).innerHTML;
		if (d.children) {
			d._children = d.children;
			d.children = null;
			
			// On cache les enfants on met un + pour montrer qu'on peut deployer
			var result = text.replace("- ", "+ ");
			document.getElementById(nameWithoutSpace).innerHTML = result;
		} else {
			d.children = d._children;
			d._children = null;
			
			// On deploie les enfants on met un - pour montrer qu'on peut cacher
			var result = text.replace("+ ", "- ");
			document.getElementById(nameWithoutSpace).innerHTML = result;
		}
		update(d);
	}

	function color(d) {
		var colorLink = d3.scale.category20();
		return d._children ? colorLink(d.group) : d.children ? "#c6dbef" : "#fd8d3c";
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

