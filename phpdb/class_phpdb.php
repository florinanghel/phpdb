<?php
/**
 * @Package: PHPDB CLASS
 * @Author: double-web
 * @$Date: 2013-11-25 19:44:03 $
 * @$Rev: 1 $
 * @$Id: class_phpdb.php 4 2013-11-25 19:44:03 double_web $
 *
 * PHPdb class
 *
**/
	class PhpDB{
	
		var $errors = '';
	
	
		// SELECT
		function select($database_name, $table_name, $select, $where){
			
			// check if database exists
			if(file_exists('phpdb/storage/'.$database_name) == FALSE){
				$this->errors = "Database `".$database_name."` not found";
				return false;
			}
		
			if($select != ""){
		
		
				$fields = $this->get_columns_from_expression($where);
				foreach($fields as $key => $field){
					foreach(glob('phpdb/storage/'.$database_name.'/'.$table_name.'/'.$field.'/*') as $txt){
						$fields[ $field ][ $this->get_txt_name($txt) ] = file_get_contents($txt);
					}
					unset($fields[ $key ]);
				}
				
				$rows = array();
				foreach($fields as $key => $val){
					foreach($val as $r => $k){
						$rows[ $r ][ $key ] = $k;
					}
				}
				unset($fiels);

				$data = array();

				$i = 0;
				foreach($rows as $file => $row){
					$tmp_express = $where;
					
					foreach($row as $coloana => $value){
						$tmp_express = str_replace('`'.$coloana.'`', "'".$value."'", $tmp_express);
					}
					$tmp_express = str_replace("=", "==", $tmp_express);
					
					
					$evaluare = eval("return ( ".$tmp_express." );");
					if($evaluare == FALSE) continue;
					else{

						if($select == "*"){
							$columns = array();
							foreach(glob('phpdb/storage/'.$database_name.'/'.$table_name.'/*') as $txt){
								$columns[] = $this->get_txt_name($txt);
							}
						}
						else{
							$columns = explode(",", $select);
						}
						
						
						foreach($columns as $field){
							$text = file_get_contents('phpdb/storage/'.$database_name.'/'.$table_name.'/'.$field.'/'.$file);
							$data[$i][$field] = $text;
						}
						$i++;
						
					}

				}
				return $data;
		
			}
			else{
				return array();
			}
		}
		
		
		// UPDATE
		function update($database_name, $table_name, $set, $where){
			
			$fields = $this->get_columns_from_expression($where);
				foreach($fields as $key => $field){
					foreach(glob('phpdb/storage/'.$database_name.'/'.$table_name.'/'.$field.'/*') as $txt){
						$fields[ $field ][ $this->get_txt_name($txt) ] = file_get_contents($txt);
					}
					unset($fields[ $key ]);
				}
				
				$rows = array();
				foreach($fields as $key => $val){
					foreach($val as $r => $k){
						$rows[ $r ][ $key ] = $k;
					}
				}
				unset($fiels);
				
				$i = 0;
				foreach($rows as $file => $row){
					$tmp_express = $where;
					
					foreach($row as $coloana => $value){
						$tmp_express = str_replace('`'.$coloana.'`', "'".$value."'", $tmp_express);
					}
					$tmp_express = str_replace("=", "==", $tmp_express);
					
					$evaluare = eval("return ( ".$tmp_express." );");
					if($evaluare == FALSE) continue;
					else{

						foreach($set as $field => $text){
							file_put_contents('phpdb/storage/'.$database_name.'/'.$table_name.'/'.$field.'/'.$file, $text, LOCK_EX);
						}
						
					}

				}
				return true;
		}
	

	
		// INSERT FUNCTION
		function insert($database_name, $table_name, $values){
			// check if database exists
			if(file_exists('phpdb/storage/'.$database_name) == FALSE){
				$this->errors = "Database `".$database_name."` not found";
				return false;
			}
			// get current id
			$cols = glob('phpdb/storage/'.$database_name.'/'.$table_name.'/*');
			if(isset($cols[0])){
				$num_of_rows = 0;
				if ($handle = opendir($cols[0].'/')) {
					while (($file = readdir($handle)) !== false){
						if (!in_array($file, array('.', '..')) && !is_dir($cols[0].'/'.$file)) $num_of_rows++;
					}
				}
				if($num_of_rows == 0){
					$curr_id = 1;
				}
				else{
					$curr_id = $num_of_rows + 1;
				}
			}
			else{
				$this->errors = 'No column found in '.$table_name.' table.';
				return false;
			}
			

			foreach($cols as $coloana){
				$path = explode("/", $coloana);
				$col = end($path);
				if(isset($values[ $col ]))
					$this->create_cell($coloana.'/'.$curr_id.'.txt', $values[ $col ]);
				else
					$this->create_cell($coloana.'/'.$curr_id.'.txt', '');
				
			}
			
			// return true
			return true;
		}
		

		// NUM ROWS
		function num_rows($data){
			return count($data);		
		}
		

		
		// DELETE
		function delete($database_name, $table_name, $expression){

			$fields = $this->get_columns_from_expression($expression);
			foreach($fields as $key => $field){
				foreach(glob('phpdb/storage/'.$database_name.'/'.$table_name.'/'.$field.'/*') as $txt){
					$fields[ $field ][ $this->get_txt_name($txt) ] = file_get_contents($txt);
				}
				unset($fields[ $key ]);
			}
			
			$rows = array();
			foreach($fields as $key => $val){
				foreach($val as $r => $k){
					$rows[ $r ][ $key ] = $k;
				}
			}
			unset($fiels);
			
			foreach($rows as $file => $row){
				$tmp_express = $expression;
				
				foreach($row as $coloana => $value){
					$tmp_express = str_replace('`'.$coloana.'`', "'".$value."'", $tmp_express);
				}
				$tmp_express = str_replace("=", "==", $tmp_express);
				
				$evaluare = eval("return ( ".$tmp_express." );");
				if($evaluare == FALSE) continue;
				else{
					// stergem din fiecare coloana, acel rand
					foreach(glob('phpdb/storage/'.$database_name.'/'.$table_name.'/*') as $txt){
						if(file_exists($txt.'/'.$file)){
							if(!@unlink($txt.'/'.$file)){
								// write error
								$this->errors = "Failed to delete ".$file." in <b>".__FILE__.'</b> at line <b>'.__LINE__.'</b>';
								// return false
								return false;
							}
						}
					}
				}
			}
			// daca totul este ok
			return true;
		}
		
		function new_database($database_name){
			if ($this->make_dir('phpdb/storage/'.$database_name) == FALSE) {
				$this->errors = 'Failed to create a new database. The database `'.$database_name.'` already exists.';
				return false;
			}
			else{
				return true;
			}
		}
		
		function drop_db($database_name){
			if(file_exists('phpdb/storage/'.$database_name) == TRUE){
				$this->deleteDir('phpdb/storage/'.$database_name);
				return true;
			}
			else{
				$this->errors = 'Database `'.$database_name.'` not found';
				return false;
			}
		}
		
		public static function deleteDir($dirPath) {
			if (! is_dir($dirPath)) {
				throw new InvalidArgumentException("$dirPath must be a directory");
			}
			if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
				$dirPath .= '/';
			}
			$files = glob($dirPath . '*', GLOB_MARK);
			foreach ($files as $file) {
				if (is_dir($file)) {
					self::deleteDir($file);
				} else {
					unlink($file);
				}
			}
			rmdir($dirPath);
		}

		function new_table($database_name, $table_name, $cols){
			// create table directory
			if ($this->make_dir('phpdb/storage/'.$database_name.'/'.$table_name) == FALSE) {
				$this->errors = 'Failed to create a new table';
				return false;
			}
			// create columns directory
			foreach($cols as $coloana){
				if ($this->make_dir('phpdb/storage/'.$database_name.'/'.$table_name.'/'.$coloana) == FALSE) {
					$this->errors = 'Failed to create a new table';
					return false;
				}
			}
			// return true
			return true;
		}
	
		function show_error(){
			return $this->errors;
		}
	
		function make_dir($path, $permission = 0700){
			if (!@mkdir($path, $permission)) return false;
			else return true;
		}
		
		function create_cell($name, $value){
			$handle = fopen($name, 'w');
			fwrite($handle, $value);
			fclose($handle);
		}
		

		function get_columns_from_expression($content){
			if(	substr_count($content, '`') != 0 ){	
				$times = substr_count($content, '`') / 2;
				$fileds = array();
				for($i=1;$i<=$times;$i++){
					$r = explode('`', $content);
					if (isset($r[1])){
						$r = explode('`', $r[1]);
						$fields[] = $r[0];
					}
					$content = str_replace('`'.$r[0].'`', '', $content);
				}
				return $fields;
			}
			else{
				return false;
			}
		}
		

		function get_txt_name($string){
			$m = explode("/", $string);
			return end($m);
		}
	}
	

?>
