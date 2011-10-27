<?php
	/*
		App.php
		Created by: Marc (23/10/11)
		Description: Used to control application wide functionality.
		Update log:
			23/10/11 (MF) - Adding DB stuff.
			
	*/
	require_once('DB.php');
	require_once('Authenticate.php');
	require_once('page.php');
	
	class App {
		// DB connection details 
		const db_name = 'sales';
		const db_host = 'localhost';
		const db_user = 'root';
		const db_pass = '';
	
		private static $db;
		public static $auth = false; // Set to false if not authenticated
		
		public static function getDB() {
			if(self::$db == null) {
				self::$db = new DB(self::db_name, self::db_host, self::db_user, self::db_pass);
			}
			return self::$db;
		}
		
		public static function secureString($str) {
			return md5($str);
		}
		
		public static function authUser($username, $password) {
			self::$auth = new Authenticate();
			self::$auth->doAuth($username, $password);
			
			if(self::$auth->isAuth()) 
				return true;
			else
				return false;
		}
		
		public static function logoutUser() {
			unset(self::$auth);
			self::$auth = false;
		}
	}
	
	//App::authUser("Marc", "test");

?>