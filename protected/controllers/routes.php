<?php
// Modules Routes
foreach(glob($settings['settings']['basePath'] . '/modules/*/controllers/routes.php') as $mod_routes) {
    require_once $mod_routes;
}

// Extensions routes
foreach(glob($settings['settings']['basePath'] . '/extensions/*/controllers/routes.php') as $ext_routes) {
    require_once $ext_routes;
}

$app->get('/[{name}]', function ($request, $response, $args) {
    
	if (empty($args['name']))
		$args['name'] = 'index';

    $settings = $this->get('settings');
    if (!file_exists($settings['theme']['path'].'/'.$settings['theme']['name'].'/views/'.$args['name'].'.phtml')) {
        return $this->response
            ->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->write('Page not found!');
    }

    $exts = json_decode( $settings['params']['extensions'], true );

    return $this->view->render($response, $args['name'] . '.phtml', [
        'name' => $args['name'],
        'request' => $_GET
    ]);
});

$app->post('/rekomendasi', function ($request, $response, $args) {
    $performa = \ExtensionsModel\BatasanPerformaModel::model()->findByPk( $_POST['batasan_performa'] );
    $biaya = \ExtensionsModel\BiayaModel::model()->findByPk( $_POST['biaya_per_bulan'] );
    $keahlian = \ExtensionsModel\KeahlianUserModel::model()->findByPk( $_POST['keahlian_user'] );
    $pengunjung = \ExtensionsModel\PengunjungModel::model()->findByPk( $_POST['jumlah_pengunjung'] );

    $transformasi_data = \ExtensionsModel\TransformasiDataModel::model()->findAll();
    $bobot = \ExtensionsModel\BobotModel::model()->findAll();

    return $this->view->render($response, 'rekomendasi.phtml', [
        'performa' => $performa,
        'biaya' => $biaya,
        'keahlian' => $keahlian,
        'pengunjung' => $pengunjung,
        'transformasi_data' => $transformasi_data,
        'bobot' => $bobot
    ]);
});