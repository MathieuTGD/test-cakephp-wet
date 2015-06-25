<?php
if (\Cake\Core\Configure::read("wetkit.ui.leftmenu")) {
    if ($this->elementExists('leftmenu')) {
        echo $this->element('leftmenu');
    } else {
        $menu = $this->fetch("wetkit-leftmenu-before") .
            $this->fetch("wetkit-leftmenu") .
            $this->fetch("wetkit-leftmenu-actions") .
            $this->fetch("wetkit-leftmenu-after");
        if (trim($menu) != null) {
            ?>
            <nav role="navigation" id="wb-sec" typeof="SiteNavigationElement"
                 class="col-md-3 col-md-pull-9 visible-md visible-lg">
                <h2><?= __d('wet_kit', 'Left Menu') ?></h2>';
                <?= $menu ?>
            </nav>
        <?php
        }
    }
}
