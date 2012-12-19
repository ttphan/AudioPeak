<?php
class SQLite extends PDO 
{
    protected $database = null;
    
    public function __construct($db) 
    {	
    	$this->database = '../db/'.$db;
    	parent::__construct('sqlite:'.$this->database);
    	parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    }
       
	public function select_query($query, $singleResult = false) 
	{
		$sth = parent::prepare($query); 

		if(!$sth->execute()) 
		{
			return array(0=>false, 1=>"There was an error in sql syntax."); 			
		}
		if($singleResult == true) 
		{			
			$result = $sth->fetch(PDO::FETCH_ASSOC);
		}
		else 
		{
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		}
		return $result;
	}
	
	public function getDB()
	{
		return $this->database;
	}
}