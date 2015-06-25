<?php
    use Cake\Core\Configure;
    $theme = Configure::read('wetkit.wet.theme');

?><!DOCTYPE html><!--[if lt IE 9]><html class="no-js lt-ie9" lang="<?= Configure::read('wetkit.lang'); ?>"><![endif]--><!--[if gt IE 8]><!-->
<html class="no-js" lang="<?= Configure::read('wetkit.lang'); ?>">
<!--<![endif]-->
<head>
    <meta charset="utf-8">
    <!-- Web Experience Toolkit (WET) / Boîte à outils de l'expérience Web (BOEW)
            wet-boew.github.io/wet-boew/License-en.html / wet-boew.github.io/wet-boew/Licence-fr.html -->
    <title><?= $this->fetch('splash-title-en') . ' / ' . $this->fetch('splash-title-fr'); ?></title>
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <!-- Meta data -->
    <meta name="description" content="<?= Configure::read('wetkit.description'); ?>">
    <!-- Meta data-->
    <!--[if gte IE 9 | !IE ]><!-->
    <link href="<?= Configure::read('wetkit.wet.path') ?>/assets/favicon.ico" rel="icon" type="image/x-icon">
    <link rel="stylesheet" href="<?= Configure::read('wetkit.wet.path') ?>/css/wet-boew.min.css"/>
    <?php if ($theme == 'theme-gcwu-fegc'): ?>
        <link rel="stylesheet" href="<?= Configure::read('wetkit.wet.path') ?>/css/theme-sp-pe.min.css">
    <?php elseif ($theme == 'theme-gc-intranet'): ?>
        <link rel="stylesheet" href="<?= Configure::read('wetkit.wet.path') ?>/css/theme.min.css">
    <?php endif; ?>
    <!--<![endif]-->
    <!--[if lt IE 9]>
    <link href="<?= Configure::read('wetkit.wet.path') ?>/assets/favicon.ico" rel="shortcut icon" />
    <link rel="stylesheet" href="<?= Configure::read('wetkit.wet.path') ?>/css/ie8-theme.min.css" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="<?= Configure::read('wetkit.wet.path') ?>/js/ie8-wet-boew.min.js"></script>
    <![endif]-->
    <noscript><link rel="stylesheet" href="<?= Configure::read('wetkit.wet.path') ?>/css/noscript.min.css" /></noscript>
</head>
<body vocab="http://schema.org/" typeof="WebPage">

<?php if ($theme == 'theme-gcwu-fegc'): ?>
    <header role="banner">
        <div class="container" id="wb-bnr">
            <?php
            if ($this->fetch("splash-banner") != null) {
                echo $this->fetch("splash-banner");
            } else {
                echo '<object id="gcwu-sig" type="image/svg+xml" tabindex="-1" role="img" data="'.Configure::read('wetkit.wet.path').'/assets/sig-alt-'.Configure::read('wetkit.lang').'.svg" aria-label="'.__d('wet_kit', 'Government of Canada').'"></object>';
            }?>
        </div>
    </header>
<?php elseif ($theme == 'theme-gc-intranet'): ?>
    <header role="banner" id="wb-bnr">
        <div class="container">
            <div class="row mrgn-tp-lg mrgn-bttm-lg">
                <div class="col-md-8 col-md-offset-2">
                    <?= $this->fetch("splash-banner") ?>
                </div>
            </div>
        </div>
    </header>
<?php endif; ?>




<main role="main" property="mainContentOfPage" class="container">
    <?= ($theme == 'theme-gc-intranet')?'<div class="row mrgn-tp-lg">':'' ?>
        <div class="col-md-12">

            <?php
            $btnEN = '
            <section class="col-md-6" lang="en">
                <h2 class="h3 text-center">'.$this->fetch('splash-title-en').'</h2>
                <ul class="list-unstyled">
                    <li><a class="btn btn-lg btn-primary btn-block"
                            href="'.$this->Url->build([
                                'controller'=>'WetKit/Tools',
                                'action'=>'lang', 'en',
                                '?'=>['url'=>$this->fetch('splash-url-en')]]).'">English</a></li>
                    <li><a class="btn btn-lg btn-default btn-block mrgn-tp-sm"
                            href="'.$this->Url->build([
                                'controller'=>'WetKit/Tools',
                                'action'=>'lang', 'en',
                                '?'=>['url'=>$this->fetch('splash-license-url-en')]]).'"
                            rel="license">Terms and conditions of use</a></li>
                </ul>
            </section>
            ';

            $btnFR = '
            <section class="col-md-6" lang="fr">
                <h2 class="h3 text-center">'.$this->fetch('splash-title-fr').'</h2>
                <ul class="list-unstyled">
                    <li><a class="btn btn-lg btn-primary btn-block"
                            href="'.$this->Url->build(['controller'=>'WetKit/Tools', 'action'=>'lang', 'fr',
                                '?'=>['url'=>$this->fetch('splash-url-fr')]]).'">Français</a></li>
                    <li><a class="btn btn-lg btn-default btn-block mrgn-tp-sm"
                            href="'.$this->Url->build(['controller'=>'WetKit/Tools', 'action'=>'lang', 'fr',
                                '?'=>['url'=>$this->fetch('splash-license-url-fr')]]).'"
                            rel="license">Conditions régissant l&#39;utilisation</a></li>
                </ul>
            </section>
            ';
            ?>

            <?php if (Configure::read('wetkit.lang') == 'fr'): ?>
                <h1 class="wb-inv">Language selection - <?= $this->fetch('splash-title-en') ?> /
                    <span lang="fr">Sélection de la langue - <?= $this->fetch('splash-title-fr') ?></span></h1>
            <?php
                echo $btnFR . $btnEN;
            else: ?>
                <h1 class="wb-inv">Sélection de la langue - <?= $this->fetch('splash-title-fr') ?> /
                    <span lang="en">Language selection - <?= $this->fetch('splash-title-en') ?></span></h1>
            <?php
                echo $btnEN . $btnFR;
            endif; ?>


        </div>
    <?= ($theme == 'theme-gc-intranet')?'</div>':'' ?>

    <?php
    if (Configure::read('debug')) {
        $err = [];
        if ($this->fetch('splash-license-url-en') == null) {
            $err[] = 'splash-license-url-en';
        }
        if ($this->fetch('splash-license-url-fr') == null) {
            $err[] = 'splash-license-url-fr';
        }
        if ($this->fetch('splash-url-en') == null) {
            $err[] = 'splash-url-fr';
        }
        if ($this->fetch('splash-url-fr') == null) {
            $err[] = 'splash-url-fr';
        }
        if ($this->fetch('splash-title-en') == null) {
            $err[] = 'splash-title-en';
        }
        if ($this->fetch('splash-title-fr') == null) {
            $err[] = 'splash-title-fr';
        }

        if (count($err) > 0) {
            echo '<div class="alert alert-danger"><div>'.__d('wet_kit', 'The following WetKit errors were found for this splash page:').'<ul>';
            foreach($err as $val) {
                echo '<li>'.__d('wet_kit', 'View block <strong>{0}</strong> is not defined.', $val).'</li>';
            }
            echo '</ul>';
            echo __d('wet_kit', "Define view blocks in your view template as follows:") ." <br><code>\$this->assign('splash-title-en', '...');</code> ";
            echo '</div></div>';
        }

    }
    ?>
</main>

<?php if ($theme == 'theme-gcwu-fegc'): ?>
<footer role="contentinfo" class="container">
    <object id="wmms" type="image/svg+xml" tabindex="-1" role="img"
            data="<?= Configure::read('wetkit.wet.path') ?>/assets/wmms-alt.svg"
            aria-label="<?= __d('wet_kit', 'Symbol of the Government of Canada') ?>"></object>
</footer>
<?php endif; ?>

<!--[if gte IE 9 | !IE ]><!-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/<?= Configure::read('wetkit.jquery-ie-version') ?>/jquery.min.js"></script>
<script src="<?= Configure::read('wetkit.wet.path') ?>/js/wet-boew.min.js"></script>
<!--<![endif]-->
<!--[if lt IE 9]>
<script src="<?= Configure::read('wetkit.wet.path') ?>/js/ie8-wet-boew2.min.js"></script>

<![endif]-->
<script src="<?= Configure::read('wetkit.wet.path') ?>/js/theme.min.js"></script>

</body>
</html>