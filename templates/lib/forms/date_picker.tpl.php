<?php ?>

<? include (TEMPLATES_PATH . "lib/forms/label.tpl.php");?>
<input type="text" 
	name="<?=$element['name']?>"
	id="<?=$element['id']?>"
	class="<?=$element['class'] ? $element['class'] : "datepicker"?>"
/>
<?/**
includes an automatic class so that it pairs with
activate_datepicker_inline.php
Set new classes at your own will, but it's not necessary
unless there's a class name collision
*/
?>