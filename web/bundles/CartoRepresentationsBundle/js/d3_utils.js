function D3_Utils(){}


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

D3_Utils.prototype.zoomIn = function() {
}

D3_Utils.prototype.zoomOut = function() {
}