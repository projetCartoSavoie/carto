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

		//Ouverture du fichier de configuration
		$fichier='../../../../app/config/config.yml'; 
		//Recuperation des lignes dans le fichier de config
		$tabfich=file($fichier);

		/*On parcourt le tableau $lines et on affiche le contenu de chaque ligne précédée de son numéro*/
		foreach ($tabfich as $lineNumber => $lineContent)
		{
			/*On recupere la ligne*/
			$MYurlTMP = $tabfich[$lineNumber];

			//Code à exécuter si la sous-chaine chaine2 est trouvée dans chaine1
			if( strstr($MYurlTMP, "urlACTWN")) {
				
				//Manipulation chaine de caractere pour un bon format d'echange
				$MYurl = substr($MYurlTMP,18);
				$MYurl = str_replace("", "", $MYurl);
				$MYurl = str_replace("\n", "", $MYurl);

			} 
		}
		$MYurl = $MYurl.$cmd.'/'.$relations.'/'.$profondeur;
		return $MYurl;
	}
	
	function search_dbpedia($postvar){
		if (isset($postvar['search'])){
			$cmd = $postvar['search'];
		}
		else { $cmd = 'entity'; }
		
		//Ouverture du fichier de configuration
		$fichier='../../../../app/config/config.yml'; 
		//Recuperation des lignes dans le fichier de config
		$tabfich=file($fichier);

		/*On parcourt le tableau $lines et on affiche le contenu de chaque ligne précédée de son numéro*/
		foreach ($tabfich as $lineNumber => $lineContent)
		{
			/*On recupere la ligne*/
			$MYurlTMP = $tabfich[$lineNumber];

			//Code à exécuter si la sous-chaine chaine2 est trouvée dans chaine1
			if( strstr($MYurlTMP, "urlACTPB")) {
				
				//Manipulation chaine de caractere pour un bon format d'echange
				$MYurl = substr($MYurlTMP,18);
				$MYurl = str_replace("", "", $MYurl);
				$MYurl = str_replace("\n", "", $MYurl);

			} 
		}
		$MYurl = $MYurl.$cmd;
		return $MYurl;
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

		//Ouverture du fichier de configuration
		$fichier='../../../../app/config/config.yml'; 
		//Recuperation des lignes dans le fichier de config
		$tabfich=file($fichier);

		/*On parcourt le tableau $lines et on affiche le contenu de chaque ligne précédée de son numéro*/
		foreach ($tabfich as $lineNumber => $lineContent)
		{
			/*On recupere la ligne*/
			$MYurlTMP = $tabfich[$lineNumber];

			//Code à exécuter si la sous-chaine chaine2 est trouvée dans chaine1
			if( strstr($MYurlTMP, "urlAUTRE")) {
				
				//Manipulation chaine de caractere pour un bon format d'echange
				$MYurl = substr($MYurlTMP,18);
				$MYurl = str_replace("", "", $MYurl);
				$MYurl = str_replace("\n", "", $MYurl);

			} 
		}
		
		$MYurl = $MYurl.$cmd;
		
		return $MYurl; 
	}

	function get_relations(){

		//Ouverture du fichier de configuration
		$fichier='../../../../app/config/config.yml'; 
		//Recuperation des lignes dans le fichier de config
		$tabfich=file($fichier);
	
		/*On parcourt le tableau $lines et on affiche le contenu de chaque ligne précédée de son numéro*/
		foreach ($tabfich as $lineNumber => $lineContent)
		{
			/*On recupere la ligne*/
			$MYurlTMP = $tabfich[$lineNumber];

			//Code à exécuter si la sous-chaine chaine2 est trouvée dans chaine1
			if( strstr($MYurlTMP, "urlOPTON")) {
				
				//Manipulation chaine de caractere pour un bon format d'echange
				$MYurl = substr($MYurlTMP,18);
				$MYurl = str_replace("", "", $MYurl);
				$MYurl = str_replace("\n", "", $MYurl);
			} 
		}
		return $MYurl;
	}
 ?>
