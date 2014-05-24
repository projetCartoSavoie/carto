<?php

	main();

	function main()
	{
		$cmd = '';
		if (isset($_POST['cmd']))
		{
			$cmd = $_POST['cmd'];
		}
		switch($cmd)
		{
			case "search_action":
				$url = search($_POST);
			break;
			case "search_dbpedia":
				$url = search_dbpedia($_POST);
			break;
			case "search_autre":
				$url = search_autre($_POST);
			break;
			case "get_relations":
				$url = get_relations();
			break;
			default:
				exit("{success:false}");
			break;
		}

		$curlSession = curl_init();

		curl_setopt($curlSession, CURLOPT_URL, $url);

		curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

		$jsonresult = curl_exec($curlSession);
		curl_close($curlSession);

		exit('{"success":"true","data":'.$jsonresult.'}');
	}

	function search($postvar) {
		if (isset($postvar['search'])){
			$cmd = $postvar['search'];
			$options = $postvar['options'];//liste des relations à prendre en
			$optionsProfondeur = $postvar['profondeur'];
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

		//return 'http://carto.localhost/fr/donnees/json/'.$cmd.'/'.$relations.'/'.$profondeur; //Céline 
		//return 'http://localhost/CartoSavoie/carto/web/fr/donnees/json/'.$cmd.'/'.$relations.'/'.$profondeur; //Juliana
		//return 'http://localhost/Projet%20-%20Visualisation%20de%20donnees/carto/web/fr/donnees/json/'.$cmd.'/'.$relations.'/'.$profondeur; // Anthony
		return 'http://carto.dev/fr/donnees/json/'.$cmd.'/'.$relations.'/'.$profondeur; // Anthony2
		//return 'http://localhost/app_dev.php/en/donnees/json/'.$cmd.'/'.$relations.'/'.$profondeur; // remy
 	}
	
	function search_dbpedia($postvar){
		if (isset($postvar['search'])){
			$cmd = $postvar['search'];
		}
		else { $cmd = 'entity'; }
		
		return 'http://carto.localhost/fr/donnees/dbpedia/json/'.$cmd; //Céline
		//return 'http://localhost/CartoSavoie/carto/web/fr/donnees/dbpedia/json/'.$cmd; //Juliana
		//return 'http://localhost/Projet%20-%20Visualisation%20de%20donnees/carto/web/fr/donnees/dbpedia/json/'.$cmd; // Anthony
		//return 'http://carto.dev/fr/donnees/dbpedia/json/'.$cmd; // Anthony2
		//return 'http://localhost/app_dev.php/en/donnees/dbpedia/json/'.$cmd; // remy
	}

	function search_autre($postvar)
	{
		if (isset($postvar['search']))
		{
			$cmd = $postvar['search'];
		}
		else { 
			$cmd = 'nano'; 
		}

		return 'http://demo4.itpassion.info/crawler.php?target='.$cmd; 
	}

	function get_relations(){
		return 'http://carto.localhost/fr/donnees/relations'; //Céline
		//return 'http://localhost/CartoSavoie/carto/web/fr/donnees/relations'; //Juliana
		//return 'http://localhost/Projet%20-%20Visualisation%20de%20donnees/carto/web/fr/donnees/relations'; // Anthony
		//return 'http://carto.dev/fr/donnees/relations'; // Anthony2
		//return 'http://localhost/app_dev.php/fr/donnees/relations'; // remy
	}
 ?>
