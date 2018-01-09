<?php

namespace Extensions\Controllers;

use Components\BaseController as BaseController;

class ResultController extends BaseController
{
    public function __construct($app, $client)
    {
        parent::__construct($app, $client);
    }

    public function register($app)
    {
        $app->map(['GET','POST'], '/view', [$this, 'view']);
    }

    public function accessRules()
    {
        return [
            ['allow',
                'actions' => ['view'],
                'users'=> ['@'],
            ],
            ['deny',
                'users' => ['*'],
            ],
        ];
    }

    public function view($request, $response, $args)
    {
        var_dump($_POST); exit;
        $model = new \ExtensionsModel\PostModel();
        $posts = $model->getPosts([ 'just_default' => true]);

        return $this->_container->module->render($response, 'posts/view.html', [
            'posts' => $posts
        ]);
    }
}