<?php
use Cake\Core\Configure;

if (Configure::read("wetkit.ui.megamenu") || Configure::read("wetkit.ui.sitemenu")):
    if ($this->elementExists('megamenu')) {
        echo $this->element('megamenu');
    } else {
        ?>
        <nav role="navigation" id="wb-sm" data-trgt="mb-pnl" class="wb-menu visible-md visible-lg"
             typeof="SiteNavigationElement">
            <?php

            if (Configure::read("wetkit.ui.megamenu")) {
                $menu = $this->fetch("wetkit-megamenu-before") .
                    $this->fetch("wetkit-megamenu") .
                    $this->fetch("wetkit-megamenu-after");
                if (trim($menu) != null) {
                    ?>
                    <div class="container nvbar">
                        <h2><?= __d('wet_kit', 'Site menu') ?></h2>

                        <div class="row">
                            <?= $menu ?>
                        </div>
                    </div>
                <?php
                }
            }

            if (Configure::read("wetkit.ui.sitemenu")) {
                echo $this->element('wk-sitemenu');
            }

            ?>
        </nav>
    <?php
    }
endif;

