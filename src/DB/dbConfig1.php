<?php
namespace Esmi\DB;
//require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Dotenv\Dotenv;

include_once('dbDrivers.php');

class dbConfig1 {

    private $drivers;
    protected $default;
    public $capsule;

    function __construct($machine=null, $driver='default') {
		if ($machine === null) {
			//$dotenv = new Dotenv\Dotenv(__DIR__);
			$dotenv = new Dotenv($_SERVER['DOCUMENT_ROOT']);
			$dotenv->load();

			$machine = getenv('machine');
		}
		if ($machine === null) {
			$machine = 'default';
		}

        $this->drivers = dbDrivers($machine);
        $this->default=$this->drivers[$driver];

        $this->setTimezone();
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
