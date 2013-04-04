<?php ?>

<? include (TEMPLATES_PATH . "lib/forms/label.tpl.php");?>
<br />
<textarea name="<?=$element['name']?>"
	id="<?=$element['id']?>"
	class="<?=$element['class']?>"><?=$element['default']?>
</textarea>