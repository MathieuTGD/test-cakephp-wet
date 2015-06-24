<?php
/*
 * This element is called within every WetKit templates
 *
 * It enables overwrites of certain portion of the
 * Wet Template via CakePHP view blocks
 * (http://book.cakephp.org/3.0/en/views.html#using-view-blocks)
 *
 * All content outside of view blocks will be ignore at render
 *
 * Notes:
 *  - You can refer to your own elements within blocks
 *  - You can use variables set in your controllers
 *    using the $this->set method
 *  - These blocks are regrouped here for your convenience
 *    but these can be overwritten in any views or element
 *  - If you have specific pages aspects (i.e.: menu items)
 *    to include in these blocks, we encourage
 *    to embed them in these using your
 *    own blocks defined in specific views/elements
 *    (See Actions in leftmenu for an example)
 */


/***********************************************
 * SITE TITLE
 * Should not be null
 ***********************************************/
if ($this->fetch('wetkit-sitetitle') == null) {
    $this->assign('wetkit-sitetitle', __d('wet_kit', 'Application Platform Services'));
}

/***********************************************
 * SUBSITE NAME
 * Leave blank to remove sub-site bar
 ***********************************************/
if ($this->fetch('wetkit-subsite') == null) {
    $this->assign('wetkit-subsite', __d('wet_kit', "APSKIT Template"));
}


/***********************************************
 * LEFT MENU
 * Custom Menu to add to the WetKit left menu
 ***********************************************/
if ($this->fetch('wetkit-leftmenu') == null) {
    $this->start('wetkit-leftmenu'); ?>
    <h2><?php echo __d('wet_kit', 'Left Menu') ?></h2>

    <ul class="list-group menu list-unstyled">
        <li><h3><a href="#"><?php echo __d('wet_kit', 'Left Side Menu') ?></a></h3>
            <ul class="list-group list-unstyled" id="portal">
                <li><?php echo $this->Html->link(__d('wet_kit', 'WetKit Info'),
                        ['controller' => 'Pages', 'action' => 'display', 'wetkitinfo'],
                        ['class' => 'list-group-item']
                    ) ?></li>
            </ul>
        </li>
    </ul>

    <?php
    // Append CRUD actions at the end of the left menu
    echo $this->fetch("wetkit-leftmenu-actions");
    ?>

    <?php
    if (\Cake\Core\Configure::read('debug')) {
        echo '<div class="alert alert-warning" style="margin-top: 12px;">Edit this menu in
        /src/Template/Element/aps-overwrites.ctp
    </div>';
    }
    $this->end(); // wetkit-leftmenu
}


/***********************************************
 * WET LANGUAGE BAR
 * Leave blank to remove
 ***********************************************/
if ($this->fetch('wetkit-wb-lng') == null) {
    $this->start('wetkit-wb-lng'); ?>
    <section id="wb-lng" class="visible-md visible-lg text-right">
        <h2 class="wb-inv"><? echo __d('wet_kit', 'Language selection') ?></h2>

        <div class="row">
            <div class="col-md-12">
                <ul class="list-inline margin-bottom-none">
                    <?php
                    // User
                    if (isset($user)) {
                        echo '<li><a href="#"><span class="glyphicon glyphicon-user"></span> '.$user['legal_given_names'].'</a></li>'.PHP_EOL;
                        echo '<li class="text-muted">|</li>';
                    }
                    ?>


                    <?php
                    // Language Switch
                    if (\Cake\Core\Configure::read("wetkit.lang") == "fr") {
                        echo '<li>'.$this->Html->link("English", "/wet_kit/tools/lang/en", ["lang"=>"en"]).'</li>';
                    } else {
                        echo '<li>'.$this->Html->link("Français", "/wet_kit/tools/lang/fr", ["lang"=>"fr"]).'</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </section>
    <?php
    $this->end(); // wetkit-wb-lng
}




/***********************************************
 * MEGA MENU
 * Custom Mega Menu to add to the WetKit
 * Leave blank to remove
 ***********************************************/
if ($this->fetch('wetkit-megamenu') == null) {
    $this->start('wetkit-megamenu'); ?>
    <nav role="navigation" id="wb-sm" data-trgt="mb-pnl" class="wb-menu visible-md visible-lg"
         typeof="SiteNavigationElement">
        <div class="container nvbar">
            <h2><?php echo __d('wet_kit', 'Site menu') ?></h2>

            <div class="row">
                <ul class="list-inline menu">
                    <li>
                        <a href="#" class="item"><?php echo __d('wet_kit', 'Users') ?></a>
                        <ul class="sm list-unstyled" id="users" role="menu">
                            <li><?php echo $this->Html->link(__d('wet_kit', 'Users'),
                                    array('controller' => 'users', 'action' => 'index')) ?></li>
                            <li><?php echo $this->Html->link(__d('wet_kit', 'User Groups'),
                                    array('controller' => 'groups', 'action' => 'index')) ?></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php
    $this->end(); // wetkit-megamenu
}


/***********************************************
 * Breadcrumb
 * Custom Breadcrumb to add to the WetKit
 * Leave blank to use the default breadcrumb
 ***********************************************/
if ($this->fetch('wetkit-breadcrumb') == null) {
    $this->start('wetkit-breadcrumb');
    /*
    <nav role="navigation" id="wb-bc" property="breadcrumb">
        <h2><?php echo __d('wet_kit', 'You are here:') ?></h2>

        <div class="container">
            <div class="row">
                <ol class="breadcrumb">
                    <?php if ($this->request->controller == 'portal') { ?>
                        <?php if ($this->request->action == 'index') { ?>
                            <li><?php echo __d('wet_kit', 'Home'); ?></li>
                        <?php } else { ?>
                            <li><?php echo $this->Html->link(__d('wet_kit', 'Home'),
                                    array("controller" => "users", "action" => "view")); ?></li>
                            <li><?php echo Inflector::humanize($this->request->action); ?></li>
                        <?php } ?>
                    <?php } else { ?>
                        <li><?php echo $this->Html->link(__d('wet_kit','Home'),
                                array("controller" => "users", "action" => "view")); ?></li>
                        <?php if ($this->request->action == 'index') { ?>
                            <li><?php echo Inflector::humanize($this->request->controller); ?></li>
                        <?php } else { ?>
                            <li><?php echo $this->Html->link(Inflector::humanize($this->request->controller),
                                    array("controller" => $this->request->controller, "action" => "index")); ?></li>
                            <li><?php echo Inflector::humanize($this->request->action); ?></li>
                        <?php } ?>
                    <?php } ?>
                </ol>
            </div>
        </div>
    </nav>
     */
    ?>

    <?php
    $this->end(); // wetkit-breadcrumb
}

