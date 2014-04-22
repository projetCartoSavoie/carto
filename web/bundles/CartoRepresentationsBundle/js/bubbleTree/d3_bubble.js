function D3_BubbleRepresentation(){}

var zoom = null;

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

D3_BubbleRepresentation.load = function(json) {

	var formatter = new D3_Formatter();
	var treeJson = formatter.to_tree(json);
	var zoomvar = d3.behavior.zoom()
			.scaleExtent([1, 10])
			.on("zoom", zoomed);

	var margin = 20,
			diameter = $("#contentCenter").width();

	var color = d3.scale.linear()
			.domain([-1, 5])
			.range(["hsl(152,80%,80%)", "hsl(228,30%,40%)"])
			.interpolate(d3.interpolateHcl);

	var pack = d3.layout.pack()
			.padding(2)
			.size([diameter - margin, diameter - margin])
			.value(function(d) { return d.size; })

	var svg = d3.select("#contentCenter").append("svg")
			.attr("width", diameter)
			.attr("height", diameter)
		.append("g")
			.attr("transform", "translate(" + diameter/2 + "," + diameter/2 + ")");

	var container = svg.append("g")
	.attr("class", "representationContainer");

	var focus = treeJson,
			nodes = pack.nodes(treeJson),
			view;

	var circle = container.selectAll("circle")
			.data(nodes)
		.enter().append("circle")
			.attr("class", function(d) { return d.parent ? d.children ? "node" : "node node--leaf" : "node node--treeJson"; })
			.style("fill", function(d) { return d.children ? color(d.depth) : null; })
			.on("click", function(d) { 
					if (focus !== d) zoom(d), d3.event.stopPropagation(); 
					var sansEspace = new RegExp(/\s/); 
					var d3_utils = new D3_Utils();
					if(sansEspace.test(d.name.toString()) == false) d3_utils.show_wikipedia(d.name); 
					else if(sansEspace.test(d.children[0].name.toString()) == false) d3_utils.show_wikipedia(d.children[0].name);
					else if(sansEspace.test(d.parent.name.toString()) == false) d3_utils.show_wikipedia(d.parent.name);
			})
			.on("dblclick", function(d){
				//var url = "http://localhost/CartoSavoie/carto/web/bundles/CartoRepresentationsBundle/action/main_action.php"; // Juliana
				//var url = "http://carto.dev/bundles/CartoRepresentationsBundle/action/main_action.php"; //Anthony
				var url = "http://carto.localhost/bundles/CartoRepresentationsBundle/action/main_action.php"; // CÃ©line
				var sansEspace = new RegExp(/\s/); 
				if(sansEspace.test(d.name.toString()) == false) var nom = d.name; 
				else if(sansEspace.test(d.children[0].name.toString()) == false) var nom = d.children[0].name;
				else if(sansEspace.test(d.parent.name.toString()) == false) var nom = d.parent.name;
				$("#contentCenter").html('<img id="loading" src="/bundles/CartoRepresentationsBundle/images/ajax-loader.gif">');
				$.ajax({
					type: "POST",
					url: url,
					data: {
						cmd: 'search_action',
						search: nom
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
			});


	var text = container.selectAll("text")
			.data(nodes)
		.enter().append("text")
			.attr("class", "label")
			.style("fill-opacity", function(d) { return d.parent === treeJson ? 1 : 0; })
			.style("display", function(d) { return d.parent === treeJson ? null : "none"; })
			.style("font-size", function(d) { if (d.name.length > 20) { return '10px'; } else if (d.name.length > 10) { return '15px'; } return '20px'; })
			.text(function(d) { if (d.name.length > 20) {return (d.name.substring(0,17) + '...');} return d.name; });
			

	var node = container.selectAll("circle,text");

	node.append("title")
		.text(function(d) { return d.name; });

	d3.select("#contentCenter")
			.style("background", color(-1))
			.on("click", function() { zoom(treeJson); });

	zoomTo([treeJson.x, treeJson.y, treeJson.r * 2 + margin]);

	function zoom(d) {
		var focus0 = focus; focus = d;

		var transition = d3.transition()
				.duration(d3.event.altKey ? 7500 : 750)
				.tween("zoom", function(d) {
					var i = d3.interpolateZoom(view, [focus.x, focus.y, focus.r * 2 + margin]);
					return function(t) { zoomTo(i(t)); };
				});

		transition.selectAll("text")
			.filter(function(d) { return d.parent === focus || this.style.display === "inline"; })
				.style("fill-opacity", function(d) { return d.parent === focus ? 1 : 0; })
				.each("start", function(d) { if (d.parent === focus) this.style.display = "inline"; })
				.each("end", function(d) { if (d.parent !== focus) this.style.display = "none"; });
	}

	function zoomTo(v) {
		var k = diameter / v[2]; view = v;
		node.attr("transform", function(d) { return "translate(" + (d.x - v[0]) * k + "," + (d.y - v[1]) * k + ")"; });
		circle.attr("r", function(d) { return d.r * k; });
	}

	d3.select(self.frameElement).style("height", diameter + "px");
	d3.selectAll('.zoom').on('click', zoomClick);

	function zoomClick() {
		var width = $("#contentCenter").width();
		var height = $("#contentCenter").height();

		var clicked = d3.event.target,
			direction = 1,
			factor = 0.2,
			target_zoom = 1,
			center = [0,0],
			extent = zoomvar.scaleExtent(),
			scale = 0;
		
		d3.event.preventDefault();
	
		// On revient sur la taille initiale
		if(this.id === 'intial_scale'){
			scale = 1;
		}
		// Zoom / Dezoom
		else {
			direction = (this.id === 'zoom_in') ? 1 : -1;
			target_zoom = zoomvar.scale() * (1 + factor * direction);
			scale = target_zoom;
		}

		interpolateZoom(center, scale);
	}

	function zoomed(center) 
	{
		var container = d3.select(".representationContainer");
		container.attr("transform",
			"translate(" + center[0] + "," + center[1] + ")"  +
			"scale(" + zoomvar.scale() + ")"
		);
	}

	function interpolateZoom (translate, scale) 
	{
		var self = this;
		return d3.transition().duration(350).tween("zoom", function () {
			var iScale = d3.interpolate(zoomvar.scale(), scale);
			return function (t) {
			zoomvar
				.scale(iScale(t))
			zoomed(translate);
			};
		});
	}

}

