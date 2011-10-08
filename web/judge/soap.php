<!--
	ideone.com
	API sample

	This script shows how to use ideone api.
-->


<style>
	th {padding: 5px; background-color: #DDDDDD;}
	td {padding: 5px; min-width: 70px; text-align: center;}
	td {border-bottom: 1px solid #DDDDDD;}
</style>

<?php

	// creating soap client
	$client = new SoapClient("http://ideone.com/api/1/service.wsdl");
	// calling test function
	$testArray = $client->testFunction("hoveychen", "sicily");


?>

