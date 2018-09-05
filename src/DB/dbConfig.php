<?php
namespace Esmi\DB;
// 2018/05/29:
//  support for :
//      __construct(): add() $elpoquent = false)
//      $this->isEloquent() .

//require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Dotenv\Dotenv;

if (file_exists("dbDrivers.php"))
    include_once('dbDrivers.php');
else {
    if(file_exists("../dbDrivers.php"))
        include_once('../dbDrivers.php');
}

class dbConfig {

    private $drivers;
    protected $default;
    public $capsule;
    private $eloquent = false;

    function __construct($path="", $machine=null, $driver='default', $eloquent = true) {
		if ($machine === null) {
			//$dotenv = new Dotenv\Dotenv(__DIR__);
            if ( $path == "") {
                $dotenv = new Dotenv($_SERVER['DOCUMENT_ROOT']);
            }
            else {
                $dotenv = new Dotenv($path);
            }
			$dotenv->load();

			$machine = getenv('machine');
		}
		if ($machine === null) {
			$machine = 'default';
		}

        $this->eloquent = $eloquent;
        $this->drivers = dbDrivers($machine);
        $this->default=$this->drivers[$driver];

        $this->setTimezone();

        if ($this->eloquent) {
            $this->capsule = new Capsule;
            $this->capsule->addConnection($this->driver($driver));

            $this->capsule->setEventDispatcher(new Dispatcher(new Container));

            // Set the cache manager instance used by connections... (optional)
            // $capsule->setCacheManager(...);

            // Make this Capsule instance available globally via static methods... (optional)
            $this->capsule->setAsGlobal();

            // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
            $this->capsule->bootEloquent();

        }
    }
    function isEloquent() {
        return $this->eloquent;
    }

    function driver($driver='default') {
        if ( isset($this->drivers[$driver]))
            return $this->drivers[$driver];
        else return NULL;
    }
    function setdriver($driver = 'default') {
        if ( isset($this->drivers[$driver])){
            $this->default = $this->driver[$driver];

            return $this->drivers[$driver];
        }
        else return NULL;
    }
    function getdriver() {
        return $this->default;
    }
    function setTimezone($tz='Asia/Taipei') {
        date_default_timezone_set ( $tz );
    }
}
