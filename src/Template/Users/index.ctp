
<h1 id="wb-cont" property="name"><?php echo __("Users"); ?>
    <span class="pull-right">
    <?= $this->Html->link(__('New User'), ['action' => 'add'], ['class' => 'btn btn-primary']) ?>
    </span>
</h1>


<?php

$userFields = [
		'id' => array('label' => __('Id')),
		'first_name' => array('label' => __('First Name')),
		'last_name' => array('label' => __('Last Name')),
		'email' => array('label' => __('Email')),
		'username' => array('label' => __('Username')),
		'is_deactivated' => array('label' => __('Is Deactivated')),
];


echo $this->List->create('users');
echo $this->List->header($userFields);

foreach ($users as $user) {
    if ($user->is_deactivated || $user->is_deleted || $user->is_removed) {
        echo $this->List->rowStart(['removed'=>true]);
    } else if ($user->is_obsolete || $user->is_deprecated ) {
        echo $this->List->rowStart(['obsolete' => true]);
    } else {
        echo $this->List->rowStart();
    }
    echo $this->List->cell($user->id, ['field' => 'id']);
    echo $this->List->cell($user->first_name, ['field' => 'first_name']);
    echo $this->List->cell($user->last_name, ['field' => 'last_name']);
    echo $this->List->cell($user->email, ['field' => 'email']);
    echo $this->List->cell($user->username, ['field' => 'username']);
    echo $this->List->cell($user->is_deactivated, ['field' => 'is_deactivated']);
    echo $this->List->cellActions($user->id, 'users');
    echo $this->List->rowEnd();
}

echo $this->List->end();
echo $this->List->paginatorControls();

?>

<?php $this->start('wetkit-leftmenu-actions'); ?>
<ul class="list-group menu list-unstyled">
    <li><h3><a href="#"><?php echo __('Actions') ?></a></h3>
        <ul class="list-group list-unstyled">
            <li><?= $this->Html->link(__('New User'), ['action' => 'add'], ['class' => "list-group-item"]) ?></li>
                        <li><?= $this->Html->link(__('List Groups'), ['controller' => 'Groups', 'action' => 'index'], ['class' => "list-group-item"]) ?> </li>
            <li><?= $this->Html->link(__('New Group'), ['controller' => 'Groups', 'action' => 'add'], ['class' => "list-group-item"]) ?> </li>
                    </ul>
    </li>
</ul>
<?php $this->end(); ?>


