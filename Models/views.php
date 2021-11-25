<?php
class Views
{
	public function __construct( Registry $registry )
	{
		$this->registry = $registry;
	}
	
	public function recordView($viewed, $viewer,$type)
	{
		$t=time();
		$date = (date("Y-m-d",$t));
		$checksql = "SELECT ID FROM views WHERE viewer='$viewer' AND viewed='$viewed' AND viewedtype='$type' AND viewdate='$date'";
		$this->registry->getObject('db')->executeQuery($checksql);
		$count = $this->registry->getObject('db')->numRows();
		
		//$count = 0;
		
		if($count<=0)
		{
			
			
			$time =	date("H:i:s");

			
			//$time = gettimeofday();
			//print_r($time);
			$sql = "INSERT INTO views (viewed,viewer,viewedtype,viewtime,viewdate) VALUES ('$viewed','$viewer','$type','$time','$date')";
			$this->registry->getObject('db')->executeQuery($sql);
		}
	}
}
?>