function D3_BubbleRepresentation(){}

/** 
 * Fonction show : appelle la fonction load en lui passant le json
 *
 * @param data : le json sous forme d'objet json ou de chaine de caractères
 */
D3_BubbleRepresentation.prototype.show = function(data) {
	// data is file path
	if(typeof data === "string"){
		d3.json(data, function(error, root) {
			if (error) alert(error);
			D3_BubbleRepresentation.load(root);
		});
	}
	// data is json
	else {
		D3_BubbleRepresentation.load(data);
	}
}

/** 
 * Fonction load : ajoute les balises svg au conteneur pour afficher la vue bubble pour le json donné
 *
 * @param json : fichier json rendu par la recherche
 */
D3_BubbleRepresentation.load = function(json) {

	//On transforme le json format commun en json format tree accepté par D3
	var formatter = new D3_Formatter();
	var treeJson = formatter.to_tree(json);


	//Variables indiquant les paramètres de notre visualisation (marges, taille, couleurs)
	var margin = 20,
			diameter = $("#contentCenter").width();

	var color = d3.scale.linear()
			.domain([-1, 5])
			.range(["hsl(152,80%,80%)", "hsl(228,30%,40%)"])
			.interpolate(d3.interpolateHcl);


	//Le packlayout de D3 permet d'agencer des ensembles de cercles dans des cercles
	var pack = d3.layout.pack()
			.padding(50)
			.size([diameter - margin, diameter - margin])
			.value(function(d) { return d.size; })

	//On configure le svg qui contiendra toute la figure
	var svg = d3.select("#contentCenter").append("svg")
			.attr("width", diameter)
			.attr("height", diameter)
		.append("g")
			.attr("transform", "translate(" + diameter/2 + "," + diameter/2 + ")");

	var container = svg.append("g")
	.attr("class", "representationContainer");

	//focus indique sur quel noeud doit se centrer la vue (au départ c'est la racine)
	var focus = treeJson;
	console.log(treeJson);
	//view contiendra un vecteur correspondant au zoom sur le focus (vecteur (x,y,r) où (x,y) = coordonnées du centre et r = taille de la zone visible)
	var view;

	//pack.nodes transforme le treeJson en un objet contenant les infos nécessaires au packlayout.
	//ces infos sont calculées automatiquement par D3 à partir du json qu'on lui fournit.
	var nodes = pack.nodes(treeJson);



	//On ajoute les cercles représentant les noeuds de l'arbre
	var circle = container.selectAll("circle")
			.data(nodes)
		.enter().append("circle")
			.attr("class", function(d) { return d.parent ? d.children ? "node" : "node node--leaf" : "node node--treeJson"; })
			.style("fill", function(d) { return d.children ? color(d.depth) : null; })
			.on("click", function(d) {
					//Le click sur un cercle provoque 2 choses :
						//La vue se centre sur ce cercle
					if (focus !== d) zoomfonc(d), d3.event.stopPropagation();
						//Le résultat d'une recherche wikipedia s'affiche dans le cadre wikipedia
						//Si le noeud a un nom contenant des espaces, on cherche un mot sans espace dans son voisinage pour faire la recherche.
					var sansEspace = new RegExp(/\s/); 
					var d3_utils = new D3_Utils();
					if(sansEspace.test(d.name.toString()) == false) d3_utils.show_wikipedia(d.name); 
					else if(sansEspace.test(d.children[0].name.toString()) == false) d3_utils.show_wikipedia(d.children[0].name);
					else if(sansEspace.test(d.parent.name.toString()) == false) d3_utils.show_wikipedia(d.parent.name);

			})
			.on("dblclick", function(d){
				var d3_utils = new D3_Utils();
				var sansEspace = new RegExp(/\s/); 
				if(sansEspace.test(d.name.toString()) == false) d3_utils.load_json(d); 
				else if(sansEspace.test(d.children[0].name.toString()) == false) d3_utils.load_json(d.children[0]);
				else if(sansEspace.test(d.parent.name.toString()) == false) d3_utils.load_json(d.parent.name);
			});

	//On ajoute le texte représentant le noeud dans chaque cercle.
	var text = container.selectAll("text")
			.data(nodes)
		.enter()
			.append("text")
			.attr("class", "label")
			/*.style("fill-opacity", function(d) { return d.parent === treeJson ? 1 : 0; })
			.style("display", function(d) { return d.parent === treeJson ? null : "none"; })
			.text(function(d) { return d.name; });*/
			.style("display", function(d) { 
				var sansEspace = new RegExp(/\s/); 
				return sansEspace.test(d.name.toString()) ? "none" : null;
			 }) 
			.style("font-size", '10px')
			.text(function(d) { return d.name; });
			
	//On ajoute un title pour voir les définitions en entier lorsque les noeuds contiennent plus d'un mot
	var node = container.selectAll("circle,text");
	node.append("title")
		.text(function(d) { return d.name; });

	//Gestion du zoom pour faire un focus sur un cercle
	d3.select("#contentCenter")
			.style("background", color(-1))
			.on("click", function() { zoomfonc(treeJson); });

	zoomTo([treeJson.x, treeJson.y, treeJson.r * 2 + margin]);

	//Lance le zoom sur un noeud
	function zoomfonc(d) {
		var focus0 = focus; focus = d;

		var transition = d3.transition()
				.duration(d3.event.altKey ? 7500 : 750)
				.tween("zoom", function(d) {
					var i = d3.interpolateZoom(view, [focus.x, focus.y, focus.r * 2 + margin]);
					return function(t) { zoomTo(i(t)); };
				});

    /*transition.selectAll("text")
      .filter(function(d) { return d.parent === focus || this.style.display === "inline"; })
        .style("fill-opacity", function(d) { return d.parent === focus ? 1 : 0; })
        .each("start", function(d) { if (d.parent === focus) this.style.display = "inline"; })
        .each("end", function(d) { if (d.parent !== focus) this.style.display = "none"; });*/
	}

	//Zoom sur une position
	function zoomTo(v) {
		var k = diameter / v[2]; view = v;
		node.attr("transform", function(d) { return "translate(" + (d.x - v[0]) * k + "," + (d.y - v[1]) * k + ")"; });
		circle.attr("r", function(d) { return d.r * k; });
	}

	//On adapte la taille de la figure à la taille du conteneur
	d3.select(self.frameElement).style("height", diameter + "px");
	
	
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

	//On désactive les boutons inutiles
	d3.selectAll('.rotate').attr("value","0").attr("class","inactif");
	d3.selectAll('.dragAndDrop').attr("value","0").attr("class","inactif");
	d3.selectAll('.zoom').attr("class","inactif");

}

