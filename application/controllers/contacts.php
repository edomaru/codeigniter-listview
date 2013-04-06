<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contacts extends CI_Controller {

	function index($offset = 0) {
		$limit = 10;
		$this->load->library('listview');
		$data = $this->listview->filter('contacts', $limit, $offset);
		
		$config['table'] = array(
			'total_rows' => $data['total_rows'],
			'per_page' => $limit,
			'query' => $data['query'],
			'numbering' => array('active' => false),
			'p_key' => 'contact_id',
			'action' => array('position' => 'last', 'merge' => false),
			'fields' => array(
				'name' => 'Nama Lengkap', 
				'email' => 'Email',
				'address' => 'Alamat tinggal',
				'phone' => array(
					'format' => array(
						'form' => array(
							'type' => 'form_input'
						)
					)
				),
				'category_id' => array(
					'label' => 'Kategori',
					'format' => array(
						'form' => array(
							'type' => 'form_dropdown',
							'options' => $this->get_categories()
						)
					)
				)
			)
		);
		
		$this->listview->initialize($config);
		echo $this->listview->render();
	}
	
	private function get_categories($options = array()) {
		$query = $this->db->get('categories');
		foreach ($query->result() as $row) {
			$options[$row->category_id] = $row->category;
		}
		return $options;
	}
	
}

/* End of file contacts.php */
/* Location: ./application/controllers/contacts.php */
