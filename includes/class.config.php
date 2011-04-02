<?PHP
    // The Config class provides a single object to store your application's settings.
    // Define your settings as public members. (We've already setup the standard options
    // required for the Database and Auth classes.) Then, assign values to those settings
    // inside the "location" functions. This allows you to have different configuration
    // options depending on the server environment you're running on. Ex: local, staging,
    // and production.


    class Config
    {
        // Singleton object. Leave $me alone.
        private static $me;

        // Add your server hostnames to the appropriate arrays. ($_SERVER['HTTP_HOST'])
        private $productionServers = array('/^site\.com$/');
        private $stagingServers    = array('/^dev.site\.com$/');
        private $localServers      = array('/^(localhost)|(ubuntu)$/');

        // Standard Config Options...

        // ...For Auth Class
        public $authDomain;         // Domain to set for the cookie
        public $authSalt;           // Can be any random string of characters

        // ...For Database Class
        public $dbReadHost;   // Database read-only server
        public $dbWriteHost;  // Database read/write server
        public $dbName;
        public $dbReadUsername;
        public $dbWriteUsername;
        public $dbReadPassword;
        public $dbWritePassword;

        public $dbOnError; // What do do on a database error (see class.database.php for details)
        public $dbEmailOnError; // Email an error report on error?

        // Add your config options here...
        public $useDBSessions; // Set to true to store sessions in the database
        
        public $basedir;

        // Singleton constructor
        private function __construct()
        {
            $this->everywhere();

            $i_am_here = $this->whereAmI();

            if('production' == $i_am_here)
                $this->production();
            elseif('staging' == $i_am_here)
                $this->staging();
            elseif('local' == $i_am_here)
                $this->local();
            elseif('shell' == $i_am_here)
                $this->shell();
            else
                die('<h1>Where am I?</h1> <p>You need to setup your server names in <code>class.config.php</code></p>
                     <p><code>$_SERVER[\'HTTP_HOST\']</code> reported <code>' . $_SERVER['HTTP_HOST'] . '</code></p>');
        }

        // Get Singleton object
        public static function getConfig()
        {
            if(is_null(self::$me))
                self::$me = new Config();
            return self::$me;
        }

        // Allow access to config settings statically.
        // Ex: Config::get('some_value')
        public static function get($key)
        {
            return self::$me->$key;
        }

        // Add code to be run on all servers
        private function everywhere()
        {
            // Store sesions in the database?
            $this->useDBSessions = false;

            // Settings for the Auth class
            $this->authDomain = $_SERVER['HTTP_HOST'];
            $this->authSalt   = '';
        }

        // Add code/variables to be run only on production servers
        private function production()
        {
            ini_set('display_errors', '0');

            define('WEB_ROOT', '/');

            $this->dbReadHost      = 'localhost';
            $this->dbWriteHost     = 'localhost';
            $this->dbName          = 'gotwilly_nc';
            $this->dbReadUsername  = 'gotwilly_nc';
            $this->dbWriteUsername = 'gotwilly_nc';
            $this->dbReadPassword  = 'm3z2aUMHNMCDXhb3';
            $this->dbWritePassword = 'm3z2aUMHNMCDXhb3';
            $this->dbOnError       = '';
            $this->dbEmailOnError  = false;
            $this->useDBSessions = false;
            $this->basedir = "";
        }

        // Add code/variables to be run only on staging servers
        private function staging()
        {
            ini_set('display_errors', '1');
            ini_set('error_reporting', E_ALL);

            define('WEB_ROOT', '');

            $this->dbReadHost      = 'localhost';
            $this->dbWriteHost     = 'localhost';
            $this->dbName          = 'ninja_lacosta';
            $this->dbReadUsername  = 'root';
            $this->dbWriteUsername = 'root';
            $this->dbReadPassword  = 'root';
            $this->dbWritePassword = 'root';
            $this->dbOnError       = 'die';
            $this->dbEmailOnError  = false;
            $this->useDBSessions = true;
            $this->basedir = "";
        }

        // Add code/variables to be run only on local (testing) servers
        private function local()
        {
            ini_set('display_errors', '1');
            ini_set('error_reporting', E_ALL);

            define('WEB_ROOT', '');

            $this->dbReadHost      = 'localhost';
            $this->dbWriteHost     = 'localhost';
            $this->dbName          = 'ninja_lacosta';
            $this->dbReadUsername  = 'root';
            $this->dbWriteUsername = 'root';
            $this->dbReadPassword  = 'root';
            $this->dbWritePassword = 'root';
            $this->dbOnError       = 'die';
            $this->dbEmailOnError  = false;
            $this->useDBSessions = true;
            $this->basedir = "/skel";
        }

        public function whereAmI()
        {
            for($i = 0; $i < count($this->productionServers); $i++)
                if(preg_match($this->productionServers[$i], getenv('HTTP_HOST')) === 1)
                    return 'production';

            for($i = 0; $i < count($this->stagingServers); $i++)
                if(preg_match($this->stagingServers[$i], getenv('HTTP_HOST')) === 1)
                    return 'staging';

            for($i = 0; $i < count($this->localServers); $i++)
                if(preg_match($this->localServers[$i], getenv('HTTP_HOST')) === 1)
                    return 'local';

            if(isset($_ENV['SHELL']))
                return 'shell';

            return false;
        }
    }
