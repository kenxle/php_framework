<?php
require_once(BASE_PATH . "config/config.form.php");
/**
 * <form> tags should be added outside of this file. 
 * This allows you to run the parser on multiple
 * sets of elements separately while remaining in the 
 * same form (might be useful for adding formatting in
 * between sets of elements). 
 * 
 * 	// How do put an extra element on the same line
	array(
		"label" => "Sales Lead Email: ", 
		"help_text" => "", 
		"type" => "text",
		"display_options" => array(
			"after_element" => " "
		), 
		"class" => $x_class, 
		"id" => "", 
		"name" => "sales_email",
		"required" => "true",
		"default" => ""
	),
		array(
		"label" => "CC", 
		"help_text" => "", 
		"type" => "toggle",
		"class" => $x_class, 
		"id" => "", 
		"name" => "cc_sales_email",
		"default" => "on"
	),
	
	//standard text input
	  	array(
		"label" => "Client: ", 
		"help_text" => "", 
		"type" => "text",
		"display_options" => array(), 
		"class" => $x_class, 
		"id" => "", 
		"name" => "your_email",
		"required" => "true",
		"default" => ""
	),
	
	//single select
	  	array(
		"label" => "DEC License: ", 
		"help_text" => "", 
		"type" => "single-select",
		"selections" => array(
			array("label" => "Basic", "value" => "basic"),
			array("label" => "Advanced", "value" => "advanced"),
			array("label" => "Premium", "value" => "premium")
		),
		"display_options" => array(), 
		"class" => $x_class, 
		"id" => "", 
		"name" => "dec_license",
		"required" => "false",
		"default" => ""
	),
	
	// multi select
	  	array(
		"label" => "Distro Points: ", 
		"help_text" => "Brand Site, Mobile, YouTube, Facebook, Orkut, etc - Ensure the files you are delivering include all deployment versions or note here what versions you are asking templates to review . Templates should always review every deployment set of comps", 
		"type" => "multi-select",
		"selections" => array(
			array("label" => "YouTube", "value" => "youtube"),
			array("label" => "Mobile YouTube", "value" => "mobileyoutube"),
			array("label" => "Facebook", "value" => "facebook"),
			array("label" => "Brand Site", "value" => "brandsite"),
			array("label" => "Mobile Brand Site", "value" => "mobilebrandsite"),
		),
		"display_options" => array(), 
		"class" => "", 
		"id" => "", 
		"name" => "distro",
		"required" => "false",
		"default" => ""
	),
	
	//multi line
	  	array(
		"label" => "Additional Notes: ", 
		"help_text" => "Any elaborations on the above? what you noticed from your review. If you did not review, please note that you have not looked at the files", 
		"type" => "text",
		"display_options" => array(
			"type" => "multi-line"
		), 
		"class" => "", 
		"id" => "additional_notes_input", 
		"name" => "additional_notes",
		"required" => "false",
		"default" => ""
	),
	
	//toggle
	  	array(
		"label" => "Show Email", 
		"help_text" => "To copy and paste as you like", 
		"type" => "toggle",
		"display_options" => array(), 
		"class" => $x_class, 
		"id" => "", 
		"name" => "show_email",
		"default" => "on"
	),
	
	//submit
	  	array(
		"label" => "Submit", 
		"help_text" => "", 
		"type" => "submit",
		"display_options" => array(), 
		"class" => $x_class, 
		"id" => "", 
		"required" => "false",
	),
	
	
 */

foreach ($form_elements as $element){
	
	
	//munge some data
	//name is name or id or label->tolower->stripped
	$element['name'] = 
	($element['name'] ? $element['name'] : 
		($element['id'] ? $element['id'] :
			str_replace( $label_strips, "", strtolower($element['label']) )
		)
	);
	//id is id or name or label->tolower->stripped
	$element['id'] = 
	($element['id'] ? $element['id'] :
		($element['name'] ? $element['name'] : 
			str_replace( $label_strips, "", strtolower($element['label']) )
		)
	);

	if(isset($element['display_options']['before_element'])){
		echo $element['display_options']['before_element'];
	}else{
		echo $form_delimiters['before_element'];
	}
	//grab the file for this type
	$file = get_template_file($element);
	//include this file
	include ($file);
	
	if(isset($element['display_options']['after_element'])){
		echo $element['display_options']['after_element'];
	}else{
		echo $form_delimiters['after_element'];
	}
}

