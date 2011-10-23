<?php
	/*
		App.php
		Created by: Marc Fraser (23/10/11)
		Description: Used to control application wide functionality.
		Update log:
			23/10/11 (MF) - Adding DB stuff.
			
	*/
	require('DB.php');

	public static class App {
		// DB connection details 
		private static const $db_name = 'sales' 
		private static const $db_host = 'localhost' 
		private static const $db_user = 'root' 
		private static const $db_pass = '' 
		// DB connection details end
		private static $db;
	
		public static getDB() {
			if(self::$db == null) {
				DB::$DB_NAME = self::$db_name;
				DB::$DB_HOST = self::$db_host;
				DB::$DB_USER = self::$db_user;
				DB::$DB_PASS = self::$db_pass;
				self::$db = DB::getInstance();
			}
			
			return self::$db;
		}
	}
	
	$app = new App();
	$app::getDB();
	
?>