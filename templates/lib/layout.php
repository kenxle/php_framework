<?php ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<?
		if($common_html_head_content) include($common_html_head_content); 
		else include(BASE_PATH. "templates/lib/head_content.tpl.php");
		if($specific_html_head_content) include($specific_html_head_content);
		include(BASE_PATH."templates/lib/social/facebook_open_graph_meta_tags.php");
		include(BASE_PATH."templates/lib/css_includes.php");
		include(BASE_PATH."templates/lib/js_includes.php");
	?>
	</head>
	<body<?=($body_id ? " id=$body_id" : "")?>>
		<div id="page_container">
		<?
			if($page_header) 	include($page_header);
			if($page_content) 	include($page_content);
			if($page_footer) 	include($page_footer);
		?>
		</div>
		<div id="debug">
			<?BENCHMARK::createReport();?>
			<?DEBUG::printBuffer();?>
			<?ERROR::printBuffer();?>
		</div>
	</body>
</html>
	
