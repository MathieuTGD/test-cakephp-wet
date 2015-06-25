<?php
namespace WetKit\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\I18n\I18n;
use Cake\I18n\Time;
use Cake\ORM\Entity;
use Cake\Utility\Inflector;

class WetKitComponent extends Component
{
    public $admin_groups = [1];

    public $components = ['Cookie', 'Flash'];

    private $default_lang = 'en';

    public function init($configs = [])
    {

        if (isset($configs['admin_groups']) && is_array($configs['admin_groups'])) {
            $this->admin_groups = $configs['admin_groups'];
        }

        $set_lang = null;
        if (isset($configs['lang']) && ($configs['lang'] == "fr" || $configs['lang'] == "en")) {
            $set_lang = $configs['lang'];
        } elseif ($this->Cookie->check("wetkit.lang")) {
            $set_lang = $this->Cookie->read("wetkit.lang");
        } elseif ($this->request->session()->check("wetkit.lang")) {
            $set_lang = $this->request->session()->read("wetkit.lang");
        } else {
            $set_lang = $this->default_lang;
        }

        $this->setLang($set_lang);
        $configs['lang'] = $set_lang;

        $this->setEnv();

        return $this->setAppData($configs);
    }

    private function _setAutoPath()
    {
        if (Configure::read("wetkit.wet.path") == null && Configure::read("wetkit.wet.autopath") != null) {
            $autopath = Configure::read("wetkit.wet.autopath");
            $autopath = str_replace('[basepath]', Configure::read("wetkit.wet.basepath"), $autopath);
            $autopath = str_replace('[version]', Configure::read("wetkit.wet.version"), $autopath);
            $autopath = str_replace('[theme]', Configure::read("wetkit.wet.theme"), $autopath);
            Configure::write("wetkit.wet.path", rtrim($autopath, '/'));
        }
        return Configure::read("wetkit");
    }

    public function isAdmin()
    {
        $is_admin = false;
        foreach ($this->admin_groups as $key => $group_id) {
            if (in_array($group_id, AuthComponent::user())) {
                $is_admin = true;
            }
        }
        return $is_admin;
    }


    public function setAppData($appData)
    {
        if (isset($appData['wet'])) {
            $appData['wet'] += Configure::read('wetkit.wet');
        }
        if (isset($appData['ui'])) {
            $appData['ui'] += Configure::read('wetkit.ui');
        }
        if (isset($appData['env'])) {
            $appData['env'] += Configure::read('wetkit.env');
        }
        $appData += Configure::read('wetkit');
        $appData += [
            "lang" => Configure::read("wetkit.lang"),
            "name" => __d('wet_kit', 'Your APP name'),
            "modified" => null,
            "release-date" => null,
            "last-release-date" => null,
            "title" => __d('wet_kit', 'Web Experience Toolkit'),
            'description' => __d('wet_kit', 'Enter a small description of your app.'),
            'creator' => __d('wet_kit', 'Enter creator name.'),
            'parent-name' => __d('wet_kit', 'APS Portal'),
            'parent-url' => __d('wet_kit', 'https://intra-l01.ent.dfo-mpo.ca'),
            'home-name' => __d('wet_kit', 'WetKit Home'),
            'meta-subject' => __d('wetkit', "Subject terms"),
        ];

        if ($appData['last-release-date'] == null) {
            $appData['last-release-date'] = $appData['release-date'];
        }

        // If no modification date as been set for the current page uses the
        // date of the application release date
        if ($appData['modified'] == null && $appData['last-release-date'] != null) {
            $appData['modified'] = $appData['last-release-date'];
        }

        Configure::write('wetkit', $appData);

        $appData = $this->_setAutoPath();

        return $appData;
    }


    public function getAppData()
    {
        return Configure::read('app');
    }


    public static function getLang()
    {
        return Configure::read("wetkit.lang");
    }

    public static function setLang($lang)
    {
        Configure::write("wetkit.lang", $lang);

        if ($lang == "en") {
            Configure::write("wetkit.lang-switch", "fr");
            Configure::write("wetkit.ISO639-2", "eng");
            setlocale(LC_TIME, 'en'.Configure::read('wetkit.locale-suffix'));
            I18n::locale('en'.Configure::read('wetkit.locale-suffix'));
        } else if ($lang == "fr") {
            Configure::write("wetkit.lang-switch", "en");
            Configure::write("wetkit.ISO639-2", "fra");
            setlocale(LC_TIME, 'fr'.Configure::read('wetkit.locale-suffix'));
            I18n::locale('fr'.Configure::read('wetkit.locale-suffix'));
        }
    }

    public function setModified($date)
    {
        if (!is_object($date) && $date !== null) {
            $date = new Time($date);
        }
        Configure::write('wetkit.modified', $date);
    }

    public function setDefault($lang)
    {
        if ($lang == "fr" || $lang == "en") {
            $this->default_lang = $lang;
        }
    }

    public function setEnv()
    {
        $env_list = [
            'DEV' => Configure::read('wetkit.env.dev'),
            'LOCAL' => Configure::read('wetkit.env.local'),
            'TEST' => Configure::read('wetkit.env.test'),
            'PROD' => Configure::read('wetkit.env.prod'),
        ];
        $env_labels = [
            'DEV' => __d('wet_kit', 'DEVELOPMENT'),
            'LOCAL' => __d('wet_kit', 'LOCAL DEVELOPMENT'),
            'TEST' => __d('wet_kit', 'TEST'),
            'PROD' => __d('wet_kit', 'PRODUCTION'),
        ];

        // Assume Production Environement
        $current_env = ['PROD' => __d('wet_kit', 'PRODUCTION')];

        if (isset($_SERVER['HTTP_HOST'])) {
            $host = current(explode(":", $_SERVER['HTTP_HOST'])); //removing the :port part of the host

            if (preg_match('/^(?:[0-9]{1,3}\.){3}[0-9]{1,3}$/', $host)) {
                $current_env = ['LOCAL' => $env_labels['LOCAL']];
            } else {
                foreach ($env_list as $key => $env) {
                    foreach ($env as $v) {
                        if (strpos($host, $v) !== false) {
                            $current_env = [$key => $env_labels[$key]];
                        }
                    }
                }
            }
        }

        Configure::write("wetkit.currentEnv", $current_env);
        return $current_env;
    }


    public function flashError($msg, Entity $entity)
    {
        //debug($entity);
        $out = $msg;
        $out .= "<hr><ul>";
        $i = 0;
        foreach ($entity->errors() as $field => $errors) {
            foreach ($errors as $type => $error) {
                $i++;
                $out .= '<li><a href="#' . mb_strtolower(Inflector::slug($field, '-')) . '">' . __d('wet_kit',
                        'Error {0}: ', $i) . ' ' . Inflector::humanize($field) . ' - ' . $error . '</a></li>';
            }
        }
        $out .= "</ul>";

        $this->Flash->error($out);
    }
}