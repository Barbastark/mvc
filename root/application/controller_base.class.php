<?php

Abstract Class baseController {

	// CATCHABLE ERRORS
	public static function captureNormal( $number, $message, $file, $line )
	{
		// Insert all in one table
		$error = array( 'type' => $number, 'message' => $message, 'file' => $file, 'line' => $line );
		// Display content $error variable
		if(strpos($error['message'], 'Using an empty Initialization Vector') > -1) return;

		if(!self::isProduction()) {
			echo '<h2>Fel</h2>';
			echo '<pre>';
			print_r( $error );

			$file = explode("/", $file);
			echo "Fil: " . $file[count($file)-1];
			echo "<br />";
			echo "Rad: $line";
			echo '</pre>';
		}
		else {
			$errorMailing = "";
			foreach ($error as $value) { $errorMailing .= $value." | "; }
			@mail("example@mail.com","[fel.se] captureNormal", "Felmeddelande: " . $errorMailing . " ", "From: from\n");
		}
	}

	// EXTENSIONS
	public static function captureException( $exception )
	{
		if(!self::isProduction()) {
			// Display content $exception variable
			echo '<pre>';
			print_r( $exception );
			echo '</pre>';
		}
		else {
			$errorMailing = "";
			foreach ($exception as $value) { $errorMailing .= $value." | "; }
			@mail("example@mail.com","[fel.se] captureException", "Felmeddelande: " . $errorMailing . " ", "From: from\n");
		}
	}

	// UNCATCHABLE ERRORS
	public static function captureShutdown()
	{
		$error = error_get_last( );
		if(!$error) {
			return true;
		}

		if(!self::isProduction()) {

			// Display content $error variable
			echo '<pre>';
			print_r( $error );
			echo '</pre>';
		}
		else {
			$errorMailing = "";
			foreach ($error as $value) { $errorMailing .= $value." | "; }
			@mail("example@mail.com","[fel.se] captureShutdown", "Felmeddelande: " . $errorMailing . " ", "From: frommail\n");
		}
	}


	/*
	 * @registry object
	 */
	protected $registry;

	function __construct($registry) {
		$this->registry = $registry;
	}

	/**
	 * @all controllers must contain an index method
	 */
	abstract function index();
}

ini_set( 'display_errors', 0 );
error_reporting( 0 );
set_error_handler( array( 'baseController', 'captureNormal' ) );
set_exception_handler( array( 'baseController', 'captureException' ) );
register_shutdown_function( array( 'baseController', 'captureShutdown' ) );
