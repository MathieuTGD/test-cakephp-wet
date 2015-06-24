<?php
use Cake\Core\Configure;

if (!Configure::read('debug')):
    throw new NotFoundException();
endif;

$markdown = $this->helpers()->load('WetKit.Markdown');
echo $markdown->text(
<<<'MD'

# EAccess Plugin

## Installation

Copy the EAccess plugin files to your project's plugins folder.

Then the EAccess plugin must be loaded in your app's ``bootstrap.php`` in order to be used.

```
Plugin::load('EAccess', ['bootstrap' => true, 'routes' => true]);
```

## Creating Users and Groups tables

You can create the necessary tables in your schema using CakePHP's migration within the Cake console.

```
bin\cake migrations migrate -p EAccess
```

You can verify if the migration as been properly applied.

````
bin\cake migrations migrate -p EAccess
````

## Initiating the EAccess Plugin in your App Controller

Add the following to your App Controller (``/src/Controller/AppController.php``) at the beginning of
 the ``initialize()`` method.

```
    $this->loadComponent('Auth');
    $this->Auth->config('authenticate', ['EAccess.Shibboleth']);
    $user = $this->Auth->identify();
    if ($user) {
        $this->Auth->setUser($user);
    }
    $this->set("user", $user);
```

## Local Environemnt

When developing on local environment, make sure your server is properly configured
to produce Shibboleth authentication headers.

MD
);
