<?php
use Cake\Core\Configure;

if (!Configure::read('debug')):
    throw new NotFoundException();
endif;

$markdown = $this->helpers()->load('WetKit.Markdown');
echo $markdown->text(
<<<'MD'

# WET User Interface Customisation

WetKit generates the WET UI but at the same time gives you plenty of placeholders to modify the content
of the interface to fit your needs.

The WetKit uses CakePHP's view blocks to allow you to change the content of the UI.

Many configurations can also be set when initializing the WetComponent to turn some elements on or off.

## Turning UI elements on or off

```
$this->appData = $this->WetKit->init([
    'ui' => [
        'megamenu' => true,
        'sitemenu' => true,
        'leftmenu' => true,
        'breadcrumb' => true,
        'search' => true,
        'subsite' => true,
        'language-bar' => true,
    ]
]);
```

## Page Header

The following placeholders are accessible in the WET page header.

- **wetkit-head-meta**: WetKit already sets the WET metatags based on the application data. You can add more
in this view block if necessary.
- **wetkit-head**: Appended to the end of the page header you can add any additional header information in this
view block such as javascript and css files.

## Page Footer

The following placeholders are accessible in the WET page header.

- **wetkit-footer**: Appended to the end of the page.

## Mega Menu

The WET left menu can be customized using the following view blocks. And renders them in that order.

- wetkit-megamenu-before
- wetkit-megamenu
- wetkit-megamenu-after

WetKit wraps all view blocks in a `nav` tag. View blocks should have all the
HTML necessary to display the menu.

```
<?php $this->start('wetkit-megamenu'); ?>

    <ul class="list-inline menu">
        <li><a href="#" class="item"><?php echo __('Users') ?></a>
            <ul class="sm list-unstyled" id="users" role="menu">
                <li><?php echo $this->Html->link(__('Users'),
                            array('controller' => 'users', 'action' => 'index')) ?></li>
                <li><?php echo $this->Html->link(__('User Groups'),
                            array('controller' => 'groups', 'action' => 'index')) ?></li>
            </ul></li>
    </ul>

<?php $this->end(); ?>
```

## Site Menu

A secondary menu as been added specifically for application menu options if the whole screen width is
needed and the left menu cannot be used for that purpose.

All view blocks are identical to the mega menu by replacing **wetkit-megamenu** by **wetkit-sitemenu**. HTML markup is the
same as the mega menu.

## Left Menu

The WET left menu can be customized using the following view blocks. And renders them in that order.

- wetkit-leftmenu-before
- wetkit-leftmenu
- wetkit-leftmenu-actions
- wetkit-leftmenu-after

WetKit wraps all view blocks in a `nav` tag. View blocks should have all the
HTML necessary to display the menu.

```
<?php $this->start('wetkit-leftmenu'); ?>

    <ul class="list-group menu list-unstyled">
        <li>
            <h3>
                <a href="#"><?php echo __('Side Menu Title') ?></a>
            </h3>
            <ul class="list-group list-unstyled">
                <li><?php echo $this->Html->link(__('Home'),
                            '/',
                            ['class' => 'list-group-item']
                        ) ?></li>
                <li><?php echo $this->Html->link(__('WetKit Info'), "/pages/wetkit_info",
                            ['class' => 'list-group-item']
                        ) ?></li>
            </ul>
        </li>
    </ul>

<?php $this->end(); ?>
```

MD
);
