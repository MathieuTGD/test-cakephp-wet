<?php use Cake\Core\Configure; ?>

<header role="banner">
    <div id="wb-bnr">
        <div id="wb-bar">
            <div class="container">
                <div class="row">
                    <?php echo $this->fetch("wetkit-wb-lng"); ?>
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
                <div id="wb-sttl" class="col-md-8">
                    <a href="/">
                    <span><?php echo $this->fetch("wetkit-sitetitle") ?></span>
                    </a>
                </div>
                <?php echo $this->fetch("wetkit-search"); ?>
            </div>
        </div>
    </div>
    <?php echo $this->fetch("wetkit-megamenu"); ?>
    <?php echo $this->element('breadcrumb'); ?>
</header>