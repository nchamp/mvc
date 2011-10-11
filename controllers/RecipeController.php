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
		$recipes = Recipe::retrieve(array("RecipeID"=>$id));
		//If successful and the count is equal to one (as desired)
		//set the recipe object in the template to be displayed
		if(count($recipes) == 1){
			$this->template->recipe = $recipes;
		}
		
		$this->template->display('show.html.php');
	}
	
	/**
	* Function to show all recipes
	*
	* @param: None
	* @return: None
	*/
	public function all(){	
		//Get ALL recipes from the database, but limit the search according to the 
		//GET parameters outlining upper and lowerl imits
		$recipes = Recipe::retrieve();
		//Set the recipes results to the template and display
		$this->template->recipes = $recipes;
		$this->template->display('all.html.php');
	}
	
	
	/**
	* Function for recipe search (From POST parameteres)
	*
	* If POST parameters are sent, then perform search
	* If no POST parameters exist in the HTTP request, display the search controls/form
	*
	* @param: None
	* @return: None
	*/
	public function search(){
		//Test if the POST parameters exist. If they don't present the user with the
		//search controls/form
		if(!isset($_POST["search"])){
			$this->template->display('search.html.php');			
		}
		
		//Get the recipe from the database
		$recipes = Recipe::search($_POST["search"]);
		//If successful and the count is equal to one (as desired)
		//set the recipe object in the template to be displayed
		$this->template->recipes = $recipes;
	
		$this->template->display('results.html.php');
	}
	
}