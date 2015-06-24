<?php
use Cake\Core\Configure;

if (!Configure::read('debug')):
    throw new NotFoundException();
endif;


$markdown = $this->helpers()->load('WetKit.Markdown');
echo $markdown->text(
<<<'MD'
# List Helper

List helper provides a flexible and consistent way to implement custom lists.
The list helper is based on the core CakePHP Form Helper. Most functionnalities found in the
Form Helper can be found in the List Helper.

## Installation

Like other Helpers the list helper needs to be initialized prior its use.

In your controller:

    public $helpers = ['WetKit.List'];

Or in your view:

    $list = $this->helpers()->load('WetKit.List');

## Using the List Helper

### Initializing a list

    public function create($model, array $options = [])

``$model`` is the name of the model representing the list. Used to check field information from the data model.

``$options`` is an array containing options for the list. Option **Templates** can be used to customized the look of the
 entire list. Any other options value will be applied as HTML attributes in the opening table tag.

Closing the list is done with

    $this->List->end();

### Creating the List Headers

    public function header(array $fieldList, array $options = [])

``$fieldlist`` is an array containing the list of fields to display in the header with the array key as the field name
and an option array containing the an optional label. When label is omitted the header will show a Humanized
 version of the field name. The field name can also be specified in the option list.

    $fieldList = [
        'first_name' => ['label' => __('First Name')],
        'last_name',
        'random_alias' => ['label' => 'Group', 'field'=>'group_id']
    ];

``$options`` contains setup options. **paginator** can be activated or deactivated if set to false. It's
set to true by default. This will generate the sorting links for the Paginator. **actions** defaulted to true adds a
Actions column at the end of the headers. Any other options value will be applied as HTML attributes in the
opening tr tag.

    echo $this->List->header($fieldList, [
        'paginator' => true,
        'actions' => true,
        'id' => 'list_header', // Adding an id attribute to the tr of the header
    ]);

### Adding Rows

Rows can be added by inserting them all at once or one by one.

Adding them all at once is limited as it gives less options to modify the way values are displayed. Actions
also have to be setup manually as a value when adding the whole row at once.

     public function row(array $valueList, array $options = [])

``$valueList`` can contain a list of values or a list of array containing __label__, __field__ and __options__ or a combination of
both. When specifying the field name the List helper will try to adapt the display based on the information
found from the model specified when the list was created.

    echo $this->List->row([
        'Four',
        $this->Html->link('Five', 'http://google.ca'),
        ['label' => true, 'field'=>'is_this'], // is_this is a boolean field, Yes will be displayed
    ], [
        'removed' => false, // If true changes display for removed records (default TRUE)
        'obsolete' => false, // If true changes display for obsolete records (default TRUE)
    ]);

Adding one cell at a time.

    echo $this->List->rowStart([
        'removed' => false, // If true changes display for removed records (default TRUE)
        'obsolete' => false, // If true changes display for obsolete records (default TRUE)
    ]);
    echo $this->List->cell($data['id']);
    echo $this->List->cell($data['first_name'], []);
    echo $this->List->cell($data['last_name'], []);
    echo $this->List->cell($data['email'], ['field' => 'email']); // Automaticaly creates mailto link
    echo $this->List->cell($data['username']);
    echo $this->List->cell($data['is_deactivated'], ['field' => 'is_deactivated']); // Boolean display
    echo $this->List->cellActions(1, 'users', ['delete'=>false, 'edit'=>false,
            'custom'=>[
                [
                    'label'=>'Custom btn',
                    'url'=>$this->Url->build(["controller"=>"user", "action"=>"view"]),
                    'class'=>'btn btn-xs btn-success'
                ],
                [
                    'label'=>'Custom btn2',
                    'url'=>'http://google.com',
                    'class'=>'btn btn-xs btn-primary'
                ],
            ]]);
    echo $this->List->rowEnd();

**cellActions** automatically generates the standards view, edit and delete action buttons.

### Adding Paginator Controls

    echo $this->List->paginatorControls();

### Creating basic lists

    echo $this->List->basicList('users', $userFields, $users, ['paginator'=>false, 'showActions'=>true]);

The method basicList generates a simple list. **showActions** option will hide the action buttons.
Custom options can be set for each elements of the list
by specifying an option array for each element as follow:

    echo $this->List->basicList('groups', $GroupsFields, $user->groups, [
        'paginator'=>false,
        'create' => [],
        'header' => ['actions' => true],
        'row' => ['obsolete' => true],
        'cell' => ['style' => "text-align: center;"],
        'cellActions' => ['delete'=>false], // Will hide the delete button
    ]);

### Creating Related List Inside a Panel

This is used to create the related list found in views. It wraps the basicList above inside a
panel with a "Related Items" title and "Add Item" button in the footer.
But it can be customized to fit other uses as well.
It accepts all the same options as the ``basicList()`` method plus the following (default values are shown):

    echo $this->List->basicPanel('groups', $GroupsFields, $user->groups, [
        'title' => null, // Set to overwrite the default panel title
        'emptyMessage' => null, // Set to overwrite the default message when no records are found
        'newButton' => true, // Add a "Add Item" button in the panel footer
        'footer' => null // Set to customize the panel footer content
    ]);


MD
);