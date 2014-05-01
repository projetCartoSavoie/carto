function D3_Utils(){}

/**
* Met dans une iframe la page url du nom passe en parametre
* @param name : name qu'on veut chercher sur wikipedia
*/
D3_Utils.prototype.show_wikipedia = function(name) {

	var s = name.replace(/\./g,"").replace(" ","_").replace("-","_");
	// On construit l'url avec le name selectionne par l'utilisateur
	var url = "http://en.m.wikipedia.org/wiki/"+s;
	// On ajoute des balises a la div qui a l'identifiant wikipedia
	$('#wikipedia').html(
			"<p><b>Informations on "+name+"</b> "+
			"<iframe id='wikiframe' src='"+url+"' "+
			"width='100%' frameborder='0'></iframe>"
			);
	var size = Math.round( $('#wikiframe').position().top
			- $('#wikipedia').position().top );
	size = $('#wikipedia').height() - size - 10;
	$('#wikiframe').css('height', size+'px');
}

/**
* Charge un nouveau json en fonction du nom 
* @param d : objet node sur lequel l'utilisateur a clique
*/
D3_Utils.prototype.load_json = function(d) {
	var wordnet = $('#WN').attr('checked'); //Rration de la source de donne
	//Url permettant de faire la recherche demandpend de la source)
	if (wordnet)
	{
		//var url = "http://localhost/bundles/CartoRepresentationsBundle/action/main_action.php"; // remy
		var url = "http://carto.localhost/bundles/CartoRepresentationsBundle/action/main_action.php"; // Celine
		//var url = "http://localhost/CartoSavoie/carto/web/bundles/CartoRepresentationsBundle/action/main_action.php"; // Juliana
		//var url = "http://localhost/Projet%20-%20Visualisation%20de%20donnees/carto/web/bundles/CartoRepresentationsBundle/action/main_action.php"; //Anthony
		//var url = "http://carto.dev/bundles/CartoRepresentationsBundle/action/main_action.php"; //Anthony2
	}
	else
	{
		//var url = "http://localhost/bundles/CartoRepresentationsBundle/action/main_action_dbpedia.php"; // remy
		var url = "http://carto.localhost/bundles/CartoRepresentationsBundle/action/main_action_dbpedia.php"; // Celine
		//var url = "http://localhost/CartoSavoie/carto/web/bundles/CartoRepresentationsBundle/action/main_action_dbpedia.php"; // Juliana
		//var url = "http://localhost/Projet%20-%20Visualisation%20de%20donnees/carto/web/bundles/CartoRepresentationsBundle/action/main_action_dbpedia.php"; //Anthony
		//var url = "http://carto.dev/bundles/CartoRepresentationsBundle/action/main_action_dbpedia.php"; //Anthony2
	}
	$("#contentCenter").html('<img id="loading" src="/bundles/CartoRepresentationsBundle/images/ajax-loader.gif>');
	
	// On fait une requete ajax afin de ne pas recharger la page
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
			// On verifie si la reponse a ete faite avec succes
			if(result.success){
				var data = result.data;
				// On regarde si un graphe est deja dessine
				if(representation){
					// S'il y a deja une representation on l'enleve
					$('svg').remove();
					// On enleve le combobox pour les relations dans la barre d'outils a gauche
					$('.relation').remove();
					d3.select('.dragAndDrop')
						.style("background-color", "#d0cbcb")
						.attr("value", "0");
				}
				representation.show(data);
				$("#loading").hide();
			}
		}
	});
	return false;
}

function move(d) {
	// d est un objet compose des coordonnees x et y
	// Pour deplacer l'element on regarde son ancienne position et on ajoute
	// les nouvelles cad la ou l'utilisateur a clique
	d.x += d3.event.dx;
	d.y += d3.event.dy;
	var actual = Number($("#rotate").attr('value'));
	// On recupere le scale si on a deja zoome sur la representation
	var sc = Number($("#representationContainer").attr('sc'));
	d3.select('.representationContainer')
		.attr("transform", "translate(" + d.x + "," + d.y + ")scale(" + sc + ")rotate(" + actual + ")")
		.attr("tx",d.x).attr("ty",d.y);
}

D3_Utils.prototype.rotate = function() {
	var actual = Number($("#rotate").attr('value')) + 20;
	var components = d3.transform($("#representationContainer").attr("transform"));
	t = components.translate;
	var tx = Number($("#representationContainer").attr('tx'));
	var ty = Number($("#representationContainer").attr('ty'));
	var sc = Number($("#representationContainer").attr('sc'));
	console.log(tx + " , " + ty);
	d3.select('.rotate').attr("value", actual);
	var container = d3.select(".svgContainer");
	// Va recuperer les donnees de l'element se trouvant dans le svg
	// et appeler la fonction move avec comme parametre les coordonnees
	d3.select('.representationContainer').attr("transform", "translate(" + tx + "," + ty + ")scale(" + sc + ")rotate(" + actual + ")");
}

/**
* Prepare le svg pour que l'utilisateur puisse faire un drag and drop
*/
D3_Utils.prototype.dragAndDrop = function(force) {
	// On regarde si on a deja clique sur le bouton dragNdrog
	if($("#drag_and_drop").attr('value') === "1"){
		// Si c'est la deuxieme fois on enleve le mode dragNdrop
		stopDragAndDrop(force);
	}
	else{
		// Sinon on avertit l'utilisateur qu'il a clique sur le bouton
		d3.select('.dragAndDrop')
			.style("background-color", "#36A9C7")
			.attr("value", "1");
		// Et on applique le mode dragNdrop
		var container = d3.select(".svgContainer")
			.attr("cursor", "move");
			
		// Va recuperer les donnees de l'element se trouvant dans le svg
		// et appeler la fonction move avec comme parametre les coordonnees
		container.call(d3.behavior.drag().on("drag", move));
	}
}

/**
* Prepare le svg pour que l'utilisateur arrete de faire un drag and drop
*/
function stopDragAndDrop(force) {
	if($("#drag_and_drop").attr('value') === "1"){
		// On change la couleur du bouton
		d3.select('.dragAndDrop')
		.style("background-color", "#d0cbcb")
		.attr("value", "0");
		
		// On enleve  le drag and drop
		var svg = d3.select(".svgContainer");
		svg.call(d3.behavior.drag().on("drag", null));
		//svg.call(force.drag);
		var node_drag = d3.behavior.drag()
			.on("drag", force.drag);
		svg.select(".representationContainer").selectAll("g.node").call(node_drag);
		$(".svgContainer").removeAttr('cursor');
	}
	else{
		this.dragAndDrop();
	}
}

// Quand on clique sur une relation on affiche
// les liens en couleur
function changeTree(links, nameRelation, colorLink){
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
}

// Quand on clique sur une relation on affiche
// les liens en couleur
function changeGraph(links, nameRelation, colorLink){
	// On redessine les liens en couleur de base
	d3.selectAll("line")
			.style("stroke-width", function(d) { return Math.sqrt(d.value); })
			.style("stroke", "#999");
	// Pour tous les liens du graphe
	links.forEach(
		function(d){
			// Si le lien a la relation selectionnee alors on met en couleur
			for(var i=0; i < d.name.length; i++){
				if(d.name[i].localeCompare(nameRelation) == 0){
					d3.selectAll('.' + d.name[i])
						.style("stroke-width", 3)
						.style("stroke",  colorLink(d.value));
				}
			}
		}
	);
}

/**
* Affiche les relations en couleur pour une representation en arbre ou en graphe
* @param json : json transforme pour recuperer le nom des relations
* @param representation : pour savoir si la representation est un arbre ou un graphe
*/
D3_Utils.prototype.showRelation = function(json, representation) {
	var colorLink = d3.scale.category20();
	// On recupere les relations utilisees pour ce json
	var data = json.relationsUsed;
	var paragraphs = d3.select('.selectRelation')
		.on("change",function() {
				// On recupere ce que l'utilisateur a choisi
				nameRelation = this.options[this.selectedIndex].value;
				if(representation === "tree"){
					changeTree(json.links, nameRelation, colorLink);
				}
				else{
					changeGraph(json.links, nameRelation, colorLink);
				}
			}
		)
		.selectAll(".relation")
			.data(data)
				.enter()
				.append("option")
				.attr("class", "relation");

	// On configure le texte
	paragraphs
		.attr("value", function (d) { return d;})
		.text(function (d) { return d; });
}

/**
* Zoom la representation
*/
D3_Utils.prototype.zoomClick = function() {
	var margin = {top: 30, right: 20, bottom: 30, left: 20};

	var clicked = d3.event.target,
		direction = 1,
		factor = 0.2,
		target_zoom = 1,
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

	interpolateZoom(scale);
}

function zoomed() {
	var container = d3.select(".representationContainer");
	var tx = Number($("#representationContainer").attr('tx'));
	var ty = Number($("#representationContainer").attr('ty'));
	var sc = zoom.scale();
	var actual = Number($("#rotate").attr('value'));
	container.attr("transform",
		"translate(" + tx + "," + ty + ")"  +
		"scale(" + sc + ")" +
		"rotate(" + actual + ")"
	);
	container.attr("sc",sc);
}

function interpolateZoom (scale) {
	var self = this;
	return d3.transition().duration(350).tween("zoom", function () {
		var iScale = d3.interpolate(zoom.scale(), scale);
		return function (t) {
		zoom
			.scale(iScale(t))
		zoomed();
		};
	});
}