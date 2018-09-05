<?php
namespace Esmi\DB;

include_once "ldbg.php";

class DB {

	protected $host;
	protected $user;
	protected $pass;
	protected $database;
	protected $CharacterSet;
	protected $ReturnDatesAsStrings;
	protected $driver;

	public $connection;

	private $o;
	private $stat;
	private $connData;

	function __construct($a, $database="") {
		$this->o = new __dbg(true);
		$this->o->setforceShow(true);

		if (!isset(a['user']))
			if (isset(a['username']))
				$a['user'] = $a['username'];

		$this->connData =$a;

		$this->host = $a['host'];
		$this->user = $a['user'];
		$this->pass = $a['password'];				// user password

		$this->database = ($database == "") ? $a['database'] : $this->database = $database;

		$this->driver = ( isset(a['drvier'])) ? $a['driver']: 'sqlsrv';

		$this->characterSet = $a['characterSet'];
		$this->ReturnDatesAsStrings = $a['ReturnDatesAsStrings'];
		$this->connInfo = $this->connectInfo();
		$this->connection = $this->conection_()

		if ( !$this->connection ) {
			//echo "db connection failure....";
			$this->stat  = false;
		}
		else {
			$this->stat = true;
		}
	}
	private function connection_() {
		switch ($this->driver) {
			case 'sqlsrv':
				return sqlsrv_connect($this->host, $this->connectInfo());
				break;
			case 'mysql':
				return mysql_connect( $this->host, $this->user, $this->pass);
				break;
			default:
				return null;
		}

	}
	function link() {
		return $this->connection;
	}
	function status(){
		return $this->stat;
	}
	function error($msg="") {
		if ($msg == "") {
			if ($this->status()) {

				return ["status" => "OK", "message" => "DB($this->database) is ready!"] ;
			}
			else {
				if( ($errors = sqlsrv_errors() ) != null) {
					return ["status" => "error", "message" => $errors];
				}
				else
					return ["status" => "error", "message" =>"DB($this->database) not ready or not connected!"];
			}
		}
		else {
			return ["status" => "error", "message" =>$msg];
		}

	}

	function host($h) { $this->host = $h;}
	function user($u) { $this->user = $u;}
	function pass($p) { $this->pass = $p;}
	function gethost() { return $this->host;}

	function connectData() {
		return $this->connData;
	}
	function connectInfo() {
		//var_dump( $this->database);
		return array(
		  "Database"=>$this->database,
		  "UID"=>$this->user,
		  "PWD"=>$this->pass,
		  "CharacterSet" => $this->characterSet,
		  "ReturnDatesAsStrings" => $this->ReturnDatesAsStrings
		  );
	}
	function geterror() {
		return sqlsrv_errors();
	}
	function query( $s , $connect = null, $params = null , $options = null ) {
		//$dbg = $this->o;
		//$dbg->var_dump($this->connInfo);
		//$dbg->var_dump( $this->conn);
		//$dbg->var_dump( $s );
		$rs = null;
		switch( $this->driver) {
			case 'sqlsrv':
				$rs =  sqlsrv_query($this->connection, $s, $params, $options);
				break;
			case 'mysql':
				$rs =  mysql_query($this->connection, $s, $params, $options, $connection ? $connection:　$this->connection);
				break;
			case 'mysqli':
				$rs =  sqlsrv_query($this->connection, $s, $params, $options, $connection ? $connection:　$this->connection);
				break
			default:

		}
		return $rs;
	}
	private function fetch_array($rs) {
		$r = null;
		switch( $this->driver) {
			case 'sqlsrv':
				$r =  sqlsrv_fetch_array($rs);
				break;
			case 'mysql':
				$r =  mysql_fetch_array($this->connection, $s, $params, $options, $connection ? $connection:　$this->connection);
				break;
			case 'mysqli':
				$r =  mysqli_fetch_array($this->connection, $s, $params, $options, $connection ? $connection:　$this->connection);
				break
			default:
		}
		return $r;

	}

	function fetch( $rs ) {
		//var_dump($rs);
		return $this->fetch_array($rs);
	}

	function fetchArray( $rs ){

		if ($rs){
			$rows = array();
			while($row = $this->fetch_array($rs)){
				array_push($rows, $row);
			}
			return $rows;
		}
		else {
			return null;
		}
	}
	function getRows($rs) {
	    return $this->fetchArray($rs);
	}
}


?>
