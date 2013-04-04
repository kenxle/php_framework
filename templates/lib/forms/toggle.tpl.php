<?php ?>
<span class="<?=$element['class']?>">
<input type="checkbox"
		name="<?=$element['name']?>"
		id="<?=$element['id']?>"
		value="<?=$element['value'] ? $element['value'] : $element['name']?>"
		<?=($element['default'] == "on" ? "checked" : "")?>
	>
	<label for="<?=$element['id']?>">
	<?=$element['label']?></label> 
	
</span>