<?php

namespace Extensions\Controllers;

use Components\BaseController as BaseController;

class ManageController extends BaseController
{
    public function __construct($app, $client)
    {
        parent::__construct($app, $client);
    }

    public function register($app)
    {
        $app->map(['GET'], '/performa/[{id}]', [$this, 'performa']);
        $app->map(['GET'], '/biaya/[{id}]', [$this, 'biaya']);
        $app->map(['GET'], '/keahlian/[{id}]', [$this, 'keahlian']);
        $app->map(['GET'], '/pengunjung/[{id}]', [$this, 'pengunjung']);
    }

    public function accessRules()
    {
        return [
            ['allow',
                'actions' => ['performa', 'biaya', 'keahlian', 'pengunjung'],
                'users'=> ['@'],
            ],
            ['deny',
                'users' => ['*'],
            ],
        ];
    }

    public function performa($request, $response, $args)
    {
        $isAllowed = $this->isAllowed($request, $response);
        if ($isAllowed instanceof \Slim\Http\Response)
            return $isAllowed;

        if(!$isAllowed){
            return $this->notAllowedAction();
        }

        $model = \ExtensionsModel\BatasanPerformaModel::model()->findByPk($args['id']);

        return $this->_container->module->render($response, 'servers/performa.html', [
            'model' => $model
        ]);
    }

    public function biaya($request, $response, $args)
    {
        $isAllowed = $this->isAllowed($request, $response);
        if ($isAllowed instanceof \Slim\Http\Response)
            return $isAllowed;

        if(!$isAllowed){
            return $this->notAllowedAction();
        }

        $model = \ExtensionsModel\BiayaModel::model()->findByPk($args['id']);

        return $this->_container->module->render($response, 'servers/biaya.html', [
            'model' => $model
        ]);
    }

    public function keahlian($request, $response, $args)
    {
        $isAllowed = $this->isAllowed($request, $response);
        if ($isAllowed instanceof \Slim\Http\Response)
            return $isAllowed;

        if(!$isAllowed){
            return $this->notAllowedAction();
        }

        $model = \ExtensionsModel\KeahlianUserModel::model()->findByPk($args['id']);

        return $this->_container->module->render($response, 'servers/keahlian.html', [
            'model' => $model
        ]);
    }



    public function pengunjung($request, $response, $args)
    {
        $isAllowed = $this->isAllowed($request, $response);
        if ($isAllowed instanceof \Slim\Http\Response)
            return $isAllowed;

        if(!$isAllowed){
            return $this->notAllowedAction();
        }

        $model = \ExtensionsModel\PengunjungModel::model()->findByPk($args['id']);

        return $this->_container->module->render($response, 'servers/pengunjung.html', [
            'model' => $model
        ]);
    }
}