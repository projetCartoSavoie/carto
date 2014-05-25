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
	var colorLink = d3.scale.category20();
	d3_utils.showRelation(json, "tree", colorLink);
		
	/***************************/
	/*		Graphe	 		   */
	/**************************/
	
	//Zoom sert aux fonctions de zoom communes à toutes les représentations
	//variable globale car utilisée dans d3_utils ... 
	zoom = d3.behavior.zoom()
			.scaleExtent([1, 10])
			.on("zoom", zoomed);
	
	// On recupere la taille de la div pour mettre le svg
	var width = $("#contentCenter").width(),
    height = $("#contentCenter").height();
	
	var diameter = width;
	
	var color = d3.scale.category20();
		
	//Le layout de D3 permet d'agencer sous forme d'arbre
	var tree = d3.layout.tree()
		.size([360, diameter / 2 - 150])
		.separation(function(a, b) { return (a.parent == b.parent ? 1 : 2) / a.depth; });

	var diagonal = d3.svg.diagonal.radial()
		.projection(function(d) { return [d.y, d.x / 180 * Math.PI]; });

	var svg = d3.select("#contentCenter").append("svg")
		.attr("width", diameter)
		.attr("height", diameter - 150)
		.attr("class", "svgContainer");
				
	// On specifie une origine
	var d = [{ x: diameter/2, y: diameter/2 }];
	// On cree un nouveau noeud <g> pour mettre plusieurs attributs
	var container = d3.select('.svgContainer')
		.data(d)
		.append("g")
		.attr("class", "representationContainer")
		.attr("id","representationContainer")
		.attr("transform", "translate(" + diameter / 2 + "," + diameter / 2 + ")")
		.attr("tx", diameter / 2)
		.attr("ty", diameter / 2)
		.attr("sc", 1);
		
	d3.select(self.frameElement).style("height", diameter - 150 + "px");
	
	// On recupere les noeuds du json grace a la fonction de d3
	var nodes = tree.nodes(json),
		links = json.links;
			
	// On cree les liens de la representation
	var link = container.selectAll(".link")
		.data(links)
		.enter()
			.append("path")
			.attr("class", "link")
			.attr("id", function(d) { return d.name; })
			.style("stroke-width", function(d) { return Math.sqrt(d.value); })
			.style("stroke", "#999")
			.attr("d", diagonal);

	// On cree les noeuds de la representation
	var node = container.selectAll(".node")
		.data(nodes)
		.enter()
			.append("g")
			.attr("class", "node")
			.attr("transform", function(d) { return "rotate(" + (d.x - 90) + ")translate(" + d.y + ")"; });

	// Les noeuds sont representes par des cercles
	node.append("circle")
		.attr("class", "circle")
		.attr("r", 5)
		.style("fill", function(d) { return color(d.group); });

	// On ajoute du texte aux noeuds
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
			//alert("coucou0"); 
			d3_utils.show_wikipedia(d.name);
		})
		.on("dblclick", function(d) {
			d3_utils.load_json(d);
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

	d3.selectAll('.zoom').on('click', d3_utils.zoomClick);
	
	// Si on clique sur le bouton ayant la classe
	// dragAndDrop on appelle la fonction dragAndDrop
	d3.selectAll('.dragAndDrop')
		.attr("value", "0")
		.on('click', d3_utils.dragAndDrop);

	// Si on clique sur le bouton ayant la classe
	// rotate on appelle la fonction Rotate
	d3.selectAll('.rotate').attr("value","0")
		.on('click', d3_utils.rotate);
}
