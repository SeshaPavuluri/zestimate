<?php
	
	$addressText      = $_REQUEST['addressText'];
	$addressCityState = $_REQUEST['addressCityState'];
	$addressText      = urlencode($addressText);
	$addressCityState = urlencode($addressCityState);
	$zwsID			  = "X1-ZWz1f58tx1wx6z_86zet"; //replace this key accordingly with your own
	$zillowBaseURL    = "http://www.zillow.com/webservice/GetDeepSearchResults.htm?";
	
	$context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
	//$url = 'http://www.zillow.com/webservice/GetDeepSearchResults.htm?zws-id=X1-ZWz1f58tx1wx6z_86zet&address=2114+Bigelow+Ave&citystatezip=Seattle%2C+WA';
	$url = $zillowBaseURL . "zws-id=" . $zwsID . "&address=". $addressText. "&citystatezip=" .$addressCityState."";
	
	$xml = file_get_contents($url, false, $context);
	//echo $xml;
	$xml = simplexml_load_string($xml); 
	
	if(strstr($xml->message->text,"Error"))
		echo $xml->message->text;
	else{ //using $$ as a delimiter on the client side
		$resultStr  = "Zestimate:". $xml->response->results->result->zestimate->amount . "$$"; //0
		$resultStr .= "TYPE:". $xml->response->results->result->useCode. "$$"; //1
		$resultStr .= "Year Built: ". $xml->response->results->result->yearBuilt. "$$"; //2
		$resultStr .= "Finished SqFt: ". $xml->response->results->result->finishedSqFt. "$$"; //3
		$resultStr .= "Baths##".$xml->response->results->result->bathrooms. "$$"; //4
		$resultStr .= "Beds##".$xml->response->results->result->bedrooms. "$$"; //5
		$resultStr .= "Last Updated: ". $xml->response->results->result->zestimate->{'last-updated'}. "$$"; //6
		$resultStr .= "Map This Home##". $xml->response->results->result->mapthishome. "$$"; //7
		$resultStr .= "Tax Assessment Year: ". $xml->response->results->result->taxAssessmentYear. "$$"; //8
		$resultStr .= "Lot Size SqFt: ". $xml->response->results->result->lotSizeSqFt. "$$"; //9
		$resultStr .= "Last Sold Date: ". $xml->response->results->result->lastSoldDate. "$$"; //10
		$resultStr .= "Last Sold Price:". $xml->response->results->result->lastSoldPrice; //11
		
		echo($resultStr); //prints out home details using xml structure
	}
?>