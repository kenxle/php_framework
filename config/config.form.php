<?php

$p = TEMPLATES_PATH . "lib/forms/";
$label_strips = array(" ", ":", "?", "/", "\\", "|", ";", "&", "(", ")");

/**
 * 
 * This var points the way to the template files that 
 * comprise a form. 
 * 
 * The first dimension are the input types. 
 * 
 * Second dimensions are the display options 
 * for each type.
 * 
 * When a element's data is contructed it can have a 
 * main type and then some display options. The 
 * display options here can point it to a new file. 
 * If there are no display options, it will always 
 * choose the default, so default should always be
 * present. 
 * 
 * This allows you to define a number of abstract
 * types and subtypes without having them tied to any
 * specific implementation method. 
 * 
 * $form_config = array(
 * 		'element_type' => array(
 * 			'display_option' => 'template_file_path.tpl.php',
 * 			'default' => 'template_file_path.tpl.php'
 * 		)
 * )
 * @var unknown_type
 */
$form_config = array(
	"text" => array(
		"multi-line" => $p."text_multiline.tpl.php",
		"default" => $p."text.tpl.php"
	),
	"single-select" => array(
		"radio" => $p."radio.tpl.php",
		"default" => $p."select.tpl.php"
	),
	"multi-select" => array(
		"checkbox" => $p."checkbox.tpl.php",
		"default" => $p."checkbox.tpl.php"
	),
	"toggle" => array(
		"default" => $p."toggle.tpl.php"
	),
	"date" => array(
		"default" => $p. "date_picker.tpl.php"
	),
	"submit" => array(
		"default" => $p."submit.tpl.php"
	),
);


function get_template_file($element){
	global $form_config;
	
	if($obj_type = $form_config[$element['type']]){
		if($disp_type = $obj_type[$element['display_options']['type']]){
			return $disp_type;
		}else{
			return $obj_type['default'];
		}
	}else{
		ERROR::writeln("form type not found: ". $element['type']);
		return null;
	}
}

function get_input_names($form_structure){
	$names_arr = array();
	foreach ($form_structure as $element){
		$names_arr[] = $element['name'];
	}
	
	return $names_arr;
	
}