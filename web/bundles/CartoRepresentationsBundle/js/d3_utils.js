function D3_Utils(){}


D3_Utils.prototype.show_wikipedia = function(name) {

	var s = name.replace(/\./g,"").replace(" ","_").replace("-","_");
	var url = "http://en.m.wikipedia.org/wiki/"+s;
	$('#wikipedia').html(
			"<iframe id='wikiframe' src='"+url+"' "+
			"frameborder='0'></iframe>"
			);
	var size = Math.round( $('#wikiframe').position().top
			- $('#wikipedia').position().top );
	size = $('#wikipedia').height() - size - 10;
	$('#wikiframe').css('height', size+'px');
}