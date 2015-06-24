



<?php $this->start('wetkit-leftmenu-actions'); ?>
<ul class="list-group menu list-unstyled">
    <li><h3><a href="#"><?php echo __('Actions') ?></a></h3>
        <ul class="list-group list-unstyled">
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
            <legend><?= __('Add User') ?></legend>
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

    </div>
