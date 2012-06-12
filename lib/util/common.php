<?php

function myflush (){
    echo(str_repeat(' ',256));
    // check that buffer is actually set before flushing
    if (ob_get_length()){           
        ob_flush();
        flush();
        ob_end_flush();
    }   
    ob_start();
}

function curPageURL() {
	 $pageURL = 'http';
	 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 return addcslashes($pageURL, '\'');
}
function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}


function array_remove($array, $toRemove){
	$key = array_search($toRemove, $array);
	$newArray = $array;
	unset($newArray[$key]);
	
	return $newArray;
}


function downloadFile($http_source, $local_destination)
 	{	
 		$ch = curl_init($http_source);
		$fp = fopen($local_destination, "w");
		if (!$fp)
			ERROR::writeln('problem opening file: '.$local_destination);
			
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		
		curl_exec($ch);
		curl_close($ch);
		
		@fclose($fp);
 	} 
 	
function retrieveURLContents($http_source){
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, $http_source);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	$result = curl_exec($ch);
	curl_close($ch);
	
	if($result === false){
		ERROR::writeln('problem retrieving URL: '.$http_source);
	}
	return $result;
}

function POSTcURL($http_source, $paramsArr){
	$ch = curl_init();
	DEBUG::writeln("url: $http_source");
	DEBUG::labelled_var_dump('params: ',$paramsArr);
	
	curl_setopt($ch, CURLOPT_URL, $http_source);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $paramsArr);
	
	
	$result = curl_exec($ch);
	DEBUG::labelled_var_dump("result: ", $result);
	curl_close($ch);
	
	if($result === false){
		$idx=0;
		
		while($idx < 10 && $result === false){
			echo "<br />cURL failed. trying again attempt " .($idx+2);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $http_source);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $paramsArr);
	 
			$result = curl_exec($ch);
			curl_close($ch);
			$idx++;
		}
		if($idx >= 10 && $result === false){
			ERROR::writeln('problem posting to URL: '.$http_source);
		}
	}
	return $result;
}
 	
function array_concat($arr1, $arr2){
	$retArr = $arr1;
	foreach ($arr2 as $item){
		$retArr[] = $item;
	}
	return $retArr;
}


/**
 * works for numerically indexed arrays too
 * @param unknown_type $arr1
 * @param unknown_type $arr2
 */
function recursive_array_eq_assoc($arr1, $arr2){
	
	if(empty($arr1) && empty($arr2)){//walked the whole thing. we're good.
		return true;
	}else{
		//check all top level keys
		$k1 = array_keys($arr1);
		$k2 = array_keys($arr2);
		$kdiff = array_diff($k1, $k2);
		if(!empty($kdiff)) return false;
		
		//shift off the first values
		$item1 = array_shift($arr1);
		$item2 = array_shift($arr2);
		if(is_array($item1) && is_array($item2)){//if they are arrays, check them
			$res = recursive_array_eq_assoc($item1, $item2);
			if($res) return recursive_array_eq_assoc($arr1, $arr2);//if subarrays were good, then continue
			else return false;
		}else{
			if($item1 == $item2) return recursive_array_eq_assoc($arr1, $arr2);
			else return false;
		}
	}
}

function array_exclude_keys($arr, $exclusion_set){
	foreach($arr as $key=>$attribute){
		if(in_array($key, $exclusion_set)){
			unset($arr[$key]);
		}
	}
	return $arr;
}

/**
 * A safe version of extract that will only extract the variables you specify. 
 * 
 * You are also given the option to pass in a function that cleans or pre-processes
 * the inputs before they are stored. 
 * 
 * @param unknown_type $allowed an array of variable names that you want to extract
 * @param unknown_type $from an array of key=>values where the keys are the variable names you want to extract (like $_GET/$_POST)
 * @param unknown_type $func an optional function that will be run on every input. 
 * 
 * @author kenstclair
 */
function extract_only($allowed, $from, $func=null){
	DEBUG::rollcall();
	if($func == null){ //create a passthrough function if none provided
		$func = function($r) {return $r;};
	}
	foreach($allowed as $var){
		global $$var;
		$$var = $func($from[$var]);
		DEBUG::lvar_dump("extracting \$$var: ", $$var);
	}
}
?>