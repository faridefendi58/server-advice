<?php

namespace Extensions\Controllers;

use Components\BaseController as BaseController;

class BiayaController extends BaseController
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

        $model = new \ExtensionsModel\PostModel();
        $posts = $model->getPosts([ 'just_default' => true]);

        return $this->_container->module->render($response, 'posts/view.html', [
            'posts' => $posts
        ]);
    }
}