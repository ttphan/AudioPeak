<?php
class MySQL 
{
	private $username = "root";
	private $password = "csrulez";
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
		
		$meanTempo = ($tempo1 + $tempo2) / 2;
		$tempo1 = $meanTempo - 1;
		$tempo2 = $meanTempo + 1;
		
		$result = $this->query("SELECT * FROM `tracks` WHERE `tempo` >= ". $tempo1 ." AND `tempo` <=". $tempo2);
		$data = array();
		for($i = 0; $i < mysql_num_rows($result); $i++) {
			$data[] = mysql_fetch_array($result, MYSQL_ASSOC);
		}
		
		return $data;
	}
	
	function getSong($tid)
	{
		$result = $this->query("SELECT * FROM `tracks` WHERE `tid` = '".$tid."'");
		return mysql_fetch_array($result, MYSQL_ASSOC);
	}
	
	function getSimilar($tid)
	{
		$query = "SELECT * FROM `similars_src` WHERE `tid` = '".$tid."'";
		$result = $this->query($query);

		$rawParts = explode(',',mysql_result($result, 0, 'target'));
		$res = array();
		for($i = 0; $i < sizeof($rawParts); $i = $i+2) {
			$res[$rawParts[$i]] = floatval($rawParts[$i+1]);
		}
		return $res;
	}
	
	public function exists($tid) 
	{
		return is_array($this->getSong($tid));
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
	
	public function getTags($tid, $getTopTag = false)
	{		
		$trackmeta = $this->getSong($tid);

		// Filters out the artist-tags and low-value tags.
		$query = "SELECT `tags`.`tag`
				FROM `tags`, `tid_tag`, `tids`
				WHERE `tids`.`tid` = '".$tid."'
				AND `tids`.`id` = `tid_tag`.`tid`
				AND `tags`.`id` = `tid_tag`.`tag`
				AND `tid_tag`.`val` > 5
				AND `tags`.`tag` != '".mysql_real_escape_string($trackmeta['artist_name'])."'
				AND `tags`.`tag` != '".mysql_real_escape_string($trackmeta['title'])."'";
		
		// 1 tag
		if($getTopTag === true) {
			$query = $query.' LIMIT 1';
			$tag = $this->query($query);			
			
			return mysql_fetch_array($tag, MYSQL_ASSOC);
		}

		// All tags
		$tags = $this->query($query);
		
		for($i = 0; $i < mysql_num_rows($tags); $i++) {
			$temp = mysql_fetch_array($tags, MYSQL_ASSOC);
			$res[] = $temp['tag'];
		}
		
		return $res;
	}
}
?>