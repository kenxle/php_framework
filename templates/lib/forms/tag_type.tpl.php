<?php
?>

<? include (TEMPLATES_PATH . "lib/forms/label.tpl.php");?>

<select class="<?=$element['class']?>"
		name="<?=$element['name']?>" 
		id="<?=$element['id']?>">
	<option value="1" title="">javascript - synchronous</option>
	<option value="3" title="">iframe</option>
	<option value="4" title="">image only</option>
</select>
