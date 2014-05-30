<?php

	main();

	function main()
	{
		$cmd = '';
		if (isset($_POST['cmd']))
		{
			$cmd = $_POST['cmd'];
		}
		$valid_actions = array('search_wordnet','search_dbpedia','search_debian','search_humour','get_relations');
		if (in_array($cmd,$valid_actions)) { $url = $cmd($_POST); }
		else { exit('success:false'); }
		$curlSession = curl_init();

		curl_setopt($curlSession, CURLOPT_URL, $url);
		curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

		$jsonresult = curl_exec($curlSession);
		curl_close($curlSession);

		exit('{"success":"true","data":'.$jsonresult.'}');
	}

	function search_wordnet($postvar) {

		if (isset($postvar['search'])){
			$cmd = $postvar['search'];
			$options = $postvar['options'];//liste des relations à prendre en compte
			$relations = "all";
			if(empty($options) == false){
				$relations = implode(",", $options);
			}
			//Gerer ici la profondeur de la recherche
			$optionsProfondeur = $postvar['profondeurWN'];
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
		
		$ini_array = parse_ini_file(__DIR__."/app.ini");
		$url = $ini_array["urlACTWN"].$cmd."/".$relations."/".$profondeur;
		return $url;
	}
	
	function search_dbpedia($postvar){
		if (isset($postvar['search'])){
			$cmd = $postvar['search'];
			//Gerer ici la profondeur de la recherche
			//exit('{success:'.$postvar['profondeur'].'}');
			$optionsProfondeur = $postvar['profondeurDB'];
			$profondeur = 10;
			if(empty($optionsProfondeur) == false){
				$profondeur = $optionsProfondeur;
			}
			//exit('{success:'.$optionsProfondeur.'-'.$profondeur.'}');
		}
		else { $cmd = 'entity'; $profondeur = 10; }
		
		$ini_array = parse_ini_file(__DIR__."/app.ini");
		$url = $ini_array["urlACTPB"];
		return $url.$cmd.'/'.$profondeur;
	}

	function search_debian($postvar)
	{
		if (isset($postvar['search']))
		{
			$cmd = $postvar['search'];
		}
		else { 
			$cmd = 'nano'; 
		}
		
		$ini_array = parse_ini_file(__DIR__."/app.ini");
		$url = $ini_array["urlAUTRE"];
		return $url.$cmd;
	}

	function search_humour($postvar)
	{
		if (isset($postvar['search']))
		{
			$cmd = $postvar['search'];
		}
		else { 
			$cmd = 'D3'; 
		}

		$ini_array = parse_ini_file(__DIR__."/app.ini");
		$url = $ini_array["urlHUMOUR"];
		return $url.$cmd;
	}

	function get_relations(){
		$ini_array = parse_ini_file(__DIR__."/app.ini");
		$url = $ini_array["urlOPTON"];
		return $url;
	}
 ?>
