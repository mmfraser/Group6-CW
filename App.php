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
	$referer = explode("/", $_SERVER['HTTP_REFERER']);
	if (in_array("IntUI", $referer)){
		require_once('AppClasses/Authenticate.php');
		require_once('AppClasses/page.php');
	
	}
	
	class App {
		public static $auth = false; 	// Set to false if not authenticated
		public static $noRows = 2;		// Number of rows on the dashboard
		public static $noCols = 3; 		// Number of columns on the dashboard
		
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
			session_destroy();
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
		
		public static function getAuthUser() {
			if(self::checkAuth()) 
				return self::$auth->getAuthUser();
			else 
				return null;
		}
		
		public static function fatalError($page, $err) {
			print '<div class="ui-state-error ui-corner-all"><span class="ui-icon ui-icon-alert" style="float:left;margin:2px 5px 0 0;"></span><span>'.$err.'</span></div>';
			$page->getFooter();
			die();
		}
		
		public static function validateEmail($email) {
			if(preg_match("/^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$/", $email)) 
				return true;
			return false;
		}
	}

?>
