<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['default'] = array(
	'search_form' => array(
		'view' 				=> "_listview/_search_form"
	),
	'show_record' => array(
		'view'				=> "_listview/_show_record"
	),
	'pagination' => array(
		"full_tag_open" 	=> '<div id="paging">' . PHP_EOL,
		"full_tag_close" 	=> '</div>'		
	),
	'table' => array(
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

