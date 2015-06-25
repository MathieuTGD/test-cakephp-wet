<?php
    $this->layout = "splash";
    $this->assign('splash-title-en', 'Web Experience Toolkit for CakePHP Framework');
    $this->assign('splash-url-en', $this->Url->build('/'));
    $this->assign('splash-license-url-en', $this->Url->build(['controller'=>'Pages', 'license']));
    $this->assign('splash-title-fr', 'Boîte à outils de l’expérience Web pour CakePHP ');
    $this->assign('splash-url-fr', $this->Url->build('/'));
    $this->assign('splash-license-url-fr', $this->Url->build(['controller'=>'Pages', 'license']));

$this->start('splash-banner');
?><?php
$this->end();
