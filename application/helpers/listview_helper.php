<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('set_message'))
{
	function set_message($class, $content, $flashdata = true)
	{
		$ci =& get_instance();
		$ci->load->library('session');
		
		$message = array('class' => $class, "content" => $content);
		if ( ! $flashdata ) {
		    $method = $ci->router->method;
		    $ci->session->set_userdata("{$method}_message", $message);
		}
		else {
		    $ci->session->set_flashdata("message", $message);
		}		
	}
}

if ( ! function_exists('get_message'))
{
	function get_message()
	{
		$ci =& get_instance();    
		$ci->load->library("session");
		
		$method = $ci->router->method;
		if ( ($msg = $ci->session->userdata("{$method}_message")) ) {        
		    // hapus pesan dalam session
		    $ci->session->unset_userdata("{$method}_message");
		    return $msg;
		}
		else {
		    return $ci->session->flashdata("message");
		}		
	}
}

if ( ! function_exists("show_message"))
{
	function show_message()
	{
		return ( ($msg = get_message()) ) ? $msg['content'] : "";
	}
}

if ( ! function_exists('array2attr'))
{
	function array2attr($data = array())
	{
		$str = "";
		if (is_array($data) && count($data)) {
		    foreach ($data as $key => $val) {
		        $str .= "{$key} = '{$val}' ";
		    }
		    $str = rtrim($str);
		}
		
		return $str;		
	}
}

if ( ! function_exists("limit_text"))
{
	function limit_text($text, $limit=300, $strip_tag = true, $strip_exclude='') {
		if ($strip_tag == true or $strip_tag == 'strip_tag') {
		    $text = strip_tags($text, $strip_exclude);
		}
		$chunk_at = strrpos(substr($text, 0, $limit), ' ');
		if (strlen($text) > $limit) {
		    $text = substr($text, 0, $chunk_at) . " ...";
		}
		return $text;
	}
}

if ( ! function_exists('set_format'))
{
	function set_format($type, $attr, $data, $p_key = null)
	{
		$data_formate = "";
	
		switch ($type) {
			# jika format dipilih adalah image
			case 'image':
				# gabungkan src dengan nilai data
				$attr['src'] = $attr['src'] . "/{$data}";
				$data_formated = img($attr);
			break;

			# jika format dipilih adalah form
			case 'form':
				$name = $attr['name'];
				
				switch ($attr['type']) {
					// radio and checkbox
					case 'form_radio':
					case 'form_checkbox':
						$items = "";
						foreach ($attr['options'] as $key => $val) {
							$frm_attr['name'] = ($attr['type'] == 'form_radio') ? "{$name}[{$p_key}]" : "{$name}[{$p_key}][{$key}]";
							
							$frm_attr['id'] = "{$name}_{$key}";

							// select item
							$frm_attr['checked'] = ($key == $data) ? TRUE : FALSE;

							$items .= call_user_func($attr['type'], $frm_attr) . $val . PHP_EOL;
						}

						$data_formated = $items;
					break;

					// form select
					case 'form_dropdown':
						// other attributes
						$sel_attr = isset($attr['attr']) ? array2attr($attr['attr']) : "";

						// create form elemen
						$data_formated = call_user_func_array($attr['type'], array($name, $attr['options'], $data, $sel_attr));
					break;

					// input text, textarea
					case 'form_input':
					case 'form_textarea':
						$input_attr['name'] = "{$name}[{$p_key}]";
						$input_attr['id'] = "{$name}_{$p_key}"; // isset($attr['attr']['id']) ? isset($attr['attr']['id']) : "{$name}_{$p_key}";
						$input_attr['value'] = $data;

						if (isset($attr['attr'])) {
							$input_attr = array_merge($input_attr, $attr['attr']);
						}
						
						// create form elemen
						$data_formated = call_user_func($attr['type'], $input_attr);
					break;
				}
			break;

			case 'link':
				// additional link atributes
				$link_attr = isset($attr['attr']) ? $attr['attr'] : array();

				// now cretate the link
				$data_formated = anchor( rtrim($attr['url'], "/") . "/" . $p_key , $data, $link_attr);
			break;

			case 'limit_text':
				$data_formated = limit_text($data, $attr['limit']);
			break;
		}

		return $data_formated;		
	}
}

if ( ! function_exists('create_buttons'))
{
	function create_buttons($data, $url, $p_key = null, $just_me = null)
	{
		if (! count($data) ) return;

		$html = "";
		
		$p_key = ( is_array($p_key) ) ? implode("/", $p_key) : $p_key;
	
		if (is_array($data)) {
		    foreach ($data as $link => $val) {        			    	
		    	if ($just_me && $link != $just_me) {
		    		continue;
		    	}
		    	
				// other anchor atributes like class, id etc
		    	$attr = $val['attr'];
		    			    	
				// normaly the anchor url in href atribute
				// just in case defined self in data-url for example so href would be filled with #
		    	$link = rtrim("{$url}/{$link}/{$p_key}", "/");
				if ($val['link_point'] != 'href') {
					$attr[($val['link_point'])]	= site_url($link);
					$link = "{$url}#";				
				}                	
		    	
		    	// label
		    	$label = isset($val['label']) ? $val['label'] : $attr['title'];
		    	
		        // create button link
		        $html .= anchor("{$link}", $label, $attr) . PHP_EOL;
		    }
		} else {                
		    $val = ucwords($data);
		    $html .= anchor("{$url}/{$data}/{$p_key}", $val);
		}

		return $html;		
	}
}

if ( ! function_exists('get_str_filter'))
{
	function get_str_filter($no_where = false)
	{
		$ci =& get_instance();
		$class_path	= $ci->router->class . "_" . $ci->router->method;
		$params 	= $ci->session->userdata("search_form_values");
		$where 		= "";
		$where_like = "";	

		if ( isset($params[$class_path]) && is_array($params) && count($params) > 0) {
			foreach ($params[$class_path] as $key => $val) {
				if (isset($val['opt'])) {
					if ($val['opt'] == 'or_like') {
						$value = $ci->db->escape_like_str($val['value']);
						$where_like .= " {$key} LIKE '%{$value}%' OR";
					}
					elseif ($val['opt'] == 'like') {
						$value = $ci->db->escape_like_str($val['value']);
						$where_like .= " {$key} LIKE '%{$value}%' AND";
					}
					elseif ($val['opt'] == 'or') {
						$where .= " {$key} = '{$val['value']}' OR";
					} 
					else {
						$where .= " {$key} = '{$val['value']}' AND";	
					}
				}
				else {
					$where .= " {$key} = '{$val['value']}' AND";	
				}    		
			}	
		}	

		$where = rtrim(rtrim($where, "AND"), "OR");
		$where_like = rtrim(rtrim($where_like, "AND"), "OR");
		$where_like = ($where_like && $where) ? " AND ({$where_like})" : $where_like;
		$where = (!$no_where && $where) ? " AND {$where}" : $where;
	
		return $where . $where_like;
	}
}

if ( ! function_exists('lvw_set_filter'))
{
	function lvw_set_filter()
	{
		$ci =& get_instance();

		$class_path = $ci->router->class . "_" . $ci->router->method;	
		$filters 	= $ci->session->userdata("search_form_filters");

		if ($_POST) {
			// menyimpan field dan nilai
			$fields = array();				

			// pastikan session berisi fields dan atribut
			if (isset($filters[$class_path]) && count($filters)) {

				// ambil setiap nilai dari post
				foreach ($filters[$class_path] as $key => $value) {
					// hapus nilai post beserta kunci sebelumnya					
					$ci->session->unset_userdata("current_{$key}");

					// simpan nilai post
					$post_value = $ci->input->post("{$key}", true);

					// kalau data post kosong tidak usah dilanjutkan
					if (empty($post_value)) continue;
			
					// jika definisi fields berupa array
					// maka seluruh elemen field akan memiliki value yang sama
					if (is_array($value['fields'])) {			
						foreach ($value['fields'] as $fkey) {
							$fields[$fkey] = array('value' => $post_value, 'opt' => 'or_like');
						}
					}
					else {
						$fields[$value['fields']] = array('value' => $post_value, 'opt' => 'and');
					}			

					// save current key's values
					$ci->session->set_userdata("current_{$key}", $post_value);
				}
			}

			// simpan field dan kunci yang berisi nilai
			$ci->session->unset_userdata("search_form_values");
			$ci->session->set_userdata("search_form_values", array($class_path => $fields));
		}
	}
}

if ( ! function_exists('lvw_array_filter'))
{
	function lvw_array_filter()
	{
		$arr = array();
		$ci =& get_instance();	
	
		$class_path = $ci->router->class . "_" . $ci->router->method;
		$filters 	= $ci->session->userdata("search_form_values");	

		if (isset($filters[$class_path])) {
			foreach ($filters[$class_path] as $key => $val) {
				$arr[$key] = $val['value'];
			}
		}	

		return $arr;
	}
}

if ( ! function_exists('lvw_db_filter'))
{
	function lvw_db_filter()
	{
		lvw_set_filter();
		
		$ci =& get_instance();
		$class_path = $ci->router->class . "_" . $ci->router->method;
		$filters 	= $ci->session->userdata("search_form_values");	

		if (isset($filters[$class_path])) {
			foreach ($filters[$class_path] as $key => $val) {
				if (isset($val['opt'])) {
					if ($val['opt'] == 'or_like') {
						$ci->db->or_like($key, $val['value']);
					}
					elseif ($val['opt'] == 'like') {
						$ci->db->like($key, $val['value']);
					}
					elseif ($val['opt'] == 'or') {
						$ci->db->or_where($key, $val['value']);
					} 
					else {
						$ci->db->where($key, $val['value']);
					}
				}
				else {
					$ci->db->where($key, $val['value']);
				}    		
			}	
		}
	}
}

if ( ! function_exists("array_merge_recursive_distinct"))
{
	/**
	 * array_merge_recursive does indeed merge arrays, but it converts values with duplicate
	 * keys to arrays rather than overwriting the value in the first array with the duplicate
	 * value in the second array, as array_merge does. I.e., with array_merge_recursive,
	 * this happens (documented behavior):
	 *
	 * array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
	 *     => array('key' => array('org value', 'new value'));
	 *
	 * array_merge_recursive_distinct does not change the datatypes of the values in the arrays.
	 * Matching keys' values in the second array overwrite those in the first array, as is the
	 * case with array_merge, i.e.:
	 *
	 * array_merge_recursive_distinct(array('key' => 'org value'), array('key' => 'new value'));
	 *     => array('key' => array('new value'));
	 *
	 * Parameters are passed by reference, though only for performance reasons. They're not
	 * altered by this function.
	 *
	 * @param array $array1
	 * @param array $array2
	 * @return array
	 * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
	 * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
	 */
	function array_merge_recursive_distinct ( array &$array1, array &$array2 )
	{
		$merged = $array1;

		foreach ( $array2 as $key => &$value ) {
			if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) ) {
				$merged [$key] = array_merge_recursive_distinct ( $merged [$key], $value );
			}
			else
			{
		  		$merged [$key] = $value;
			}
	  	}

	  	return $merged;
	}
}


