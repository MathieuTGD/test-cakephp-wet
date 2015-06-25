<?php
use Cake\Core\Configure;

if (Configure::read("wetkit.ui.language-bar") === true):
    $languageBar = $this->fetch('wetkit-language-bar');
    if ($this->elementExists('language-bar')) {
        echo $this->element('language-bar');
    } else if (trim($languageBar) != '') {
        echo $languageBar;
    } else {
        ?>


        <?php if (Configure::read('wetkit.wet.theme') == 'theme-base') { ?>
            <section id="wb-lng" class="visible-md visible-lg">
                <h2 class="wb-inv"><? echo __d('wet_kit', 'Language selection') ?></h2>
                <ul class="text-right">
                    <?php if (Configure::read("wetkit.lang") == "en") { ?>
                        <li><?php echo $this->Html->link('Français', '/wet_kit/tools/lang/fr',
                                ['lang' => 'fr', 'hreflang' => 'fr']) ?></li>
                        <li class="curr">English&#32;<span>(current)</span></li>
                    <?php } ?>
                    <?php if (Configure::read("wetkit.lang") == "fr") { ?>
                        <li class="curr">Français <span>(actuel)</span></li>
                        <li><?php echo $this->Html->link('English', '/wet_kit/tools/lang/en',
                                ['lang' => 'en', 'hreflang' => 'en']) ?></li>
                    <?php } ?>
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
                        echo '<li><a href="#"><span class="glyphicon glyphicon-user"></span> ' .
                            $user['first_name'] . '</a></li>' . PHP_EOL;
                        echo '<li class="text-muted">|</li>';
                    }
                    ?>
                    <?php
                    // Language Switch
                    if (Configure::read("wetkit.lang") == "fr") {
                        echo '<li>' . $this->Html->link("English", "/wet_kit/tools/lang/en",
                                ["lang" => "en"]) . '</li>';
                    } else {
                        echo '<li>' . $this->Html->link("Français", "/wet_kit/tools/lang/fr",
                                ["lang" => "fr"]) . '</li>';
                    }
                    ?>
                </ul>
            </section>
        <?php } ?>

        <?php if (Configure::read('wetkit.wet.theme') == 'theme-gcwu-fegc') { ?>
            <li id="wb-lng">
                <h2><?php echo __d('wet_kit', 'Language selection') ?></h2>
                <ul class="list-inline">
                    <?php if (Configure::read("wetkit.lang") == "fr") {
                        echo '<li>' . $this->Html->link("English", "/wet_kit/tools/lang/en",
                                ["lang" => "en"]) . '</li>';
                    } else {
                        echo '<li>' . $this->Html->link("Français", "/wet_kit/tools/lang/fr",
                                ["lang" => "fr"]) . '</li>';
                    } ?>
                </ul>
            </li>
        <?php } ?>

    <?php
    }

endif;