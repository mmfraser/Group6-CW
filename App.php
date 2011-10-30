<?php
	/*
		App.php
		Created by: Marc (23/10/11)
		Description: Used to control application wide functionality.
		Update log:
			23/10/11 (MF) - Adding DB stuff.
			
	*/
	error_reporting(E_ERROR | E_WARNING);
	ob_start();
	session_start();
	require_once('AppClasses/DB.php');
	require_once('AppClasses/Authenticate.php');
	require_once('AppClasses/page.php');
	
	class App {
		// DB connection details 
		const db_name = 'sales';
		const db_host = 'localhost';
		const db_user = 'root';
		const db_pass = '';
	
		private static $db;
		public static $auth = false; // Set to false if not authenticated
		
		public static function getDB() {
			return DB::getInstance();
		}
		
		public static function secureString($str) {
			return md5($str);
		}
		
		public static function authUser($username, $password) {
			if(isset($_SESSION['loggedIn']) && !is_object(self::$auth)) {
				self::$auth = unserialize($_SESSION['loggedIn']);
			} else {
				self::$auth = new Authenticate();
				self::$auth->doAuth($username, $password);
			}

			if(self::$auth->isAuth()) {
				return true;
			} else
				return false;
		}
		
		public static function logoutUser() {
			unset(self::$auth);
			self::$auth = false;
		}
		
		public static function checkAuth() {
			if(isset($_SESSION['loggedIn']) && !is_object(self::$auth)) {
				self::$auth = unserialize($_SESSION['loggedIn']);
			}
			if(!is_object(self::$auth)) {
				return false;
			} else {
				if(self::$auth->isAuth())
					return true;
				return false;
			}
		}
		
		public static function fatalError($page, $err) {
			print '<div class="ui-state-error ui-corner-all"><span class="ui-icon ui-icon-alert" style="float:left;margin:2px 5px 0 0;"></span><span>'.$err.'</span></div>';
			$page->getFooter();
			die();
		}
	}

?>