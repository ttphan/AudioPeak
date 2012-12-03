<?php 
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('error_reporting', E_ERROR | E_WARNING | E_PARSE);
?>
<!doctype html>

<html>

  <head>
    <title>AudioPeak</title>
    <meta charset="utf-8" />
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>	
    <script src="../js/ajax.js"></script>
  </head>

  <body>
	<h1>AJAX test</h1>
	<input name="getFiller" type="submit" value="Klik mij (getFiller)!" 
		onClick="getFiller()"/>
	<input name="searchStr" id="inputSearchStrId" type="text">
	<input name="searchButton" type="submit" value="Zoek!"
		onclick="search()"/>
  </body>
</html>