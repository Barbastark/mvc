<?php

class router {
	/*
	* @the registry
	*/
	private $registry;

	/*
	* @the controller path
	*/
	private $path;

	private $args = array();

	public $file;

	public $controller;

	public $action;

	public $addMoreControllers;  


	function __construct($registry) {
		$this->registry = $registry;
	}

	/**
	 *
	 * @set controller directory path
	 *
	 * @param string $path
	 *
	 * @return void
	 *
	**/

	function setPath($path) {

		/*** check if path i sa directory ***/
		if (is_dir($path) == false)
		{
			throw new Exception ('Invalid controller path: `' . $path . '`');
		}
		/*** set the path ***/
		$this->path = $path;
	}

	/**
	 *
	 * @load the controller
	 *
	 * @access public
	 *
	 * @return void
	 *
	**/

	public function loader()
	{
		/*** check the route ***/
		$this->getController();

		/*** if the file is not there diaf ***/
		if (is_readable($this->file) == false)
		{
			$this->file = $this->path .'/indexController.php';
			$this->controller = 'index';
			$this->action = 'error404';
		}

		/*** include the controller ***/
		//	print $this->file . "<br/>";    // Which file is read
		include $this->file;

		/*** a new controller class instance ***/
		$class = $this->controller . 'Controller';
		$controller = new $class($this->registry);

		/*** check if the action is callable ***/
		if (is_callable(array($controller, $this->action)) == false)
		{
			$action = 'error404';
		}
		else
		{
			$action = $this->action;
		}

		/*** run the action ***/
		if (empty($this->addMoreControllers)) {
			$controller->$action();
		} 
		else {
			$controller->$action($this->addMoreControllers);  // enable more pages
		}
	}

	/**
	 *
	 * @get the controller
	 *
	 * @access private
	 *
	 * @return void
	 *
	**/

	private function getController() {

		/*** get the route from the url ***/
		$route = (empty($_GET['rt'])) ? '' : $_GET['rt'];
		$route = str_replace("-", "_",$route);

		if (empty($route)) {
			$route = 'index';
		} else {
			if(($parts[1] == "example1") || ($parts[1] == "example2")) {
				$this->controller = "index";
				$this->action = $parts[1];
			} 
		}

		if (empty($this->controller)) { $this->controller = 'index'; }

		/*** Get action ***/
		if (empty($this->action)) {
			$this->action = 'index';
		} else if($this->action == 'index.php') {
			$this->action = 'index';
		}

		/*** set the file path ***/
		$this->file = $this->path .'/'. $this->controller . 'Controller.php';
	}
}


