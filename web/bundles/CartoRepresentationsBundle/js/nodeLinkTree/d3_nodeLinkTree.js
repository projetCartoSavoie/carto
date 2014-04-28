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
	
	d3_utils.showRelation(json, "tree");
		
	/***************************/
	/*		Graphe	 		   */
	/**************************/
	
	//Zoom sert aux fonctions de zoom communes à toutes les représentations
	zoom = d3.behavior.zoom()
			.scaleExtent([1, 10])
			.on("zoom", zoomed);
	
	// On recupere la taille de la div pour mettre le svg
	var width = $("#contentCenter").width(),
    height = $("#contentCenter").height();
	
	var diameter = width;
	
	var color = d3.scale.category20();
	var colorLink = d3.scale.category20();

	//Le layout de D3 permet d'agencer sous forme d'arbre
	var tree = d3.layout.tree()
		.size([360, diameter / 2 - 200])
		.separation(function(a, b) { return (a.parent == b.parent ? 1 : 2) / a.depth; });

	var diagonal = d3.svg.diagonal.radial()
		.projection(function(d) { return [d.y, d.x / 180 * Math.PI]; });
		
	// On cree un nouveau noeud <svg>
	//On configure le svg qui contiendra toute la figure
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
		.attr("r", 5)
		.style("fill", function(d) { return color(d.group); });
		
	// On affiche un titre lorsqu'on passe la souris
	node.append("title")
		.text(function(d) { return d.name; });

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
			d3_utils.show_wikipedia(d.name);
		})
<<<<<<< HEAD:web/bundles/CartoRepresentationsBundle/js/d3_nodeLinkTree.js
		.on("dblclick", function(d){
			//var url = "http://localhost/CartoSavoie/carto/web/bundles/CartoRepresentationsBundle/action/main_action.php"; // Juliana
			var url = "http://carto.dev/bundles/CartoRepresentationsBundle/action/main_action.php"; //Anthony
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
							$('.relation').remove();
						}
						representation.show(data);
						$("#loading").hide();
					}
				}
			});
			return false;
=======
		.on("dblclick", function(d) {
			d3_utils.load_json(d);
>>>>>>> 1c13079a6d351aac414f198846c180a80051ade6:web/bundles/CartoRepresentationsBundle/js/nodeLinkTree/d3_nodeLinkTree.js
		});

	d3.selectAll('.zoom').on('click', zoomClick);
	
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
		var iScale = d3.interpolate(zoom.scale(), scale);
		return function (t) {
		zoom
			.scale(iScale(t))
		zoomed(translate);
		};
	});
}
