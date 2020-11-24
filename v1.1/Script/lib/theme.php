<?php
class Theme {
	protected static $instance = null;
	public $activeUser = null;
	public static $data = array('name' => 'index', 'body_id' => 'white', 'title' => '');
	public static $headers = array();
	public static $footers = array('footer/default');
	public static $css = array('materialize' => 'assets/css/materialize.min.css',
        'plugins' => 'assets/css/plugins.css', 'style' => 'assets/css/style.css', 'overrides' => 'assets/css/overrides.css');
	public static $js = array( 'materialize' => 'assets/js/materialize.min.js',
        'script' => 'assets/js/script.js', 'plugins' => 'assets/js/plugins.js');
	public function __construct() {
		if (isset($_SESSION['JWT']) && !empty($_SESSION['JWT'])) {
			$this->activeUser = auth();
		}
	}
	public static function ActiveUser() {
		return self::getInstance()->activeUser;
	}
    public static function getInstance() {
        if (!isset(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }
    public static function Config() {
        global $config;
        return $config;
    }
	public static function init_data() {
		global $config;
		self::$data['title']       = $config->default_title;
		self::$data['cssfiles']   = self::$css;
		self::$data['jsfiles']   = self::$js;
    }
    public static function show($partial) {
	    global $public_pages;
		if (isset($_POST['ajax']) && $_POST['ajax'] == true) {
			echo '<span class="page_title hide">' . self::$data['title'] .'</span>';
            echo '<span class="page_name hide">' . self::$data['name'] .'</span>';
            if( !in_array(self::$data['name'],$public_pages) ) {
                echo '<span class="is_user_logged_in hide">' . IS_LOGGED . '</span>';
            }else{
                echo '<span class="is_user_logged_in hide">1</span>';
            }
		}
		render($partial,self::$data);
		exit();
    }
}
Class Emails extends Theme {
	public static function parse($template, $passed_data = array()) {
		global $config,$_BASEPATH, $lang,$_DS;
		$theme_path = $_BASEPATH . 'themes' . $_DS . $config->theme . $_DS;
		$file_path = $theme_path . 'emails' . $_DS . str_replace( $_DS , '/', $template ) . '.php';
		if (!file_exists($file_path)) {
			return false;
		} else {
			$data = (object) self::$data;
			if (!empty($passed_data)) {
				foreach ($passed_data as $key => $value) {
					if (is_array($value)) {
						${$key} = (object) $value;
					} else {
						${$key} = $value;
					}
				}
			}
			ob_start();
			require $file_path;
			$result = ob_get_contents();
			ob_clean();
			return $result;
		}
	}
}
