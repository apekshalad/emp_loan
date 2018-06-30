<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_Sms {

	public function Index(){}
	
	
	
	// Update Post_meta
	public function send_sms($args = null)
	{
		//$this->load->model('Common_model');
		$CI =& get_instance();
		$CI->load->model('Common_model');
		//$data['query'] = $this->Common_model->commonQuery("select * from post_meta where `post_id` = $post_id ");
		$query = $CI->Common_model->commonQuery("select * from options where option_key = 'user_name' OR option_key = 'password' OR option_key = 'sender_id'");			
		//echo $query->num_rows();
		$user_name = $password = $sender_id = "";

		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{	
				//$metadata_array[$row->meta_key] = $row->meta_value;
				if($row->option_key  == "user_name")
				{
					$user_name = $row->option_value;
				}else if($row->option_key  == "password"){
					$password = $row->option_value;
				}else if($row->option_key  == "sender_id"){
					$sender_id = $row->option_value;
				}
			}
			
			if(!empty($user_name) && !empty($password ) )
			{
				extract($args);
			//echo $user_name . " " .  $password ; 
				$url = "http://api.mimsms.com/api/sendsms/plain?user=$user_name&password=$password&sender=$sender_id&SMSText=".urlencode($message)."&GSM=".$mobile;
				
				
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$curl_scraped_page = curl_exec($ch);
				curl_close($ch);
			//print_r($curl_scraped_page);
			
			}
			//return $metaid = $CI->Common_model->commonUpdate('post_meta',$datai,'meta_id',$meta_id);			
		}
		else
		{
			//$datai = array( 'meta_key' => $key,	'meta_value' => $val, 'post_id' => $post_id);
			
			return  " sms sending failed";
								
			//return $metaid=$CI->Common_model->commonInsert('post_meta',$datai);
		}
		//exit;
	}
	
	
	
}

/* End of file Myhelpers.php */