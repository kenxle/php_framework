<?php ?>

<? include (TEMPLATES_PATH . "lib/forms/label.tpl.php");?>
<input type="file" 
	name="<?=$element['name']?>"
	id="<?=$element['id']?>"
	class="<?=$element['class']?>"
	size="<?=$element['display_options']['size'] ? $element['display_options']['size'] : 40?>"
	/>
	

<? include(TEMPLATES_PATH . "lib/forms/help_text.tpl.php")?>