<?php
namespace Esmi\DB;

class TABLE{

	private $o;
	protected $table;
	protected $db;

	function __construct($t,$db) {

		//parent::__construct($connect);
		$this->db = $db;
		$this->o = new __dbg(true);
		$this->o->setforceShow(true);
		$this->table = $t;
	}
	function settable($t) {
		$this->table = $t;
	}
	function gettable() {
		return $this->table;
	}
	function query($stmt) {

	    return $this->db->query($stmt);
	}
	function fetchArray($rs) {
	    return $this->db->fetchArray($rs);
	}
	function getTotal( $rs) {
		if ( $rs ) {
			$row = $this->db->fetch($rs);

			return $row[0];
		}
		else {
			return 0;
		}

	}
	function getRows( $rs) {
	    return $this->fetchArray($rs);
	}
	function total(){

		$stmt = "select count(*) from $this->table ";
		$rs = $this->query($stmt);
		return $this->getTotal($rs);
	}
	function  rows( $after_stmt = ""){

		$stmt = "select * from $this->table ";
		if ( $after_stmt != "") {
			$stmt = $stmt . $after_stmt;
		}
		$rs2 = $this->query( $stmt );
		$rows = $this->getRows($rs2);

		return $rows;
	}
	function getError() {
	    return sqlsrv_errors();
	}
	function getfields() {

        $stmt = "SELECT * FROM $this->table";
        $rs = sqlsrv_prepare( $this->db->connection, $stmt );

        foreach( sqlsrv_field_metadata( $rs ) as $fieldMetadata ) {
            foreach( $fieldMetadata as $name => $value) {
               echo "$name: $value<br />";
            }
              echo "<br />";
        }

	}
}
