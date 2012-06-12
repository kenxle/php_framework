<?php

/**
 * options
 * @param $js_min_off 
 */

//the basics
?><script type="text/javascript" src="/js/lib/prototype/prototype.js"></script><?
// inline. usually has initPage. 
// inline_js should only be used for functions that need a value from PHP
if($inline_js){
	foreach($inline_js as $f){
		include(BASE_PATH."www/".$f);
	}
}
	
// the regular js
    foreach($js_files as $f){
    	if($js_min_off){?>
		    <script type="text/javascript" src="/<?=$f?>" ></script>
    	<?}else{?>
	    	<script type="text/javascript" src="/min/?f=<?=$f?>" ></script>
    	<?}
    ?>
    <?}?>
    <script type="text/javascript">
   		document.observe("dom:loaded", function(){
			initPage();
   		});
    </script>
