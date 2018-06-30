<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_Global {

	public function Index(){}
	
	public function uri_check()
	{
		
		$str=uri_string();
		$strs=explode("/",$str);
		
		if( isset($strs[1]))   
		{
			$data['func']=$strs[1]; 			$data['class']=$strs[0];
			
		}
		else {
			$data['func']='home';
			$data['class']='home';
		}	
			
		
		
		//$data1=$this->getSEOfields();
		//$data = array_merge($data,$data1);
		
		return $data;
	}	
	
	public function get_slug($input_string = NULL)
	{
		
		$slug= trim($input_string);
		
		$slug	=	preg_replace('/[^A-Za-z0-9 ]/', '', $slug);
		
		$aslug=explode(" ",$slug);
		foreach($aslug as $k=>$v)
		{	
			$aslug[$k] = strtolower($aslug[$k]);
			if(!$aslug[$k]) unset($aslug[$k]);
			
		}
		$slug= implode("-", $aslug);	
		
		return $slug;
	}
	
	public function EncryptClientId($id)
	{
		return substr(md5($id), 0, 8).dechex($id);
	}

	public function DecryptClientId($id)
	{
		$md5_8 = substr($id, 0, 8);
		$real_id = hexdec(substr($id, 8));
		return ($md5_8==substr(md5($real_id), 0, 8)) ? $real_id : 0;
	}
	
	// Update Post_meta
	public function update_post_meta($post_id , $key  ,$val)
	{
		//$this->load->model('Common_model');
		$CI =& get_instance();
		//$data['query'] = $this->Common_model->commonQuery("select * from post_meta where `post_id` = $post_id ");
		$query = $CI->Common_model->commonQuery("select * from post_meta where post_id = '$post_id' AND meta_key = '$key' ");			
		//echo $query->num_rows();

		if($query->num_rows() > 0)
		{
			$row=$query->row();
			$meta_id=$row->meta_id;
			$datai = array('meta_value' => $val);
			
			return $metaid = $CI->Common_model->commonUpdate('post_meta',$datai,'meta_id',$meta_id);			
		}
		else
		{
			$datai = array( 'meta_key' => $key,	'meta_value' => $val, 'post_id' => $post_id);
								
			return $metaid=$CI->Common_model->commonInsert('post_meta',$datai);
		}
		//exit;
	}
	
	// Get Post_meta
	public function get_post_meta($id = NULL ,$key = NULL)
	{
		/*if($id != 0)
		return "kuch to aaya hai..";
		else
		return "kuch bhi nhi aaya..";*/
		//$this->load->model('Common_model');
		$CI =& get_instance();
		//return $key;	
		
		//echo "select * from post_meta where 'post_id' = '$id' AND 'meta_key' = '$key' ";
		
		$query = $CI->Common_model->commonQuery("select * from post_meta where post_id = '$id' AND meta_key = '$key' ");	
		
		if($query->num_rows()>0)
		{
			$row = $query->row();
			return $val = $row->meta_value;
		}
		else
			return false;
	}
	
	// Get Post_meta
	public function get_post_metadata($id = NULL)
	{
		$CI =& get_instance();
		
		$query = $CI->Common_model->commonQuery("select * from post_meta where post_id = '$id'");	
		
		if($query->num_rows()>0)
		{
			$metadata_array = array();
			foreach($query->result() as $row)
			{	
				$metadata_array[$row->meta_key] = $row->meta_value;
			}
			//$row = $query->row();
			return $metadata_array;
		}
		else
			return false;
	}
	
	// Get User_meta
	public function get_user_meta($id = null ,$key = null)
	{
		$CI =& get_instance();
		
		$query = $CI->Common_model->commonQuery("select * from user_meta where user_id = '$id' AND meta_key = '$key' ");	
		
		if($query->num_rows()>0)
		{
			$row = $query->row();
			return $val = $row->meta_value;
		}
		else
			return false;
	}
	
}

/* End of file Myhelpers.php */