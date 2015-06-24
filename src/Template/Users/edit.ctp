

<h1 id="wb-cont" property="name">
    <?= __('User') .__(':')." ". h($user->id) ?>
    <span class="pull-right">
        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id), 'class' => "btn btn-danger"]) ?>
        <?= $this->Html->link(__('View'), ['action' => 'view', $user->id], ['class' => "btn btn-default"]) ?>
    </span>
</h1>


<?php $this->start('wetkit-leftmenu-actions'); ?>
<ul class="list-group menu list-unstyled">
    <li><h3><a href="#"><?php echo __('Actions') ?></a></h3>
        <ul class="list-group list-unstyled">
            <li><?= $this->Form->postLink(
                    __('Delete'),
                    ['action' => 'delete', $user->id],
                    ['confirm' => __('Are you sure you want to delete # {0}?', $user->id), 'class' => "list-group-item list-group-item-danger"]
                )
            ?></li>
            <li><?= $this->Html->link(__('List Users'), ['action' => 'index'], ['class' => "list-group-item"]) ?></li>
                                    <li><?= $this->Html->link(__('List Groups'), ['controller' => 'Groups', 'action' => 'index'], ['class' => "list-group-item"]) ?> </li>
                        <li><?= $this->Html->link(__('New Group'), ['controller' => 'Groups', 'action' => 'add'], ['class' => "list-group-item"]) ?> </li>
        </ul>
    </li>
</ul>
<?php $this->end(); ?>

        <div class="users form large-10 medium-9 columns">
        <?= $this->Form->create($user); ?>
        <fieldset>
            <legend><?= __('Edit User') ?></legend>
            <?php
            echo $this->Form->input('first_name');
            echo $this->Form->input('last_name');
            echo $this->Form->input('email');
            echo $this->Form->input('username');
            echo $this->Form->input('ad_groups');
            echo $this->Form->input('is_deactivated');
            echo $this->Form->input('groups._ids', ['options' => $groups]);
                ?>
        </fieldset>
        <?= $this->Form->submit(__('Submit'), ['type' => 'submit']) ?>
        <?= $this->Form->button(__('Reset'), ['type' => 'reset']) ?>
    <?= $this->Form->end() ?>

            <?php echo $this->Wet->whoDidIt($user); ?>
    </div>
