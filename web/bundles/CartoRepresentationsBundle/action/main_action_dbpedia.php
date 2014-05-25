<?php
	if (isset($_POST['search'])){
		$cmd = $_POST['search'];
	}
	else { $cmd = 'entity'; }

	$curlSession = curl_init();

	//Ouverture du fichier de configuration
	$fichier='../../../../app/config/config.yml'; 
	//Recuperation des lignes dans le fichier de config
	$tabfich=file($fichier);
	//Formatage de l'URL pour la requete CURL
	$MYurl = $tabfich[35].$cmd;
	//Manipulation chaine de caractere pour un bon format d'echange
	$MYurl = substr($MYurl,18);
	$MYurl = str_replace("", "", $MYurl);
	$MYurl = str_replace("\n", "", $MYurl);

	//echo $MYurl;
	
	curl_setopt($curlSession, CURLOPT_URL, $MYurl);

	curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

	$jsonresult = curl_exec($curlSession);
	curl_close($curlSession);

	exit('{"success":"true","data":'.$jsonresult.'}');
?>





