<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['default'] = array(
	'search_form' => array(
		// 'full_tag_open' 	=> '<div class="searchform">',
		// 'full_tag_close' 	=> '</div>',
		'view' 				=> "_listview/_search_form"
	),
	'show_record' => array(
		// 'full_tag_open'		=> '<div class="table-info">',
		// 'full_tag_close'	=> '</div>',
		'view'				=> "_listview/_show_record"
	),
	'pagination' => array(
		"full_tag_open" 	=> '<div id="paging">' . PHP_EOL,
		"full_tag_close" 	=> '</div>',
		'anchor_class' 		=> "class='btn'",
		"first_link" 		=> "&laquo; Awal",
		"last_link"			=> "Akhir &raquo;",
		'prev_link' 		=> 'Sebelumnya',
		'next_link' 		=> 'Selanjutnya',
		'cur_tag_open' 		=> "\n<a href='#' class='btn blue'>",
		'cur_tag_close' 	=> '</a>'
	),
	'table' => array(
		'action'			=> array(
			'attr' => array('style' => 'width:40px;'),
			'position' => 'last', #last or first
			'merge' => true, 
			'label' => 'Aksi',
			'links'	=> array(
				'edit'      => array('link_point' => 'data-url', 'attr' => array('title' => 'Edit', 'data-title' => 'Form Edit', 'data-class' => 'edit', 'class' => 'uibutton edit-icon tiptip', 'onclick' => 'windowForm($(this)); return false;')),
    			'delete'    => array('link_point' => 'data-url', 'attr' => array('title' => 'Delete', 'class' => 'uibutton delete-icon red tiptip', 'onclick' => 'deleteData($(this)); return false;'))
			)
		)
	)
);

/* End of file green.php */
/* Location: ./config/listview_default.php */

