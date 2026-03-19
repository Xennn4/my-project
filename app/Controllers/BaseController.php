<?php

namespace App\Controllers;

use App\Models\ApplicationModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $request;
    protected $helpers = ['cookie', 'date', 'security', 'menu', 'useraccess'];
    protected $session, $segment, $validation, $encrypter, $ApplicationModel, $data = [];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->session    = service('session');
        $this->segment    = service('uri');
        $this->validation = \Config\Services::validation();
        $this->encrypter  = \Config\Services::encrypter();

        $user = null;
        $menuCategory = [];

        if (class_exists(\App\Models\ApplicationModel::class)) {
            $this->ApplicationModel = new ApplicationModel();
            $user = $this->ApplicationModel->getUser(username: session()->get('username'));
            $menuCategory = $this->ApplicationModel->getAccessMenuCategory(session()->get('role'));
        }

        $segment = $this->segment->getSegment(1);
        $subsegment = $segment ? $this->segment->getSegment(2) : '';

        $this->data = [
            'segment'      => $segment,
            'subsegment'   => $subsegment,
            'user'         => $user,
            'MenuCategory' => $menuCategory
        ];
    }
}