<?php
    use Cake\Core\Configure;
    
    if ($this->elementExists('wetkit-overwrites')) {
        $this->element('wetkit-overwrites');
    }
    
    echo $this->element('wk-head');
?>

<?php if (key(Configure::read("wetkit.currentEnv")) != "PROD" && Configure::read('debug') && Configure::read('wetkit.env.envBar')) {?>
	<?php if (Configure::read('wetkit.wet.theme') == 'theme-gcwu-fegc') { ?>
	   <body vocab="http://schema.org/" typeof="WebPage" style="padding-top: 30px; background-position: center 73px">
	<?php } else { ?>
	   <body vocab="http://schema.org/" typeof="WebPage" style="padding-top: 30px">
	<?php } ?>
	<?php echo $this->element('wk-env-banner');?>
<?php } else {?>
	<body vocab="http://schema.org/" typeof="WebPage">
<?php } ?>

<ul id="wb-tphp">
    <li class="wb-slc"><a class="wb-sl" href="#wb-cont"><?php echo __d('wet_kit', 'Skip to main content') ?></a></li>
    <li class="wb-slc visible-md visible-lg">
        <a class="wb-sl" href="#wb-info"><?php echo __d('wet_kit', 'Skip to "About this site"') ?></a>
    </li>
    
    <?php if ($this->fetch("wetkit-leftmenu")) { ?>
        <li class="wb-slc visible-md visible-lg">
            <a class="wb-sl" href="#wb-sec"><?php echo __d('wet_kit', 'Skip to section menu') ?></a>
        </li>
    <?php } ?>
</ul>

<?php echo $this->element('header-'.Configure::read('wetkit.wet.theme')); ?>

<?php if ($this->element("wk-leftmenu")) { ?>
<div class="container">
    <div class="row">
        <main class="col-md-9 col-md-push-3" property="mainContentOfPage" role="main">
<?php } else { ?>
    <main role="main" property="mainContentOfPage" class="container">
<?php } ?>
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>
                <?= $this->Wet->modified() ?>
<?php if ($this->fetch("wetkit-leftmenu")) { ?>

        </main>
        <nav id="wb-sec" class="col-md-3 col-md-pull-9 visible-md visible-lg" typeof="SiteNavigationElement" role="navigation">
        <?php echo $this->fetch("wetkit-leftmenu"); ?>
        </nav>
    </div>
</div>
<?php } else { ?>
    </main>
<?php } ?>

<?= $this->element('wk-footer') ?>

</body>
</html>
