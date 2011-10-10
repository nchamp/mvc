<?php
require_once(LIBRARY_PATH . DS . 'Template.php');
require_once(APP_PATH . DS . 'models/Cuisine.php');
/**
 * Cuisine Controller Class
 *
 * Routes all user HTTP requests relating to Cuisines and displays the appropriate view
 *
 * @author Nicholas Potesta
 *
 */
class CuisineController{

	/**
	 * Controller constructor
	 *
	 * @param: None
	 * @return: None
	 */
	public function __construct(){
		$this->template = new Template;
		$this->template->template_dir = APP_PATH . DS . 'views' . DS . 'Cuisines' . DS;
		 
		$this->template->title = "Cuisines";
	}

	/**
	 * Controller homepage/index
	 *
	 * @param: None
	 * @return: None
	 */
	public function index(){
		$this->template->display('index.html.php');
	}

	/**
	 * Show specific Cuisine by ID
	 *
	 * @param: $id An integer value representing the Cuisine ID
	 * @return: None
	 */
	public function show($id){
		$this->template->id = $id;

		//Get the Cuisine from the database
		$Cuisines = Cuisine::retrieve(array("id"=>$id));
		//If successful and the count is equal to one (as desired)
		//set the Cuisine object in the template to be displayed
		if(count($Cuisines) == 1){
			$this->template->Cuisine = $Cuisines[0];
		}

		$this->template->display('show.html.php');
	}



}