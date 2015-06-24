



<?php $this->start('wetkit-leftmenu-actions'); ?>
<ul class="list-group menu list-unstyled">
    <li><h3><a href="#"><?php echo __('Actions') ?></a></h3>
        <ul class="list-group list-unstyled">
            <li><?= $this->Html->link(__('List Groups'), ['action' => 'index'], ['class' => "list-group-item"]) ?></li>
                                    <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index'], ['class' => "list-group-item"]) ?> </li>
                        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add'], ['class' => "list-group-item"]) ?> </li>
        </ul>
    </li>
</ul>
<?php $this->end(); ?>

        <div class="groups form large-10 medium-9 columns">
        <?= $this->Form->create($group); ?>
        <fieldset>
            <legend><?= __('Add Group') ?></legend>
            <?php
            echo $this->Form->input('name_eng');
            echo $this->Form->input('name_fra');
            echo $this->Form->input('acronym');
            echo $this->Form->input('description_eng');
            echo $this->Form->input('description_fra');
            echo $this->Form->input('is_removed');
            echo $this->Form->input('users._ids', ['options' => $users]);
                ?>
        </fieldset>
        <?= $this->Form->submit(__('Submit'), ['type' => 'submit']) ?>
        <?= $this->Form->button(__('Reset'), ['type' => 'reset']) ?>
    <?= $this->Form->end() ?>

    </div>
