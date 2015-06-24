<?php

/**
 * This is a general purpose element to output item lists
 *
 * @param string $model - the name of the model associated with this list (ex.: Asset)
 * @param object $items - a table entity object of items that the element must display
 * @param boolean $showActions - Show actions if set to TRUE
 * @param array $fields - an array of the fields (with some params) that should be outputed
 *    $fields = array(
 *    'asset_id' => array('label' => __('Asset'), 'link' => array('label_field' => 'asset_id', 'controller' => 'assets')),
 *    'version_number',
 *    'svn_revision',
 *    'release_date',
 *  'is_deleted' => array('as_bool' => true)
 *    );
 * @param string $add_button_param
 * @param array $encryptedFields - an array of encrypted fields strings (i.e. ['article.comment',...] )
 *      These will be appended to the wetkit Configuration Encrypted Fields
 *      (Configure::read('wetkit.encryptedFields'))
 * @param array $actions - list of additional actions besided delete, edit and view
 *    $actions = array(
 *    array(
 *        'label' => __('Login As'),
 *        'condition' => (AuthComponent::user('group_id') == '1' ? true : false),
 *        'class' => 'btn-danger',
 *        'controller' => 'users',
 *        'action' => 'impersonate',
 *        'action_param' => 'id',
 *        'is_post_link' => true,
 *        'post_link_warning' => __('Are you sure you want to impersonate this user?')
 *    ),
 *    array(
 *        'label' => __('Reset'),
 *        'condition' => (AuthComponent::user('group_id') == '1' ? true : false),
 *        'class' => 'btn-danger',
 *        'controller' => 'users',
 *        'action' => 'adminReset',
 *        'action_param' => 'id',
 *    ),
 *    );
 * @param string $label - label for the entire table (defaults to Related Something)
 * @param string $boolTrue - String to print for boolean TRUE
 * @param string $boolFalse - String to print for boolean False
 */

use Cake\Utility\Inflector;
use Cake\Core\Configure;

if (!isset($showActions)) $showActions = true;
if (!isset($dateTimeFormat)) $dateTimeFormat = Configure::read('wetkit.dateTimeFormat');

$defaultEncryptedFields = Configure::read("wetkit.encryptedFields");
if (!isset($encryptedFields) || !is_array($encryptedFields)) $encryptedFields = array();
$encryptedFields += $defaultEncryptedFields;


$controller = Inflector::camelize(Inflector::pluralize($model));  //asset_versions
$model_label_p = Inflector::humanize($controller); //Asset Versions
$model_label_s = Inflector::singularize($model_label_p); //Asset Version


if (!isset($boolTrue)) $boolTrue = $this->Wet->iconValid() . ' ' . __d('wet_kit', 'Yes');
if (!isset($boolFalse)) $boolFalse = $this->Wet->iconInvalid() . ' ' . __d('wet_kit', 'No');




    $isAdmin = $wetkit['isAdmin'];


?>

<?php
if ($this->request->controller != $controller) {
    if (!isset($label)) {
        $label = __d('wet_kit', 'Related {0}', __d('wet_kit', $model_label_p));
    }
    ?>
    <div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?php echo $label; ?></h3>
    </div>
<?php } ?>

<?php if (!empty($items)) { ?>
	<div class="table-responsive">
		<table class="table table-condensed table-striped">
			<tr>
				<?php if ($this->request->controller == $controller) {
    foreach ($fields as $key => $val) {
        if (!isset($val['label'])) {
            $val['label'] = null;
            if (strpos($key, $lang) !== false) {
                $val['label'] = Inflector::humanize(str_replace('_' . $lang, "", $key));
            }
            if ($key == 'name' || $val['label'] == 'name') {
                $val['label'] = __d('wet_kit', 'Name');
            }
            if ($key == 'description' || $val['label'] == 'description') {
                $val['label'] = __d('wet_kit', 'Description');
            }
        }
        if (!isset($val['as_bool'])) {
            $val['as_bool'] = false;
        }
        if (substr($key, 0, 3) === "is_") {
            $val['as_bool'] = true;
        }
        ?>
        <th <?php if ($val['as_bool']) {
            echo 'class="text-center"'; } ?>>
            <?php
            //show encrypted icon
            if (in_array($model . '.' . $key, $encryptedFields)) {
                echo $this->Misc->showEncryptedNotice();
            }

            if (isset($val['label'])) {
                echo $this->Paginator->sort($key, $val['label']);
            } else {
                echo $this->Paginator->sort($key);
            }
            ?>
        </th>
    <?php } ?>

<?php } else { ?>

    <?php foreach ($fields as $key => $val) {
        if (!isset($val['label'])) {
            $val['label'] = null;
            if (strpos($key, $lang) !== false) {
                $val['label'] = Inflector::humanize(str_replace('_' . $lang, "", $key));
            }
        }
        if (!isset($val['as_bool'])) {
            $val['as_bool'] = false;
        }
        if (substr($key, 0, 3) === "is_") {
            $val['as_bool'] = true;
        } ?>

        <th>
            <?php
            //show encrypted icon
            if (in_array($model . '.' . $key, $encryptedFields)) {
                echo $this->Wet->showEncryptedNotice();
            }

            if (isset($val['label'])) {
                echo $val['label'];
            } else {
                echo Inflector::humanize($key);
            }
            ?>
        </th>
    <?php } ?>

<?php } ?>
				<?php if ($showActions === true) { ?>
				<th class="text-center col-sm-3"><?php echo __d('wet_kit', 'Actions'); ?></th>
				<?php } ?>
			</tr>
			<?php
    foreach ($items as $item)
    {
        if (isset($item[$model])) {
            $item = $item[$model];
        }
        $classes = array();
        $isDeleted = false;
        $isModified = false;
        $isCancelled = false;
        $linkClasses = "";
        $delIcon = "";

        if (isset($item->is_removed)) {
            if ($item->is_removed == true) {
                $classes[] = 'danger';
                $isDeleted = true;
                $linkClasses = "text-muted";
                $delIcon = '<span style="display: block; position: absolute; left: -1.2em; top: 0.5em;" title="' . __d('wet_kit', "Deleted") . '" class="glyphicon glyphicon-remove text-danger"></span> ';
            }
        }
        if (isset($item->is_obsolete)) {
            if ($item->is_obsolete == true) {
                $classes[] = 'warning';
                $isDeleted = true;
                $linkClasses = "text-muted";
                $delIcon = '<span style="display: block; position: absolute; left: -1.2em; top: 0.5em;" title="' . __d('wet_kit', "Obsolete") . '" class="glyphicon glyphicon-minus-sign text-warning"></span> ';
            }
        }
        ?>

        <tr <?php if (count($classes) > 0) {
            echo 'class="' . implode(" ", $classes) . '"'; }?>>

            <?php
            $i = 0;
            foreach ($fields as $key => $val) {
                if (!isset($val['as_bool'])) {
                    $val['as_bool'] = false;
                }
                if (substr($key, 0, 3) === "is_") {
                    $val['as_bool'] = true;
                }

                ?>
                <td <?php if ($val['as_bool']) {
                    echo 'class="text-center"';
                }
                echo 'style="position: relative;"'; ?>>
                    <?php
                    if ($i == 0) {
                        echo $delIcon;
                    }
                    if ($isDeleted) {
                        echo '<span class="text-muted">';
                    }

                    if (isset($val['link'])) {
                        if ($key == 'user_id') {
                            if ($isAdmin == '1') {
                                echo $this->Html->link($item[$val['link']['label_field']],
                                    array('controller' => $val['link']['controller'], 'action' => 'view', $item[$key]),
                                    array("class" => $linkClasses));
                            } else {
                                echo $item[$val['link']['label_field']];
                            }
                        } else {
                            echo $this->Html->link($item[$val['link']['label_field']],
                                array('controller' => $val['link']['controller'], 'action' => 'view', $item[$key]),
                                array("class" => $linkClasses));
                        }
                    } else {
                        if ($val['as_bool']) {
                            echo $item[$key] ? $boolTrue : $boolFalse;
                        } else {
                            if (strpos($key, 'email') !== false) {
                                echo '<a href="mailto:' . $item[$key] . '" class="' . $linkClasses . '">' . $item[$key] . '</a>';
                            } else {
                                if (strpos($key, 'url') !== false) {
                                    echo '<a href="' . $item[$key] . '" class="' . $linkClasses . '">' . $item[$key] . '</a>';
                                } else {
                                    if (strpos($key, 'amount') !== false || strpos($key,
                                            'cost') !== false || strpos($key, 'fee') !== false || strpos($key,
                                            'price') !== false
                                    ) {
                                        echo $this->Number->currency($item[$key]);
                                    } else {
                                        if ($key == 'icon') {
                                            echo '<span class="glyphicon ' . $item['icon'] . '"></span>';
                                        } else {
                                            echo $item[$key];
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if ($isDeleted) {
                        echo "</span>";
                    }
                    ?>
                </td>
                <?php $i++;
            } ?>


            <?php if ($showActions === true) { ?>
            <td class="text-center">
                <?php
                if (isset($actions)) {
                    foreach ($actions as $action) {

                        //Initialize variables and setting missing ones to null
                        if (isset($action['actionParam'])) {
                            $param = $action['actionParam'];
                            $param = $item[$param];
                        } else {
                            $param = null;
                        }

                        if (!isset($action['isPostLink'])) {
                            $action['isPostLink'] = false;
                        }
                        if (!isset($action['postLinkWarning'])) {
                            $action['postLinkWarning'] = __d('wet_kit', 'Are you sure you want to perform this action?');
                        }

                        if ($action['isPostLink']) {
                            if (isset($action['condition'])) {
                                if ($action['condition']) {
                                    echo $this->Form->postLink($action['label'], array(
                                        'controller' => $action['controller'],
                                        'action' => $action['action'],
                                        $param
                                    ), array('class' => 'btn btn-xs ' . $action['class']), $action['postLinkWarning']);
                                }
                            } else {
                                echo $this->Form->postLink($action['label'],
                                    array('controller' => $action['controller'], 'action' => $action['action'], $param),
                                    array('class' => 'btn btn-xs ' . $action['class']), $action['postLinkWarning']);
                            }
                        } else {
                            if (isset($action['condition'])) {
                                if ($action['condition']) {
                                    echo $this->Html->link($action['label'], array(
                                        'controller' => $action['controller'],
                                        'action' => $action['action'],
                                        $param
                                    ), array('class' => 'btn btn-xs ' . $action['class']));
                                }
                            } else {
                                echo $this->Html->link($action['label'],
                                    array('controller' => $action['controller'], 'action' => $action['action'], $param),
                                    array('class' => 'btn btn-xs ' . $action['class']));
                            }
                        }
                    }
                }
                ?>
                <?php if ($isAdmin == '1') {
                    echo $this->Form->postLink(__d('wet_kit', 'Delete'),
                        array('controller' => $controller, 'action' => 'delete', $item['id']),
                        array('class' => 'btn btn-xs btn-danger'),
                        __d('wet_kit', 'Are you sure you want to delete {0} # {1}?', $model_label_s, $item['id']));
                } ?>
                <?php echo $this->Html->link(__d('wet_kit', 'View'),
                    array('controller' => $controller, 'action' => 'view', $item['id']),
                    array('class' => 'btn btn-xs btn-default')); ?>
                <?php if ($isAdmin == '1') {
                    echo $this->Html->link(__d('wet_kit', 'Edit'),
                        array('controller' => $controller, 'action' => 'edit', $item['id']),
                        array('class' => 'btn btn-xs btn-warning'));
                } ?>
            </td>
            <?php } // end IF showActions ?>
        </tr>
    <?php } // End foreach ?>
		</table>
	</div>
	<?php if ($this->request->controller == $controller) { ?>
		<nav style="margin-top: 0">
			<span class="pull-right">
				<small><?php echo $this->Paginator->counter(array('format' => __d('wet_kit', 'Showing {{start}} to {{end}} of {{count}} entries'))); ?></small>
			</span>
			<ul class="pagination pull-left" style="margin-top: 0">
			<?php
                echo $this->Paginator->prev(__d('wet_kit', 'previous'));
                echo $this->Paginator->numbers();
                echo $this->Paginator->next(__d('wet_kit', 'next'));
            ?>
			</ul>
		</nav>
	<?php } ?>
<?php } else { ?>
    <?php if ($this->request->controller == $controller) { ?>
        <div class="panel-body"><?php echo __d('wet_kit', 'There are no {0}', strtolower($model_label_p)) ?></div>
    <?php } else { ?>
        <div class="panel-body"><?php echo __d('wet_kit', 'There are no related {0}', strtolower($model_label_p)) ?></div>
    <?php } ?>
<?php } ?>

<?php if (($isAdmin == '1') && $this->request->controller != $controller) { ?>
    <div class="panel-footer">
        <?php
        if (isset($add_button_param)) {
            echo $this->Html->link(__d('wet_kit', 'New {0}', $model_label_s),
                array('controller' => $controller, 'action' => 'add', $add_button_param),
                array('class' => 'btn btn-primary'));
        } else {
            echo $this->Html->link(__d('wet_kit', 'New {0}', $model_label_s),
                array('controller' => $controller, 'action' => 'add'), array('class' => 'btn btn-primary'));
        }
        ?>
    </div>
<?php } ?>

<?php if ($this->request->controller != $controller) { ?>
    </div>
<?php } ?>