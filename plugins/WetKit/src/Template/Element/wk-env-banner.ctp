<?php
if (\Cake\Core\Configure::read("wetkit.env.envBar") === true):
    $current_env = \Cake\Core\Configure::read("wetkit.currentEnv");
?>
<div class="navbar-fixed-top"
     style="width: 100%; height: 30px; min-height: 30px; margin-bottom: 0; background-color: #ba3431; padding: 5px; font-weight: bold; color: white; text-shadow: none;">
    <span class="glyphicon glyphicon-exclamation-sign"></span>
    <?php echo __d('wet_kit', 'Alert: You are currently on a {0} environment ({1}).', current($current_env),
        $this->request->env('HTTP_HOST'));
    ?>
</div>
<?php
endif;