<?php
class MySQL 
{
	private $username = "root";
	private $password = "toor";
	private $dbname = "audiopeak";
	private $conn;
	
	function __construct() 
	{
		$this->conn = mysql_connect('localhost', $this->username, $this->password);
		if(!conn)
			die('Could not connect: ' . mysql_error());
		
		mysql_select_db($this->dbname) or die("Unable to select database '".$this->dbname."'.<br />username: ".$this->username."<br />password: ".$this->password."<br/>MySQL error: ".mysql_error()."");
	}
	
	function getSongs($tempo1 = 0, $tempo2 = PHP_INT_MAX)
	{
		$tempo1 = round($tempo1,0,PHP_ROUND_HALF_DOWN);
		$tempo2 = round($tempo2,0,PHP_ROUND_HALF_UP);
		if($tempo1 > $tempo2) {
			$temp = $tempo1;
			$tempo1 = $tempo2;
			$tempo2 = $temp;
		}
		
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