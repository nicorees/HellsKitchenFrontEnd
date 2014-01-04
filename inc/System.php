<?php

//require system constants
require_once("inc/constants.php");

//require Session class
require_once("inc/Session.php");

class System {

	// define helpers, they will be automatically required
	private $helpers = array("CSS", "JS", "Menubuilder");

	private $pages_unauth = array(
			"register",
			"login",
	);

	private $pages_auth = array(
			"welcome",
			"edit",
			"editData",
			"editEMail",
			"editPassword",
			"displayAllProducts",
			"configurePizza",
			"logout",
			"generateProduct",
			"displayPrivateProducts",	
			"newAddress",
			"chooseStatus",
			"cart"
	);

	private $controllers = array(
			"cartRemove",
			"cartAdd",
			"generateProduct",
			"changeProductVisibility",
			"rateProduct"
	);

	public function __construct() {
		Session::init();
		
		$this->load_helpers();
		$this->register_autoloaders();
		
		if (isset($_GET['c']) && !isset($_GET['p']) && isset($_SESSION['customerID']))
			$this->process();
		else
			$this->display();
	}

	//autoload function for models
	private static function modelAutoloader($class) {
		if (file_exists(DOC_ROOT . "model/" . $class . ".php")) {
			include(DOC_ROOT . "model/" . $class . ".php");
		}
	}

	//autoload function for controllers
	private static function controllerAutoloader($class) {
		if (file_exists(DOC_ROOT . "controller/" . $class . ".php")) {
			include(DOC_ROOT . "controller/" . $class . ".php");
		}
	}
	
	//register autoload function(s)
	private function register_autoloaders() {
		spl_autoload_register("self::modelAutoloader");
		spl_autoload_register("self::controllerAutoloader");
	}

	// load helpers
	private function load_helpers() {
		if ( !empty($this->helpers) ) {
			foreach ($this->helpers as $h) {
				if (file_exists(DOC_ROOT . "helpers/$h.php")) {
					require_once(DOC_ROOT . "helpers/$h.php");
				}
			}
		}
	}

	private function display() {
		
		if(!empty($_GET) && isset($_GET['p'])) {
			
			$page = $_GET['p'];
			
			if( in_array($page, $this->pages_unauth) )
				$this->render($page);
			else if( in_array($page, $this->pages_auth) && isset($_SESSION['customerID']))
				$this->render($page);
			else if (!in_array($page, $this->pages_auth) && 
					 !in_array($page, $this->pages_unauth) && 
					 isset($_SESSION['customerID']))
				$this->render("welcome");
			else
				$this->render("login");
		}
		else {
			if(isset($_SESSION['customerID']))
				$this->render("welcome");
			else
				$this->render("login");
		}
	}

	private function process() {
		$controller = $_GET['c'];

		if(in_array($controller, $this->controllers))
			require_once( DOC_ROOT . "controller/$controller.php" );
		else
			$this->render("welcome");
	}

	private function render($content) {
		require_once( DOC_ROOT . "templates/header.php" );
		require_once( DOC_ROOT . "view/$content.php" );
		require_once( DOC_ROOT . "templates/footer.php" );
	}
}

$system = new System();

// end of System.php