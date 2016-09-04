<?
class ModSlider extends Prototype {
	function index () {
		$this->links[] = '/assets/css/slider.css';
		$this->scripts[] = '/assets/js/slider.js';
		
		$this->options = array_merge(array(
			'table' => 'banners',
			'seo' => false,
			'std' => true
		), $this->options);
		
		$this->data['attr'] = '';
		
		$this->options['field'] = $this->options['table'].'.*';
		
		$items = $this->db->select($this->options);
		
		$this->data['items'] = $this->to_object(array_reverse((array)$items));
		$this->data['count'] = count((array)$items);
		
		$options = array_merge(array(
			'pagination' => true,
			'arrows' => true,
			'loader' => true,
			'auto' => true,
			'speed' => 500,
			'delay' => 3000
		), $this->options['view']);
		
		foreach ($options as $name => $value) {
			$value = is_bool($value) ? ($value ? 'true' : 'false') : $value;
			$this->data['attr'] .= ' data-'.$name.'="'.$value.'"';
		}
		
		$options = array_merge(array(
			'item_tmpl' => isset($this->options['item_tmpl']) ? $this->options['item_tmpl'] : 'item',
			'template' => $this->template
		), $options);
		
		$this->data['options'] = $this->to_object($options);
		
		return true;
	}
}