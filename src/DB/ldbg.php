<?php
class __dbg {
	protected $isdebug = true;
	protected $isforceShow = false;
	protected $lf = "\r\n";
	
	function __construct($flag)	{	$this->isdebug = $flag;	}
	function isdbg() {
		return $this->isdebug;
	}
	function enable($flag=true) {
		$this->isdebug = $flag;
	}
	function disable() {
		$this->enable(false);
	}
	function crlf($s) 			{	$this->lf = $s;	}
	function setforceShow($f) 	{	$this -> isforceShow = $f; }
	
	function show( $s, $flag=false) {
		if ($this->isforceShow) {	echo $s . $this->lf;	}
		else {
			if ( $flag )
				echo $s . $this->lf;
		}
	}
	function echod( $s ){
		if ($this->isdebug)		{	echo $s . $this->lf;	}
	}
	function echod2( $s ){
		if ($this->isdebug)		{	echo $s ;		}
	}
	function var_dump($v) {
		if ($this->isdebug)		{	var_dump($v) ;		}
	}

}

