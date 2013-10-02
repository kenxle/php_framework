<?php ?>
<span class="<?=$element['class']?>">
<input type="checkbox"
		name="<?=$element['name']?>"
		id="<?=$element['id']?>"
		value="<?=$element['value'] ? $element['value'] : $element['name']?>"
		<?=($element['default'] == "on" 
			|| $element['default'] == "checked" ? "checked" : "")?>
	>
	<label for="<?=$element['id']?>">
	<?=$element['label']?></label> 
	
</span>