<?php
	if (isset($_POST['search'])){
		$cmd = $_POST['search'];
	}
	else { 
		$cmd = 'exemple'; 
	}

	$curlSession = curl_init();

	curl_setopt($curlSession, CURLOPT_URL, 'http://demo4.itpassion.info/crawler.php?target='.$cmd); //Céline 

	curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

	$jsonresult = curl_exec($curlSession);
	curl_close($curlSession);

	exit('{"success":"true","data":'.$jsonresult.'}');
?>
