<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;

$this->layout = "default";

if (!Configure::read('debug')):
    throw new NotFoundException();
endif;

?>

<header>
    <div class="text-center">
        <?= $this->Html->image('http://cakephp.org/img/cake-logo.png') ?>
        <h1>Get the Ovens Ready</h1>
    </div>
</header>

<div class="panel panel-default">
    <div class="panel-heading"><h3 class="panel-title">Configure CakePHP</h3></div>
    <div class="panel-body">


        <?php
        if (Configure::read('debug')):
            Debugger::checkSecurityKeys();
        endif;
        ?>
        <p id="url-rewriting-warning" style="background-color:#e32; color:#fff;display:none">
            URL rewriting is not properly configured on your server.
            1) <a target="_blank" href="http://book.cakephp.org/3.0/en/installation/url-rewriting.html" style="color:#fff;">Help me configure it</a>
            2) <a target="_blank" href="http://book.cakephp.org/3.0/en/development/configuration.html#general-configuration" style="color:#fff;">I don't / can't use URL rewriting</a>
        </p>


        <div class="col-md-6">

            <?php if (version_compare(PHP_VERSION, '5.4.16', '>=')): ?>
                <p class="text-success"><span class="glyphicon glyphicon-ok"></span> Your version of PHP is 5.4.16 or higher.</p>
            <?php else: ?>
                <p class="text-danger"><span class="glyphicon glyphicon-remove"></span>Your version of PHP is too low. You need PHP 5.4.16 or higher to use CakePHP.</p>
            <?php endif; ?>

            <?php if (extension_loaded('mbstring')): ?>
                <p class="text-success"><span class="glyphicon glyphicon-ok"></span> Your version of PHP has the mbstring extension loaded.</p>
            <?php else: ?>
                <p class="text-danger"><span class="glyphicon glyphicon-remove"></span>Your version of PHP does NOT have the mbstring extension loaded.</p>;
            <?php endif; ?>

            <?php if (extension_loaded('openssl')): ?>
                <p class="text-success"><span class="glyphicon glyphicon-ok"></span> Your version of PHP has the openssl extension loaded.</p>
            <?php elseif (extension_loaded('mcrypt')): ?>
                <p class="text-success"><span class="glyphicon glyphicon-ok"></span> Your version of PHP has the mcrypt extension loaded.</p>
            <?php else: ?>
                <p class="text-danger"><span class="glyphicon glyphicon-remove"></span>Your version of PHP does NOT have the openssl or mcrypt extension loaded.</p>
            <?php endif; ?>

            <?php if (extension_loaded('intl')): ?>
                <p class="text-success"><span class="glyphicon glyphicon-ok"></span> Your version of PHP has the intl extension loaded.</p>
            <?php else: ?>
                <p class="text-danger"><span class="glyphicon glyphicon-remove"></span>Your version of PHP does NOT have the intl extension loaded.</p>
            <?php endif; ?>

        </div>

        <div class="col-md-6">

            <?php if (is_writable(TMP)): ?>
                <p class="text-success"><span class="glyphicon glyphicon-ok"></span> Your tmp directory is writable.</p>
            <?php else: ?>
                <p class="text-danger"><span class="glyphicon glyphicon-remove"></span>Your tmp directory is NOT writable.</p>
            <?php endif; ?>

            <?php if (is_writable(LOGS)): ?>
                <p class="text-success"><span class="glyphicon glyphicon-ok"></span> Your logs directory is writable.</p>
            <?php else: ?>
                <p class="text-danger"><span class="glyphicon glyphicon-remove"></span>Your logs directory is NOT writable.</p>
            <?php endif; ?>

            <?php $settings = Cache::config('_cake_core_'); ?>
            <?php if (!empty($settings)): ?>
                <p class="text-success"><span class="glyphicon glyphicon-ok"></span> The <em><?= $settings['className'] ?>Engine</em> is being used for core caching. To change the config edit config/app.php</p>
            <?php else: ?>
                <p class="text-danger"><span class="glyphicon glyphicon-remove"></span>Your cache is NOT working. Please check the settings in config/app.php</p>
            <?php endif; ?>
        </div>

        <div class="col-md-12">
            <div class="well">
                <?php
                try {
                    $connection = ConnectionManager::get('default');
                    $connected = $connection->connect();
                } catch (Exception $connectionError) {
                    $connected = false;
                    $errorMsg = $connectionError->getMessage();
                    if (method_exists($connectionError, 'getAttributes')):
                        $attributes = $connectionError->getAttributes();
                        if (isset($errorMsg['message'])):
                            $errorMsg .= '<br />' . $attributes['message'];
                        endif;
                    endif;
                }
                ?>
                <?php if ($connected): ?>
                    <p class="text-success"><span class="glyphicon glyphicon-ok"></span> CakePHP is able to connect to the database. (Setup connection information in config/app.php)</p>
                <?php else: ?>
                    <p class="text-danger"><span class="glyphicon glyphicon-remove"></span> CakePHP is NOT able to connect to the database. (Setup connection information in config/app.php)<br /><br /><?= $errorMsg ?></p>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>



<div class="panel panel-default">
    <div class="panel-heading"><h3 class="panel-title">Configure APS WetKit</h3></div>
    <div class="panel-body">
        <?php

        // VERIFY FOR THE WETKIT-OVERWRITES ELEMENT
        if ($this->elementExists('wetkit-overwrites')) { ?>
            <p class="text-success"><span class="glyphicon glyphicon-ok"></span> Your <code>wetkit-overwrite.ctp</code> element has been created in your project.</p>
        <?php } else { ?>
            <p class="text-danger"><span class="glyphicon glyphicon-remove"></span> Your project is missing the <code>wetkit-overwrite.ctp</code> element. Copy <code>/plugins/WetKit/src/Template/Element/wetkit-overwrites.default.ctp</code> to <code>/src/Template/Element/wetkit-overwrites.ctp</code> (Rename file, removing .default)</p>
        <?php } ?>
    </div>
</div>


<div class="panel panel-default">
    <div class="panel-heading"><h3 class="panel-title">Configure eAccess Tables</h3></div>
    <div class="panel-body">
        <?php
        $tables = ConnectionManager::get('default')->schemaCollection()->listTables();

        if (in_array('users', $tables)){
            echo '<div class="alert alert-success">User table found!</div>';
            $userTable = TableRegistry::get("Users");
            echo '<div class="well">';
            echo '<p>Your user table must at least contain the following fields:</p>';
                foreach (['id', 'first_name', 'last_name', 'email', 'username', 'is_deactivated'] as $field){
                    if (in_array($field, $userTable->schema()->columns())) {
                        echo '<div class="text-success"><span class="glyphicon glyphicon-ok"></span> `'.$field.'` field found </div>';
                    } else {
                        echo '<div class="text-danger"><span class="glyphicon glyphicon-remove"></span> `'.$field.'` field not found </div>';
                    }
                }
            echo '</div>';
        } else {
            echo '<div class="alert alert-danger">No User table found. Run the migration script below to create the users table.</div>';
        }
        if (in_array('groups', $tables)){
            echo '<div class="alert alert-success">Groups table found!</div>';
            $groupTable = TableRegistry::get("Groups");
            echo '<div class="well">';
            echo '<p>Your group table must at least contain the following field:</p>';
            foreach (['id', 'name_eng', 'name_fra', 'acronym'] as $field){
                if (in_array($field, $groupTable->schema()->columns())) {
                    echo '<div class="text-success"><span class="glyphicon glyphicon-ok"></span> `'.$field.'` field found </div>';
                } else {
                    echo '<div class="text-danger"><span class="glyphicon glyphicon-remove"></span> `'.$field.'` field not found </div>';
                }
            }
            echo '</div>';
        } else {
            echo '<div class="alert alert-danger">No Groups table found. Run the migration script below to create the groups table.</div>';
        }
        ?>

        <?php
            echo $this->Html->link('<span class="glyphicon glyphicon-question-sign"></span> Click here for more information on the EAccess plugin',
                    ['controller'=>"pages", "action"=>"eaccess_plugin"],
                    ['class' => 'btn btn-info', 'escape' => false]
                );
        ?>
    </div>
</div>


<hr/>

<div class="col-md-12">
    <div class="well">
        <h3 class="">More about Cake</h3>
        <p>
            CakePHP is a rapid development framework for PHP which uses commonly known design patterns like Front Controller and MVC.
        </p>
        <p>
            Our primary goal is to provide a structured framework that enables PHP users at all levels to rapidly develop robust web applications, without any loss to flexibility.
        </p>

        <ul>
            <li><a href="http://cakefoundation.org/">Cake Software Foundation</a>
                <ul><li>Promoting development related to CakePHP</li></ul></li>
            <li><a href="http://www.cakephp.org">CakePHP</a>
                <ul><li>The Rapid Development Framework</li></ul></li>
            <li><a href="http://book.cakephp.org/3.0/en/">CakePHP Documentation</a>
                <ul><li>Your Rapid Development Cookbook</li></ul></li>
            <li><a href="http://api.cakephp.org/3.0/">CakePHP API</a>
                <ul><li>Quick Reference</li></ul></li>
            <li><a href="http://bakery.cakephp.org">The Bakery</a>
                <ul><li>Everything CakePHP</li></ul></li>
            <li><a href="http://plugins.cakephp.org">CakePHP plugins repo</a>
                <ul><li>A comprehensive list of all CakePHP plugins created by the community</li></ul></li>
            <li><a href="https://groups.google.com/group/cake-php">CakePHP Google Group</a>
                <ul><li>Community mailing list</li></ul></li>
            <li><a href="irc://irc.freenode.net/cakephp">irc.freenode.net #cakephp</a>
                <ul><li>Live chat about CakePHP</li></ul></li>
            <li><a href="https://github.com/cakephp/">CakePHP Code</a>
                <ul><li>For the Development of CakePHP Git repository, Downloads</li></ul></li>
            <li><a href="https://github.com/cakephp/cakephp/issues">CakePHP Issues</a>
                <ul><li>CakePHP issues and pull requests</li></ul></li>
        </ul>
    </div>
</div>

