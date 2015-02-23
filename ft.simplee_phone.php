<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Simplee_phone_ft extends EE_Fieldtype {

	var $info = array(
		'name' => 'SimplEE Phone',
		'version' => '1.0.0'
	);
	
	
	public $parts = array(
		'country' => '',
		'area' => '',
		'three' => '',
		'four' => ''
	);
	
	var $has_array_data = TRUE;
	
	function install(){}
	
	function parse_parts($raw){
		$this->parts['country'] = '';
		$this->parts['area'] = '';
		$this->parts['three'] = '';
		$this->parts['four'] = '';
		
		$phone = preg_replace("/[^0-9]/","",$raw);
		if(strlen($phone) > 10) {
	        $this->parts['country'] = substr($phone, 0, strlen($phone)-10);
	        $this->parts['area'] = substr($phone, -10, 3);
	        $this->parts['three'] = substr($phone, -7, 3);
	        $this->parts['four'] = substr($phone, -4, 4);
	    }
	    else if(strlen($phone) == 10) {
	        $this->parts['area'] = substr($phone, 0, 3);
	        $this->parts['three'] = substr($phone, 3, 3);
	        $this->parts['four'] = substr($phone, 6, 4);
	    }
	    else if(strlen($phone) == 7) {
	        $this->parts['three'] = substr($phone, 0, 3);
	        $this->parts['four'] = substr($phone, 3, 4);
	    }
	    return $this->parts;
	}
	
	function format_parts($parts){
		$phone = $parts['country'] ? "+".$parts['country'] : "";
		$phone .= $parts['area'] ? "(".$parts['area'].")" : "";
		$phone .= $parts['three']."-".$parts['four'];
		return $phone;
	}
	
	function display_field($phone){
		return form_input($this->field_id, $phone);
	}
	
	function save($data){
		$data = ee()->input->post($this->field_id);
		$parts = $this->parse_parts($data);
		$phone = $this->format_parts($parts);
		return $phone;
	}
	
	
	function replace_tag($data, $params = array(), $tagdata = FALSE){
		
		if($tagdata){
			$parts = $this->parse_parts($data);
			$parts['full'] = $this->format_parts($parts);
			return ee()->TMPL->parse_variables_row($tagdata, $parts);
		} else
			return $data;
	}
	
	
	
	
	/* ========================================================
	=========================   MATRIX   ====================== */
	
	
	function display_cell( $data ){
    	return form_input($this->cell_name, $data);
    }
    
    function save_cell($data){
		$parts = $this->parse_parts($data);
		$phone = $this->format_parts($parts);
		return $phone;
    }
    
    /* ========================================================
	=========================   GRID   ======================== */

	public function accepts_content_type($name){
	    return ($name == 'channel' || $name == 'grid');
	}
}