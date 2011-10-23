<?php
	/*
		App.php
		Created by: Marc Fraser (23/10/11)
		Description: Used to control application wide functionality.
		Update log:
			23/10/11 (MF) - Adding DB stuff.
			
	*/
	require('DB.php');

	class App {
		// DB connection details 
		const db_name = 'sales';
		const db_host = 'localhost';
		const db_user = 'root';
		const db_pass = '';
		// DB connection details end
		private static $db;
	
		public static function getDB() {
			if(self::$db == null) {
				$db = new DB(self::db_name, self::db_host, self::db_user, self::db_pass);

				self::$db = $db;
			}
			return self::$db;
		}
	}
	
	$app = new App();
	$app::getDB()->getDataRow("SELECT * FROM user");
	
?>