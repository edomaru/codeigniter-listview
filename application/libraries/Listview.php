<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Codeigniter Listview
 * Class for generate HTML Table from codeigniter query object
 *
 * @author Edo Masaru <masaruedo@yahoo.com>
 */
class Listview {		

	/**
     * handle table
     * @var array
     */
	private $table = array();
    
    /**
     * handle show record form
     */
    private $show_record = array();
    
    /**
     * handle pagination links
     */
    private $pagination = array();
    
    /**
     * handle search form
     */
    private $search_form = array();

	/**
	 * handle current class
	 */
    private $class_path = null;
    
    public function __construct($param = array()) 
	{
    	$this->ci =& get_instance();        

		$this->ci->load->library( "session" );
		$this->ci->load->helper( array("url", "form", "html", "listview") );
		
        $this->initialize($param);        
    }
	
    function filter($table, $limit, $offset)
	{
		lvw_db_filter();
		
		$data['query'] 		= $this->ci->db->select("SQL_CALC_FOUND_ROWS *", false)->from($table)->limit($limit, $offset)->get();		
		$data['total_rows'] = $this->ci->db->query("SELECT FOUND_ROWS() total_rows")->row()->total_rows;

		return $data;
	}
	
    public function initialize($param = array()) 
	{
    	// load config
        $this->ci->config->load("listview_core");
        $this->theme = $this->ci->config->item("theme");

		// if theme defined
		if ($this->theme) {
			$this->ci->config->load("listview_{$this->theme}");	
		}
		
    	// load listview core and theme configuration   	
    	$lvw_config = $this->ci->config->item("core");
    	$lvw_theme 	= $this->ci->config->item($this->theme);

		foreach ($lvw_config as $name => $value) {
			if ($lvw_theme) {
				$this->{$name} = array_merge($value, $lvw_theme[$name]); 
			} else {
				$this->{$name} = $value; 
			}	
		}			

    	// if there any configuration in parameter
        if ( count($param) ) {        	
            // overwrite listview configuration with configuration parameter
            foreach ($param as $name => $value) {             
                if ( isset($this->{$name}) && $value) {                  	
                	$item = $this->{$name};
					                	
                    $this->{$name} = array_merge_recursive_distinct($item, $value);                    
                }
            }
            
			// if no class_path defined, get current class name
            if (! $this->table['class_path'] ) {
            	$this->table['class_path'] = $this->ci->router->class;
            }

            // create session key as <class name>_<method name>
            $this->class_path = $this->ci->router->class . "_" . $this->ci->router->method;
            
			// set pagination base_url parameter
            if (! $this->pagination['base_url'] ) {            	
            	$base_url = $this->table['class_path'] . "/" . $this->ci->router->method;
            	$this->pagination['base_url']	= site_url($base_url);
            }

            // copy base_url search form from pagination base_url config
            if (! $this->search_form['base_url'] ) {
            	$this->search_form['base_url']		= $this->pagination['base_url'];  
            }
            	       
			if ( ! $this->table['total_rows'] && is_object($this->table['query']) ) {
				$this->table['total_rows'] = $this->table['query']->num_rows();
			}
            $this->show_record['total_rows'] 	= $this->pagination['total_rows'] = $this->table['total_rows'];
            $this->show_record['per_page'] 		= $this->pagination['per_page']  = $this->table['per_page'];
			
			// if no record found, show message
		    if ( ! $this->table['total_rows'] ) {
		    	$this->ci->lang->load("listview_msg");
		    	$this->ci->load->helper("language");
		    	
		        if ( $this->ci->session->userdata("search_form_values") ) {
		            set_message( lang('msg_nodata_found_class'), lang("msg_nodata_found_content"), false);
		        } else {
		            set_message(lang('msg_nodata_class'), lang("'msg_nodata_content'"), false);
		        }
		    }
		    			
            $this->set_search_form_filters();
        }        
    }

	/**
	 * menyimpan definisi kunci untuk pencarian data ke session
	 */	 
    private function set_search_form_filters() {
    	$ci =& get_instance();
    	$fields = array();
	
		// We need keys for searhing data
		$keys = $this->search_form['filters'];
		
		/**
		 * If no keys defined
		 * get fields from table config		 
		 * if no fields table config found, get from query
		 */
    	if (! $keys) {
    		# jika ada definisi field yang akan ditampilkan
		    if (count($this->table['fields']) > 0) {
		        $fields = $this->table['fields'];
		    }
		    
		    # jika tidak ada definisi field, ambil langsung dari query
		    else {
		    	if (is_object($this->table['query']) && $this->table['query']->num_rows() > 0) {
		    		$list_fields = $this->table['query']->list_fields();
				    foreach ($list_fields as $field) {
				    	$fields[$field] = ucwords( str_replace("_", " ", $field) );
				    }
		    	}

		    	$this->table['fields'] = $fields;
		    }

			# kopikan field tabel untuk field kunci pencarian
			$keys = array(
				'keywords' => array('fields' => array_keys($fields))
			);
    	}
    	
    	# hapus session sebelumnya
    	$this->ci->session->unset_userdata("search_form_filters");

    	# simpan session baru
		$this->ci->session->set_userdata("search_form_filters", array("{$this->class_path}" => $keys));
		
		# simpan nilai default jika ada
		$values = array();

		foreach ($keys as $key => $val) {			
			if (isset($val['value'])) {
				if (is_array($val['fields'])) {			
					foreach ($val['fields'] as $fkey) {
						$values[$fkey] = array('value' => $post_value, 'opt' => 'or_like');
					}
				}
				else {
					$values[$val['fields']] = array('value' => $val['value'], 'opt' => 'and');
				}			

				$ci->session->set_userdata("current_{$key}", $val['value']);			
			}
		}

		# hapus session sebelumnya
    	$this->ci->session->unset_userdata("search_form_values");    	

    	# simpan session yang baru
    	$this->ci->session->set_userdata("search_form_values", array("{$this->class_path}" => $values));
    }
    
    private function attr_merge(&$data = array(), $data2 = array()) {
        if ( is_array($data) && count($data) ) {
        	foreach ($data2 as $key => $val) {
                if ( isset($data[$key]) ) {                	
            		$data[$key] = $this->attr_merge($data[$key], $data2[$key]); 
            		// $data[$key] = array_merge_recursive($data[$key], $data2[$key]);
                }
            }
        } else {
		   if ($data2) {
			   $data = $data2;
		   }
    	}		
    }	

	public function show_record() {		
        /*$new_view = $this->ci->load->set_theme($this->theme)->view($view, $this->show_record, TRUE);
        $new_view = $this->params['show_record']['full_tag_open'] .
        			$new_view .
        			$this->params['show_record']['full_tag_close'];

        return $new_view;
        */
        return $this->show_record;
	}

	public function total_rows() {
		return $this->table['total_rows'];
	}

	/**
	 * Generate table HTML
	 * @return string $table HTML Table
	 */
	public function table() {
		$html 	= "";
		$query 	= $this->table['query'];
		
		if (is_object($query) && $query->num_rows() > 0) {
			# status multi select
			$multi_select 	= false;			
			# multi_select handler
			$ms_attr 		= array();

			# jika multi select diaktifkan
			if ($this->table['multi_select']['active']) {
				# catat status
				$multi_select = true;
				$ms_attr = $this->table['multi_select'];
				
				$html = form_open($ms_attr['form']['action'], $ms_attr['form']['attr']);
			}

			# status numbering
			$numbering = false;
			# numbering handler
			$num_attr = array();
			
			# nomor urut
			$numb = 0;
			if ($this->table['numbering']['active']) {
				# catat status
				$numbering = true;
				// $num_attr = $this->table['numbering'];
				// print_r($num_attr);
				
				# catat nomor halaman aktif
		        $num = $this->ci->uri->segment($this->pagination['uri_segment']);//$this->cur_page;
		        $num = ($num) ? $num : 0;	
			}

			$table_attr = array2attr($this->table['attr']);
			$html .= "<table {$table_attr}>";			

			# field yang akan ditampilkan
			$fields = $this->table['fields'];
			
            # field yang disembunyikan
            $hidden_fields = $this->table['hidden_fields'];
			# action 
            $action 		= $this->table['action'];

            # buat tabel header
            $thead = "";            
            # check status no header
			if (! $this->table['no_header']) {
				$thead = $this->create_heading_table($fields, $hidden_fields, $action);
			}            

			# catat table row
			$tbody = "<tbody>";
            foreach ($query->result_array() as $no => $val) {
				# catat primary key
				if (is_array($this->table['p_key']) && count($this->table['p_key'])) {
					$p_key = array();
					foreach ($this->table['p_key'] as $key) {
						if (isset($val[$key])) {
							$p_key[] = $val[$key];
						}
					}	
				} else {
					$p_key = isset($val["{$this->table['p_key']}"]) ? $val["{$this->table['p_key']}"] : null;	
				}
                
                $tbody .= "<tr>";

				# bikin checkbox untuk setiap baris
				if ($multi_select) {
					# ambil nilai checkbox
					$checkbox = $ms_attr['form']['checkbox'];
					
					# ubah atribut value dan name pada checkbox
					$check_name = $checkbox['check_item']['name'];
					$checkbox['check_item']['value'] 	= $p_key;
					$checkbox['check_item']['name'] 	= $check_name . "[]";
					$checkbox['check_item']['id'] 		=  $check_name . $no;

					# simpan checkbox dalam table data
                    $tbody .= "<td>" . form_checkbox($checkbox['check_item']) ."</td>";
                } 

                # bikin nomor utur baris
                if ($numbering) {
                	$num++;

                	# bikin html atribut untuk table data
                	$attr = array2attr($this->table['numbering']['attr']);    

                	# simpan nomor urut dalam table data               
                    $tbody .= "<td {$attr}>{$num}</td>";
                }

                # cek jika posisi action di awal
				if ( $action['position'] == 'first' && $p_key) {	
					if ($action['merge']) {
						$tbody .= "<td>" . create_buttons($action['links'], $this->table['class_path'], $p_key) . "</td>";	
					} else {
						foreach ($action['links'] as $key => $link) {
							$tbody .= "<td>" . create_buttons($action['links'], $this->table['class_path'], $p_key, $key) . "</td>";	
						}
					}
				}

				# sekarang tampilkan data dari resultset ke dalam table row
				foreach ($fields as $fkey => $fval) {
					# jika nama filed termasuk yang akan disembunyikan
                    if (in_array($fkey, $hidden_fields)) {
                        continue;
                    }

                    // jika definisi field terdapat atribut lain
                    // seperti format dan style
                    if (is_array($fval)) {
                        # simpan row data
                        $data = $val["{$fkey}"];

                        # get format if any
                        $data_formated = "";
                        if (isset($fval['format'])) {
                        	foreach ($fval['format'] as $ft_key => $ft_val) {
                        		$ft_val['name'] = "{$fkey}";
                        		$data_formated .= set_format($ft_key, $ft_val, $data, $p_key);
                        	}
                        } else {
                        	$data_formated = $data;
                        }
                        
                        $tbody .= "<td>{$data_formated}</td>";
                    }
                    // normal tanpa definisi atribut atau format
                    else {
                        $data = $val["{$fkey}"];
                        $tbody .= "<td>{$data}</td>";
                    }
				}
				
				# cek jika posisi action di akhir
				if ( $action['position'] == 'last' && $p_key) {					
					if ($action['merge']) {
						$tbody .= "<td>" . create_buttons($action['links'], $this->table['class_path'], $p_key) . "</td>";	
					} else {
						foreach ($action['links'] as $key => $link) {
							$tbody .= "<td>" . create_buttons($action['links'], $this->table['class_path'], $p_key, $key) . "</td>";	
						}
					}	
				}
				
                $tbody .= "</tr>";
            }
            $tbody .= "</tbody>";
            
            # buat tabel
            $html .= $thead;
            $html .= $tbody;

			# periksa status multi select
			if ($multi_select) {
				$html .= form_close();
			}
			$html .= "</table>";
		}

		return $html;
	}

	/**
	 * create table heading
	 */
	private function create_heading_table($fields, $hidden_fields, $action) {
		$th = "";		
		
		// multi select
		$multi_select = $this->table['multi_select'];		
		if ($multi_select['active'] == true) {
			$form		= $multi_select['form'];
            $th_attr 	= array2attr($multi_select['attr']);
            $th 		.= "<th {$th_attr}>" . form_checkbox($form['checkbox']['check_all']) . "</th>";
        }
        // auto numbering
        $numbering = $this->table['numbering'];
        if ($numbering['active'] == true) {
        	$th_attr 	= $numbering['attr'];
            $label 		= $numbering['label'];
            $th 		.= "<th style='{$th_attr}'>{$label}</th>";
        } 

		// if action in first position
        if ( $action['position'] == 'first' && $this->table['p_key']) {
            $th 		.= $this->create_action($action);
        }
        
        foreach ($fields as $fkey => $fval) {
            // if field name tobe hidden
            if (in_array($fkey, $hidden_fields)) {
                continue;
            }
            
            if (is_array($fval)) {                           
                $label 	= isset($fval['label']) ? $fval['label'] : $fkey;
                $attr 	= isset($fval['attr']) ? array2attr($fval['attr']) : "";
                $th 	.= "<th {$attr}>{$label}</th>";
            } else {
                $th .= "<th>{$fval}</th>";                
            }
        }

        # if action in last position
        if ( $action['position'] == 'last' && $this->table['p_key']) {
            $th 		.= $this->create_action($action);
        }
        
        return $th;
	}

	private function create_action($action = array()) 
	{
		$th = "";
		
		if ( is_array($action) && count($action) ) {
			if ($action['merge']) {
				$th_attr = array2attr($action['attr']);
				$th .= "<th {$th_attr}>{$action['label']}</th>";
			} else {
				$links = $action['links'];
				if (count($links)) {
					foreach ($links as $key => $val) {						
						$th .= "<th>{$val['attr']['title']}</th>";
					}
				}
			}
		}

		return $th;
	}

	/**
	 * create search form partial
	 */
	public function search_form() 
	{
		$theme = "";
		if ($this->theme != "default") {
			$theme = $this->theme;
		}
		
		return $this->ci->load->set_theme($theme)->view($this->search_form['view'], $this->search_form, true);
	}

	/**
	 * render search form, table and pagination in one
	 */
	public function render() 
	{
		$data = array(
			'search_form' 	=> $this->search_form(),
			'table' 		=> $this->table(),
			'pagination' 	=> $this->pagination(),
			'message'		=> show_message()
		);
		
		$theme = "";
		if ($this->theme != "default") {
			$theme = $this->theme;
		}
		
		return $this->ci->load->set_theme($theme)->view($this->table['view'], $data, true);
	}

	/**
	 * create pagination links
	 * @return	string	$link		pagination link
	 */
	function pagination() 
	{    
		$this->ci->load->library("pagination");
		$this->ci->pagination->initialize($this->pagination);
		//print_r($this->pagination);
		return $this->ci->pagination->create_links();
	}

}

/* End of file Listview.php */
/* Location: ./application/libraries/Listview.php */
