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


	//Ouverture du fichier de configuration
	$fichier='../../../../app/config/config.yml'; 
	//Recuperation des lignes dans le fichier de config
	$tabfich=file($fichier);
	//Formatage de l'URL pour la requete CURL
	$MYurl = $tabfich[34].$cmd.'/'.$relations.'/'.$profondeur;
	//Manipulation chaine de caractere pour un bon format d'echange
	$MYurl = substr($MYurl,18);
	$MYurl = str_replace("", "", $MYurl);
	$MYurl = str_replace("\n", "", $MYurl);

	
	curl_setopt($curlSession, CURLOPT_URL, $MYurl);
	
	curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

	$jsonresult = curl_exec($curlSession);
	curl_close($curlSession);

	exit('{"success":"true","data":'.$jsonresult.'}');
?>
