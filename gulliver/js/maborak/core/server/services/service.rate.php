<?php
class Service_Rate
{
	private $options;
	public  $db;
	function __construct($options = null)
	{
		$this->options = $options;
		
	}
	public function get()
	{
		if (!$this->exists()) {
			$this->create();
		}
		$q="SELECT * FROM rate WHERE SERVICE='".$this->options->data->id."' LIMIT 1";
		$a=mysql_query($q,$this->db) or die(mysql_error());
		$r=mysql_fetch_array($a);
		$r['e']=$this->valid();
		return $r;
		//return "pidiendo ID:".$this->options->id;
	}
	public function valid()
	{
		return $_SERVER['REMOTE_ADDR'];
	}
	public function exists()
	{
		$q="SELECT * FROM rate WHERE SERVICE='".$this->options->data->id."'";
		//echo $q;
		$a=mysql_query($q,$this->db) or die(mysql_error());
		//echo "Verificando: ".$this->options->data->id;
		$n = mysql_num_rows($a);
		//echo $n;
		return ($n>0)?true:false;
	}
	public function create()
	{
		$q="INSERT INTO rate (`UID`, `USERS`, `RATE`, `SERVICE`) VALUES (NULL, '0', '0', '{$this->options->data->id}')";
		$w = mysql_query($q,$this->db) or die(mysql_error());
	}
	public function set()
	{
		//return "poniendo valor:".$this->options->data->value." al ID:".$this->options->data->id;
		$a=$this->get();
		//(promedio+usuarios)+actual/usuarios+actual
		//$r=round(((($a['RATE']*$a['USERS'])+$this->options->data->value)/($a['USERS']+1)),2);
		$r=round(((($a['RATE']*$a['USERS'])+$this->options->data->value)/($a['USERS']+1)),1);
		/*$r=(($r%2)>0.5)?($r+1):($r-1);
		$r=($r<0)?0:$r;
		$r=($r>10)?10:$r;*/
		//echo (int)round(((($a['RATE']*$a['USERS'])+$this->options->data->value)/($a['USERS']+1)),1);
		$sql = "UPDATE rate SET `USERS` = '".($a['USERS']+1)."', `RATE` = '{$r}' WHERE SERVICE = '{$this->options->data->id}' LIMIT 1";
		mysql_query($sql) or die($sql);
		return $this->get();
	}
	function __call($n,$a)
	{
		return isset($this->n)?$this->$n($a):"Invalid action";
	}
}
?>