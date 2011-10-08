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
	$testArray = $client->testFunction("test", "test");
	
	// printing returned values
	echo "<table>\n";
	echo "<tr><th>key</th><th>value</th><th>string</th><th>float</th><th>integer</th><th>bool</th></tr>\n";
	foreach($testArray as $k => $v) {
		echo "<td>" . $k . "</td><td>" . $v
			. "</td><td>" . is_string($v)
			. "</td><td>" . is_float($v)
			. "</td><td>" . is_integer($v)
			. "</td><td>" . is_bool($v)
			. "</td><td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n";
	
?>

