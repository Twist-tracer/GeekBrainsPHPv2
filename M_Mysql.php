<?
	
	define("DB_HOST","localhost");
	define("DB_USER","root");
	define("DB_PASSWORD","");
	define("DB_NAME","GB_PHPv2");
	
	
	class M_Mysql{
		private static $instance;
		
		private $link;
		
		private function __construct(){
			$this->link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die("Er:".mysqli_error($link));
			
			//ToDo
		}
		
		public static function GetInstance(){
			if(self::$instance == null)  
				self::$instance = new M_Mysql();
			
			return self::$instance;
		}
		
		//SELECT * FROM t1 LEFT JOIN t2 ON t1.id = t2.a_id WHERE t1.id > (SELECT MAX(t2.a_id) FROM t2)
		public function Select($sql){
			$result = mysqli_query($this->link, $sql);
			
			if(!$result) die(mysqli_error($link));
			
			$count = mysqli_num_rows($result);
			
			$rows = array();
			for($i=0;$i<$count;$i++){
				$rows[] = mysqli_fetch_assoc($result);
			}
			
			return $rows;
		}
		
		//INSERT INTO t1 (f1,f2,f3) VALUES(v1,v2,3)
		// $object = ["f1"=>"v1", "f2"=>"v2",...]
		public function Insert($table, $object){
			$columns = array();
			$values = array();
			
			foreach($object as $key => $value){
				$key = mysql_real_escape_string($key);
				$columns[] = $key;
				
				if($value == NULL){
					$values[] = "NULL";
				}
				else{
					$value = mysql_real_escape_string($value);
					$values[] = "'$value'";
				}
			}
			
			$columns_s = implode(",", $columns);
			$values_s = implode(",", $values);
			
			$sql = "INSERT INTO $table ($columns_s) VALUES ($values_s)";
			
			$result = mysqli_query($sql);
			if(!$result) die (mysqli_error($this->link));
			
			return mysqli_insert_id($this->link);
			
		}
	
		//UPDATE t1 SET f1=v1, f2=v2, f3=v3 WHERE id > 7
		
		public function Update($table, $object, $where){
			
			$sets = array();
			
			foreach($object as $key => $value){
				$key = mysql_real_escape_string($key);
				
				if($value == NULL){
					$sets[] = "$key=NULL";
				}
				else{
					$value = mysql_real_escape_string($value);
					$sets[] = "$key='$value'";
				}
			}
			
			$sets_s = implode(",",$sets);
			
			$sql = sprintf("UPDATE %s SET %s WHERE %s", $table, $sets_s, $where);
			$result = mysqli_query($this->link, $sql);
			
			if(!$result) die (mysqli_error($this->link));
			
			return mysqli_affected_rows($this->link);
			
		}
		
		//DELETE FROM table1 WHERE id=5
		
		public function Delete($table, $where){
			$sql = sprintf("DELETE FROM %s WHERE %s", $table, $where);
			$result = mysqli_query($this->link, $sql);
			if(!$result) die (mysqli_error($this->link));
			
			return mysqli_affected_rows($this->link);
		}
		
	}
	
	
	
?>




