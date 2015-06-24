<h1 id="wb-cont" property="name">
    <?= __('Group') .__(':')." ". h($group->id) ?>
    <span class="pull-right">
        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $group->id], ['confirm' => __('Are you sure you want to delete # {0}?', $group->id), 'class' => "btn btn-danger"]) ?>
        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $group->id], ['class' => "btn btn-warning"]) ?>
    </span>
</h1>


<dl class="dl-horizontal">
            <dt><?= __('Name Eng') ?></dt>
            <dd><?= h($group->name_eng) ?></dd>
            <dt><?= __('Name Fra') ?></dt>
            <dd><?= h($group->name_fra) ?></dd>
            <dt><?= __('Acronym') ?></dt>
            <dd><?= h($group->acronym) ?></dd>
            <dt><?= __('Id') ?></dt>
            <dd><?= $this->Number->format($group->id) ?></dd>
            <dt><?= __('Is Removed') ?></dt>
            <dd><?= $group->is_removed ? __('Yes') : __('No'); ?></dd>
            <dt><?= __('Description Eng') ?></dt>
            <dd><?= $this->Text->autoParagraph(h($group->description_eng)); ?></dd>
            <dt><?= __('Description Fra') ?></dt>
            <dd><?= $this->Text->autoParagraph(h($group->description_fra)); ?></dd>
</dl>



<?php

$UsersFields = array(
		'id' => array('label' => __('Id')),
		'first_name' => array('label' => __('First Name')),
		'last_name' => array('label' => __('Last Name')),
		'email' => array('label' => __('Email')),
		'username' => array('label' => __('Username')),
		'ad_groups' => array('label' => __('Ad Groups')),
		'is_deactivated' => array('label' => __('Is Deactivated')),

);
echo $this->List->relatedPanel('users', $UsersFields, $group->users, ['paginator'=>false]);
?>


<?php echo $this->Wet->whoDidIt($group); ?>

<?php $this->start('wetkit-leftmenu-actions'); ?>
<ul class="list-group menu list-unstyled">
    <li><h3><a href="#"><?php echo __('Actions') ?></a></h3>
        <ul class="list-group list-unstyled">
            <li><?= $this->Html->link(__('Edit Group'), ['action' => 'edit', $group->id], ['class' => "list-group-item list-group-item-warning"]) ?> </li>
            <li><?= $this->Form->postLink(__('Delete Group'), ['action' => 'delete', $group->id], ['confirm' => __('Are you sure you want to delete # {0}?', $group->id), 'class' => "list-group-item list-group-item-danger"]) ?> </li>
            <li><?= $this->Html->link(__('List Groups'), ['action' => 'index'], ['class' => "list-group-item"]) ?> </li>
            <li><?= $this->Html->link(__('New Group'), ['action' => 'add'], ['class' => "list-group-item"]) ?> </li>
            <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index'], ['class' => "list-group-item"]) ?> </li>
            <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add'], ['class' => "list-group-item"]) ?> </li>
        </ul>
    </li>
</ul>
<?php $this->end(); ?>
