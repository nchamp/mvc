<?php
require_once(LIBRARY_PATH . DS . 'Template.php');
require_once(APP_PATH . DS . 'models/Recipe.php');
/**
 * Recipe Controller Class
 * 
 * Routes all user HTTP requests relating to Recipes and displays the appropriate view
 * 
 * @author Nicholas Potesta
 *
 */
class RecipeController{
	
	/**
	 * Controller constructor
	 * 
	 * @param: None
	 * @return: None
	 */
	public function __construct(){
		$this->template = new Template;
    	$this->template->template_dir = APP_PATH . DS . 'views' . DS . 'recipes' . DS;
    	
    	$this->template->title = "Recipes";
	}
	
	/**
	* Show specific Recipe by ID
	*
	* @param: $id An integer value representing the recipe ID
	* @return: None
	*/
	public function show($id){
		$this->template->id = $id;
		
		//Get the recipe from the database
		$recipes = Recipe::retrieve(array("id"=>$id));
		//If successful and the count is equal to one (as desired)
		//set the recipe object in the template to be displayed
		if(count($recipes) == 1){
			$this->template->recipe = $recipes[0];
		}
		
		$this->template->display('show.html.php');		
	}
	
	/**
	* Show recipe search results (From POST parameteres)
	*
	* @param: $criteria Associative array consisting of key=Field value=Criteria
	* @return: None
	*/
	public function show($criteria){
		$this->template->id = $id;
	
		//Get the recipe from the database
		$recipes = Recipe::retrieve(array("id"=>$id));
		//If successful and the count is equal to one (as desired)
		//set the recipe object in the template to be displayed
		if(count($recipes) == 1){
			$this->template->recipe = $recipes[0];
		}
	
		$this->template->display('show.html.php');
	}
	
}