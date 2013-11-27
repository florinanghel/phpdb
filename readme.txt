phpdb
=====

A little php class for the simple websites, which need a small database.

INSTALATION:
1. Download this project.
2. Unzip it.
3. Move or Copy the phpdb folder, in your application folder.
4. Include in every page class_phpdb.php.
5. Create the object: $db = new PhpDB;
6. Enjoy !


COMMANDS:

SELECT: $db->select( database_name , table_name , columns , where_condition );
  - return FALSE on error;
  - return an array with the informations <=> mysql_query(SELECT) + mysql_fetch_array()
  
CREATE DATABASE: $db->new_database( database_name );
  - return FALSE on error;
  - return TRUE on success.
  
CREATE TABLE: $db->new_table( database_name , new_table_name , array('col1', 'col2', 'col3', 'col4') );
  - return FALSE on error;
  - return TRUE on success;
  - the 3nd argument need an array with the columns names.
  
INSERT DATA: $db->insert( database_name , new_table_name , array( 'col1' => '3', 
															                                    'col2' => 'whole', 
															                                    'col3' => md5('rudwdbistrre'), 
															                                    'col4' => '2099-62-52 13:32:40'));
  - return FALSE on error;
  - return TRUE on success;
  
DELETE ROWS: $db->delete( database_name , new_table_name , where );
  - return FALSE on error;
  - return TRUE on success;
  
COUNT ROWS: $db->num_rows( select_array );
  - return the number of the rows;
  
DROP DB: $db->drop_db( database_name );
  - return FALSE on error;
  - return TRUE on success;
  
In the example.php file, you have all the examples.

Good Luck !
