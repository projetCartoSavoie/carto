// Fonction d'impression du graphe
function PrintElem(elem)
{
	Popup($(elem).html());
}

// Met dans une fenêtre d'impression le graphe 
function Popup(data) 
{
	var mywindow = window.open('', 'contentCenter', 'height=400,width=600');
	mywindow.document.write('<html><head><title>Réprésentation du mot ');
	mywindow.document.write($('#search').val());
	if ($('#quantite').val()){
		mywindow.document.write(' pour une profondeur de ');
		mywindow.document.write($('#quantite').val());
	}
	if ($('input:checked[name = options]').val()){
		mywindow.document.write(' avec la(les) relation(s) ');
		$('input:checked[name = options]').each(function() {
				mywindow.document.write($(this).val());
				mywindow.document.write(', ');
		});
		mywindow.document.write('sélectionnée(s)');
	}
	mywindow.document.write('</title>');
	/*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
	mywindow.document.write('</head><body >');
	mywindow.document.write(data);
	mywindow.document.write('</body></html>');

	mywindow.print();
	mywindow.close();

	return true;
}