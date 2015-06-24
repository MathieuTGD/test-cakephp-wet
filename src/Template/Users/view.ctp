<h1 id="wb-cont" property="name">
    <?= __('User') .__(':')." ". h($user->id) ?>
    <span class="pull-right">
        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id), 'class' => "btn btn-danger"]) ?>
        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $user->id], ['class' => "btn btn-warning"]) ?>
    </span>
</h1>


<dl class="dl-horizontal">
            <dt><?= __('First Name') ?></dt>
            <dd><?= h($user->first_name) ?></dd>
            <dt><?= __('Last Name') ?></dt>
            <dd><?= h($user->last_name) ?></dd>
            <dt><?= __('Email') ?></dt>
            <dd><?= h($user->email) ?></dd>
            <dt><?= __('Username') ?></dt>
            <dd><?= h($user->username) ?></dd>
            <dt><?= __('Id') ?></dt>
            <dd><?= $this->Number->format($user->id) ?></dd>
            <dt><?= __('Is Deactivated') ?></dt>
            <dd><?= $user->is_deactivated ? __('Yes') : __('No'); ?></dd>
            <dt><?= __('Ad Groups') ?></dt>
            <dd><?= $this->Text->autoParagraph(h($user->ad_groups)); ?></dd>
</dl>



<?php

$GroupsFields = array(
		'id' => array('label' => __('Id')),
		'name_eng' => array('label' => __('Name Eng')),
		'name_fra' => array('label' => __('Name Fra')),
		'acronym' => array('label' => __('Acronym')),
		'description_eng' => array('label' => __('Description Eng')),
		'description_fra' => array('label' => __('Description Fra')),
		'is_removed' => array('label' => __('Is Removed')),

);
echo $this->List->relatedPanel('groups', $GroupsFields, $user->groups, ['paginator'=>false]);
?>


<?php echo $this->Wet->whoDidIt($user); ?>

<?php $this->start('wetkit-leftmenu-actions'); ?>
<ul class="list-group menu list-unstyled">
    <li><h3><a href="#"><?php echo __('Actions') ?></a></h3>
        <ul class="list-group list-unstyled">
            <li><?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id], ['class' => "list-group-item list-group-item-warning"]) ?> </li>
            <li><?= $this->Form->postLink(__('Delete User'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id), 'class' => "list-group-item list-group-item-danger"]) ?> </li>
            <li><?= $this->Html->link(__('List Users'), ['action' => 'index'], ['class' => "list-group-item"]) ?> </li>
            <li><?= $this->Html->link(__('New User'), ['action' => 'add'], ['class' => "list-group-item"]) ?> </li>
            <li><?= $this->Html->link(__('List Groups'), ['controller' => 'Groups', 'action' => 'index'], ['class' => "list-group-item"]) ?> </li>
            <li><?= $this->Html->link(__('New Group'), ['controller' => 'Groups', 'action' => 'add'], ['class' => "list-group-item"]) ?> </li>
        </ul>
    </li>
</ul>
<?php $this->end(); ?>
