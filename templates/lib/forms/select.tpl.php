<?php ?>


<? include (TEMPLATES_PATH . "lib/forms/label.tpl.php");?>
<select name="<?=$element['name']?>"
	id="<?=$element['id']?>"
	class="<?=$element['class']?>"
>
	
	<?if(!$element['display_options']['exclude_blank_option']){?>
		<option value=""></option>
	<?}?>
	<?foreach($element['selections'] as $selection){?>
		<option value="<?=$selection['value']?>"
			<?=$element['default'] == $selection['value'] ? "selected='selected'" : ""?>>
			<?=$selection['label']?>
		</option>
	<?}?>

</select>

<? include(TEMPLATES_PATH . "lib/forms/help_text.tpl.php")?>
	