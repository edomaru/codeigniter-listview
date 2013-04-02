<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Demo extends CI_Controller {

	function basic($offset = 0) 
	{	
		$limit = 10;
		$config['table'] = array(
			'total_rows' 	=> $this->db->count_all("actor"),
			'query' 		=> $this->db->limit($limit, (int) $offset)->get("actor"),
			'per_page'		=> $limit			
		);
		
		$this->load->library("listview", $config);
		echo $this->listview->render();
	}

	function basic_link1($offset = 0) 
	{	
		$limit = 10;
		$config['table'] = array(
			'total_rows' 	=> $this->db->count_all("actor"),
			'query' 		=> $this->db->limit($limit, (int) $offset)->get("actor"),
			'per_page'		=> $limit,
			'p_key'			=> 'actor_id'
		);
		
		$this->load->library("listview", $config);
		echo $this->listview->render();
	}

	function basic_link2($offset = 0) 
	{	
		$limit = 10;
		$config['table'] = array(
			'total_rows' 	=> $this->db->count_all("actor"),
			'query' 		=> $this->db->limit($limit, (int) $offset)->get("actor"),
			'per_page'		=> $limit,
			'p_key'			=> 'actor_id',
			'action'		=> array('position' => 'last')
		);
		
		$this->load->library("listview", $config);
		echo $this->listview->render();
	}

	function basic_link3($offset = 0) 
	{	
		$limit = 10;
		$config['table'] = array(
			'total_rows' 	=> $this->db->count_all("actor"),
			'query' 		=> $this->db->limit($limit, (int) $offset)->get("actor"),
			'per_page'		=> $limit,
			'p_key'			=> 'actor_id',
			'action'		=> array('position' => 'last'),
			'action'		=> array('merge' => false),
			'multi_select'	=> array('active' => false)
		);
		
		$this->load->library("listview", $config);
		echo $this->listview->render();
	}

	function just_table($offset = 0) 
	{
		$limit = 10;
		$config['table'] = array(
			'attr'			=> array('width' => '700'),
			'total_rows' 	=> $this->db->count_all("actor"),
			'query' 		=> $this->db->limit($limit, (int) $offset)->get("actor"),
			'per_page'		=> $limit,
			'p_key'			=> 'actor_id',
			'numbering'		=> array('active' => false),
			'hidden_fields'	=> array('actor_id', 'last_update')
		);
		
		$this->load->library("listview", $config);
		echo $this->listview->table();
	}

	function table_filter($offset = 0) 
	{
		$limit = 10;
		
		$this->load->library("listview");
		$result = $this->listview->filter('actor', (int) $limit, (int) $offset);
		
		$config['table'] = array(
			'attr'			=> array('width' => '700'),
			'total_rows' 	=> $result['total_rows'],
			'query' 		=> $result['query'],
			'per_page'		=> $limit,
			'p_key'			=> 'actor_id',
			'numbering'		=> array('active' => false),
			'hidden_fields'	=> array('actor_id', 'last_update')
		);
		
		$this->listview->initialize($config);		
		echo $this->listview->render();
	}	

	private function get_category_film($limit, $offset) {
		lvw_db_filter();

		$data['query'] = $this->db->select("SQL_CALC_FOUND_ROWS `fi`.`film_id`, `title`, `category_id`, `description`, `release_year`, `language_id`", false)
							 ->from("film fi")
							 ->join("film_category fc", "fi.film_id = fc.film_id")
							 ->order_by("fi.film_id")
							 ->limit($limit, $offset)
							 ->get();
		$data['total_rows'] = $this->db->query("SELECT FOUND_ROWS() AS total_rows")->row()->total_rows;

		return $data;
	}

	private function get_categories() {
		$options = array();
		$query = $this->db->get("category");
		foreach ($query->result() as $row) {
			$options[$row->category_id] = $row->name;
		}

		return $options;
	}

	private function get_languages() {
		$options = array();
		$query = $this->db->get("language");
		foreach ($query->result() as $row) {
			$options[$row->language_id] = $row->name;
		}

		return $options;
	}
	
	
	function table_form($offset = 0) 
	{
		$limit = 10;
		
		$this->load->library("listview");
		$result = $this->get_category_film($limit, (int) $offset);
		
		$config['table'] = array(			
			'attr'			=> array('width' => '100%'),
			'total_rows' 	=> $result['total_rows'],
			'query' 		=> $result['query'],
			'per_page'		=> $limit,
			'p_key'			=> 'film_id',
			'numbering'		=> array('active' => false),
			'fields'		=> array(
				'title' => array('label' => 'Judul Film',
					'format' => array('form' => array(
							'type' => 'form_input',
							'attr' => array('class' => 'inputbox')
						)
					) 
				),
				'category_id' => array('label' => 'Kategori',
					'format' => array('form' => array(
							'type' 		=> 'form_dropdown',
							'options' 	=> $this->get_categories()
						)
					)
				),
				'description' => array( 'label' => 'Deskripsi',
					'format' => array(
						'limit_text' => array('limit' => 100)
					)
				),
				'release_year' => 'Tahun Rilis',
				'language_id' => array('label' => 'Bahasa',
					'format'  => array(
						'form' => array(
							'type' => 'form_dropdown',
							'options' => $this->get_languages()
						)
					)
				)
			)
		);
		
		$this->listview->initialize($config);
		echo $this->listview->render();
	}

	public function index() {
		$this->load->helper("url");
	
		$links = array(
			"demo/basic" 		=> "Listview with basic configuration",
			"demo/basic_link1" => "Listview with Link (default in first)",
			"demo/basic_link2" => "Listview with Link (in last position)",
			"demo/basic_link3" => "Listview with Link (Separate, No checkbox)",
			"demo/just_table"	=> "Just Table with attribute setup, hidden some fields, no search form, no pagination, no numbering",
			"demo/table_filter"=> "Table with activate filter search",
			"demo/table_form"	=> "Table with Form Element"
		);		
		
		echo "<ol>";
		foreach ($links as $link =>  $label) {
			echo "<li>" . anchor($link, $label) . "</li>";
		}
		echo "</ol>";
	}
	
}

/* End of file demo.php */
/* Location: ./application/controllers/demo.php */
