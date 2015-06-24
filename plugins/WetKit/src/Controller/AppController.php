<?php
namespace WetKit\Controller;

use Cake\Controller\Controller;

class AppController extends Controller
{

    public function initialize()
    {
        $this->loadComponent('Flash');
    }
}