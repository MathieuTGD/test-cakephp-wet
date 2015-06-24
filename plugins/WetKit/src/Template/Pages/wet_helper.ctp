<?php
use Cake\Core\Configure;

if (!Configure::read('debug')):
    throw new NotFoundException();
endif;


$markdown = $this->helpers()->load('WetKit.Markdown');
echo $markdown->text(
<<<'MD'

# Wet Helper

## Loading the Wet Helper

You can make the Wet Helper available throughtout your application by loading it in your AppController.

```
public $helpers = ['WetKit.Wet']
```

## `field` method

``field($data, $field, [Optional]$lang) return string``

The method field returns the value of the ``$field`` parameter corresponding
to the user's language. Unless ``$lang`` is specified.

The following command would look for either name_eng or name_fra depending on the user's language.

    $this->Wet->field($data, "name");

## `modified` Method

``modified(array $options=[]) return string``

Outputs the WET modified date at the bottom of a page. The `date` option accept either a Time object
or a date formatted string (i.e.: 2015-12-25 ). If `date` is null the date set in the WetKit Component
will be shown used.

```
$date = new \Cake\I18n\Time('2015-02-20');
// or $date = '2015-02-20';
echo $this->Wet->modified([
    'date' => $date, // **Optional
    'label' => __('Test:'), // **Optional
    'dateFormat' => 'YYYY-MM-dd', // **Optional
]);
```

By default the output date format will use the configuration value set in `Configure::read('wetkit.dateFormat')`.

## `whoDidIt` Method

The method whoDidIt returns created and modified information on edit and view pages.

``$options`` lets you overwrite the encapsulating div's class and style as well as the date format. All Options
are optional.

```
$this->Wet->whoDidIt($data, [
    'class' => 'alert alert-info',
    'role' => 'alert',
    'style' => '',
    'dateTimeFormat' => 'YYYY-MM-dd'
]);
```

You can also overwrite the values coming from `$data` by specifying them in the options.
Time can be specified as a string or a Time object.

```
echo $this->Wet->whoDidIt($data, [
    'modified' => '2015-12-25',
    'created' => new \Cake\I18n\Time('2014-12-25'),
    'modified_by' => 'John Doe',
    'created_by' => 'Jane Doe',
    'dateTimeFormat' => 'YYYY-MM-dd'
]);
```

MD
);
