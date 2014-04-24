function D3_Utils(){}

/**
 * Met dans une iframe la page url du nom passe en parametre
 * @param name : name qu'on veut chercher sur wikipedia
 */
D3_Utils.prototype.show_wikipedia = function(name) {

	var s = name.replace(/\./g,"").replace(" ","_").replace("-","_");
	var url = "http://en.m.wikipedia.org/wiki/"+s;
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
	var wordnet = $('#WN').attr('checked'); //Récupération de la source de données demandée
	//Url permettant de faire la recherche demandée (dépend de la source)
	if (wordnet)
	{
		//var url = "http://localhost/bundles/CartoRepresentationsBundle/action/main_action.php"; // remy
		//var url = "http://carto.localhost/bundles/CartoRepresentationsBundle/action/main_action.php"; // Celine
		var url = "http://localhost/CartoSavoie/carto/web/bundles/CartoRepresentationsBundle/action/main_action.php"; // Juliana
		//var url = "http://localhost/Projet%20-%20Visualisation%20de%20donnees/carto/web/bundles/CartoRepresentationsBundle/action/main_action.php"; //Anthony
		//var url = "http://carto.dev/bundles/CartoRepresentationsBundle/action/main_action.php"; //Anthony2
	}
	else
	{
		//var url = "http://localhost/bundles/CartoRepresentationsBundle/action/main_action_dbpedia.php"; // remy
		//var url = "http://carto.localhost/bundles/CartoRepresentationsBundle/action/main_action_dbpedia.php"; // Celine
		var url = "http://localhost/CartoSavoie/carto/web/bundles/CartoRepresentationsBundle/action/main_action_dbpedia.php"; // Juliana
		//var url = "http://localhost/Projet%20-%20Visualisation%20de%20donnees/carto/web/bundles/CartoRepresentationsBundle/action/main_action_dbpedia.php"; //Anthony
		//var url = "http://carto.dev/bundles/CartoRepresentationsBundle/action/main_action_dbpedia.php"; //Anthony2
	}
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
}

function move(d) {
	// d est un objet compose des coordonnees x et y
	// Pour deplacer l'element on regarde son ancienne position et on ajoute
	// les nouvelles cad la ou l'utilisateur a clique
	d.x += d3.event.dx;
	d.y += d3.event.dy;
	d3.select('.representationContainer').attr("transform", "translate(" + d.x + "," + d.y + ")");
}

/**
 * Prepare le svg pour que l'utilisateur puisse faire un drag and drop
 */
D3_Utils.prototype.dragAndDrop = function() {
	if($("#drag_and_drop").attr('value') === "1"){
		stopDragAndDrop();
	}
	else{
		d3.select('.dragAndDrop')
			.style("background-color", "#36A9C7")
			.attr("value", "1");
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
function stopDragAndDrop() {
	if($("#drag_and_drop").attr('value') === "1"){
		// On change la couleur du bouton
		d3.select('.dragAndDrop')
		.style("background-color", "#d0cbcb")
		.attr("value", "0");
		
		// On enleve  le drag and drop
		var container = d3.select(".svgContainer");				
		container.call(d3.behavior.drag().on("drag", null));
		$(".svgContainer").removeAttr('cursor');
	}
	else{
		this.dragAndDrop();
	}
}