<?php

namespace Extensions\Controllers;

use Components\BaseController as BaseController;

class DefaultController extends BaseController
{
    public function __construct($app, $client)
    {
        parent::__construct($app, $client);
    }

    public function register($app)
    {
        $app->map(['GET'], '/view', [$this, 'view']);
    }

    public function accessRules()
    {
        return [
            ['allow',
                'actions' => ['view','create','update','delete'],
                'users'=> ['@'],
            ],
            ['deny',
                'users' => ['*'],
            ],
        ];
    }

    public function view($request, $response, $args)
    {
        $isAllowed = $this->isAllowed($request, $response);
        if ($isAllowed instanceof \Slim\Http\Response)
            return $isAllowed;

        if(!$isAllowed){
            return $this->notAllowedAction();
        }

        $batasan_performa = \ExtensionsModel\BatasanPerformaModel::model()->findAll();
        $biaya = \ExtensionsModel\BiayaModel::model()->findAll();
        $keahlian_user = \ExtensionsModel\KeahlianUserModel::model()->findAll();
        $pengunjung = \ExtensionsModel\PengunjungModel::model()->findAll();
        $bobot = \ExtensionsModel\BobotModel::model()->findAll();
        $transformasi_data = \ExtensionsModel\TransformasiDataModel::model()->findAll();

        $tabulasi_model = new \ExtensionsModel\TabulasiWpmModel();
        $tabulasi_wpm = $tabulasi_model->getItems();

        return $this->_container->module->render($response, 'servers/view.html', [
            'batasan_performa' => $batasan_performa,
            'biaya' => $biaya,
            'keahlian_user' => $keahlian_user,
            'pengunjung' => $pengunjung,
            'bobot' => $bobot,
            'transformasi_data' => $transformasi_data,
            'tabulasi_wpm' => $tabulasi_wpm
        ]);
    }
}