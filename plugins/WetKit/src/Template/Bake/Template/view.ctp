<%
/**
* CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
* Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
*
* Licensed under The MIT License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
* @link          http://cakephp.org CakePHP(tm) Project
* @since         0.1.0
* @license       http://www.opensource.org/licenses/mit-license.php MIT License
*/
use Cake\Utility\Inflector;

$associations += ['BelongsTo' => [], 'HasOne' => [], 'HasMany' => [], 'BelongsToMany' => []];
$immediateAssociations = $associations['BelongsTo'] + $associations['HasOne'];
$associationFields = collection($fields)
    ->map(function($field) use ($immediateAssociations) {
        foreach ($immediateAssociations as $alias => $details) {
            if ($field === $details['foreignKey']) {
                return [$field => $details];
            }
        }
    })
    ->filter()
    ->reduce(function($fields, $value) {
        return $fields + $value;
    }, []);

$groupedFields = collection($fields)
    ->filter(function($field) use ($schema) {
        return $schema->columnType($field) !== 'binary';
    })
    ->groupBy(function($field) use ($schema, $associationFields) {
        $type = $schema->columnType($field);
        if (isset($associationFields[$field])) {
            return 'string';
        }
        if (in_array($type, ['integer', 'float', 'decimal', 'biginteger'])) {
            return 'number';
        }
        if (in_array($type, ['date', 'time', 'datetime', 'timestamp'])) {
            return 'date';
        }
        return in_array($type, ['text', 'boolean']) ? $type : 'string';
        })
    ->toArray();

$groupedFields += ['number' => [], 'string' => [], 'boolean' => [], 'date' => [], 'text' => []];
$pk = "\$$singularVar->{$primaryKey[0]}";
%>
<h1 id="wb-cont" property="name">
    <?= __('<%= Inflector::humanize($singularVar) %>') .__(':')." ". h($<%= $singularVar %>-><%= $displayField %>) ?>
    <span class="pull-right">
        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', <%= $pk %>], ['confirm' => __('Are you sure you want to delete # {0}?', <%= $pk %>), 'class' => "btn btn-danger"]) ?>
        <?= $this->Html->link(__('Edit'), ['action' => 'edit', <%= $pk %>], ['class' => "btn btn-warning"]) ?>
    </span>
</h1>


<dl class="dl-horizontal">
<% if ($groupedFields['string']) : %>
<% foreach ($groupedFields['string'] as $field) : %>
<% if (isset($associationFields[$field])) :
$details = $associationFields[$field];
%>
            <dt><?= __('<%= Inflector::humanize($details['property']) %>') ?></dt>
            <dd><?= $<%= $singularVar %>->has('<%= $details['property'] %>') ? $this->Html->link($<%= $singularVar %>-><%= $details['property'] %>-><%= $details['displayField'] %>, ['controller' => '<%= $details['controller'] %>', 'action' => 'view', $<%= $singularVar %>-><%= $details['property'] %>-><%= $details['primaryKey'][0] %>]) : '' ?></dd>
<% else : %>
            <dt><?= __('<%= Inflector::humanize($field) %>') ?></dt>
            <dd><?= h($<%= $singularVar %>-><%= $field %>) ?></dd>
<% endif; %>
<% endforeach; %>
<% endif; %>
<% if ($groupedFields['number']) : %>
<% foreach ($groupedFields['number'] as $field) : %>
<% if ($field != 'created_by' && $field != 'modified_by') : %>
            <dt><?= __('<%= Inflector::humanize($field) %>') ?></dt>
            <dd><?= $this->Number->format($<%= $singularVar %>-><%= $field %>) ?></dd>
<% endif; %>
<% endforeach; %>
<% endif; %>
<% if ($groupedFields['date']) : %>
<% foreach ($groupedFields['date'] as $field) : %>
<% if ($field != 'created' && $field != 'modified') : %>
            <dt><%= "<%= __('" . Inflector::humanize($field) . "') %>" %></dt>
            <dd><?= h($<%= $singularVar %>-><%= $field %>) ?></dd>
<% endif; %>
<% endforeach; %>
<% endif; %>
<% if ($groupedFields['boolean']) : %>
<% foreach ($groupedFields['boolean'] as $field) : %>
            <dt><?= __('<%= Inflector::humanize($field) %>') ?></dt>
            <dd><?= $<%= $singularVar %>-><%= $field %> ? __('Yes') : __('No'); ?></dd>
<% endforeach; %>
<% endif; %>
<% if ($groupedFields['text']) : %>
<% foreach ($groupedFields['text'] as $field) : %>
            <dt><?= __('<%= Inflector::humanize($field) %>') ?></dt>
            <dd><?= $this->Text->autoParagraph(h($<%= $singularVar %>-><%= $field %>)); ?></dd>
<% endforeach; %>
<% endif; %>
</dl>


<%
$relations = $associations['HasMany'] + $associations['BelongsToMany'];
foreach ($relations as $alias => $details):
$otherSingularVar = Inflector::variable($alias);
$otherPluralHumanName = Inflector::humanize($details['controller']);
%>

<?php
<%
$fieldArrayName = Inflector::camelize($details['property'])."Fields";
%>

$<%= $fieldArrayName %> = array(
<%
foreach ($details['fields'] as $field) {
    if ($field != 'created' && $field != 'modified' && $field != 'created_by' && $field != 'modified_by') {
        $isKey = false;
        if (!empty($associations['belongsTo'])) {
            foreach ($associations['belongsTo'] as $alias => $details) {
                if ($field === $details['foreignKey']) {
                    $isKey = true;
                    if ($field === "id") $label = 'Id';
                    else if (substr($field, -3) === "_id") $label = Inflector::humanize(substr($field, 0, strlen($field) - 3));
                    echo "\t\t'".$field."' => array('label' => __('".$label."'), 'link' => array('label_field' => '".$field."', 'controller' => '{$details['controller']}')),\n";
                    break;
                }
            }
        }
        if ($isKey !== true) {
            echo "\t\t'".$field."' => array('label' => __('".Inflector::humanize($field)."')),\n";
        }
    }
}
%>

);
echo $this->List->relatedPanel('<%= $details['property'] %>', $<%= $fieldArrayName %>, $<%= $singularVar %>-><%= $details['property'] %>, ['paginator'=>false]);
?>

<% endforeach; %>

<?php echo $this->Wet->whoDidIt($<%= $singularVar %>); ?>

<?php $this->start('wetkit-leftmenu-actions'); ?>
<ul class="list-group menu list-unstyled">
    <li><h3><a href="#"><?php echo __('Actions') ?></a></h3>
        <ul class="list-group list-unstyled">
            <li><?= $this->Html->link(__('Edit <%= $singularHumanName %>'), ['action' => 'edit', <%= $pk %>], ['class' => "list-group-item list-group-item-warning"]) ?> </li>
            <li><?= $this->Form->postLink(__('Delete <%= $singularHumanName %>'), ['action' => 'delete', <%= $pk %>], ['confirm' => __('Are you sure you want to delete # {0}?', <%= $pk %>), 'class' => "list-group-item list-group-item-danger"]) ?> </li>
            <li><?= $this->Html->link(__('List <%= $pluralHumanName %>'), ['action' => 'index'], ['class' => "list-group-item"]) ?> </li>
            <li><?= $this->Html->link(__('New <%= $singularHumanName %>'), ['action' => 'add'], ['class' => "list-group-item"]) ?> </li>
<%
            $done = [];
            foreach ($associations as $type => $data) {
            foreach ($data as $alias => $details) {
            if ($details['controller'] != $this->name && !in_array($details['controller'], $done)) {
            %>
            <li><?= $this->Html->link(__('List <%= $this->_pluralHumanName($alias) %>'), ['controller' => '<%= $details['controller'] %>', 'action' => 'index'], ['class' => "list-group-item"]) ?> </li>
            <li><?= $this->Html->link(__('New <%= Inflector::humanize(Inflector::singularize(Inflector::underscore($alias))) %>'), ['controller' => '<%= $details['controller'] %>', 'action' => 'add'], ['class' => "list-group-item"]) ?> </li>
<%
            $done[] = $details['controller'];
            }
            }
            }
            %>
        </ul>
    </li>
</ul>
<?php $this->end(); ?>
