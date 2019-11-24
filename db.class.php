<?php  

class db {
	// establish a database connection to your Oracle database.
	private $servername = 'talsprddb01.int.its.rmit.edu.au'; //host name
	private $servicename = 'CSAMPR1.ITS.RMIT.EDU.AU'; //database name
	private $username = 's3653373'; //database username
	private $password = 'Gupt123#'; //database password

	public function connect_orcale() {
		//create the connection
		$connection = $this -> servername."/".$this -> servicename;
		$conn = oci_connect($this -> username, $this -> password, $connection);

		//check for connection error
		if (!$conn) {
			$e = oci_error();
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}
		else
			return $conn;
	}
}

?>