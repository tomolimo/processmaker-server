<?php
class Service_Comments
{
	private $options;
	public  $db;
	function __construct($options = null)
	{
		$this->options = $options;
	}
	public function get()
	{
		$q="SELECT * FROM comments ORDER BY UID DESC LIMIT 100";
		$a=mysql_query($q,$this->db) or die(mysql_error());
		$r=array();
		while ($e=mysql_fetch_array($a)) {
			$r[]=array(
				'name'=>htmlentities($e['NOMBRE']),
				'comment'=>htmlentities($e['COMENTARIO'])
			);
		}
		return $r;
	}
  public function post()
  {
    // The mysql_escape_string function has been DEPRECATED as of PHP 5.3.0.
    // $q="INSERT INTO comments (`UID`, `NOMBRE`, `COMENTARIO`) VALUES (NULL, '".mysql_escape_string($this->options->data->name)."', '".mysql_escape_string($this->options->data->comment)."')";
    $q = "INSERT INTO comments (`UID`, `NOMBRE`, `COMENTARIO`) VALUES (NULL, '" . mysql_real_escape_string($this->options->data->name) . "', '". mysql_real_escape_string($this->options->data->comment). "')";    
    $w = mysql_query($q,$this->db) or die(mysql_error());
  }
	function __call($n,$a)
	{
		return isset($this->n)?$this->$n($a):"Invalid action";
	}
}
?>