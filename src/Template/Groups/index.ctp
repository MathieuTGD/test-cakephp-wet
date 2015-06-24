
<h1 id="wb-cont" property="name"><?php echo __("Groups"); ?>
    <span class="pull-right">
    <?= $this->Html->link(__('New Group'), ['action' => 'add'], ['class' => 'btn btn-primary']) ?>
    </span>
</h1>


<?php

$groupFields = [
		'id' => array('label' => __('Id')),
		'name_eng' => array('label' => __('Name Eng')),
		'name_fra' => array('label' => __('Name Fra')),
		'acronym' => array('label' => __('Acronym')),
		'is_removed' => array('label' => __('Is Removed')),
];


echo $this->List->create('groups');
echo $this->List->header($groupFields);

foreach ($groups as $group) {
    if ($group->is_deactivated || $group->is_deleted || $group->is_removed) {
        echo $this->List->rowStart(['removed'=>true]);
    } else if ($group->is_obsolete || $group->is_deprecated ) {
        echo $this->List->rowStart(['obsolete' => true]);
    } else {
        echo $this->List->rowStart();
    }
    echo $this->List->cell($group->id, ['field' => 'id']);
    echo $this->List->cell($group->name_eng, ['field' => 'name_eng']);
    echo $this->List->cell($group->name_fra, ['field' => 'name_fra']);
    echo $this->List->cell($group->acronym, ['field' => 'acronym']);
    echo $this->List->cell($group->is_removed, ['field' => 'is_removed']);
    echo $this->List->cellActions($group->id, 'groups');
    echo $this->List->rowEnd();
}

echo $this->List->end();
echo $this->List->paginatorControls();

?>

<?php $this->start('wetkit-leftmenu-actions'); ?>
<ul class="list-group menu list-unstyled">
    <li><h3><a href="#"><?php echo __('Actions') ?></a></h3>
        <ul class="list-group list-unstyled">
            <li><?= $this->Html->link(__('New Group'), ['action' => 'add'], ['class' => "list-group-item"]) ?></li>
                        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index'], ['class' => "list-group-item"]) ?> </li>
            <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add'], ['class' => "list-group-item"]) ?> </li>
                    </ul>
    </li>
</ul>
<?php $this->end(); ?>


