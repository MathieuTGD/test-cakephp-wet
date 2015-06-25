<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    public $theme = "WetKit";

    public $lang;
    public $appData;

    public $helpers = [
        'WetKit.Wet',
        'Paginator' => ['templates' => 'WetKit.paginator-templates'],
        'Form' => ['templates' => 'WetKit.wet_form'],
        'WetKit.List',
    ];

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * @return void
     */
    public function initialize()
    {
        $this->loadComponent('Flash');

        // Setup Language using WetKit
        $this->loadComponent("WetKit.WetKit");
        $this->appData = $this->WetKit->init([
            'ui' => [
                'leftmenu' => true,
            ]
            /*
            // Default data:
            "lang" => Configure::read("wetkit.lang"),
            "name" => __d('wet_kit', 'Your APP name'),
            "modified" => null,
            "release-date" => null,
            "last-release-date" => null,
            "title" => __d('wet_kit','Web Experience Toolkit'),
            'description' => __d('wet_kit', 'Enter a small description of your app.'),
            'creator' => __d('wet_kit', 'Enter creator name.'),
            'parent-name' => __d('wet_kit', 'APS Portal'),
            'parent-url' => __d('wet_kit', 'https://intra-l01.ent.dfo-mpo.ca'),
            'home-name' => __d('wet_kit', 'WetKit Home'),
            'meta-subject' => __d('wetkit', "Subject terms"),
            'meta-lang' => (Configure::read("wetkit.lang") == 'fr')?'fra':'eng',
            */
        ]);
        $this->lang = $this->appData['lang'];
        $this->set("appData", $this->appData);
        $this->set("lang", $this->lang);

        // Setup eAccess Authentication
        $this->loadComponent('Auth');
        $this->Auth->config('authenticate', ['EAccess.Shibboleth']);
        $user = $this->Auth->identify();
        if ($user) {
            $this->Auth->setUser($user);
        }
        $this->Auth->allow("display");
        $this->set("user", $user);

    }
}
