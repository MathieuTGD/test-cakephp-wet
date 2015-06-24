

<h1 id="wb-cont" property="name">
    <?= __('Group') .__(':')." ". h($group->id) ?>
    <span class="pull-right">
        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $group->id], ['confirm' => __('Are you sure you want to delete # {0}?', $group->id), 'class' => "btn btn-danger"]) ?>
        <?= $this->Html->link(__('View'), ['action' => 'view', $group->id], ['class' => "btn btn-default"]) ?>
    </span>
</h1>


<?php $this->start('wetkit-leftmenu-actions'); ?>
<ul class="list-group menu list-unstyled">
    <li><h3><a href="#"><?php echo __('Actions') ?></a></h3>
        <ul class="list-group list-unstyled">
            <li><?= $this->Form->postLink(
                    __('Delete'),
                    ['action' => 'delete', $group->id],
                    ['confirm' => __('Are you sure you want to delete # {0}?', $group->id), 'class' => "list-group-item list-group-item-danger"]
                )
            ?></li>
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
            <legend><?= __('Edit Group') ?></legend>
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

            <?php echo $this->Wet->whoDidIt($group); ?>
    </div>
