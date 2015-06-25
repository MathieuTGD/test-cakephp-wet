<?php
namespace WetKit\Controller;

//use WetKit\Controller\AppController;
use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\View\Helper\UrlHelper;

class ToolsController extends AppController
{


    /**
     * Switch the application active language
     *
     * By default the page calling this action will be reloaded.
     * You can specify the redirect url by adding a url parameter to your request as ?url=redirectsUrl
     *
     * @param $language
     */
    public function lang($language)
    {
        $this->loadComponent('Cookie');

        $this->request->session()->write('Config.language', $language);
        $this->request->session()->write('wetkit.lang', $language);
        $this->Cookie->write('lang', $language);

        if ($this->request->query('url') != null ) {
            $root = UrlHelper::build('/');
            $url = str_replace($root, '/', $this->request->query('url'));
            $this->redirect($url);
        } else {
            $this->redirect($this->referer());
        }

    }


}