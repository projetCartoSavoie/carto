<?php
	if (isset($_POST['search'])){
		$cmd = $_POST['search'];
	}
	else { $cmd = 'entity'; }
	//var_dump($cmd);

	$curlSession = curl_init();
<<<<<<< HEAD
	curl_setopt($curlSession, CURLOPT_URL, 'http://carto.localhost/app_dev.php/fr/donnees/json/'.$cmd);
=======
	curl_setopt($curlSession, CURLOPT_URL, 'http://localhost/app_dev.php/fr/donnees/json/'.$cmd);
>>>>>>> 56f02e291c4f2028d35a5d02f8bb9a0c2285ce6a
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
<<<<<<< HEAD
		curl_setopt($curlSession, CURLOPT_URL, 'http://carto.dev/fr/donnees/json/'.$search);
=======
		curl_setopt($curlSession, CURLOPT_URL, 'http://carto.localhost/bundles/CartoRepresentationsBundle/json/exemple.json');
>>>>>>> 0a63713b64681a406a674729fc5c9ef88ab2865c
		curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

		$jsonresult = curl_exec($curlSession);
		curl_close($curlSession);
				
		exit('{"success":"true","data":'.$jsonresult.'}');
	}*/
?>
