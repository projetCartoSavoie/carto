function loadJsCssFile(url, fileType){
	alert(fileType);

	var head = document.getElementsByTagName("head")[0];
	if(fileType == "js"){
		var script = document.createElement("script");
		script.setAttribute("type", "text/javascript");
		script.setAttribute("source", url);
		head.appendChild(script);
		alert(script);
	}
	else if(fileType == "css"){
		var link = document.createElement("link");
		link.setAttribute("rel", "stylesheet");
		link.setAttribute("type", "text/css");
		link.setAttribute("href", url);
		head.appendChild(link);
		alert(link);
	}
}