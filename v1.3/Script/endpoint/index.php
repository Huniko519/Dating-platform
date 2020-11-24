<?php
header('Content-Type: application/json; charset=UTF-8');
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';
$_allowResources      = array(
	'users',
	'options',
	'messages',
    'notifications',
    'blogs',
    'stories'
);
$_allowRequestMethods = array(
	'GET',
	'POST',
	'PUT',
	'DELETE',
	'PATCH'
);
$_statusCodes         = array(
	200 => 'OK',
	204 => 'No Content',
	400 => 'Bad Request',
	401 => 'Unauthorized',
	403 => 'Forbidden',
	404 => 'Not Found',
	405 => 'Method Not Allowed',
    500 => 'Server Internet Error',
    503 => 'Service Unavailable'
);
$_version          = route(1);
$_key              = route(2);
$_id               = route(4);
$_resourceName     = route(3);
$_sub_resourceName = route(5);
$_sub_resourceID   = route(6);
try {
	$_requestMethod = $_SERVER['REQUEST_METHOD'];
	if (!in_array($_requestMethod, $_allowRequestMethods)) {
		json(array(
			'message' => 'Method Not Allowed',
			'code' => 405
		), 405);
	}
	if ($_version == null) {
		json(array(
			'message' => 'Wrong endpoint version',
			'code' => 405
		), 405);
	}
	if ($_key == null) {
		json(array(
			'message' => 'Wrong endpoint key',
			'code' => 405
		), 405);
	}
	if ($_resourceName == null) {
		json(array(
			'message' => 'Wrong endpoint method',
			'code' => 405
		), 405);
	}
	if (!in_array($_resourceName, $_allowResources)) {
		json(array(
			'message' => 'Unauthorized resources access',
			'code' => 401
		), 401);
	}
	require($_ENDPOINT_PATH . 'hooks' . $_DS . 'auth.php');
	$_resourceFile = $_ENDPOINT_PATH . 'models' . $_DS . $_resourceName . '.php';
	if (file_exists($_resourceFile)) {
		require($_resourceFile);
		$resource = new $_resourceName();
	} else {
		json(array(
			'message' => 'Resources file not found',
			'code' => 503
		), 503);
	}
} catch (Exception $e) {
	json(array(
		'message' => $e->getMessage(),
		'code' => 405
	), 405);
}

$dataset = null;
$config = null;
$lang = null;
mysqli_close($conn);
$db = null;
