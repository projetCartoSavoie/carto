<?php
	if (isset($_POST['search'])){
		$cmd = $_POST['search'];
		$options = $_POST['options'];//liste des relations à prendre en
		$optionsProfondeur = $_POST['profondeur'];
		$relations = "all";
		if(empty($options) == false){
			$relations = implode(",", $options);
		}
		//Gérer ici la profondeur de la recherche
		$profondeur = 3;
		if(empty($optionsProfondeur) == false){
			$profondeur = $optionsProfondeur;
		}
	}
	else { 
		$cmd = 'entity'; 
		$relations = "all";
		$profondeur = 3;
	}

	$curlSession = curl_init();

	//curl_setopt($curlSession, CURLOPT_URL, 'http://carto.localhost/fr/donnees/json/'.$cmd.'/'.$relations.'/'.$profondeur); //Céline 
	curl_setopt($curlSession, CURLOPT_URL, 'http://localhost/CartoSavoie/carto/web/fr/donnees/json/'.$cmd.'/'.$relations.'/'.$profondeur); //Juliana
	//curl_setopt($curlSession, CURLOPT_URL, 'http://localhost/Projet%20-%20Visualisation%20de%20donnees/carto/web/fr/donnees/json/'.$cmd.'/'.$relations.'/'.$profondeur); // Anthony
	//curl_setopt($curlSession, CURLOPT_URL, 'http://carto.dev/fr/donnees/json/'.$cmd.'/'.$relations.'/'.$profondeur); // Anthony2
	//curl_setopt($curlSession, CURLOPT_URL, 'http://localhost/app_dev.php/en/donnees/json/'.$cmd.'/'.$relations.'/'.$profondeur); // remy

	curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

	$jsonresult = curl_exec($curlSession);
	curl_close($curlSession);

	exit('{"success":"true","data":'.$jsonresult.'}');
?>
