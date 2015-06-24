<?php
use Cake\Core\Configure;

if (!Configure::read('debug')):
    throw new NotFoundException();
endif;


$markdown = $this->helpers()->load('WetKit.Markdown');
echo $markdown->text(
<<<'MD'

# WetKit Component

## Overview

The WetKit Component contains many methods which are used by the WetKit Plugin functionalities.

## Initializing WetKit component

The WetKit component should be initialized at the beginning of your AppController.

```
public function initialize()
{
    // Initializing the WetKit Component
    $this->loadComponent("WetKit.WetKit");
    $this->appData = $this->WetKit->init([
        'release_date' => '2015-06-16',
    ]);
    $this->set("appData", $this->appData);
```

## Setting up language

WetKit takes care of managing your application language.
Initial setup in your application is required to use the language options.

Add the following lines at the beginning of your AppController's (``/src/Controller/AppController.php``)
initialize function to load the WetKit Component. ``$this->WetKit->setDefault('fr');`` can be used to force a
default value, english will be used as default if the property is not set manually.

```
public function initialize()
{
    // Initializing the WetKit Component
    $this->loadComponent("WetKit.WetKit");
    $this->appData = $this->WetKit->init([
        'release_date' => '2015-06-16',
    ]);
    $this->set("appData", $this->appData);

    // Saving language
    $this->lang = $this->wetkit['lang']; // Making $this->lang accessible in any Controller
    $this->set("lang", $this->lang); // Making $lang accessible in any View
```

This will lookup for cached language information and reset the language accordingly.

Language information is saved in your App Configs under ``wetkit.lang`` and can
be access anywhere using ``Cake\Core\Configure``.

```
Configure::read('wetkit.lang');
```

Linking to ``/wet_kit/tools/lang/en`` or ``/wet_kit/tools/lang/fr`` will change the language and
set appropriate cookie and session information. The page containing the link will be reloaded in
the new set language.

## Setting Environment

WetKit can detect the current environment it's running on (LOCAL, DEV, TEST or PROD). You can
set the values WetKit look for in the host name when initializing the component.

```
$this->appData = $this->WetKit->init([
        'env' => [
            'envBar' => true, // Default is true
            'local' => ['-local', 'localhost'],
            'dev' => ['http://my-dev-address'],
            'test' => ['-uat', '-testing'],
        ]
    ]);
```

WetKit will look at the host (minus the port number) for the strings specified for each value. The environment
bar will never show when debug is turned off or when on production. Setting _envBar_ to false will hide the
bar.

MD
);
