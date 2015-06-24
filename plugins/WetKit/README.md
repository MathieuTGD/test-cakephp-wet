# WetKit plugin for CakePHP

## Installation




## Manual Install

Copy the WetKit folder in /plugins/WetKit

Load the WetKit plugin by adding the following line at the end of /config/bootstrap.php

```
Plugin::load('WetKit', ['bootstrap' => true, 'routes' => true, 'autoload' => true]);
```


To use the Wet theme in your CakePHP project you must specify so in your App Controller (/src/Controller/AppController.php)

```
class AppController extends Controller
{
    public $theme = "WetKit";
```


To be able to load menus and title in the Wet frame and manage them you need to copy and **rename** the file

```
/plugins/WetKit/src/Template/Element/wetkit-overwrites.default.ctp
```

to

```
/src/Template/Element/wetkit-overwrites.ctp
```

To ensure future compatibility do not modify any files in the WetKit plugin