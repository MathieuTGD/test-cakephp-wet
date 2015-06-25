<?php
if (\Cake\Core\Configure::read("wetkit.ui.sitemenu")) {
    if ($this->elementExists('language-bar')) {
        echo $this->element('language-bar');
    } else {

        $menu = $this->fetch("wetkit-sitemenu-before") .
            $this->fetch("wetkit-sitemenu") .
            $this->fetch("wetkit-sitemenu-after");
        if (trim($menu) != null) {
            ?>
            <div class="container nvbar">
                <h2><?= __d('wet_kit', 'Application Menu') ?></h2>

                <div class="row" style="background-color: #618000;">
                    <?= $menu; ?>
                </div>
            </div>
        <?php
        }
    }
}