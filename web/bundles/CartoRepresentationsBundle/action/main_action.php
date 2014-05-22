<?php
	$cmd = '';
	if (isset($_POST['cmd'])){
 		$cmd = $_POST['cmd'];
 	}
 	switch($cmd){
 		case "search_action":
 			search($_POST);
 		break;
		case "search_dbpeadia":
			search_dbpedia($_POST);
		break;
		case "get_relations":
			get_relations();
		break;
 		default:
 			exit("{success:false}");
 		break;
 	}
 	
 	function search($_POST) {
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
 	}
	
	function search_dbpedia($_POST){
		if (isset($_POST['search'])){
			$cmd = $_POST['search'];
		}
		else { $cmd = 'entity'; }

		$curlSession = curl_init();
		//curl_setopt($curlSession, CURLOPT_URL, 'http://carto.localhost/fr/donnees/dbpedia/json/'.$cmd); //Céline
		curl_setopt($curlSession, CURLOPT_URL, 'http://localhost/CartoSavoie/carto/web/fr/donnees/dbpedia/json/'.$cmd); //Juliana
		//curl_setopt($curlSession, CURLOPT_URL, 'http://localhost/Projet%20-%20Visualisation%20de%20donnees/carto/web/fr/donnees/dbpedia/json/'.$cmd); // Anthony
		//curl_setopt($curlSession, CURLOPT_URL, 'http://carto.dev/fr/donnees/dbpedia/json/'.$cmd); // Anthony2
		//curl_setopt($curlSession, CURLOPT_URL, 'http://localhost/app_dev.php/en/donnees/dbpedia/json/'.$cmd); // remy

		curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

		$jsonresult = curl_exec($curlSession);
		curl_close($curlSession);

		exit('{"success":"true","data":'.$jsonresult.'}');
	}
	
	function get_relations(){
		$curlSession = curl_init();
		//curl_setopt($curlSession, CURLOPT_URL, 'http://carto.localhost/fr/donnees/relations'); //Céline
		curl_setopt($curlSession, CURLOPT_URL, 'http://localhost/CartoSavoie/carto/web/fr/donnees/relations'); //Juliana
		//curl_setopt($curlSession, CURLOPT_URL, 'http://localhost/Projet%20-%20Visualisation%20de%20donnees/carto/web/fr/donnees/relations'); // Anthony
		//curl_setopt($curlSession, CURLOPT_URL, 'http://carto.dev/fr/donnees/relations'); // Anthony2
		//curl_setopt($curlSession, CURLOPT_URL, 'http://localhost/app_dev.php/fr/donnees/relations'); // remy

		curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

		$jsonresult = curl_exec($curlSession);
		curl_close($curlSession);

		exit('{"success":"true","data":'.$jsonresult.'}');
	}
 ?>
