<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['theme'] = "default";

$config['core'] = array(
	'table' => array(
		# daftar field yang akan ditampilkan dalam tabel
		'fields' => array(),

		# daftar field yang akan disembunyikan
		'hidden_fields' => array(),

		# nama field yang bertindak sebagai primary key
		'p_key' => null,

		# atribut HTML Table (class, id, style dll)
		'attr' => null,

		# opsi untuk mengaktifkan/menonaktifkan mulsi select
		# yaitu memungkinkan pengguna memilih beberapa baris record
		# yang nanti mungkin akan dihapus secara bersamaan
		'multi_select' => array(
			'active' => true,
			'attr' => array('style' => 'width:10px;'),
			'form' => array(
				'action' => "", 
				"attr" => array('class' => "formdata"),
				"checkbox" => array(
					'check_all' => array('class' => 'checkall', 'name' => 'check_all', 'value' => ""),
					'check_item' => array('class' => 'check_item', 'name' => 'check_item', 'value' => "")
				)
			)
		),

		# opsi untuk mengaktifkan/menonaktifkan penomeran otomatis pada baris tabel
		'numbering' => array(
			'active' => true,
			'label' => 'No',
			'attr' => array('style' => 'width:10px;')
		),

		# metode pengurutan
		# by: nama field yang akan digunakan untuk pengurutan
		# order: ASC|DESC
		'sort' => array('by' => null, 'order' => 'desc'),

		# Aksi tambahan untuk tabel (Edit, Delete)
		'action'			=> array(
			'attr' => array('style' => 'width:40px;'),
			'position' => 'first', #last | first | none
			'merge' => true, 
			'label' => 'Action',
			'links'	=> array(
				'edit'      => array('link_point' => 'href', 'attr' => array('title' => 'Edit')),
    			'delete'    => array('link_point' => 'href', 'attr' => array('title' => 'Delete', 'onclick' => "javascript:return confirm('Anda yakin akan menghapus data ini ?')"))
			)
		),

		# jumlah baris yang akan ditampilkan dalam satu halaman
		'per_page' => 20,

		# jumlah record keseluruhan
		'total_rows' => 0,

		# mysql Query yang nanti akan ditampilkan ke menjadi baris tabel
		'query' => null,

		# Nama controller
		# jika dikosongkan akan dicari otomatis
		'class_path' => "",

		# opsi jika tabel tidak akan dibuatkan tabel header <th>
		'no_header' => false,

		# alamat Url tabel ditampilkan
		'base_url' => '',

		# alamat view
		'view' => '_listview/_container'
	),
	'show_record' => array(
		'options' => array(20 => "20", 50 => "50", 100 => "100"),
		'total_rows' => 0
	),
	'pagination' => array(
		'base_url' => "",
		'total_rows' => 0,
		'per_page' => 20,
		'uri_segment' => 3
	),
	'search_form' => array(
		'filters' => null,
		'base_url' => "",
		"data" => array()		
	)
);

/* End of file listview_core.php */
/* Location: ./application/config/listview_core.php */

