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

/**
 * Post format :
 * array(5) {
["batasan_performa"]=>string(1) "2"
["jumlah_pengunjung"]=>string(4) "1500"
["biaya_per_bulan"]=>string(5) "10000"
["keahlian_user"]=>string(1) "8"
["pertimbangan"]=>array(4) {
["batasan_performa"]=>string(1) "3"
["pengunjung"]=>string(1) "3"
["biaya"]=>string(1) "3"
["keahlian_teknis"]=>string(1) "3"
}
}
 */
$app->post('/rekomendasi', function ($request, $response, $args) {
    $performa = [
        'nilai_maksimal' => $_POST['batasan_performa']/10
    ];
    if ((int)$_POST['batasan_performa'] <= 1) {
        $performa['kriteria'] = 'Terbatas';
    } elseif ((int)$_POST['batasan_performa'] > 2 && (int)$_POST['batasan_performa'] < 3) {
        $performa['kriteria'] = 'Sedang';
    } else {
        $performa['kriteria'] = 'Tidak terbatas';
    }

    if ((int)$_POST['biaya_per_bulan'] <= 100000) {
        $biaya = [
            'kriteria' => '<= 100.000',
            'bil_fuzzy' => 'Murah',
            'nilai_minimal' => 0.1,
            'nilai_maksimal' => 0.3,
            'biaya_per_bulan' => $_POST['biaya_per_bulan']
        ];
    } elseif ((int)$_POST['biaya_per_bulan'] > 100000 && (int)$_POST['biaya_per_bulan'] <= 499999) {
        $biaya = [
            'kriteria' => '100.001 - 499.000',
            'bil_fuzzy' => 'Sedang',
            'nilai_minimal' => 0.3,
            'nilai_maksimal' => 0.8,
            'biaya_per_bulan' => $_POST['biaya_per_bulan']
        ];
    } else {
        $biaya = [
            'kriteria' => '> 500.000',
            'bil_fuzzy' => 'Mahal',
            'nilai_minimal' => 0.9,
            'nilai_maksimal' => 0.9,
            'biaya_per_bulan' => $_POST['biaya_per_bulan']
        ];
    }

    $keahlian = [ 'nilai_minimal' => $_POST['keahlian_user']/10 ];
    if ($_POST['keahlian_user'] <= 4) {
        $keahlian['kriteria'] = 'Pemula';
        $keahlian['bil_fuzzy'] = 'Rendah';
    } elseif ($_POST['keahlian_user'] > 4 && $_POST['keahlian_user'] <= 8) {
        $keahlian['kriteria'] = 'Sedang';
        $keahlian['bil_fuzzy'] = 'Cukup';
    } else {
        $keahlian['kriteria'] = 'Tinggi';
        $keahlian['bil_fuzzy'] = 'Tinggi';
    }

    if ((int)$_POST['jumlah_pengunjung'] <= 1000) {
        $pengunjung = [
            'kriteria' => '< 1.000',
            'bil_fuzzy' => 'Sedikit',
            'nilai_minimal' => 0.1,
            'nilai_maksimal' => 0.3,
            'jumlah_pengunjung' => $_POST['jumlah_pengunjung']
        ];
    } elseif ((int)$_POST['jumlah_pengunjung'] > 1000 && (int)$_POST['jumlah_pengunjung'] <= 15000) {
        $pengunjung = [
            'kriteria' => '1.000 - 15.000',
            'bil_fuzzy' => 'Sedang',
            'nilai_minimal' => 0.3,
            'nilai_maksimal' => 0.8,
            'jumlah_pengunjung' => $_POST['jumlah_pengunjung']
        ];
    } else {
        $pengunjung = [
            'kriteria' => '> 15.000',
            'bil_fuzzy' => 'Tinggi',
            'nilai_minimal' => 0.9,
            'nilai_maksimal' => 0.9,
            'jumlah_pengunjung' => $_POST['jumlah_pengunjung']
        ];
    }

    $transformasi_data = \ExtensionsModel\TransformasiDataModel::model()->findAll();
    if (!isset($_POST['pertimbangan'])) {
        $bobot = \ExtensionsModel\BobotModel::model()->findAll();
    } else {
        $tot = array_sum( array_values($_POST['pertimbangan'])) / 10;
        $bobot = [
            1 => [
                'kriteria' => 'Performa Batasan',
                'nilai' => ($_POST['pertimbangan']['batasan_performa']/10)/$tot,
                'pertimbangan' => $_POST['pertimbangan']['batasan_performa']/10
            ],
            2 => [
                'kriteria' => 'Pengunjung',
                'nilai' => ($_POST['pertimbangan']['pengunjung']/10)/$tot,
                'pertimbangan' => $_POST['pertimbangan']['pengunjung']/10
            ],
            3 => [
                'kriteria' => 'Biaya',
                'nilai' => ($_POST['pertimbangan']['biaya']/10)/$tot,
                'pertimbangan' => $_POST['pertimbangan']['biaya']/10
            ],
            4 => [
                'kriteria' => 'Keahlian Teknis',
                'nilai' => ($_POST['pertimbangan']['keahlian_teknis']/10)/$tot,
                'pertimbangan' => $_POST['pertimbangan']['keahlian_teknis']/10
            ],
        ];
    }

    return $this->view->render($response, 'rekomendasi.phtml', [
        'performa' => $performa,
        'biaya' => $biaya,
        'keahlian' => $keahlian,
        'pengunjung' => $pengunjung,
        'transformasi_data' => $transformasi_data,
        'bobot' => $bobot
    ]);
});