<?php

foreach (glob(__DIR__.'/*_controller.php') as $controller) {
	$cname = basename($controller, '.php');
	if (!empty($cname)) {
		require_once $controller;
	}
}

foreach (glob(__DIR__.'/../components/*.php') as $component) {
    $cname = basename($component, '.php');
    if (!empty($cname)) {
        require_once $component;
    }
}

$app->group('/server-recomendation', function () use ($user) {
    $this->group('/batasan-performa', function() use ($user) {
        new Extensions\Controllers\BatasanPerformaController($this, $user);
    });
    $this->group('/biaya', function() use ($user) {
        new Extensions\Controllers\BiayaController($this, $user);
    });
});

?>
