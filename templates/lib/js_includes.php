<?php

/**
 * options
 * @param $js_min_off 
 */

//the basics
?><script type="text/javascript" src="<?=JS_WEB_PATH?>lib/jquery-1.8.3.js"></script><?
// inline. usually has initPage. 
// inline_js should only be used for functions that need a value from PHP
if($inline_js){
	foreach($inline_js as $f){
		include(JS_ROOT.$f);
	}
}
	
// the regular js
    foreach($js_files as $f){
    	if($js_min_off){?>
		    <script type="text/javascript" src="<?=JS_WEB_PATH.$f?>" ></script>
    	<?}else{?>
	    	<script type="text/javascript" src="/min/?f=<?=$f?>" ></script>
    	<?}
    ?>
    <?}?>
    <script type="text/javascript">
   		$( function(){
			initPage();
   		});
    </script>
