<?php use Cake\Core\Configure; ?>

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

    //Enter your title here
    if (Configure::read('wetkit.wet.theme') == 'theme-base') {
        $this->assign('wetkit-sitetitle', __d('wet_kit', 'Web Experience Toolkit').'<span class="wb-inv">, </span><small>'.__d('wet_kit', 'Collaborative open source project led by the Government of Canada').'</small>');
    } else {
        $this->assign('wetkit-sitetitle', Configure::read('wetkit.title'));
    }
}

/***********************************************
 * SUBSITE NAME
 * Leave blank to remove sub-site bar
 * ONLY WORKS FOR THEME-GC-INTRANET!
 ***********************************************/
if ($this->fetch('wetkit-subsite') == null) {
    $this->assign('wetkit-subsite', Configure::read('wetkit.name'));
}


/***********************************************
 * LEFT MENU
 * Custom Menu to add to the WetKit left menu
 ***********************************************/
if ($this->fetch('wetkit-leftmenu') == null) {
    $this->start('wetkit-leftmenu'); ?>
    <h2><?php echo __d('wet_kit', 'Left Menu') ?></h2>

    <ul class="list-group menu list-unstyled">
        <li><h3>
                <a href="#"><?php echo __d('wet_kit', 'Left Side Menu') ?></a>
            </h3>
            <ul class="list-group list-unstyled" id="portal">
                <li><?php echo $this->Html->link(__d('wet_kit', 'Home'),
                        '/',
                        ['class' => 'list-group-item']
                    ) ?></li>
                <li><?php echo $this->Html->link(__d('wet_kit', 'WetKit Info'), "/pages/wetkit_info",
                        ['class' => 'list-group-item']
                    ) ?></li>
            </ul></li>
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

    <?php if (Configure::read('wetkit.wet.theme') == 'theme-base') {?>
        <section id="wb-lng" class="visible-md visible-lg">
            <h2 class="wb-inv"><? echo __d('wet_kit', 'Language selection') ?></h2>
            <ul class="text-right">
                <?php if (\Cake\Core\Configure::read("wetkit.lang") == "en") {?>
                    <li><?php echo $this->Html->link('Français', '/wet_kit/tools/lang/fr', ['lang'=>'fr', 'hreflang'=>'fr']) ?></li>
                    <li class="curr">English&#32;<span>(current)</span></li>
                <?php }?>
                <?php if (\Cake\Core\Configure::read("wetkit.lang") == "fr") {?>
                    <li class="curr">Français <span>(actuel)</span></li>
                    <li><?php echo $this->Html->link('English', '/wet_kit/tools/lang/en', ['lang'=>'en', 'hreflang'=>'en']) ?></li>
                <?php }?>
            </ul>
        </section>
    <?php } ?>

    <?php if (Configure::read('wetkit.wet.theme') == 'theme-gc-intranet') { ?>
        <section id="wb-lng">
            <h2><? echo __d('wet_kit', 'Language selection') ?></h2>
            <ul class="list-inline margin-bottom-none">
                <?php
                // User
                if ($user !== false) {
                    echo '<li><a href="#"><span class="glyphicon glyphicon-user"></span> '.$user['first_name'].'</a></li>'.PHP_EOL;
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
        </section>
    <?php } ?>

    <?php if (Configure::read('wetkit.wet.theme') == 'theme-gcwu-fegc') { ?>
        <li id="wb-lng">
            <h2><?php echo __d('wet_kit', 'Language selection')?></h2>
            <ul class="list-inline">
                <?php if (\Cake\Core\Configure::read("wetkit.lang") == "fr") {
                    echo '<li>'.$this->Html->link("English", "/wet_kit/tools/lang/en", ["lang"=>"en"]).'</li>';
                } else {
                    echo '<li>'.$this->Html->link("Français", "/wet_kit/tools/lang/fr", ["lang"=>"fr"]).'</li>';
                }?>
            </ul>
        </li>
    <?php } ?>

    <?php
    $this->end(); // wetkit-wb-lng
}

/***********************************************
 * SITE SEARCH WIDGET
 * Set to null if you do not want to show a search bar on your site
 ***********************************************/
if ($this->fetch('wetkit-search') == null) {
    $this->start('wetkit-search'); ?>

    <?php if (Configure::read('wetkit.wet.theme') == 'theme-base') { ?>
        <section id="wb-srch" class="col-md-4 visible-md visible-lg">
            <h2><?php echo __d('wet_kit', 'Search')?></h2>
            <form action="https://google.ca/search" method="get" role="search" class="form-inline">
                <div class="form-group">
                    <label for="wb-srch-q"><?php echo __d('wet_kit', 'Search website')?></label>
                    <input id="wb-srch-q" class="form-control" name="q" type="search" value="" size="27" maxlength="150">
                    <input type="hidden" name="q" value="site:wet-boew.github.io OR site:github.com/wet-boew/">
                </div>
                <button type="submit" id="wb-srch-sub" class="btn btn-default"><?php echo __d('wet_kit', 'Search')?></button>
            </form>
        </section>
    <?php } ?>

    <?php if (Configure::read('wetkit.wet.theme') == 'theme-gc-intranet') { ?>
        <section id="wb-srch" class="visible-md visible-lg">
            <h2><?php echo __d('wet_kit', 'Search') ?></h2>

            <form action="http://search-recherche.ent.dfo-mpo.ca/index-eng.php" method="post" role="search"
                  class="form-inline">
                <div class="form-group">
                    <label for="wb-srch-q"><?php echo __d('wet_kit', 'Search website') ?></label>
                    <input id="wb-srch-q" class="form-control" name="texthighlight" type="search" value="" size="27" maxlength="150"/>
                </div>
                <button type="submit" id="wb-srch-sub" class="btn btn-default"><?php echo __d('wet_kit', 'Search') ?></button>
            </form>
        </section>
    <?php } ?>

    <?php if (Configure::read('wetkit.wet.theme') == 'theme-gcwu-fegc') { ?>
        <section id="wb-srch" class="visible-md visible-lg">
            <h2><?php echo __d('wet_kit', 'Search') ?></h2>
            <form action="http://recherche-search.gc.ca/rGs/s_r" method="get" role="search" class="form-inline">
                <div class="form-group">
                    <label for="wb-srch-q"><?php echo __d('wet_kit', 'Search website') ?></label>
                    <input id="wb-srch-q" class="form-control" name="q" type="search" value="" size="27" maxlength="150">
                    <input type="hidden" name="st" value="s" />
                    <input type="hidden" name="s5bm3ts21rch" value="x" />
                    <input type="hidden" name="num" value="10" />
                    <input type="hidden" name="st1rt" value="0" />
                    <input type="hidden" name="langs" value="<?= Configure::read("wetkit.lang") ?>" />
                    <input type="hidden" name="cdn" value="dfo" />
                </div>
                <button type="submit" id="wb-srch-sub" class="btn btn-default"><?php echo __d('wet_kit', 'Search') ?></button>
            </form>
        </section>
    <?php } ?>

    <?php
    $this->end(); // wetkit-footer
}



/***********************************************
 * SITE MENU
 * Custom Site  Menu to add to the WetKit
 * Leave blank to remove
 ***********************************************/
if ($this->fetch('wetkit-sitemenu') == null) {
    $this->start('wetkit-sitemenu'); ?>
    <div class="container nvbar">
        <h2>Site menu</h2>
        <div class="row" style="background-color: #618000;">
            <ul class="list-inline menu small">
                <li><?= $this->Html->link("Site Option 1", ['controller'=>'pages', 'action'=>'home']) ?></li>
                <li><?= $this->Html->link("Site Option 2", ['controller'=>'pages', 'action'=>'home']) ?></li>
            </ul>
        </div>
    </div>
    <?php
    $this->end(); // wetkit-sitemenu
}


/***********************************************
 * MEGA MENU
 * Custom Mega Menu to add to the WetKit
 * Leave blank to remove
 ***********************************************/
if ($this->fetch('wetkit-megamenu') == null) {
    $this->start('wetkit-megamenu'); ?>
    <nav role="navigation" id="wb-sm" data-trgt="mb-pnl"
         class="wb-menu visible-md visible-lg" typeof="SiteNavigationElement">
        <div class="container nvbar">
            <h2><?php echo __d('wet_kit', 'Site menu') ?></h2>

            <div class="row">
                <ul class="list-inline menu">
                    <li><a href="#" class="item"><?php echo __d('wet_kit', 'Users') ?></a>
                        <ul class="sm list-unstyled" id="users" role="menu">
                            <li><?php echo $this->Html->link(__d('wet_kit', 'Users'),
                                    array('controller' => 'users', 'action' => 'index')) ?></li>
                            <li><?php echo $this->Html->link(__d('wet_kit', 'User Groups'),
                                    array('controller' => 'groups', 'action' => 'index')) ?></li>
                        </ul></li>
                </ul>
            </div>
        </div>
        <?= $this->fetch('wetkit-sitemenu') ?>
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

    //Enter your custom breadcrumb code here

    ?>

    <?php
    $this->end(); // wetkit-breadcrumb
}

/***********************************************
 * SITE FOOTER
 * Should not be null
 ***********************************************/
if ($this->fetch('wetkit-footer') == null) {
    $this->start('wetkit-footer'); ?>

    <footer role="contentinfo" id="wb-info"
            class="visible-sm visible-md visible-lg wb-navcurr">
        <div class="container">
            <nav role="navigation"<?php if (Configure::read('wetkit.wet.theme') == 'theme-base') echo ' class="row"'?>>
                <h2><?php echo __d('wet_kit', 'About this site')?></h2>

                <?php if (Configure::read('wetkit.wet.theme') == 'theme-gcwu-fegc') { ?>
                    <ul id="gc-tctr" class="list-inline">
                        <li><a rel="license" href="http://wet-boew.github.io/wet-boew/License-en.html"><?php echo __d('wet_kit', 'Terms and conditions')?></a></li>
                        <li><a href="http://www.tbs-sct.gc.ca/tbs-sct/common/trans-eng.asp"><?php echo __d('wet_kit', 'Transparency')?></a></li>
                    </ul>
                <?php } ?>
                <?php if (Configure::read('wetkit.wet.theme') != 'theme-base') {?><div class="row"><?php } ?>
                    <section class="col-sm-3">
                        <h3><?php echo __d('wet_kit', 'Contact us')?></h3>
                        <ul class="list-unstyled">
                            <li><a href="https://github.com/wet-boew/wet-boew/issues/new"><?php echo __d('wet_kit', 'Questions or comments?')?></a></li>
                        </ul>
                    </section>
                    <section class="col-sm-3">
                        <h3><?php echo __d('wet_kit', 'About')?></h3>
                        <ul class="list-unstyled">
                            <li><a href="http://wet-boew.github.io/v4.0-ci/index-en.html#about"><?php echo __d('wet_kit', 'About the Web Experience Toolkit')?></a></li>
                            <li><a href="http://www.tbs-sct.gc.ca/ws-nw/index-eng.asp"><?php echo __d('wet_kit', 'About the Web Standards')?></a></li>
                        </ul>
                    </section>
                    <section class="col-sm-3">
                        <h3><?php echo __d('wet_kit', 'News')?></h3>
                        <ul class="list-unstyled">
                            <li><a href="https://github.com/wet-boew/wet-boew/pulse"><?php echo __d('wet_kit', 'Recent project activity')?></a></li>
                            <li><a href="https://github.com/wet-boew/wet-boew/graphs"><?php echo __d('wet_kit', 'Project statistics')?></a></li>
                        </ul>
                    </section>
                    <section class="col-sm-3">
                        <h3><?php echo __d('wet_kit', 'Stay connected')?></h3>
                        <ul class="list-unstyled">
                            <li><a href="https://twitter.com/WebExpToolkit"><?php echo __d('wet_kit', 'Twitter')?></a></li>
                        </ul>
                    </section>
                    <?php if (Configure::read('wetkit.wet.theme') != 'theme-base') {?></div><?php } ?>
                <?php if (Configure::read('wetkit.wet.theme') == 'theme-gc-intranet') { ?>
                    <ul id="gc-tctr" class="list-inline">
                        <li><a rel="license" href="http://wet-boew.github.io/wet-boew/License-en.html"><?php echo __d('wet_kit', 'Terms and conditions')?></a></li>
                        <li><a href="http://www.tbs-sct.gc.ca/tbs-sct/common/trans-eng.asp"><?php echo __d('wet_kit', 'Transparency')?></a></li>
                    </ul>
                <?php } ?>
            </nav>
        </div>
    </footer>

    <?php
    $this->end(); // wetkit-footer
}