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

	console.log(treeJson);

	var margin = 20,
			diameter = 960;

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
			.attr("transform", "translate(" + diameter / 2 + "," + diameter / 2 + ")");

	var focus = treeJson,
			nodes = pack.nodes(treeJson),
			view;

	/*treeJson.x = 470; treeJson.y = 470; treeJson.r = 470;
	treeJson.children.forEach(function(ch) { ch.x = 100; ch.y = 100; ch.r = 100; });*/

	var circle = svg.selectAll("circle")
			.data(nodes)
		.enter().append("circle")
			.attr("class", function(d) { return d.parent ? d.children ? "node" : "node node--leaf" : "node node--treeJson"; })
			.style("fill", function(d) { return d.children ? color(d.depth) : null; })
			.on("click", function(d) { if (focus !== d) zoom(d), d3.event.stopPropagation(); });

	var text = svg.selectAll("text")
			.data(nodes)
		.enter().append("text")
			.attr("class", "label")
			.style("fill-opacity", function(d) { return d.parent === treeJson ? 1 : 0; })
			.style("display", function(d) { return d.parent === treeJson ? null : "none"; })
			.text(function(d) { return d.name; });

	var node = svg.selectAll("circle,text");

	d3.select("body")
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

}
/*
function zoomClick() {

	var width = $("#contentCenter").width();
	var height = $("#contentCenter").height();

	var clicked = d3.event.target,
		direction = 1,
		factor = 0.2,
		target_zoom = 1,
		center = [width / 2, height / 2],
		extent = zoom.scaleExtent(),
		translate = zoom.translate(),
		translate0 = [],
		l = [],
		view = {x: translate[0], y: translate[1], k: zoom.scale()};

	d3.event.preventDefault();
	direction = (this.id === 'zoom_in') ? 1 : -1;
	target_zoom = zoom.scale() * (1 + factor * direction);

	if (target_zoom < extent[0] || target_zoom > extent[1]) { return false; }

	translate0 = [(center[0] - view.x) / view.k, (center[1] - view.y) / view.k];
	view.k = target_zoom;
	l = [translate0[0] * view.k + view.x, translate0[1] * view.k + view.y];

	view.x += center[0] - l[0];
	view.y += center[1] - l[1];

	interpolateZoom([view.x, view.y], view.k);
}

function zoomed() {
	var container = d3.select(".representationContainer");
	container.attr("transform",
		"translate(" + zoom.translate() + ")" +
		"scale(" + zoom.scale() + ")"
	);
}

function interpolateZoom (translate, scale) {
	var self = this;
	return d3.transition().duration(350).tween("zoom", function () {
		var iTranslate = d3.interpolate(zoom.translate(), translate),
			iScale = d3.interpolate(zoom.scale(), scale);
		return function (t) {
		zoom
			.scale(iScale(t))
			.translate(iTranslate(t));
		zoomed();
		};
	});
}*/
