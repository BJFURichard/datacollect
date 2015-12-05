<?php
	class Admin extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
			$this->load->database();
		}
		function user_select($user,$pass)
		{
			$sql = "SELECT * FROM admin WHERE username = ? AND userpass = ?"; 
			$query = $this->db->query($sql, array($user,$pass));
			return $query;
		}
		function all_users()
		{
			$sql = "select staff_info.staff_name name,staff_info.catalog_id catalog,staff_info.staff_id id,count(staff_gps.lat) record
					from staff_info left join staff_gps
					on staff_info.staff_phone_number= staff_gps.staff_phone_number
					group by staff_info.staff_name
					order by staff_info.staff_id"; 
			$query = $this->db->query($sql, array());
			return $query;
		}
		function getNewestPoints(){
			$sql='select t1.time time,lat,len,staff_id,staff_name
					from
					(select staff_gps.staff_phone_number,staff_gps.Time,Lat,Len,txt,pic,
									voc,staff_id,staff_name
					  from 
					staff_gps LEFT JOIN staff_info 
					ON staff_gps.staff_phone_number=staff_info.staff_phone_number) t1
					JOIN
					(
					select staff_phone_number, MAX(Time) time 
					FROM staff_gps 
					where NOW()-Time<= 100*100
					GROUP BY staff_phone_number
					)t2 
					on t1.staff_phone_number=t2.staff_phone_number
					and t1.Time=t2.time
			';//5分钟之内的最新记录
			$query=$this->db->query($sql);
			return $query;
		}
		function getNewestLines(){
			$sql='
				SELECT gps.id id,gps.time time,gps.Lat lat,gps.Len len,info.staff_name name
				FROM staff_gps gps 
				INNER JOIN staff_info info on gps.id=info.staff_id
				WHERE DATE_FORMAT(gps.Time,\'%Y-%m-%d\')= DATE_FORMAT(NOW(),\'%Y-%m-%d\')
				order by id' 
			;//5分钟之内的最新记录
			$query=$this->db->query($sql);
			return $query;
			/*		
			select * from staff_info where staff_id in(
			select id 
			from staff_gps 
			where DATE_FORMAT(staff_gps.Time,'%Y-%m-%d')= DATE_FORMAT(NOW(),'%Y-%m-%d')
			)//返回当日有外业记录的人*/
			/*返回当日的所有记录及人员信息*/
			/*选择*/
		}
		function getMarkersById(){
			$sql='select t1.time time,lat,len,staff_id,staff_name
					from
					(select staff_gps.staff_phone_number,staff_gps.Time,Lat,Len,txt,pic,
									voc,staff_id,staff_name
					  from 
					staff_gps LEFT JOIN staff_info 
					ON staff_gps.staff_phone_number=staff_info.staff_phone_number) t1
					JOIN
					(
					select staff_phone_number, MAX(Time) time 
					FROM staff_gps 
					GROUP BY staff_phone_number
					)t2 
					on t1.staff_phone_number=t2.staff_phone_number
					and t1.Time=t2.time
			';
			$query=$this->db->query($sql);
			return $query;
		}
		function getDaysById($id)
		{
			$sql = 'SELECT a.id id,DATE_FORMAT( a.Time, "%Y-%m-%d" ) days
					FROM
					(select * from staff_gps where id=?) a
					GROUP BY DATE_FORMAT( Time, "%Y-%m-%d" )' ; 
			$query=$this->db->query($sql, array($id));
			return $query;
		}
		function getMarks($date,$id)
		{
			$sql = 'select id,staff_gps.Time time,lat,len,staff_name name
from staff_gps LEFT JOIN staff_info ON staff_gps.staff_phone_number=staff_info.staff_phone_number 
where DATE_FORMAT( staff_gps.Time, "%Y-%m-%d" )=? and staff_info.staff_id=?' ; 
			$query=$this->db->query($sql, array($date,$id));
			return $query;
		}
	}
?>