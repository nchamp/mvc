<<<<<<< HEAD:models/RecipeBook.php
<?php

require_once(LIBRARY_PATH . DS . 'Database.php');

/**
 * This is the RecipeBook class.
 *
 * @author Jiang Chen
 */
class RecipeBook {

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

	  if (!isset($data['UserID']) || empty($data['UserID'])) { 
	     $errors['UserID'] = 'You must provide the user id.';
		 unset($data['UserID']);
	  }

	  if (!isset($data['Name']) || empty($data['Name'])) {
        $errors['Name'] = 'You must provide the recipe book name.';
		unset($data['Name']);
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
    * A method for retrieving recipe book from the RecipeBook table.
	*
	* @param array $data An optional array of key:value pairs to be used as
	*                    parameters in the SQL query. 
	* @return array An array of database Objects where each Object represents a
	*               recipe book.
	*/
   public static function retrieve(array $data = array()) { 

      $sql = 'SELECT * FROM RecipeBook';
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
    * Writes a new row to the RecipeBook table based on given data.
	* 
	* @param array $data The POSTed data.  
	* @return int Returns BookID of the inserted row (or throws an Exception) 
	*/
   public static function create(array $data) {

	  $sql  = 'INSERT INTO RecipeBook (UserID, Name) VALUES (?, ?)';
	  $values = array( 
	     $data['UserID'],
		 $data['Name']
	  );

	  try {
         $database = Database::getInstance(); 

         $statement = $database->pdo->prepare($sql);
		 $return = $statement->execute($values);

         if ($return) {
    	    $BookID = $database->pdo->lastInsertId(); 
		 }
		 $database->pdo = null; 
	  } catch (PDOException $e) {
        echo $e->getMessage();
		exit; 
      }
	  if ($return) {
         return $BookID;
	  }
	  return false;
   }

   /**
    * Updates an existing row in the RecipeBook table based on given data. 
	*
	* @param int $BookID The row id of the RecipeBook to update.
	* @param array $data The POSTed data.   
	* @return int bool Whether update was successful or not.  
	*/
   public static function update($BookID, array $data) {

	  $sql  = 'UPDATE RecipeBook SET UserID = ?, Name = ? WHERE BookID = ?';
	  $values = array(
         $data['UserID'],
		 $data['Name'],
		 $BookID
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
   
   public static function delete($BookID, array $data) {
   
      $sql = 'DELETE FROM RecipeBook WHERE BookID = ?';
	  $values = array( 
	     $data['UserID'],
		 $data['Name'],
		 $BookID
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
