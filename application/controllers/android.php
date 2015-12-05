<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Android extends CI_Controller {

	public function index()
	{
		$this->load->model("User");
		if (isset($_POST['name']) && isset($_POST['pass']) ) {
			$uname = $_POST['name'];
			$upass = $_POST['pass'];
			$user=$this->User->user_select($uname);
			$response = array();
			if($user->num_rows() > 0)
			{
				$row=$user->row_array();
				if($row['userpass']==$upass)
				{
					$response["success"] = 1;
					$response["message"] = "ok";
				}
				else{
					$response["success"] = 2;
					$response["message"] = "wrong";
				}
				
				echo json_encode($response);
			}else {
				$response["success"] = 0;
				$response["message"] = "no";

				// echoing JSON response
				echo json_encode($response);
			}
		}else{
			// required field is missing
            $response["success"] = 2;
			$response["message"] = "3";

			// echoing JSON response
			echo json_encode($response);
		}
		
	}
	public function route()
	{
		$this->load->model("User");
		$uname = $_POST['user_name'];
		$lng = $_POST['log'];
		$lat = $_POST['lan'];
		
		//将GPS坐标转化为百度地图坐标
		$c=file_get_contents("http://api.map.baidu.com/ag/coord/convert?from=0&to=4&x=$lng&y=$lat");
		$arr=(array)json_decode($c);
		if(!$arr['error'])
		{
			$ulog=base64_decode($arr['y']);
			$ulat=base64_decode($arr['x']);
		}
		
		//转化end
		
		$time= date("Y-m-d H:i:s", time());
		$user=$this->User->user_select($uname);
		$data=array(
			'staff_id'=>$uname,
			'Time'=>$time,
			'Len'=>$ulat,
			'Lat'=>$ulog
			);
		$query=$this->User->route($data);
	
		$response = array();
		if($query>=1){
			$response["success"] = 1;
			$response["message"] = "success";
			echo json_encode($response);
		}
	}
	
	public function login()
	{
		$this->load->model("Andfunc");
		if (isset($_POST['phone']) && isset($_POST['pass']) ) {
			$uname = $_POST['phone'];
			$upass = $_POST['pass'];
			$user=$this->Andfunc->user_select($uname);
			//声明返回数组
			$response = array();
			//
			if($user->num_rows() > 0)
			{
				$row=$user->row_array();
				if($row['staff_pass']==$upass)
				{
					$response["success"] = 1;
					$response["message"] = "ok";
				}
				else{
					$response["success"] = 2;
					$response["message"] = "wrong";
				}
				
				echo json_encode($response);
			}else {
				$response["success"] = 0;
				$response["message"] = "no";

				// echoing JSON response
				echo json_encode($response);
			}
		}else{
			// required field is missing
			$response["success"] = 2;
			$response["message"] = "3";

			// echoing JSON response
			echo json_encode($response);
		}
		
	}
	public function getlocation()
	{
		$this->load->model("Andfunc");
		
		if ( isset($_POST['log'])&& isset($_POST['lan']) ) {
			$uname = $_POST['user_name'];
			
			$lng = $_POST['log'];
			$lat = $_POST['lan'];
			$id=null;
			//将GPS坐标转化为百度地图坐标
			
			$c=file_get_contents("http://api.map.baidu.com/ag/coord/convert?from=0&to=4&x=$lng&y=$lat");
			$arr=(array)json_decode($c);
			if(!$arr['error'])
			{
				$lat=base64_decode($arr['y']);
				$lng=base64_decode($arr['x']);
			}
			
			//转化end
			
			$time= date("Y-m-d H:i:s", time());
			$p=$this->Andfunc->getIdByPhone($uname);
			
			foreach($p->result_array() as $row){
				$id=$row["staff_id"];
			}
			echo $id;
			$data=array(
				'id'=>$id,
				'staff_phone_number'=>$uname,
				'Time'=>$time,
				'Len'=>$lng,
				'Lat'=>$lat
				);
			$query=$this->Andfunc->route($data);
		
			$response = array();
			if($query>=1){
				$response["success"] = 1;
				$response["message"] = "success";
				echo json_encode($response);
			}
			
		}
		else{
			
			$response["success"] = 2;
			$response["message"] = "3";

			// echoing JSON response
			echo json_encode($response);
		}
		
	}
	
}

?>