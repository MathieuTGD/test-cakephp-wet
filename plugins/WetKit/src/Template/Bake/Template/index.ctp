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

$fields = collection($fields)
->filter(function($field) use ($schema) {
return !in_array($schema->columnType($field), ['binary', 'text']);
})
->take(7);
%>

<h1 id="wb-cont" property="name"><?php echo __("<%= $pluralHumanName %>"); ?>
    <span class="pull-right">
    <?= $this->Html->link(__('New <%= $singularHumanName %>'), ['action' => 'add'], ['class' => 'btn btn-primary']) ?>
    </span>
</h1>


<?php
<%
$fieldArrayName = $singularVar."Fields";
%>

$<%= $fieldArrayName %> = [
<%
foreach ($fields as $field) {
    if ($field != 'created' && $field != 'modified' && $field != 'created_by' && $field != 'modified_by') {
        $isKey = false;
        if (!empty($associations['belongsTo'])) {
            foreach ($associations['belongsTo'] as $alias => $details) {
                if ($field === $details['foreignKey']) {
                    $isKey = true;
                    if ($field === "id") $label = 'Id';
                    else if (substr($field, -3) === "_id") $label = Inflector::humanize(substr($field, 0, strlen($field) - 3));
                    echo "\t'".$field."' => array('label' => __('".$label."'), 'link' => array('label_field' => '".$field."', 'controller' => '{$details['controller']}')),\n";
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
];


echo $this->List->create('<%= $schema->name() %>');
echo $this->List->header($<%= $fieldArrayName %>);

foreach ($<%= $pluralVar %> as $<%= $singularVar %>) {
    if ($<%= $singularVar %>->is_deactivated || $<%= $singularVar %>->is_deleted || $<%= $singularVar %>->is_removed) {
        echo $this->List->rowStart(['removed'=>true]);
    } else if ($<%= $singularVar %>->is_obsolete || $<%= $singularVar %>->is_deprecated ) {
        echo $this->List->rowStart(['obsolete' => true]);
    } else {
        echo $this->List->rowStart();
    }
<%
        foreach ($fields as $field) {
            if ($field != 'created' && $field != 'modified' && $field != 'created_by' && $field != 'modified_by') {
                $isKey = false;
                if (!empty($associations['BelongsTo'])) {
                    foreach ($associations['BelongsTo'] as $alias => $details) {
                        if ($field === $details['foreignKey']) {
                            $isKey = true;
%>
    echo $this->List->cell($this->Url->build($<%= $singularVar %>-><%= $details['property'] %>-><%= $details['displayField'] %>, ['controller' => '<%= $details['controller'] %>', 'action' => 'view', $<%= $singularVar %>-><%= $details['property'] %>-><%= $details['primaryKey'][0] %>]), ['field' => '<%= $details['displayField'] %>']);
<%
                        break;
                    }
                    }
                }
                if ($isKey !== true) {
%>
    echo $this->List->cell($<%= $singularVar %>-><%= $field %>, ['field' => '<%= $field %>']);
<%
            }
                $pk = '$' . $singularVar . '->' . $primaryKey[0];
            }
        }
%>
    echo $this->List->cellActions($<%= $singularVar %>->id, '<%= $pluralVar %>');
    echo $this->List->rowEnd();
}

echo $this->List->end();
echo $this->List->paginatorControls();

?>

<?php $this->start('wetkit-leftmenu-actions'); ?>
<ul class="list-group menu list-unstyled">
    <li><h3><a href="#"><?php echo __('Actions') ?></a></h3>
        <ul class="list-group list-unstyled">
            <li><?= $this->Html->link(__('New <%= $singularHumanName %>'), ['action' => 'add'], ['class' => "list-group-item"]) ?></li>
            <%
            $done = [];
            foreach ($associations as $type => $data):
            foreach ($data as $alias => $details):
            if ($details['controller'] != $this->name && !in_array($details['controller'], $done)):
            %>
            <li><?= $this->Html->link(__('List <%= $this->_pluralHumanName($alias) %>'), ['controller' => '<%= $details['controller'] %>', 'action' => 'index'], ['class' => "list-group-item"]) ?> </li>
            <li><?= $this->Html->link(__('New <%= $this->_singularHumanName($alias) %>'), ['controller' => '<%= $details['controller'] %>', 'action' => 'add'], ['class' => "list-group-item"]) ?> </li>
            <%
            $done[] = $details['controller'];
            endif;
            endforeach;
            endforeach;
            %>
        </ul>
    </li>
</ul>
<?php $this->end(); ?>


