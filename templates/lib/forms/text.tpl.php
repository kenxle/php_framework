<?php ?>

<? include (TEMPLATES_PATH . "lib/forms/label.tpl.php");?>
<input type="text" 
	name="<?=$element['name']?>"
	id="<?=$element['id']?>"
	class="<?=$element['class']?>"
	value="<?=$element['default']?>"
	<?=$element['display_options']['disabled'] ? "disabled='true'" : "" ?>
	<?=$element['display_options']['readonly'] ? "readonly='true'" : "" ?>
/>
<? include(TEMPLATES_PATH . "lib/forms/help_text.tpl.php")?>