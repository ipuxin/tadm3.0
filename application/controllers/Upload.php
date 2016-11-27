<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends MY_Controller {

	private $_config;

	public function __construct(){
		parent::__construct();
		//$this->load->model('upload_model');

		$config['upload_path'] = './data/images/';
		$config['allowed_types'] = 'jpg|png';
		$config['max_size'] = '2048';
		$config['max_width']  = '1440';
		$config['max_height']  = '800';
		//$config['file_name'] = time()."_".$name;
		$config['encrypt_name'] = TRUE;
		$this->_config = $config;
	}

	public function productImage($type=''){
		$Post = $this->input->post();
		$this->_config['upload_path'] = './data/images/product/'.date('Y-m-d')."/";
		if($type=='Min'){
			$this->_config['max_size'] = '32';
			$this->_config['max_width']  = '160';
			$this->_config['max_height']  = '160';
		}elseif($type=='Big'){
			$this->_config['max_size'] = '128';
			$this->_config['max_width']  = '1080';
			$this->_config['max_height']  = '540';
		}elseif($type=='List'){
			$this->_config['max_size'] = '128';
			$this->_config['max_width']  = '540';
			$this->_config['max_height']  = '470';
		}

		$res = $this->FilePost('ImageUpload');
		if(is_array($res)){
			$return['error'] = $res;
		}else{
			$return['imageUrl'] = substr($this->_config['upload_path'],1).$res;
		}
		$this->returnJson($return);
	}

	public function bannerImage(){
		$Post = $this->input->post();
		$this->_config['upload_path'] = './data/images/banner/'.date('Y-m-d')."/";
		$this->_config['max_size'] = '150';
		$this->_config['max_width']  = '640';
		$this->_config['max_height']  = '320';

		$res = $this->FilePost('ImageUpload');
		if(is_array($res)){
			$return['error'] = $res;
		}else{
			$return['imageUrl'] = substr($this->_config['upload_path'],1).$res;
		}
		$this->returnJson($return);
	}

	public function cateImage(){
		$Post = $this->input->post();
		$this->_config['upload_path'] = './data/images/cate/'.date('Y-m-d')."/";
		$this->_config['max_size'] = '50';
		$this->_config['max_width']  = '160';
		$this->_config['max_height']  = '200';

		$res = $this->FilePost('ImageUpload');
		if(is_array($res)){
			$return['error'] = $res;
		}else{
			$return['imageUrl'] = substr($this->_config['upload_path'],1).$res;
		}
		$this->returnJson($return);
	}

	public function shopImage(){
		$Post = $this->input->post();
		$this->_config['upload_path'] = './data/images/shop/'.date('Y-m-d')."/";
		$this->_config['max_size'] = '150';
		$this->_config['max_width']  = '640';
		$this->_config['max_height']  = '320';

		$res = $this->FilePost('ImageUpload');
		if(is_array($res)){
			$return['error'] = $res;
		}else{
			$return['imageUrl'] = substr($this->_config['upload_path'],1).$res;
		}
		$this->returnJson($return);
	}

	public function testUpload(){
		$this->view('testUpload',$data);
	}

	public function FilePost($file = '',$name = ''){
		$dir = $this->config->item('file_path').substr($this->_config['upload_path'],1);
		if(!file_exists($dir)){mkdir($dir,'0777',true);}

		$this->load->library('upload', $this->_config);
		if(isset($_FILES[$file]) && $_FILES[$file]['name']){
			if(!$this->upload->do_upload($file)){
				$error = array('error' => $this->upload->display_errors('',''));
				//print_r($error);
				//$this->load->view('upload_form', $error);
				return $error;
			}else{
				$data = array('upload_data' => $this->upload->data());
				//print_r($data);
				//$this->load->view('upload_success', $data);
				return $data['upload_data']['file_name'];
			}
		}
	}

}