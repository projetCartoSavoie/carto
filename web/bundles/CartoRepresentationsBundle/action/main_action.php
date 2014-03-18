<?php
	$curlSession = curl_init();
		curl_setopt($curlSession, CURLOPT_URL, 'http://carto.localhost/bundles/CartoRepresentationsBundle/json/exemple.json');
		curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

		$jsonresult = curl_exec($curlSession);
		curl_close($curlSession);

	echo 'ça a marché';
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
