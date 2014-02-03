<?php
// automatically adds a file named alterations.browser_name.css to the css list
// minimizes all css


if($css_min_off){
	foreach($css_files as $file){?>
		<link rel="stylesheet" type="text/css" href="<?=CSS_WEB_PATH. $file?>">
	<?}
}else{?>
	<link rel="stylesheet" type="text/css" href="/min/?debug&f=<?=implode(',', $css_files)?>">
<?}
// inline_css should only be used for styles that need a value from PHP
if($inline_css){
	foreach($inline_css as $f){
		include(CSS_ROOT.$f);
	}
}

