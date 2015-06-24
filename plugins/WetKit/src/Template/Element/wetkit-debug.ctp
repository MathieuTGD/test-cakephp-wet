<?php
use Cake\Core\Configure;

if (Configure::read('debug')):

    ?>

    <style>
        .wetkit-debug {
            color: white;
            position: fixed;
            bottom: 50px;
            right: 0;
            background-color: #090;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            padding: 6px;
        }

        .wetkit-validation {
            color: white;
            position: fixed;
            bottom: 90px;
            right: 0;
            background-color: #900;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            padding: 6px;
        }

        .wetkit-debug a, .wetkit-validation a {
            color: white;
            text-decoration: none;
        }

        .wetkit-debug a:hover,
        #wetkit-debug-link:hover,
        #wetkit-validation-link:hover,
        .wetkit-validation a:hover {
            text-shadow: 0px 0px 5px white;
            cursor: pointer;
        }
    </style>

    <div class="wetkit-debug">
        <span id="wetkit-debug-link"><span class="glyphicon glyphicon-wrench"></span> WetKit</span>
        <span id="wetkit-debug-menu"></span>
    </div>

    <?php
    $menu = [
        ' &nbsp;<span class="glyphicon glyphicon-question-sign"></span> '. $this->Html->link(__d('wet_kit', 'Help'), "/pages/wetkit_info"),
        ' &nbsp;<span class="glyphicon glyphicon-list"></span> '. $this->Html->link(__d('wet_kit','Change Log'), "/pages/change_log")
    ];





    /*
     * WETKIT Validation
     *
     * Verifies for different WetKit components to make sure they're properly configured.
     */

    $errors = [];

    // Looks if application name is set
    if ( Configure::read("wetkit.name") == __d('wet_kit', 'Your APP name') ) {
        $errors[] = '<span class="glyphicon glyphicon-pencil"></span>&nbsp; ' .
            $this->Html->link(__d("wet_kit", "App Name Not Set"), [
                'controller' => 'pages',
                'action' => 'wetkit_info',
                '#' => "config"
            ]);

    }

    // Looks if application title is set
    if ( Configure::read("wetkit.title") == __d('wet_kit','Web Experience Toolkit') ) {
        $errors[] = '<span class="glyphicon glyphicon-pencil"></span>&nbsp; ' .
            $this->Html->link(__d("wet_kit", "App Title Not Set"), [
                'controller' => 'pages',
                'action' => 'wetkit_info',
                '#' => "config"
            ]);

    }

    // Looks if release date is set
    if ( Configure::read("wetkit.release-date") == null ) {
        $errors[] = '<span class="glyphicon glyphicon-calendar"></span>&nbsp; ' .
            $this->Html->link(__d("wet_kit", "App Release Date Not Set"), [
                'controller' => 'pages',
                'action' => 'wetkit_info',
                '#' => "config"
            ]);

    }

    // Looks if metadata is set
    if (
        Configure::read("wetkit.title") == __d('wet_kit', 'Web Experience Toolkit') ||
        Configure::read("wetkit.description") == __d('wet_kit', 'Enter a small description of your app.') ||
        Configure::read("wetkit.meta-subject") == __d('wet_kit', 'Subject terms') ||
        Configure::read("wetkit.creator") == __d('wet_kit', 'Enter creator name.')
    ) {
        $errors[] = '<span class="glyphicon glyphicon-tags"></span>&nbsp; ' .
            $this->Html->link(__d("wet_kit", "Meta Data Not Set"), [
                'controller' => 'pages',
                'action' => 'wetkit_info',
                '#' => "metadata"
            ]);

    }

    if (count($errors) > 0):
        ?>
        <div class="wetkit-validation">
            <span id="wetkit-validation-link"><span
                    class="glyphicon glyphicon-cog"></span> <?= __d('wet_kit',' Config Errors') ?></span>
            <span id="wetkit-validation-menu"></span>
        </div>

    <?php
    endif;







    ?>

    <script>
        $(document).ready(function () {
                $('#wetkit-debug-link').on("click", function () {
                    if ($('#wetkit-debug-menu').html().length > 0) {
                        $('#wetkit-debug-menu').html("");
                    } else {
                        $('#wetkit-debug-menu').html(
                            <?php
                                foreach ($menu as $item) {
                                    echo "'".$item."' +";
                                }
                            ?>
                            ''
                        );
                    }
                });

                $('#wetkit-validation-link').on("click", function () {
                    if ($('#wetkit-validation-menu').html().length > 0) {
                        $('#wetkit-validation-menu').html("");
                    } else {
                        $('#wetkit-validation-menu').html(
                            <?php
                                foreach ($errors as $error) {
                                    echo "'<br> ".$error."' +";
                                }
                            ?>
                            ''
                        );
                    }
                });
            }
        );
    </script>

<?php
endif;
