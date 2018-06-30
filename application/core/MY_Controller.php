<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * This controller contains the common functions
 * @author Mindlogix Technologies 
 *
 */

class MY_Controller extends CI_Controller {
	
	var $theme;
	var $site_users;
	var $site_user_access;
		
	function __construct()
	{
		parent::__construct();
		
		$CI =& get_instance();
		$this->theme = $CI->config->item('theme') ;
		
		$this->site_users 		= $CI->config->item('site_users') ;
		$this->site_user_access 	= $CI->config->item('site_user_access') ;
		
    }
	
	public function has_menu_access($menu_item = "", $user_type )
	{
		
		if(in_array($user_type, $this->site_users ))
		{
			//if(in_array($user_type, $this->site_user_access ))
			$menu_access = $this->site_user_access [$user_type]['menu']	 ;
			
			if($menu_access['has_access'] == 'access_all')
			{
				return true;
			}else if($menu_access['has_access'] == 'limited'){
				
				$menu_items = $menu_access['menu_items'];
				if(in_array($menu_item, $menu_items ))
					return true;
			}
			//print_r($menu_access);
			
			return false;
		}
		else return false;
		
	}
	
	public function has_class_access($class_item = "", $user_type = "" )
	{
		
		if($class_item == "")
		{
			$class_item =  $this->router->fetch_class();
			//echo $this->router->fetch_method();
		}
		
		$user_type = $this->session->userdata('user_type');
		if(in_array($user_type, $this->site_users ))
		{
			//if(in_array($user_type, $this->site_user_access ))
			$class_access = $this->site_user_access [$user_type]['controller']	 ;
			
			if($class_access['has_access'] == 'access_all')
			{
				return true;
			}else if($class_access['has_access'] == 'limited'){
				
				$class_items = $class_access['all_items'];
				if(in_array($class_item, $class_items ))
					return true;
			}
		}
		
		return false;
		
	}
	
	public function has_method_access($method_item = "", $user_type = "" )
	{
		
		//if($method_item == "")
		//{
			$class_item =  $this->router->fetch_class();
			$method_item =  $this->router->fetch_method();
			//echo $this->router->fetch_method();
		//}
		
		//echo "class".$class_item ;
		
		$user_type = $this->session->userdata('user_type');
		if(in_array($user_type, $this->site_users ))
		{
			//if(in_array($user_type, $this->site_user_access ))
			$method_access = $this->site_user_access [$user_type]['view']	 ;
			
			if($method_access['has_access'] == 'access_all')
			{
				return true;
			}else if($method_access['has_access'] == 'limited'){
				
				//print_r($method_access);
				//if(in_array($class_item, $method_access ['all_items']))
				if(array_key_exists($class_item, $method_access ['all_items']) )
				{
					$method_items = $method_access['all_items'][$class_item];
					if(in_array($method_item, $method_items ))
						return true;
				}		
			}
		}
		
		return false;
		
	}
	
	public function has_widget_access($widget_item = "", $user_type = "" )
	{
		
		if($widget_item == "")
		{
			return false;
		}
		
		
		$user_type = $this->session->userdata('user_type');
		if(in_array($user_type, $this->site_users ))
		{
			//if(in_array($user_type, $this->site_user_access ))
			if(isset($this->site_user_access [$user_type]['widget']	))
				$widget_access = $this->site_user_access [$user_type]['widget']	 ;
			else
				return false;
				
				
			if($widget_access['has_access'] == 'access_all')
			{
				return true;
			}else if($widget_access['has_access'] == 'limited'){
				
				//print_r($widget_access);
				if(in_array($widget_item, $widget_access ['all_items']))
				//if(array_key_exists($class_item, $widget_access ['widget_items']) )
				{
					//$widget_items = $widget_access['all_items'][$class_item];
					//if(in_array($widget_item, $widget_items ))
						return true;
				}		
			}
		}
		
		return false;
		
	}
	
	public function has_permission($item = "", $task = "", $user_type = "" )
	{
		
		
		$user_type = $this->session->userdata('user_type');
		if(in_array($user_type, $this->site_users ))
		{
			//if(in_array($user_type, $this->site_user_access ))
			$access = $this->site_user_access [$user_type]['content']	 ;
			
			if($access['has_access'] == 'access_all')
			{
				return true;
			}else if($access['has_access'] == 'limited'){
				
				$all_items = $access['all_items'];
				//if(in_array($class_item, $all_items ))
				if(array_key_exists($item,$all_items))
				{
					$current = $all_items[$item];
					if(in_array($task, $current))
						return true;
				}	
			}
		}
		
		return false;
		
	}
	
	public function get_default_status($item = "", $user_type = "" )
	{
		
		
		$user_type = $this->session->userdata('user_type');
		if(in_array($user_type, $this->site_users ))
		{
			//if(in_array($user_type, $this->site_user_access ))
			$access = $this->site_user_access [$user_type]['content']	 ;
			
			if($access['default_status'] == 'publish_all')
			{
				return 'publish';
			}else if($access['default_status'] == 'limited'){
				
				$all_items = $access['statuses'];
				//if(in_array($class_item, $all_items ))
				if(array_key_exists($item,$all_items))
				{
					$status = $all_items[$item];
					//if(in_array($task, $current))
						//return true;
					return $status;	
				}	
			}
		}
		
		return "draft";
		
	}
	
	
}