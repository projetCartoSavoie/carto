<?php
	if (isset($_POST['search'])){
		$cmd = $_POST['search'];
	}
	else { $cmd = 'entity'; }

	$curlSession = curl_init();
	//curl_setopt($curlSession, CURLOPT_URL, 'http://carto.localhost/fr/donnees/dbpedia/json/'.$cmd); //Céline
	//curl_setopt($curlSession, CURLOPT_URL, 'http://localhost/CartoSavoie/carto/web/fr/donnees/dbpedia/json/'.$cmd); //Juliana
	curl_setopt($curlSession, CURLOPT_URL, 'http://127.0.0.1/fr/donnees/dbpedia/json/'.$cmd); //Remi
	//curl_setopt($curlSession, CURLOPT_URL, 'http://localhost/Projet%20-%20Visualisation%20de%20donnees/carto/web/fr/donnees/dbpedia/json/'.$cmd); // Anthony
	//curl_setopt($curlSession, CURLOPT_URL, 'http://carto.dev/fr/donnees/dbpedia/json/'.$cmd); // Anthony2
	//curl_setopt($curlSession, CURLOPT_URL, 'http://localhost/app_dev.php/en/donnees/dbpedia/json/'.$cmd); // remy

	curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

	$jsonresult = curl_exec($curlSession);
	curl_close($curlSession);

	exit('{"success":"true","data":'.$jsonresult.'}');
?>
