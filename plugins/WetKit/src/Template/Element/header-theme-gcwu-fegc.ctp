<?php use Cake\Core\Configure; ?>

<header role="banner">
    <div id="wb-bnr">
        <div id="wb-bar">
            <div class="container">
                <div class="row">
                    <object id="gcwu-sig" type="image/svg+xml" tabindex="-1" role="img"
                            data="<?= Configure::read('wetkit.wet.path') ?>/assets/sig-<?= Configure::read("wetkit.lang") ?>.svg"
                            aria-label="<?php echo __d('wet_kit', 'Government of Canada') ?>">
                    </object>
                    <ul id="gc-bar" class="list-inline">
                        <li><a href="<?php echo __d('wet_kit', 'http://www.canada.ca/en/index.html') ?>" rel="external">Canada.ca</a></li>
                        <li><a href="<?php echo __d('wet_kit', 'http://www.canada.ca/en/services/index.html')?>" rel="external"><?php echo __d('wet_kit', 'Services')?></a></li>
                        <li><a href="<?php echo __d('wet_kit', 'http://www.canada.ca/en/gov/dept/index.html')?>" rel="external"><?php echo __d('wet_kit', 'Departments')?></a></li>
                        <?php echo $this->fetch("wetkit-wb-lng"); ?>
                    </ul>
                    <section class="wb-mb-links col-xs-12 visible-sm visible-xs" id="wb-glb-mn">
                        <?php if ($this->fetch("wetkit-search")) $menu_title = __d('wet_kit', 'Search and Menu'); else $menu_title = __d('wet_kit', 'Menu'); ?>
                        <h2><?php echo $menu_title ?></h2>
                        <ul class="pnl-btn list-inline text-right">
                            <li>
                                <a href="#mb-pnl" title="<?php echo $menu_title ?>" aria-controls="mb-pnl" class="overlay-lnk btn btn-sm btn-default" role="button">
                                    <?php if ($this->fetch("wetkit-search")) { ?><span class="glyphicon glyphicon-search"><?php } ?>
                                        <span class="glyphicon glyphicon-th-list">
                                            <span class="wb-inv"><?php echo $menu_title ?></span>
                                        </span>
                                    <?php if ($this->fetch("wetkit-search")) { ?></span><?php } ?>
                                </a>
                           </li>
                        </ul>
                        <div id="mb-pnl"></div>
                    </section>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div id="wb-sttl" class="col-md-5">
                    <a href="/">
                        <span><?php echo $this->fetch("wetkit-sitetitle") ?></span>
                    </a>
                </div>
                <object id="wmms" type="image/svg+xml" tabindex="-1" role="img" 
                    data="<?= Configure::read('wetkit.wet.path') ?>/assets/wmms.svg"
                    aria-label="<?php echo __d('wet_kit', 'Symbol of the Government of Canada') ?>">
                </object>
                
            <?php echo $this->fetch("wetkit-search"); ?>
            </div>
        </div>
    </div>
    <?php echo $this->fetch("wetkit-megamenu"); ?>
    <?php echo $this->element('breadcrumb'); ?>
</header>