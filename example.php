<?php
	/*
	
	--- CREATE DATABASE ------>
	// creem baza de date
	if( $db->new_database('database_name') == FALSE)
		echo $db->show_error();
	else
		echo 'I am a good script, so without problems I created a new database, for you :)';
	--------------------------->
	
	
	
	--- CREATE TABLE ---------->
	// creem un tabel
	if( $db->new_table('database_name', 'new_table_name', array('col1', 'col2', 'col3', 'col4')) == FALSE)
		echo $db->show_error();
	else
		echo 'Done';
	--------------------------->
		
		
		
	--- INSERT DATA ----------->
	if($db->insert('database_name', 'new_table_name', array('col1' => '3', 
															'col2' => 'whole', 
															'col3' => md5('rudwdbistrre'), 
															'col4' => '2099-62-52 13:32:40')) == FALSE)
		echo $db->show_error();
	else
		echo 'Done';
	--------------------------->
		
		
		
	--- DELETE ROWS ---------->
	$do = $db->delete('database_name', 'new_table_name', "(`col1`='3' AND `col4`='5') OR `col3`='double_web'");
	if($do == false) echo $db->show_error();
	else echo 'The rows has removed';
	--------------------------->
	
	
	
	--- SELECT --------------->
	$do = $db->select('teme_online', 'users', "*", "`user_id`='1' || `user_id`='3'");
	if($do == false) 
		echo $db->show_error();
	else 
		echo "<pre>".print_r($do, TRUE)."</pre>";
	--------------------------->
	
	
	
	--- NUM ROWS -------------->
	echo $db->num_rows($do);
	--------------------------->
	
	
	--- REMOVE DB ------------->
	if($db->drop_db('database_name') == FALSE) echo $db->show_error();
	else echo 'Done';
	--------------------------->
*/	
?>
