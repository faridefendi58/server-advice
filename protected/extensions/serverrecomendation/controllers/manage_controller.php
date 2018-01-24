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
        $app->map(['GET', 'POST'], '/transformasi/[{id}]', [$this, 'transformasi']);
    }

    public function accessRules()
    {
        return [
            ['allow',
                'actions' => ['transformasi'],
                'users'=> ['@'],
            ],
            ['deny',
                'users' => ['*'],
            ],
        ];
    }

    public function transformasi($request, $response, $args)
    {
        $isAllowed = $this->isAllowed($request, $response);
        if ($isAllowed instanceof \Slim\Http\Response)
            return $isAllowed;

        if(!$isAllowed){
            return $this->notAllowedAction();
        }

        $model = \ExtensionsModel\TransformasiDataModel::model()->findByPk($args['id']);

        if (isset($_POST['TransformasiData'])){
            $model->jenis_server = $_POST['TransformasiData']['jenis_server'];
            $model->performa_batasan = $_POST['TransformasiData']['performa_batasan'];
            $model->pengunjung = $_POST['TransformasiData']['pengunjung'];
            $model->keahlian_user = $_POST['TransformasiData']['keahlian_user'];
            $model->biaya = $_POST['TransformasiData']['biaya'];

            $update = \ExtensionsModel\TransformasiDataModel::model()->update($model);
            if ($update) {
                $message = 'Data Anda telah berhasil diubah.';
                $success = true;
            } else {
                $message = \ExtensionsModel\TransformasiDataModel::model()->getErrors(false);
                $success = false;
            }
        }

        return $this->_container->module->render($response, 'servers/transformasi.html', [
            'model' => $model,
            'message' => ($message) ? $message : null,
            'success' => ($success) ? $success : null
        ]);
    }
}