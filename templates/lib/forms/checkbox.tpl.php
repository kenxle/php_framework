<?php ?>

<span class="<?=$element['class']?>"><?=$element['label']?></span>
<?foreach($element['selections'] as $selection){?>
	<input type="checkbox"
		name="<?=$element['name']?>[]"
		id="<?=$element['id'].$selection['value']?>"
		value="<?=$selection['value']?>"
	>
	<label for="<?=$element['id'].$selection['value']?>">
	<?=$selection['label']?></label> &nbsp;&nbsp;&nbsp;
<?}