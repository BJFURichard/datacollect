<?php
	class Andfunc extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
			$this->load->database();
		}
		function user_select($user)
		{
			$this->db->select('*');
			$this->db->where('staff_phone_number', $user);
			$this->db->from('staff_info');
			$query = $this->db->get();
			return $query;
		}
		
		function getIdByPhone($user){
			$this->db->select('*');
			$this->db->where('staff_phone_number', $user);
			$this->db->from('staff_info');
			$query = $this->db->get();
			return $query;
		}
		function add($data)
		{
			$this->db->insert('people', $data); 
			return $this->db->affected_rows();
		}
		function addadmin($data)
		{
			$this->db->insert('admin', $data); 
			return $this->db->affected_rows();
		}
		function getid()
		{
			$sql = "select MAX(staff_id) max FROM people"; 
			$query=$this->db->query($sql);
			return $query;
		}
		function update($data,$id)
		{
			$this->db->where('staff_id',$id );
			$this->db->update("people", $data);
			return $this->db->affected_rows();
		}
		function delUser($uid){
			if(is_array($uid)){ 
            $uid = implode(',',$uid); 
			} 
			$sql = 'delete from people where staff_id in ('.$uid.')'; 
			$this->db->query($sql);
			return $this->db->affected_rows();
		}
		function route($data){
			$this->db->insert('staff_gps', $data);
			return $this->db->affected_rows();
		}
	}
?>