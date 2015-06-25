<?php
use Cake\Core\Configure;

if (!Configure::read('debug')):
    throw new NotFoundException();
endif;

?>

<h1>Wetkit Information</h1>

<ul>
    <li><?= $this->Html->link(__d('wet_kit', "Wet UI Customisation"), ["controller"=>"pages", "action"=>"wetkit_ui"]) ?></li>
    <li><?= $this->Html->link(__d('wet_kit', "Wet Splash Page"), ["controller"=>"pages", "action"=>"wetkit_splash"]) ?></li>
    <li><?= $this->Html->link(__d('wet_kit', "Wet Helper"), ["controller"=>"pages", "action"=>"wet_helper"]) ?></li>
    <li><?= $this->Html->link(__d('wet_kit', "List Helper"), ["controller"=>"pages", "action"=>"list_helper"]) ?></li>
    <li><?= $this->Html->link(__d('wet_kit', "WetKit Component"), ["controller"=>"pages", "action"=>"wetkit_component"]) ?></li>
    <li><?= $this->Html->link(__d('wet_kit', "EAccess Plugin"), ["controller"=>"pages", "action"=>"eaccess_plugin"]) ?></li>
</ul>

<?php


$markdown = $this->helpers()->load('WetKit.Markdown');
echo $markdown->text(
<<<'MD'
## How to use the WetKit plugin

The Wetkit adds a layer to integrate WET into the CakePHP and streamline the implementation of applications
with the gorverment of Canada policies and guidelines.

_Add more information here..._

## <a name="config"></a>Application Configuration

The WetKit plugin uses its WetKitComponent to manage the application's information. The application
data can be set when the WetKit component is loaded in your App Controller.

```
$this->wetkit = $this->WetKit->init([
    'release-date' => '2015-06-30',
    'name' => "My App name"
]);
```

- **release-date**: Refers to the date the application has been released for the first time. This value
affects various part of the application or used to default other values such as the modification date of a page
or meta data.
- **last-release-date**: Date of the latest release. Can be used to default application values if they
are not specified.
- **modified**: The date used to fill the WET Date Modified field at the bottom of each page.
If left empty, the release-date or last-release-date will be use.
- **title**: The title of your application. Fills the WET site title in the header.
- **name**: The name of your application. Used for page title and WET headers.
- **parent-name**, **parent-url**: Used to add a link in the breadcrumb to a parent system or site.
- **home-name**: Changes the home name of your application in the breadcrumb.

Refer to ``plugins/WetKit/src/Controller/Component/WetKitComponent.php`` method ``setAppData()`` for a
full list of available properties for your app.

Go to the [WetKit Component Help Page][WetKitComponent] for details on initializing and using the
WetKit component in your application.

### <a name="metadata"></a>Adding Metadata

Metadata are set via the WetkitComponent either when initializing it or by calling the ``setAppData(array)``
method by passing the same configuration array.

```
$this->WetKit->init([
    // Other Application Configs here...
    "modified" => '2015-12-25',
    "release-date" => '2015-10-31',
    "title" => __d('wet_kit','Web Experience Toolkit'),
    'description' => __d('wet_kit', 'Enter a small description of your app.'),
    'creator' => __d('wet_kit', 'Enter creator name.'),
    'meta-subject' => __("Subject terms"),
]);
```

## Internet/Intranet Wet Themes

WetKit uses the Intranet Wet Theme by default but it can be configured to use the Internet Wet theme simply by
changing the ``wetkit.wet.theme`` configuration in your project's ``bootstrap.php`` configurations.

    Configure::write("wetkit.wet.theme", "theme-gcwu-fegc");

You can also change the Wet version using the ``wetkit.wet.version`` configuration.

    Configure::write("wetkit.wet.version", "4.0.6");

Important: Make sure the version is hosted in the APS environment (https://intra-l01.ent.dfo-mpo.ca/share/wet/).

> **IMPORTANT:** Changing the WET version may affect some outputs of the WetKit Plugin. When possible update your WetKit plugin to ensure it's compatible with latest Wet versions instead of changing it manually.



MD
. "[WetKitComponent]: ".$this->Url->build(["controller"=>"pages", "action"=>"wetkit_component"])
);
