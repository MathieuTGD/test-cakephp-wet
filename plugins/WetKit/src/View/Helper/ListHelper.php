<?php
namespace WetKit\View\Helper;

use Cake\Collection\Collection;
use Cake\Core\Configure;
use Cake\Form\Form;
use Cake\ORM\Entity;
use Cake\Routing\Router;
use Cake\Utility\Inflector;
use Cake\View\Form\ArrayContext;
use Cake\View\Form\ContextInterface;
use Cake\View\Form\EntityContext;
use Cake\View\Form\FormContext;
use Cake\View\Form\NullContext;
use Cake\View\Helper;
use Cake\View\Helper\IdGeneratorTrait;
use Cake\View\StringTemplateTrait;
use Cake\View\View;
use RuntimeException;
use Traversable;


class ListHelper extends Helper
{
    use StringTemplateTrait;
    use IdGeneratorTrait;

    /**
     * Other helpers used by FormHelper
     *
     * @var array
     */
    public $helpers = ['Paginator', 'Form', 'Html'];

    /**
     * Default config for the helper.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'idPrefix' => null,
        'errorClass' => 'form-error',
        'typeMap' => [
            'string' => 'text',
            'datetime' => 'datetime',
            'boolean' => 'checkbox',
            'timestamp' => 'datetime',
            'text' => 'textarea',
            'time' => 'time',
            'date' => 'date',
            'float' => 'number',
            'integer' => 'number',
            'decimal' => 'number',
            'binary' => 'file',
            'uuid' => 'string'
        ],
        'templates' => [
            'listStart' => '<table class="table table-striped"{{attrs}}>',
            'listEnd' => '</table>',
            'rowStart' => '<tr{{attrs}}>',
            'rowStartDeleted' => '<tr class="danger text-danger"{{attrs}}>',
            'rowStartObsolete' => '<tr class="warning text-warning"{{attrs}}>',
            'rowEnd' => '</tr>',
            'hiddenBlock' => '<div style="display:none;">{{content}}</div>',
            'cellBoolean' => '<td style="text-align:center;"{{attrs}}>{{content}}</td>',
            'cellFalse' => '<td style="text-align:center;" class="text-danger"{{attrs}}><span class="glyphicon glyphicon-remove-sign"></span> {{content}}</td>',
            'cellTrue' => '<td style="text-align:center;" class="text-success"{{attrs}}><span class="glyphicon glyphicon-ok-sign"></span> {{content}}</td>',
            'cellEncrypted' => '<td><span class="glyphicon glyphicon-lock"{{attrs}}></span> {{content}}</td>',
            'cell' => '<td{{attrs}}>{{content}}</td>',
            'cellEmail' => '<td{{attrs}}><span class="glyphicon glyphicon-envelope"></span> <a href="mailto:{{content}}">{{content}}</a></td>',
            'cellText' => '<td{{attrs}}>{{content}}</td>',
            'panelList' => '<div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">{{title}}</h3></div>{{content}}{{footer}}</div>',
            'panelListBody' => '<div class="panel-body">{{content}}</div>',
            'panelListTable' => '<div class="table-responsive">{{content}}</div>',
            'panelListFooter' => '<div class="panel-footer">{{content}}</div>'
        ]
    ];

    /**
     * Context for the current form.
     *
     * @var \Cake\View\Form\ContextInterface
     */
    protected $_context;


    /**
     * Context provider methods.
     *
     * @var array
     * @see addContextProvider
     */
    protected $_contextProviders = [];


    /**
     * Construct the widgets and binds the default context providers
     *
     * @param \Cake\View\View $View The View this helper is being attached to.
     * @param array $config Configuration settings for the helper.
     */
    public function __construct(View $View, array $config = [])
    {
        parent::__construct($View, $config);
        $this->_addDefaultContextProviders();
    }


    /**
     * Returns an HTML TABLE element.
     *
     * ### Options:
     *
     * - `templates` The templates you want to use for this list. Any templates will be merged on top of
     *   the already loaded templates. This option can either be a filename in /config that contains
     *   the templates you want to load, or an array of templates to use.
     *
     * @param mixed $model The context for which the form is being defined. Can
     *   be an ORM entity, ORM resultset, or an array of meta data. You can use false or null
     *   to make a model-less form.
     * @param array $options An array of html attributes and options.
     * @return string A formatted opening TABLE tag.
     */
    public function create($model = null, array $options = [])
    {
        $append = '';

        if (empty($options['context'])) {
            $options['context'] = [];
        }
        $options['context']['entity'] = $model;
        $context = $this->_getContext($options['context']);
        unset($options['context']);

        $isCreate = $context->isCreate();

        $options += [
            'templates' => null,
        ];


        $templater = $this->templater();


        if (!empty($options['templates'])) {
            $templater->push();
            $method = is_string($options['templates']) ? 'load' : 'add';
            $templater->{$method}($options['templates']);
        }
        unset($options['templates']);


        $htmlAttributes = [];
        $htmlAttributes += $options;
        $this->fields = [];

        if (!empty($append)) {
            $append = $templater->format('hiddenBlock', ['content' => $append]);
        }

        return $templater->format('listStart', [
            'attrs' => $templater->formatAttributes($htmlAttributes)
        ]) . $append;
    }


    /**
     * Closes an HTML list
     *
     * @return string A closing table tag.
     */
    public function end()
    {
        $out = '';
        $templater = $this->templater();
        $out .= $templater->format('listEnd', []);

        $templater->pop();
        $this->_context = null;
        return $out;
    }


    /**
     * Generate the header TH cells of the list
     *
     * ### Options
     *
     * - `paginator` - Default true - Generates paginator links if set to true
     * - `actions` - Adds a header Actions at the end of the list
     * - HTML attributes - All other options will be added as HTML attributes
     *
     * ### FieldList
     *
     * - Format1 [ 'fieldname1', 'fieldname2'...]
     * - Format1 [ 'fieldname1' => ['label' => 'Display this1'], ...]
     *
     *
     * @param array $fieldList - List of fields
     * @param array $options
     * @return string
     */
    public function header(array $fieldList, array $options = [])
    {
        $options += [
            'paginator' => true,
            'actions' => true,
        ];

        $out = "";

        $out .= $this->rowStart($options);

        foreach ($fieldList as $key => $value) {
            if (!is_array($value)) {
                $value = [
                    'label' => Inflector::humanize(Inflector::delimit($value)),
                    'paginator' => $options['paginator'],
                ];
            }
            $value += [
                'paginator' => $options['paginator'],
                'label' => Inflector::humanize(Inflector::delimit($key)),
                'field' => $key,
            ];
            if ($value['paginator'] === true) {
                $out .= '<th>' . $this->Paginator->sort($value['field'], $value['label']) . '</th>';
            } else {
                $out .= '<th>' . $value['label'] . '</th>';
            }


        }
        if ($options['actions'] === true) {
            $out .= '<th>' . __('Actions') . '</th>';
        }

        $out .= $this->rowEnd();

        return $out;
    }


    /**
     * Generates a TD element
     *
     * ### Options
     *
     * See each field type method for more information. Any options that are part of
     * $attributes or $options for the different **type** methods can be included in `$options` for input().
     * Additionally, any unknown keys that are not in the list below, or part of the selected type's options
     * will be treated as a regular HTML attribute for the generated input.
     *
     * - `type` - Force the type of data you want. e.g. `type => 'boolean'`
     * - `field` - The name of the database field, if type is not specified it will be set from the database info
     * - `templates` - Specify custom templates
     * - `booleanTrue` - Default `Yes` - Sets the text to display for true boolean values
     * - `booleanFalse` - Default `No` - Sets the text to display for false boolean values
     * - `encrypted` - true or false - Set to true if this field is an encrypted or protected value
     * - `maxLength` - Integer - The maximum length of the text displayed in the cell
     * - HTML attributes - All other options will be added as HTML attributes
     *
     * @param string $label Content of the cell
     * @param array $options Options for the cell
     * @return string Completed form widget.
     */
    public function cell($label, array $options = [])
    {

        $options += [
            'type' => null,
            'field' => null,
            'templates' => [],
            'booleanTrue' => __d('wet_kit', 'Yes'),
            'booleanFalse' => __d('wet_kit', 'No'),
            'encrypted' => false,
            'maxLength' => null
        ];
        $field = $options['field'];
        unset($options['field']);

        $template = 'cell';
        $type = $options['type'];
        $content = $label;

        if ($field !== null || $type !== null) {
            $options = $this->_parseOptions($field, $options);
            $type = $options['type'];
            // BOOLEAN
            if ($type == 'boolean') {
                if ($label == 0) {
                    $template = 'cellFalse';
                    $content = $options['booleanFalse'];
                } else {
                    if ($label == 1 || $label === true) {
                        $template = 'cellTrue';
                        $content = $options['booleanTrue'];
                    }
                }
            }
            // EMAIL
            if ($type == 'email') {
                $template = 'cellEmail';
            }
            // TEXT TYPE
            if ($type == 'text') {
                $template = 'cellText';
            }

            if ($options['maxLength'] !== null && !in_array($type, ['boolean', 'email'])) {
                if (strlen($content) > $options['maxLength']) {
                    $content = substr($content, 0, $options['maxLength']) . '...';
                }
            }
        }
        unset($options['type']);
        unset($options['booleanFalse']);
        unset($options['booleanTrue']);
        unset($options['maxLength']);


        if ($options['encrypted'] !== false) {
            $template = 'cellEncrypted';
        }
        unset($options['encrypted']);

        $templater = $this->templater();
        $newTemplates = $options['templates'];

        if ($newTemplates) {
            $templater->push();
            $templateMethod = is_string($options['templates']) ? 'load' : 'add';
            $templater->{$templateMethod}($options['templates']);
        }
        unset($options['templates']);


        $out = $templater->format($template, [
            'content' => $content,
            'attrs' => $templater->formatAttributes($options)
        ]);


        if ($newTemplates) {
            $templater->pop();
        }

        return $out;
    }

    /**
     * Creates a cell with related edit, view and delete buttons
     *
     * ### Options
     *
     * - `delete`/`view`/`edit` - Boolean to hide of show button
     * - `custom` - Array - Custom buttons
     * - HTML attributes - All other options will be added as HTML attributes
     *
     * @param $id
     * @param $controller
     * @param array $options
     * @return string
     */
    public function cellActions($id, $controller, array $options = [])
    {
        $out = '';

        $options += [
            'delete' => true,
            'view' => true,
            'edit' => true,
            'class' => 'text-center',
            'custom' => null,
        ];

        if ($options["delete"] === true) {
            $out .= $this->Form->postLink(
                    __d('wet_kit', 'Delete'),
                    ['controller' => $controller, 'action' => 'delete', $id],
                    [
                        'class' => 'btn btn-xs btn-danger',
                        'confirm' => __d('wet_kit', 'Are you sure you want to delete this record?')
                    ]) . ' ';
        }

        if ($options['view'] === true) {
            $out .= $this->Html->link(__d('wet_kit', 'View'),
                    ['controller' => $controller, 'action' => 'view', $id],
                    ['class' => 'btn btn-xs btn-default']) . ' ';
        }

        if ($options['edit'] === true) {
            $out .= $this->Html->link(__d('wet_kit', 'Edit'),
                ['controller' => $controller, 'action' => 'edit', $id],
                ['class' => 'btn btn-xs btn-warning']);
        }
        unset($options['delete']);
        unset($options['view']);
        unset($options['edit']);


        if (is_array($options['custom'])) {
            if (array_key_exists('label', $options['custom'])) {
                $options['custom'] += [
                    'label' => "undefined",
                    'url' => '#',
                    'class' => 'btn btn-xs btn-default'
                ];
                $out .= $this->Html->link($options['custom']['label'], $options['custom']['url'],
                    ['class' => $options['custom']['class']]);
            } elseif (is_array($options['custom'])) {
                foreach ($options['custom'] as $lbl => $custom) {
                    $custom += [
                        'label' => "undefined",
                        'url' => '#',
                        'class' => 'btn btn-xs btn-default'
                    ];
                    $out .= $this->Html->link($custom['label'], $custom['url'], ['class' => $custom['class']]);
                }
            }

        }
        unset($options['custom']);

        return $this->cell($out, $options);
    }


    /**
     * Generate an entire row of data based on a field list
     *
     * ### Options
     *
     * - See options for rowStart
     * - `templates` accepts custom templates
     *
     * ### FieldList
     *
     * - List of values [ 'value1', 'value2'...]
     * - List of fields and values [ ['label' => 'Display this1', 'field'=>'fieldName'], ...]
     *
     * @param array $valueList - A list of values with data
     * @param array $options
     * @return string
     */
    public function row(array $valueList, array $options = [])
    {
        $options += [
            'options' => null,
            'removed' => false,
            'obsolete' => false,
            'templates' => [],
        ];

        $out = "";

        $templater = $this->templater();
        $newTemplates = $options['templates'];

        if ($newTemplates) {
            $templater->push();
            $templateMethod = is_string($options['templates']) ? 'load' : 'add';
            $templater->{$templateMethod}($options['templates']);
        }
        unset($options['templates']);

        $out .= $this->rowStart($options);

        foreach ($valueList as $key => $value) {
            if (is_array($value)) {
                $listLabel = $value['label'];
                unset($value['label']);
                $out .= $this->cell($listLabel, $value);
            } else {
                $out .= $this->cell($value);
            }
        }

        $out .= $this->rowEnd();


        if ($newTemplates) {
            $templater->pop();
        }

        return $out;
    }


    /**
     * Generate the HTML to start a row
     *
     * ### Options
     *
     * - `removed` - default false. Show a removed (deleted) row
     * - `obsolete` - default false. Style a row for obsolete data
     *
     * @param array $options
     * @return string
     */
    public function rowStart(array $options = [])
    {
        $options += [
            'removed' => false,
            'obsolete' => false,
        ];

        $template = 'rowStart';
        if ($options['obsolete'] !== false) {
            $template = 'rowStartObsolete';
        }
        if ($options['removed'] !== false) {
            $template = 'rowStartDeleted';
        }

        $templater = $this->templater();

        $htmlAttributes = [];
        $htmlAttributes += $options;

        return $templater->format($template, [
            'attrs' => $templater->formatAttributes($htmlAttributes)
        ]);
    }

    /**
     * Generate the HTML to close a row (Templates: rowEnd)
     *
     * @return string
     */
    public function rowEnd()
    {
        $out = '';
        $templater = $this->templater();
        $out .= $templater->format('rowEnd', []);

        return $out;
    }


    /**
     * Generates the controls for Paginator
     *
     * @return string
     */
    public function paginatorControls()
    {
        $out = '

        <nav style="margin-top: 0">
                    <span class="pull-right">
                        <small>' . $this->Paginator->counter(array(
                'format' => __d('wet_kit', 'Showing {{start}} to {{end}} of {{count}} entries')
            )) . '</small>
        </span>
        <ul class="pagination pull-left" style="margin-top: 0">

            ' . $this->Paginator->prev(__d('wet_kit', 'previous')) . '
            ' . $this->Paginator->numbers() . '
            ' . $this->Paginator->next(__d('wet_kit', 'next')) . '

        </ul>
        </nav>
        ';

        return $out;
    }


    /**
     * Generates a basic list with limited options
     *
     * ### Options
     *
     * - `paginator` - default false. Adds paginator controls when true.
     *
     * @param array $model - plural lowercase name of the model
     * @param array $fieldList - List of fields to display ( [fieldname => label] )
     * @param array $dataset - data object
     * @param array $options
     * @return string
     */
    public function basicList($model, $fieldList, $dataset, array $options = [])
    {
        $options += [
            'paginator' => false,
            'create' => [],
            'header' => [],
            'row' => [],
            'cell' => [],
            'cellActions' => [],
        ];
        $paginator = $options['paginator'];
        unset($options['paginator']);

        $createOptions = $options['create'];
        unset($options['create']);
        $headerOptions = $options['header'];
        $headerOptions['paginator'] = $paginator;
        unset($options['header']);
        $rowOptions = $options['row'];
        unset($options['row']);
        $cellOptions = $options['cell'];
        unset($options['cell']);
        $cellActionsOptions = $options['cellActions'];
        unset($options['cellActions']);

        $showActions = true;
        if (isset($headerOptions['actions']) && $headerOptions['actions'] != true) {
            $showActions = false;
        }

        $out = $this->create($model, $createOptions);
        $out .= $this->header($fieldList, $headerOptions);

        foreach ($dataset as $data) {

            if ($data['is_deactivated']) {
                $tempOptions = $rowOptions;
                $tempOptions['removed'] = true;
                $out .= $this->rowStart($tempOptions);
            } else {
                $out .= $this->rowStart($rowOptions);
            }

            foreach ($fieldList as $field => $label) {
                $tempOptions = $cellOptions;
                $cellOptions['field'] = $field;
                $out .= $this->cell($data[$field], $tempOptions);
            }

            if ($showActions) {
                $out .= $this->cellActions($data->id, 'users', $cellActionsOptions);
            }
            $out .= $this->rowEnd();
        }

        $out .= $this->end();

        if ($paginator) {
            $out .= $this->paginatorControls();
        }

        return $out;
    }


    /**
     * Wraps the results of the basicList() method in a div panel
     *
     * ### Options
     *
     * - `paginator` - default false. Adds paginator controls when true.
     * - `title` - Title of the panel
     * - `emptyMessage` - Message to be displayed when no records are found
     * - `newButton` - Add a "Add Item" button in the panel footer when set to true (default=true)
     * - `footer` - Content of the panel footer (overwrites the newButton feature)
     *
     * @param array $model - plural lowercase name of the model
     * @param array $fieldList - List of fields to display ( [fieldname => label] )
     * @param array $dataset - data object
     * @param array $options
     * @return string
     */
    public function relatedPanel($model, $fieldList, $dataset, array $options = [])
    {
        $templater = $this->templater();
        $options += [
            'paginator' => false,
            'title' => null,
            'emptyMessage' => null,
            'newButton' => true,
            'footer' => null
        ];

        $title = $options['title'];
        if (!isset($options['title'])) {
            $title = __d('wet_kit', "Related {0}", Inflector::singularize($model));
        }
        unset($options['title']);

        $empty = $options['emptyMessage'];
        if (!isset($options['emptyMessage'])) {
            $empty = __d('wet_kit', "There are no related {0}.", $model);
        }
        unset($options['emptyMessage']);



        $footer = null;
        if ($options['newButton']) {
            $footer = $templater->format("panelListFooter", [
                'content' => $this->Html->link(__d('wet_kit', 'Add {0}', ucfirst(Inflector::singularize($model))), ["controller"=>$model, "action"=>"add"], ['class'=>"btn btn-primary"]),
            ]);
        }
        unset($options['newButton']);

        if (isset($dataset) && is_array($dataset) && count($dataset) > 0) {
            $content = $templater->format("panelListTable", [
                'content' => $this->basicList($model, $fieldList, $dataset, $options)
            ]);
        } else {
            $content = $templater->format("panelListBody", [
                'content' => $empty
            ]);
            $footer = null;
        }

        if ($options['footer']) {
            $footer = $options['footer'];
        }
        unset($options['footer']);


        $out = $templater->format("panelList", [
            'content' => $content,
            'title' => $title,
            'footer' => $footer,
        ]);

        return $out;
    }


    /**
     * Add the default suite of context providers provided by CakePHP.
     *
     * @return void
     */
    protected function _addDefaultContextProviders()
    {
        $this->addContextProvider('orm', function ($request, $data) {
            if (is_array($data['entity']) || $data['entity'] instanceof Traversable) {
                $pass = (new Collection($data['entity']))->first() !== null;
                if ($pass) {
                    return new EntityContext($request, $data);
                }
            }
            if ($data['entity'] instanceof Entity) {
                return new EntityContext($request, $data);
            }
            if (is_array($data['entity']) && empty($data['entity']['schema'])) {
                return new EntityContext($request, $data);
            }
        });

        $this->addContextProvider('form', function ($request, $data) {
            if ($data['entity'] instanceof Form) {
                return new FormContext($request, $data);
            }
        });

        $this->addContextProvider('array', function ($request, $data) {
            if (is_array($data['entity']) && isset($data['entity']['schema'])) {
                return new ArrayContext($request, $data['entity']);
            }
        });
    }


    /**
     * Create the URL for a list based on the options.
     *
     * @param \Cake\View\Form\ContextInterface $context The context object to use.
     * @param array $options An array of options from create()
     * @return string The action attribute for the form.
     */
    protected function _listUrl($context, $options)
    {
        if ($options['action'] === null && $options['url'] === null) {
            return $this->request->here(false);
        }

        if (is_string($options['url']) ||
            (is_array($options['url']) && isset($options['url']['_name']))
        ) {
            return $options['url'];
        }

        if (isset($options['action']) && empty($options['url']['action'])) {
            $options['url']['action'] = $options['action'];
        }

        $actionDefaults = [
            'plugin' => $this->plugin,
            'controller' => $this->request->params['controller'],
            'action' => $this->request->params['action'],
        ];

        $action = (array)$options['url'] + $actionDefaults;

        $pk = $context->primaryKey();
        if (count($pk)) {
            $id = $context->val($pk[0]);
        }
        if (empty($action[0]) && isset($id)) {
            $action[0] = $id;
        }
        return $action;
    }


    /**
     * Correctly store the last created form action URL.
     *
     * @param string|array $url The URL of the last form.
     * @return void
     */
    protected function _lastAction($url)
    {
        $action = Router::url($url, true);
        $query = parse_url($action, PHP_URL_QUERY);
        $query = $query ? '?' . $query : '';
        $this->_lastAction = parse_url($action, PHP_URL_PATH) . $query;
    }

    /**
     * Generates input options array
     *
     * @param string $fieldName The name of the field to parse options for.
     * @param array $options Options list.
     * @return array Options
     */
    protected function _parseOptions($fieldName, $options)
    {
        $needsMagicType = false;
        if (empty($options['type'])) {
            $needsMagicType = true;
            $options['type'] = $this->_inputType($fieldName, $options);
        }

        $options = $this->_magicOptions($fieldName, $options, $needsMagicType);
        return $options;
    }


    /**
     * Magically set option type and corresponding options
     *
     * @param string $fieldName The name of the field to generate options for.
     * @param array $options Options list.
     * @param bool $allowOverride Whether or not it is allowed for this method to
     * overwrite the 'type' key in options.
     * @return array
     */
    protected function _magicOptions($fieldName, $options, $allowOverride)
    {
        $context = $this->_getContext();

        $type = $context->type($fieldName);
        $fieldDef = $context->attributes($fieldName);

        if ($options['type'] === 'number' && !isset($options['step'])) {
            if ($type === 'decimal' && isset($fieldDef['precision'])) {
                $decimalPlaces = $fieldDef['precision'];
                $options['step'] = sprintf('%.' . $decimalPlaces . 'F', pow(10, -1 * $decimalPlaces));
            } elseif ($type === 'float') {
                $options['step'] = 'any';
            }
        }

        $typesWithOptions = ['text', 'number', 'radio', 'select'];
        $magicOptions = (in_array($options['type'], ['radio', 'select']) || $allowOverride);
        if ($magicOptions && in_array($options['type'], $typesWithOptions)) {
            $options = $this->_optionsOptions($fieldName, $options);
        }

        if ($allowOverride && substr($fieldName, -5) === '._ids') {
            $options['type'] = 'select';
        }

        if ($allowOverride && substr($fieldName, 0, 3) === 'is_') {
            $options['type'] = 'boolean';
        }

        if ($options['type'] === 'select' && array_key_exists('step', $options)) {
            unset($options['step']);
        }


        return $options;
    }


    /**
     * Selects the variable containing the options for a select field if present,
     * and sets the value to the 'options' key in the options array.
     *
     * @param string $fieldName The name of the field to find options for.
     * @param array $options Options list.
     * @return array
     */
    protected function _optionsOptions($fieldName, $options)
    {
        if (isset($options['options'])) {
            return $options;
        }

        $pluralize = true;
        if (substr($fieldName, -5) === '._ids') {
            $fieldName = substr($fieldName, 0, -5);
            $pluralize = false;
        } elseif (substr($fieldName, -3) === '_id') {
            $fieldName = substr($fieldName, 0, -3);
        }
        $fieldName = array_slice(explode('.', $fieldName), -1)[0];

        $varName = Inflector::variable(
            $pluralize ? Inflector::pluralize($fieldName) : $fieldName
        );
        $varOptions = $this->_View->get($varName);
        if (!is_array($varOptions) && !($varOptions instanceof Traversable)) {
            return $options;
        }
        if ($options['type'] !== 'radio') {
            $options['type'] = 'select';
        }
        $options['options'] = $varOptions;
        return $options;
    }


    /**
     * Returns the input type that was guessed for the provided fieldName,
     * based on the internal type it is associated too, its name and the
     * variables that can be found in the view template
     *
     * @param string $fieldName the name of the field to guess a type for
     * @param array $options the options passed to the input method
     * @return string
     */
    protected function _inputType($fieldName, $options)
    {
        $context = $this->_getContext();

        if ($context->isPrimaryKey($fieldName)) {
            return 'hidden';
        }

        if (substr($fieldName, -3) === '_id') {
            return 'select';
        }

        $internalType = $context->type($fieldName);
        $map = $this->_config['typeMap'];
        $type = isset($map[$internalType]) ? $map[$internalType] : 'text';
        $fieldName = array_slice(explode('.', $fieldName), -1)[0];

        switch (true) {
            case isset($options['checked']):
                return 'checkbox';
            case isset($options['options']):
                return 'select';
            case in_array($fieldName, ['passwd', 'password']):
                return 'password';
            case in_array($fieldName, ['tel', 'telephone', 'phone']):
                return 'tel';
            case $fieldName === 'email':
                return 'email';
            case isset($options['rows']) || isset($options['cols']):
                return 'textarea';
        }

        return $type;
    }


    /**
     * Find the matching context provider for the data.
     *
     * If no type can be matched a NullContext will be returned.
     *
     * @param mixed $data The data to get a context provider for.
     * @return mixed Context provider.
     * @throws \RuntimeException when the context class does not implement the
     *   ContextInterface.
     */
    protected function _getContext($data = [])
    {
        if (isset($this->_context) && empty($data)) {
            return $this->_context;
        }
        $data += ['entity' => null];

        foreach ($this->_contextProviders as $provider) {
            $check = $provider['callable'];
            $context = $check($this->request, $data);
            if ($context) {
                break;
            }
        }
        if (!isset($context)) {
            $context = new NullContext($this->request, $data);
        }
        if (!($context instanceof ContextInterface)) {
            throw new RuntimeException(
                'Context objects must implement Cake\View\Form\ContextInterface'
            );
        }
        return $this->_context = $context;
    }

    /**
     * Add a new context type.
     *
     * Form context types allow FormHelper to interact with
     * data providers that come from outside CakePHP. For example
     * if you wanted to use an alternative ORM like Doctrine you could
     * create and connect a new context class to allow FormHelper to
     * read metadata from doctrine.
     *
     * @param string $type The type of context. This key
     *   can be used to overwrite existing providers.
     * @param callable $check A callable that returns an object
     *   when the form context is the correct type.
     * @return void
     */
    public function addContextProvider($type, callable $check)
    {
        foreach ($this->_contextProviders as $i => $provider) {
            if ($provider['type'] === $type) {
                unset($this->_contextProviders[$i]);
            }
        }
        array_unshift($this->_contextProviders, ['type' => $type, 'callable' => $check]);
    }
}