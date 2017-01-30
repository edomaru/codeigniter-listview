<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['default'] = array(
	'search_form' => array(
		'view' 				=> "_listview/_search_form"
	),
	'show_record' => array(
		'view'				=> "_listview/_show_record"
	),
	'pagination' => array(
		"full_tag_open" 			=> '<div class="col-md-12" style="text-align:center;"><ul class="pagination" style="margin:0px;"> ' . PHP_EOL,
		"full_tag_close" 			=> '</ul></div>',
		"use_page_numbers" 			=> TRUE,
		"prev_link"					=> '&laquo;',
		"next_link" 				=> '&raquo;',
		"first_tag_open"			=>"<li>",
		"first_tag_close"			=>"</li>",
		"next_tag_open"				=>'<li>',
		"next_tag_close"			=>'</li>',
		'prev_tag_open'				=>'<li>',
		'prev_tag_close'			=>'</li>',
		'num_tag_open'				=>'<li>',
		'num_tag_close'				=>'</li>',
		'cur_tag_open'				 => ' <li class="active"><a href="#">',
		'cur_tag_close' 			=> '</a></li>'			
	),
	'table' => array(
		'attr'					=>array('class'=>'table table-responsive table-striped table-hover ')
		/*'action'			=> array(
			'attr' => array('style' => 'width:40px;'),
			'position' => 'first', 
			'merge' => true, 
			'label' => 'Aksi',
			'links'	=> array(
				'edit'      => array('link_point' => 'href', 'attr' => array('title' => 'Edit')),
    			'delete'    => array('link_point' => 'href', 'attr' => array('title' => 'Delete'))
			)
		)*/
	)
);

/* End of file green.php */
/* Location: ./config/listview_default.php */

