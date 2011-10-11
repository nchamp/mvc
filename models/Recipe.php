<<<<<<< HEAD:models/Recipe.php
<?php

require_once(LIBRARY_PATH . DS . 'Database.php');

/**
 * This is the Recipe class.
 *
 * @author Jiang Chen 
 */
class Recipe {

   /**
    * If validation fails, errors are written to this variable.
	*/
   private static $errors;
   
   /**
    * A method for validating the data
	* 
	* @param $data An array of POSTed data.
	* @return bool Whether the data is valid or not.
	*/
   public static function validates(array &$data) {
      $errors = array();

	  if (!isset($data['Ingredients']) || empty($data['Ingredients'])) {
        $errors['Ingredients'] = 'You must provide the ingredients';
	    unset($data['Ingredients']);
      } 

      if (!preg_match("/^[0-9]+$/i", $data['ServingSize'])) {
	     $error['ServingSize'] = 'You serving size must only be numbers.';
	  }
	  if (!isset($data['ServingSize']) || empty($data['ServingSize'])) {
         $errors['ServingSize'] = 'You must provide the serving size.';
		 unset($data['ServingSize']);
	  }
	  
	  if (!preg_match("/^[0-9]+$/i", $data['CookTime'])) {
	     $error['ServingSize'] = 'You cook time must only be numbers.';
	  }
	  if (!isset($data['CookTime']) || empty($data['CookTime'])) {
         $errors['CookTime'] = 'You must provide the cook time.';
		 unset($data['CookTime']);
	  }
	  
	  if (!isset($data['Difficulty']) || empty($data['Difficulty'])) {
        $errors['Difficulty'] = 'You must provide the difficulty.';
		unset($data['Difficulty']);
	  }
	  
	  if (!isset($data['CuisineID']) || empty($data['CuisineID'])) {
        $errors['CuisineID'] = 'You must provide the cuisine id.';
		unset($data['CuisineID']);
	  }

	  if (!preg_match("/^[0-9]+$/i", $data['Rating'])) {
	     $error['Rating'] = 'You rating must only be numbers.';
	  }
	  if (!isset($data['Rating']) || empty($data['Rating'])) {
         $errors['Rating'] = 'You must provide the rating.';
		 unset($data['Rating']);
	  }
	  
	  self::$errors = $errors;
	  if (count($errors)) {
         return false;
	  }
	  return true;
   }

   /**
    * Returns any validation errors.
	* 
	* @return array An array of errors, or an empty array.
	*/
   public static function errors() { 
      return self::$errors;  
   }

   /**
    * A method for retrieving recipes from the Recipe table.
	*
	* @param array $data An optional array of key:value pairs to be used as
	*                    parameters in the SQL query.
    *        int $upperlim An optional integer value of the upper limit of returned
    *        			  results
    *        int $lowerlim An optional integer value of the lower limit of returned
    *        			  results
	* @return array An array of database Objects where each Object represents a 
	*               recipe.
	*/
   public static function retrieve(array $data = array(),$upperlim = null, $lowerlim = null) { 
      $sql = 'SELECT * FROM Recipe';
	  $values = array();
	  if (count($data)) {
         $count = 0; 
		 foreach ($data as $key => $value) { 
		    if ((++$count) == 1) {
    		   $sql .= " WHERE {$key} = ?";
			   $values[] = $value;
		    } else {
			  $sql .= " AND {$key} = ?";
			  $values[] = $value;
			}
		 }
	  }

	  try {
         $database = Database::getInstance(); 

         $statement = $database->pdo->prepare($sql); 

		 $statement->execute($values);
		 // result is FALSE if no rows found
		 $result = $statement->fetchAll(PDO::FETCH_OBJ); 

         $database->pdo = null;
	  } catch (PDOException $e) {
        echo $e->getMessage(); 
		exit;  
	  }
	  if (count($result) > 1) {
        return $result;  
	  } else if (count($result) == 1) {
        return $result[0]; 
	  } else {
        return NULL; 
	  }
   }

   /**
   * A method for searching for recipes from the Recipe table.
   * This uses the SQL 'LIKE' command to search for the best matches to the criteria
   *
   * @param array $data An optional array of key:value pairs to be used as
   *                    parameters in the SQL query.
   *        int $upperlim An optional integer value of the upper limit of returned
   *        			  results
   *        int $lowerlim An optional integer value of the lower limit of returned
   *        			  results
   * @return array An array of database Objects where each Object represents a
   *               recipe.
   */   
   public static function search(array $data = array(),$upperlim = null, $lowerlim = null) {
   	$sql = 'SELECT * FROM Recipe';
   	$values = array();
   	if (count($data)) {
   		$count = 0;
   		foreach ($data as $key => $value) {
   			if ((++$count) == 1) {
   				$sql .= " WHERE {$key} = ?";
   				$values[] = '%'.$value.'%';
   			} else {
   				$sql .= " AND {$key} = ?";
   				$values[] = '%'.$value.'%';
   			}
   		}
   	}
   
   	try {
   		$database = Database::getInstance();
   
   		$statement = $database->pdo->prepare($sql);
   
   		$statement->execute($values);
   		// result is FALSE if no rows found
   		$result = $statement->fetchAll(PDO::FETCH_OBJ);
   
   		$database->pdo = null;
   	} catch (PDOException $e) {
   		echo $e->getMessage();
   		exit;
   	}
   	if (count($result) > 1) {
   		return $result;
   	} else if (count($result) == 1) {
   		return $result[0];
   	} else {
   		return NULL;
   	}
   }
   /**
    * Writes a new row to the Recipe table based on given data.
	*
	* @param array $data The POSTed data.
	* @return int Returns RecipeID of the inserted row (or throws an Exception)
	*/
   public static function create(array $data) {

	  $sql  = 'INSERT INTO Recipe (Ingredients, ServingSize, CookTime, Difficulty, CuisineID, Rating, PhotoLink) VALUES (?, ?, ?, ?, ?, ?, ?)';
	  $values = array(
         $data['Ingredients'],
		 $data['ServingSize'],
		 $data['CookTime'],
		 $data['Difficulty'],
         $data['CuisineID'],
         $data['Rating'],
         $data['PhotoLink']
	  );

	  try {
         $database = Database::getInstance();

		 $statement = $database->pdo->prepare($sql);
		 $return = $statement->execute($values);

         if ($return) {
   		    $RecipeID = $database->pdo->lastInsertId(); 
		 }
		 $database->pdo = null;
	  } catch (PDOException $e) {
        echo $e->getMessage();
		exit; 
	  }
	  if ($return) { 
	    return $RecipeID;
	  }
	  return false;
   }

   /**
    * Updates an existing row in the Recipe table based on given data. 
	*
	* @param int $RecipeID The row id of the recipe to update. 
	* @param array $data The POSTed data.
	* @return int bool Whether update was successful or not.  
	*/
   public static function update($RecipeID, array $data) {

	  $sql  = 'UPDATE Recipe SET Ingredients = ?, ServingSize = ?, Difficulty = ?, CuisineID = ?, Rating = ?, PhotoLink = ? WHERE RecipeID = ?';
	  $values = array(
         $data['Ingredients'],
		 $data['ServingSize'],
		 $data['Difficulty'],
		 $data['CuisineID'],
		 $data['Rating'],
		 $data['PhotoLink'],
		 $RecipeID
	  );

	  try {
         $database = Database::getInstance();

		 $statement = $database->pdo->prepare($sql);
		 $return = $statement->execute($values);

         $database->pdo = null; 
	  } catch (PDOException $e) {
        echo $e->getMessage();  
		exit;  
	  }
	  return $return;
   }
   
   public static function delete($RecipeID, array $data) { 
   
      $sql = 'DELETE FROM Recipe WHERE RecipeID = ?';
	  $values = array(
	     $data['Ingredients'],
		 $data['ServingSize'],
		 $data['Difficulty'],
		 $data['CuisineID'],
		 $data['Rating'],
		 $data['PhotoLink'],
		 $RecipeID
	  );
	  
	  try {
	     $database = Database::getInstance();
		 
		 $statement = $database->pdo->prepare($sql);
		 $return = $statement->execute($values);
		 
		 $database->pdo = null;
	  } catch (PDOException $e) {
	    echo $e->getMessage(); 
		exit;
	  }
	  return $return; 
   }
   
}

?>