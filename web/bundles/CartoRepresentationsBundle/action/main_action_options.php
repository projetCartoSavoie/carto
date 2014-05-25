<?php
	$curlSession = curl_init();
	
	//Ouverture du fichier de configuration
	$fichier='../../../../app/config/config.yml'; 
	//Recuperation des lignes dans le fichier de config
	$tabfich=file($fichier);
	//Formatage de l'URL pour la requete CURL
	$MYurl = $tabfich[36];
	//Manipulation chaine de caractere pour un bon format d'echange
	$MYurl = substr($MYurl,18);
	$MYurl = str_replace("", "", $MYurl);
	$MYurl = str_replace("\n", "", $MYurl);
	
	
	//curl_setopt($curlSession, CURLOPT_URL, 'http://carto.localhost/fr/donnees/relations'); //Céline
	//curl_setopt($curlSession, CURLOPT_URL, 'http://localhost/CartoSavoie/carto/web/fr/donnees/relations'); //Juliana
	curl_setopt($curlSession, CURLOPT_URL, $MYurl); //Remi
	//curl_setopt($curlSession, CURLOPT_URL, 'http://localhost/Projet%20-%20Visualisation%20de%20donnees/carto/web/fr/donnees/relations'); // Anthony
	//curl_setopt($curlSession, CURLOPT_URL, 'http://carto.dev/fr/donnees/relations'); // Anthony2
	//curl_setopt($curlSession, CURLOPT_URL, 'http://localhost/app_dev.php/fr/donnees/relations'); // remy

	curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

	$jsonresult = curl_exec($curlSession);
	curl_close($curlSession);

	exit('{"success":"true","data":'.$jsonresult.'}');
?>
