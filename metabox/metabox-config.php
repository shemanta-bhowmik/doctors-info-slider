<?php 


add_action('cmb2_admin_init', 'sujan_metabox_fields_add');

function sujan_metabox_fields_add(){

	$prefix = '_prefix_';

	$metaboxsection = new_cmb2_box(array(
		'title' 		=> __('Informations', 'doctors-info'),
		'id'			=> 'doctors-info-fields',
		'object_types' 	=> array('doctors_info')
	));

	$metaboxsection->add_field(array(
		'name' 		=> __('Name', 'doctors-info'),
		'type'		=> 'text',
		'id'		=> $prefix . 'doctors_name'
	));

	

	$metaboxsection->add_field(array(
		'name' 		=> __('Age', 'doctors-info'),
		'type'		=> 'text',
		'id'		=> $prefix . 'doctors_age'
	));

	$metaboxsection->add_field(array(
		'name' 		=> __('Degree', 'doctors-info'),
		'type'		=> 'text',
		'id'		=> $prefix . 'doctors_degree'
	));

	$metaboxsection->add_field(array(
		'name' 		=> __('Chamber', 'doctors-info'),
		'type'		=> 'wysiwyg',
		'id'		=> $prefix . 'doctors_chamber'
	));




}