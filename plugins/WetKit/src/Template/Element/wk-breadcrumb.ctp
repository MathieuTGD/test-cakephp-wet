<?php
use Cake\Utility\Inflector;
use Cake\Core\Configure;

if (Configure::read('wetkit.ui.breadcrumb') === true) {
    $custom_breadcrumb = $this->fetch("wetkit-breadcrumb");
    if (trim($custom_breadcrumb) === "") {
        ?>
        <nav role="navigation" id="wb-bc" property="breadcrumb">
            <h2><?php echo __d('wet_kit', 'You are here:') ?></h2>

            <div class="container">
                <div class="row">
                    <ol class="breadcrumb">
                        <?php
                        if (Configure::read("wetkit.parent-url")) {
                            echo '<li>' . $this->Html->link(Configure::read("wetkit.parent-name"),
                                    Configure::read("wetkit.parent-url")
                                ) . '</li>';
                        }
                        ?>
                        <li><?php echo $this->Html->link(Configure::read("wetkit.home-name"),
                                array("controller" => "users", "action" => "view")); ?></li>
                        <?php if ($this->request->action == 'index') { ?>
                            <li><?php echo Inflector::humanize($this->request->controller); ?></li>
                        <?php } else { ?>
                            <li><?php echo $this->Html->link(Inflector::humanize($this->request->controller),
                                    array("controller" => $this->request->controller, "action" => "index")); ?></li>
                            <li><?php echo Inflector::humanize($this->request->action); ?></li>
                        <?php } ?>
                    </ol>
                </div>
            </div>
        </nav>
    <?php
    } else {
        echo $custom_breadcrumb;
    }
}

