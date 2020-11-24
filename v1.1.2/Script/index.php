<?php
require_once realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'bootstrap.php';

$console_log = array();
$url_action   = (empty(route(1))) ? 'index' : str_replace('-', '', route(1));
$public_pages = array(
	'login',
	'register',
	'about',
	'contact',
	'forgot',
	'mail-otp',
	'privacy',
	'terms',
	'reset',
	'profile',
	'aj',
	'worker'
);


$config->nextmode_text = __('Night mode');
$config->is_rtl = false;
if($_SESSION['activeLang'] == 'arabic'){
    $config->is_rtl = true;
}
// night mode
if (empty($_COOKIE['mode'])) {

    if (!empty($config->displaymode)) {
        if ($config->displaymode == 'day') {
            setcookie("mode", 'day', time() + (10 * 365 * 24 * 60 * 60), '/');
            $_COOKIE['mode'] = 'day';
            $config->displaymode = 'day';
            $config->nextmode = 'night';
            $config->nextmode_text = __('Night mode');
        } else if ($config->displaymode == 'night') {
            setcookie("mode", 'night', time() + (10 * 365 * 24 * 60 * 60), '/');
            $_COOKIE['mode'] = 'night';
            $config->displaymode = 'night';
            $config->nextmode = 'day';
            $config->nextmode_text = __('Day mode');
        }
    }

} else {
    if ($_COOKIE['mode'] == 'day') {
        $config->displaymode = 'day';
        $config->nextmode = 'night';
        $config->nextmode_text = __('Night mode');
    }
    if ($_COOKIE['mode'] == 'night') {
        $config->displaymode = 'night';
        $config->nextmode = 'day';
        $config->nextmode_text = __('Day mode');
    }
}

if (!empty($_GET['mode'])) {
    if ($_GET['mode'] == 'day') {
        setcookie("mode", 'day', time() + (10 * 365 * 24 * 60 * 60), '/');
        $_COOKIE['mode'] = 'day';
        $config->displaymode = 'day';
        $config->nextmode = 'night';
        $config->nextmode_text = __('Night mode');
    } else if ($_GET['mode'] == 'night') {
        setcookie("mode", 'night', time() + (10 * 365 * 24 * 60 * 60), '/');
        $_COOKIE['mode'] = 'night';
        $config->displaymode = 'night';
        $config->nextmode = 'day';
        $config->nextmode_text = __('Day mode');
    }
}

if(IS_LOGGED === true){

}else{
    logout(false);
}
if( !isset( $_COOKIE['JWT'] ) ) {
    logout(false);
}else{
    $u = $db->objectBuilder()->where('id',GetUserFromSessionID($_COOKIE['JWT']))->get('users',1,array('web_token','start_up','active','web_token_created_at','verified'));
    if( !empty($u)) {
        $_SESSION['JWT'] = $u[0];
    }
    $_SESSION['user_id'] = $_COOKIE['JWT'];
}

if( isset($_COOKIE['JWT']) && isset( $_SESSION['user_id'] ) ){
    if( $_COOKIE['JWT'] !== $_SESSION['user_id'] ){
        logout();
    }
}

$maintenance_mode = false;
if ( $config->maintenance_mode == 1 ) {
    if ( IS_LOGGED === false ) {
        $maintenance_mode = true;
        //http://localhost/quickdatescript.com/?access=admin
        if(isset($_GET['access']) && $_GET['access'] == 'admin'){
            $maintenance_mode = false;
        }
    } else {
        if(auth()) {
            if (auth()->admin === "0") {
                $maintenance_mode = true;
            }
        }
    }
    if( $maintenance_mode === true ){
        $maintenance_contoller_file       = $_CONTROLLERS . 'maintenance.php';
        if (file_exists($maintenance_contoller_file)) {
            require_once $maintenance_contoller_file;
            Maintenance::show();
            exit();
        }

    }
}

$contoller_file       = $_CONTROLLERS . strtolower($url_action) . '.php';
if (file_exists($contoller_file)) {
    require_once $contoller_file;
}

if (IS_LOGGED === true) {
    if ($url_action == 'register' || $url_action == 'forgot' || $url_action == 'reset') {
        header("Location: ./");
        exit();
    }
}

$contoller_index_file = $_CONTROLLERS . 'findmatches.php';

if (isset($_SESSION['JWT']) && !empty($_SESSION['JWT'])) {


//    var_dump($_SERVER["REQUEST_URI"]);
//    var_dump(strpos($_SERVER["REQUEST_URI"],'/aj/') === false);
    if(strpos($_SERVER["REQUEST_URI"],'/aj/') === false) {
        if (auth()->start_up == "0" || auth()->start_up == "1" ) {
            $contoller_userverify_file = $_CONTROLLERS . 'steps.php';
            if (file_exists($contoller_userverify_file)) {
                if (!class_exists('Steps', false)) {
                    require_once $contoller_userverify_file;
                }
                Steps::show();
                exit();
            }
        }
    }


    if (auth()->verified == 0) {
        if (route(1) == 'verifymail' || route(1) == 'verifymailotp' || route(1) == 'verifyphone' || route(1) == 'verifyphoneotp' || route(1) == 'aj') {
            call_user_func(array(
                ucfirst($url_action),
                'show'
            ));
        } else {
            if( $config->emailValidation == "1" ) {
                $contoller_userverify_file = $_CONTROLLERS . 'steps.php';
                if (file_exists($contoller_userverify_file)) {
                    if (!class_exists('Steps', false)) {
                        require_once $contoller_userverify_file;
                    }
                    Steps::show();
                }
            }
        }
    }

    if (route(1) == '') {
        $contoller_index_file = $_CONTROLLERS . 'findmatches.php';
        if (file_exists($contoller_index_file)) {
            if( !class_exists('FindMatches',false) ){
                require_once $contoller_index_file;
            }
            FindMatches::show();
        }
    } else {

        if( substr($url_action, 0, 1) == '@' ){
            $username = strtolower(substr($url_action, 1));
            if( strtolower(auth()->username) == $username ){
                $contoller_myprofile_file = $_CONTROLLERS . 'myprofile.php';
                if (file_exists($contoller_myprofile_file)) {
                    if( !class_exists('Myprofile',false) ){
                        require_once $contoller_myprofile_file;
                    }
                    Myprofile::show();
                }
            }else{
                $_user = LoadEndPointResource('users');
                $user = $_user->isUsernameExists($username);
                if( isset( $user['id'] ) && $user['id'] > 0 ){

                    if(isUserInBlockList(strtolower($username)) === false) {
                        $contoller_profile_file = $_CONTROLLERS . 'profile.php';
                        if (file_exists($contoller_profile_file)) {
                            if( !class_exists('Profile',false) ){
                                require_once $contoller_profile_file;
                            }
                            Profile::show();
                        }
                    }else{

                        if(strpos($_SERVER["REQUEST_URI"],'/aj/') === false) {
                            echo "<script>window.location.href = window.site_url;</script>";
                        }else{
                            header('Location: ' . $config->uri);
                        }
                        exit();
                    }

                }else{
                    header('Location: ' . $config->uri);
                    exit();
                }
            }

            exit();
        }
        if (ctype_alpha($url_action)) {
            if (is_callable(array(
                ucfirst($url_action),
                'show'
            ))) {
                call_user_func(array(
                    ucfirst($url_action),
                    'show'
                ));
            } else {
                header('Location: ' . $config->uri);
                exit();
            }
        } else {
            header('Location: ' . $config->uri);
            exit();
        }
    }

}else{

    if (ctype_alpha($url_action)) {
		if (in_array(route(1), $public_pages)) {
			if (is_callable(array(
				ucfirst($url_action),
				'show'
			))) {
				call_user_func(array(
					ucfirst($url_action),
					'show'
				));
			} else {
				header('Location: ' . $config->uri);
				exit();
			}
		} else {
            $contoller_home_file  = $_CONTROLLERS . 'login.php';
            if (file_exists($contoller_home_file)) {
				if( !class_exists('Login',false) ){
                    require_once $contoller_home_file;
				}
                Login::show();
            }
		}
	} else {
		header('Location: ' . $config->uri);
		exit();
	}
}


$config = null;
$lang = null;
mysqli_close($conn);
$db = null;
$conn = null;