<?php
use Cake\Core\Configure;

if (!Configure::read('debug')):
    throw new NotFoundException();
endif;

$markdown = $this->helpers()->load('WetKit.Markdown');
echo $markdown->text(
<<<'MD'
# WetKit Splash Page

Splash pages have there own layout. The splash layout generates the whole splash page by using view blocks to
fill-in custom parts.

Here's an example of a splash page template file, splash.ctp:

```
<?php
    $this->layout = "splash";
    $this->assign('splash-title-en', 'Web Experience Toolkit for CakePHP Framework');
    $this->assign('splash-url-en', $this->Url->build('/'));
    $this->assign('splash-license-url-en', $this->Url->build(['controller'=>'Pages', 'license']));
    $this->assign('splash-title-fr', 'Boîte à outils de l’expérience Web pour CakePHP ');
    $this->assign('splash-url-fr', $this->Url->build('/'));
    $this->assign('splash-license-url-fr', $this->Url->build(['controller'=>'Pages', 'license']));
```

MD
);
