<?php 
	if (!isset($type)) $type = 'danger';
	//success
	//info
	//warning
	//danger
?>

<div class="alert alert-<?php echo $type ?>">
	<p><?php echo $message; ?></p>
</div>