codeigniter-listview v.1
========================

**Codeigniter Listview** is a PHP Codeigniter library for generate HTML table, Pagination, search form and table row links like edit, delete from Query Object.
It also suport displaying images, links and form elements in table rows.

Instalation
---

1. copy config/listview_core.php and config/listview_default.php to your config folder
2. copy helpers/listview_helper.php to your helpers folder
3. copy libraries/listview.php to your libraries folder
4. copy views/_listview folder to your views folder

Usage
---
1. Basic sample
```php
function basic($offset = 0) 
{	
	$limit = 20;
	$config['table'] = array(
		'total_rows' 	=> $this->db->count_all("actor"),
		'query' 		=> $this->db->limit($limit, (int) $offset)->get("actor"),
		'per_page'		=> $limit			
	);
	
	$this->load->library("listview", $config);
	echo $this->listview->render();
}
```

2. Show links in table rows
```php
function basic_link1($offset = 0) 
{	
	$limit = 20;
	$config['table'] = array(
		'total_rows' 	=> $this->db->count_all("actor"),
		'query' 		=> $this->db->limit($limit, (int) $offset)->get("actor"),
		'per_page'		=> $limit,
		'p_key'			=> 'actor_id'
	);
	
	$this->load->library("listview", $config);
	echo $this->listview->render();
}
```

3. Show links in table rows (position last, separate links, hide multi select)
```php
function basic_link3($offset = 0) 
{	
	$limit = 20;
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
```
4. Activate table filter, add table attribute and hide some fields
```php
function table_filter($offset = 0) 
{
	$limit = 20;
	
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
```