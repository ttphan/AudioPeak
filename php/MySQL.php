<?php
class MySQL 
{
	private $username = "root";
	private $password = "toor";
	private $dbname = "audiopeak";
	
	function __construct() 
	{
		if(!mysql_connect('localhost', $this->username, $this->password))
			die('Could not connect: ' . mysql_error());
		
		mysql_select_db($this->dbname) or die("Unable to select database '".$this->dbname."'.<br />username: ".$this->username."<br />password: ".$this->password."<br/>MySQL error: ".mysql_error()."");
	}
	
	function __destruct()
	{
		mysql_close();
	}
	
	function getSongs($tempo1 = 0, $tempo2 = PHP_INT_MAX)
	{
		$result = $this->query("SELECT * FROM `tracks` WHERE `tempo` >= ". $tempo1 ." AND `tempo` <=". $tempo2);
		$data = array();
		
		for($i = 0; $i < mysql_num_rows($result); $i++) {
			$data[] = mysql_fetch_array($result, MYSQL_ASSOC);
		}
		
		return $data;
	}
	
	function getSong($id)
	{
		$result = $this->query("SELECT * FROM `tracks` WHERE `id` = '".$id."'");
		return mysql_fetch_array($result, MYSQL_ASSOC);
	}
	
/* 	function getSimilarIds($id) {
		$result = $this->query("SELECT `target` FROM `similars_src` WHERE `tid` = '".$id."'");
		$similars = mysql_fetch_array($result,MYSQL_ASSOC);
		var_dump($similars);
		return explode(",", $similars['target']);
	} */
	
	private function query($query) {
		$result = mysql_query($query);
		if(!$result) {
			die("error in query: ".$query);
		}
		return $result;
	}
}
?>