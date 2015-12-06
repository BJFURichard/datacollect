<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Angular extends CI_Controller {

	public function index()
	{
		$errors         = array();  	// array to hold validation errors
		$data 			= array(); 		// array to pass back data
		// validate the variables ======================================================
		if (empty($_REQUEST['name']))
			$errors['name'] = '用户名为空.';

		if (empty($_REQUEST['password']))
			$errors['password'] = '密码为空.';
		if ( ! empty($errors)) {

			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
		} else {

			// 用户名密码正确访问数据库判断是否登录
			$this->load->model("Admin");
			$query=$this->Admin->user_select($_REQUEST['name'],$_REQUEST['password']);
			if($query->num_rows()>0){
				$data['success'] = 1;
				$data['message'] = 'Success!';
			}else{
				$data['success'] = false;
				$errors['getback'] =  '用户名或密码错误!';
				$data['errors'] =$errors;
			}
			
		}
		// return all our data to an AJAX call
		echo json_encode($data);	
	}
	public function login()
	{
		$errors         = array();  	// array to hold validation errors
		$data 			= array(); 		// array to pass back data
		// validate the variables ======================================================
		if (empty($_REQUEST['name']))
			$errors = '用户名为空.';

		if (empty($_REQUEST['password']))
			$errors = '密码为空.';
		if ( ! empty($errors)) {

			// if there are items in our errors array, return those errors
			$data['success'] = false;
			$data['errors']  = $errors;
		} else {
			// 用户名密码正确访问数据库判断是否登录
			$this->load->model("Admin");
			$query=$this->Admin->user_select($_REQUEST['name'],$_REQUEST['password']);
			if($query->num_rows()>0){
				$data['success'] = true;
				$data['message'] = 'Success!';
			}else{
				$data['success'] = false;
				$errors = '用户名或密码错误.';
				$data['errors']  = $errors;
			}
			
		}
		// return all our data to an AJAX call
		echo json_encode($data);	
	}
	public function getallusers(){
		header("Content-type:text/html;charset=utf-8");
		$this->load->model("Admin");
		$p=$this->Admin->all_users();
		$outp="[";
		foreach($p->result_array() as $row){
			if ($outp != "[") {$outp .= ",";}
			$outp .= '{"name":"'  . $row["name"] . '",';
			$outp .= '"catalog":"'  . $row["catalog"] . '",';
			$outp .= '"id":"'  . $row["id"] . '",';
			$outp .= '"record":"'  . $row["record"] . '"}';
		}
		$outp .="]";
		echo $outp;
		
	}
	public function mainpage(){
		$this->load->view("mainpage.html");
	}
	public function getMarkers(){
		header("Content-type:text/html;charset=utf-8");
		$this->load->model("Admin");
		
		$p=$this->Admin->getDaysById($_REQUEST['id']);
		$outp="[";
		foreach($p->result_array() as $row){
			if($outp!="["){$outp.=",";}
			$outp .= '{"id":"'  . $row["id"] . '",';
			$outp .= '"day":"'  . $row["days"] . '"}';
		}
		$outp .="]";
		echo $outp;
	}
	public function getAllLatest(){
		header("Content-type:text/html;charset=utf-8");
		$this->load->model("Admin");
		$p=$this->Admin->getNewestPoints();
		$outp="[";
		foreach($p->result_array() as $row){
			if ($outp != "[") {$outp .= ",";}
			$outp .= '{"time":"'  . $row["time"] . '",';
			$outp .= '"lat":"'  . $row["lat"] . '",';
			$outp .= '"len":"'  . $row["len"] . '",';
			$outp .= '"id":"'  . $row["staff_id"] . '",';
			$outp .= '"name":"'  . $row["staff_name"] . '"}';
		}
		$outp .="]";
		echo $outp;
	}
	public function getAllLatestLine(){
		header("Content-type:text/html;charset=utf-8");
		$this->load->model("Admin");
		$p=$this->Admin->getNewestLines();
		$outp="[";
		foreach($p->result_array() as $row){
			if ($outp != "[") {$outp .= ",";}
			$outp .= '{"time":"'  . $row["time"] . '",';
			$outp .= '"lat":"'  . $row["lat"] . '",';
			$outp .= '"len":"'  . $row["len"] . '",';
			$outp .= '"id":"'  . $row["id"] . '",';
			$outp .= '"name":"'  . $row["name"] . '"}';
		}
		$outp .="]";
		echo $outp;
	}
	function getMarksByID()
	{
		$userID=2;//缺省，测试专用
		$userID=$_REQUEST["user"];
		
		$userDate='2015-03-21';//缺省，测试专用
		$userDate=$_REQUEST["date"];
		
		header("Content-type:text/html;charset=utf-8");
		$this->load->model("Admin");
		$q=$this->Admin->getMarks($userDate,$userID);
		if($q->num_rows()>0){
			$outp="[";
			foreach($q->result_array() as $row){
				if ($outp != "[") {$outp .= ",";}
				$outp .= '{"time":"'  . $row["time"] . '",';
				$outp .= '"lat":"'  . $row["lat"] . '",';
				$outp .= '"len":"'  . $row["len"] . '",';
				$outp .= '"id":"'  . $row["id"] . '",';
				$outp .= '"name":"'  . $row["name"] . '"}';
			}
			$outp .="]";
			echo $outp;
		}
		else{
			echo 2;
		}
	}
	
	
}

?>