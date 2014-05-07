<?php
	if (isset($_POST['search'])){
		$cmd = $_POST['search'];
		$options = $_POST['options'];//liste des relations à prendre en
		$relations = "all";
		if(empty($options) == false){
			$relations = implode(",", $options);
		}
	}
	else { $cmd = 'entity'; }

	$curlSession = curl_init();

	//Pour tester la possibilité de rechercher une ou des relations données, remplacez /all par /rel1,rel2,... (au moins une relation)
	curl_setopt($curlSession, CURLOPT_URL, 'http://carto.localhost/fr/donnees/json/'.$cmd.'/'.$relations); //Céline  // .'/'.$options = rel1,rel2,rel3
	//curl_setopt($curlSession, CURLOPT_URL, 'http://localhost/CartoSavoie/carto/web/fr/donnees/json/'.$cmd.'/'.$relations); //Juliana
	//curl_setopt($curlSession, CURLOPT_URL, 'http://localhost/Projet%20-%20Visualisation%20de%20donnees/carto/web/fr/donnees/json/'.$cmd.'/all'); // Anthony
	//curl_setopt($curlSession, CURLOPT_URL, 'http://carto.dev/fr/donnees/json/'.$cmd.'/'.$relations); // Anthony2
	//curl_setopt($curlSession, CURLOPT_URL, 'http://carto.dev/fr/donnees/json/'.$cmd.'/'.$relations);
	//curl_setopt($curlSession, CURLOPT_URL, 'http://localhost/app_dev.php/en/donnees/json/'.$cmd.'/'.$relations); // remy

	curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

	$jsonresult = curl_exec($curlSession);
	curl_close($curlSession);

	exit('{"success":"true","data":'.$jsonresult.'}');
?>
