<?php use Cake\Core\Configure; ?>

<header role="banner">
    <div id="wb-bnr">
        <div id="wb-bar">
            <div class="container">
                <div class="row">
                    <object id="gcwu-sig" type="image/svg+xml" tabindex="-1" role="img" 
                        data="<?= Configure::read('wetkit.wet.path') ?>/assets/sig-blk-en.svg"
                        aria-label="Government of Canada">
                    </object>
                    <?= $this->element("wk-language-bar"); ?>
                    <section class="wb-mb-links col-xs-12 visible-sm visible-xs" id="wb-glb-mn">
                        <?php if (Configure::read("wetkit.ui.search") === true) $menu_title = __d('wet_kit', 'Search and Menu'); else $menu_title = __d('wet_kit', 'Menu'); ?>
                        <h2><?php echo $menu_title ?></h2>
                        <ul class="pnl-btn list-inline text-right">
                            <li>
                                <a href="#mb-pnl" title="<?php echo $menu_title ?>" aria-controls="mb-pnl" class="overlay-lnk btn btn-sm btn-default" role="button">
                                    <?php if (Configure::read("wetkit.ui.search") === true) { ?><span class="glyphicon glyphicon-search"><?php } ?>
                                        <span class="glyphicon glyphicon-th-list">
                                            <span class="wb-inv"><?php echo $menu_title ?></span>
                                        </span>
                                    <?php if (Configure::read("wetkit.ui.search") === true) { ?></span><?php } ?>
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
                <div id="wb-sttl" class="col-md-12">
                    <a href="/"><span><?php echo $this->fetch("wetkit-sitetitle") ?></span></a>
                </div>
                <object id="wmms" type="image/svg+xml" tabindex="-1" role="img"
                        data="<?= Configure::read('wetkit.wet.path') ?>/assets/wmms-intra.svg"
                        aria-label="<?php echo __d('wet_kit', 'Symbol of the Government of Canada') ?>"></object>
                <?= $this->element("wk-search") ?>
            </div>
        </div>
    </div>
    <?php if ($this->fetch("wetkit-subsite")) { ?>
        <div id="wb-bnr-ss">
            <div class="container">
                <div class="row col-md-12">
                    <div id="wb-ss">
                        <a href="#"><?= $this->fetch("wetkit-subsite") ?></a>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php  echo $this->element("wk-megamenu"); ?>
    <?php  echo $this->element('wk-breadcrumb'); ?>
</header>