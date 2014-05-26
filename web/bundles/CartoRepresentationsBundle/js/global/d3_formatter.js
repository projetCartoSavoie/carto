function D3_Formatter(){}


/**
 * Transforme le json generique en json compris par d3js pour la representation "graph"
 * @param json : graph -> JSON generique a transformer
 * @return json : graph -> JSON generique bien forme
 */
D3_Formatter.prototype.to_graph = function(graph){

	// Get Nodes
	var nodeArray = [];
	var typeColor = {};
	var i = 0;
	graph.noeuds.forEach(
		function(node) { 
			node.name = node.nom;
			
			// Nous n'avons plus besoin de nom
			delete node.nom;
			
			if(typeColor[node.type] == null){
				typeColor[node.type] = i;
				i++;
			}
			node.group = typeColor[node.type];
			
			// On construit un tableau de noeud afin d'avoir leur position
			// pour pouvoir creer les links du graphe
			nodeArray.push(node.id);
		}
	);
	
	graph.nodes = graph.noeuds;
	
	// On met des couleurs pour chaque relation
	var colorLink = {};
	// On definit une value de type int pour d3js
	// La premiere relation ayant une value de 1, la seconde de 2, ...
	var value = 1;
	graph.relations.forEach(
		function(relation){
			colorLink[relation] = value;
			value++;
		}
	);

	// Get Links
	graph.links = [];
	
	// Get Connexion
	graph.relationsUsed = [];
	
	// Parcours des graphes du json
	graph.graphe.forEach(
		function(graphe) {
		
			// La source est l'element noeud du graphe
			source = graphe.noeud;
			
			// Si la source est bien definie dans la liste des noeuds
			if(nodeArray.indexOf(source) != -1){
				
				// Parcours de l'ensemble des relations, pour avoir leur nom
				graph.relations.forEach(
					function(relation) {
					
						// Si le graphe a la relation
						if(graphe[relation]){
							// On parcourt l'ensemble des targets de la relation
							graphe[relation].forEach(
								function(target) {
									nameRelation = [];
									// Si la target est bien definie dans la liste des noeuds
									if(nodeArray.indexOf(target) != -1){
										for(var i = 0; i < graph.links.length; i++) {
											if (graph.links[i].source == nodeArray.indexOf(target) 
												&& graph.links[i].target == nodeArray.indexOf(source)) {
												nameRelation.push(relation);
												graph.links[i].name.push(relation);
											}
										}
										if(nameRelation.length  == 0){
											nameRelation.push(relation);
											// On ajoute un link bien formate au tableau links du graphe
											graph.links.push({
												source: nodeArray.indexOf(source),
												target: nodeArray.indexOf(target),
												value: colorLink[relation],
												name: nameRelation
											});
										}
									}
								}
							);
							// On insere les relations correspondantes dans les deux sens
							if(graph.relationsUsed.indexOf(relation) == -1){
								graph.relationsUsed.push(relation);
							}
						}
						
						// On incremente la value afin d'avoir des valeurs differentes pour chaque relation
						value++;
					}
				);
			}
		}
	);
	// On supprime du json ce dont nous avons plus besoins
	delete graph.noeuds;
	delete graph.relations;
	delete graph.graphe;

	return graph;
}


D3_Formatter.getNode = function(tree, id){
	if(tree.uid == id){
		return tree;
	}
	else {
		for(var i = 0; i < tree.children.length; i++){
			var node = D3_Formatter.getNode(tree.children[i], id);
			if(node){
				return node;
			}
		}
	}
};

/**
 * Transforme le json generique en json compris par d3js pour la representation "tree"
 * @param json : graph -> JSON generique a transformer
 * @return json : graph -> JSON generique bien forme
 */
D3_Formatter.prototype.to_tree = function(tree){
	var d3_tree = {};
	
	// Get Nodes
	var nodes = {};
	var infos = [];
	
	// On met des couleurs pour chaque relation
	var colorLink = {};
	// On definit une value de type int pour d3js
	// La premiere relation ayant une value de 1, la seconde de 2, ...
	var value = 1;
	tree.relations.forEach(
		function(relation){
			colorLink[relation] = value;
			value++;
		}
	);
	
	// Get Connexion
	tree.relationsUsed = [];
	
	// Get Links
	tree.links = [];
	
	var vu = {};
	var typeNode = {};
	var i = 0;
	tree.noeuds.forEach(
		function(node) { 
			infos = [];
			vu[node.id] = false;
			if(typeNode[node.type] == null){
				typeNode[node.type] = i;
				i++;
			}
			infos.push(node.nom);
			infos.push({
				type: node.type,
				color: typeNode[node.type]
			});

			// On construit une map avec key l'id et value le nom et des infos supplémentaires
			nodes[node.id] = infos;
		}
	);
	
	//On va parcourir le graphe pour construire l'arbre
	tree.graphe.forEach(
		//Sur chaque élément du graphe on va regarder le noeud concerné
		function(graphe) {
			
			// La source est l'element noeud du tree
			var root_id = graphe.noeud;
			
			// Si la source est bien definie dans la liste des noeuds
			if(nodes[root_id] || Object.keys(d3_tree).length == 0){
				var node = {};
				if(Object.keys(d3_tree).length == 0){
					d3_tree = {
						uid: root_id,
						name: nodes[root_id][0],
						size: 500,
						group: nodes[root_id][1].color,
						type: nodes[root_id][1].type,
						children: []
					};
					node = d3_tree;
				}
				// Nous ne sommes plus sur le pere mais sur un enfant
				else {
					node = D3_Formatter.getNode(d3_tree, root_id);
				}
				
				// Si node n'est pas defini -> le fichier json est mal forme
				if(node){
				
					// Parcours de l'ensemble des relations
					tree.relations.forEach(
						function(relation) {
						
							// Si le graphe a la relation
							if(graphe[relation]){
								
								// On parcourt l'ensemble des enfants de la relation
								graphe[relation].forEach(
									function(child) {
									
										// Si le child est bien defini dans la liste des noeuds
										if(nodes[child] && !vu[child]){
											vu[child] = true;
											var nodeChild = {
												uid: child,
												name: nodes[child][0],
												size: 100 + Math.floor(Math.random()*200),
												group: nodes[child][1].color,
												type: nodes[child][1].type,
												children: []
											};
											node.children.push(nodeChild);
											tree.links.push({
												source: node,
												target: nodeChild,
												value: colorLink[relation],
												name: relation
											});
											// On insere les relations correspondantes dans un seul sens
											// pere -> fils
											if(tree.relationsUsed.indexOf(relation) == -1){
												tree.relationsUsed.push(relation);
											}
										}
									}
								);
							}
						}
					);
				}
			}
		}
	);
	d3_tree.typeNode = typeNode;
	d3_tree.relationsUsed = tree.relationsUsed;
	d3_tree.links = tree.links;
	return d3_tree;
}
