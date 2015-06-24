<?php
namespace WetKit\Controller;

//use WetKit\Controller\AppController;
use App\Controller\AppController;
use Cake\Core\Configure;

class ToolsController extends AppController
{



    public function lang($language)
    {
        $this->loadComponent('Cookie');
        if ($language == 'fr') {
            $this->request->session()->write('Config.language', 'fr');
            $this->request->session()->write('wetkit.lang', 'fr');
            $this->Cookie->write('lang', 'fr');
            setlocale(LC_TIME, 'fr_CA');
        } else {
            $this->request->session()->write('Config.language', 'en');
            $this->request->session()->write('wetkit.lang', 'en');
            $this->Cookie->write('lang', 'en');
            setlocale(LC_TIME, 'en_CA');
        }
        $this->redirect($this->referer());
    }


}