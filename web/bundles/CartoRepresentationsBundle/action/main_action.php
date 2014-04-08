<?php
	if (isset($_POST['search'])){
		$cmd = $_POST['search'];
	}
	else { $cmd = 'entity'; }
	//var_dump($cmd);

	$curlSession = curl_init();
	curl_setopt($curlSession, CURLOPT_URL, 'http://carto.localhost/app_dev.php/fr/donnees/json/'.$cmd); //Céline
	//curl_setopt($curlSession, CURLOPT_URL, 'http://localhost/CartoSavoie/carto/web/fr/donnees/json/'.$cmd); //Juliana
	curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

	$jsonresult = curl_exec($curlSession);
	curl_close($curlSession);

	exit('{"success":"true","data":'.$jsonresult.'}');
	/*$cmd = '';
	if (isset($_POST['cmd'])){
		$cmd = $_POST['cmd'];
	}
	switch($cmd){
		case "search_action":
			search($_POST);
		break;
		default:
			exit("{success:false}");
		break;
	}
	
	function search($_POST) {
		$search = $_POST['search'];
				
		$curlSession = curl_init();
		curl_setopt($curlSession, CURLOPT_URL, 'http://carto.localhost/bundles/CartoRepresentationsBundle/json/exemple.json');
		curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

		$jsonresult = curl_exec($curlSession);
		curl_close($curlSession);
				
		exit('{"success":"true","data":'.$jsonresult.'}');
	}*/
?>
