<<<<<<< HEAD:models/Cuisine.php
<?php

require_once(LIBRARY_PATH . DS . 'Database.php');

/**
 * This is the Cuisine class. 
 *
 * @author Jiang Chen
 */
 class Cuisine {

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

	  if (!isset($data['CuisineName']) || empty($data['CuisineName'])) {
         $errors['CuisineName'] = 'You must provide your cuisine name.';
	     unset($data['CuisineName']);
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
    * A method for retrieving cuisines from the Cuisine table.
	*
	* @param array $data An optional array of key:value pairs to be used as
	*                    parameters in the SQL query.
	* @return array An array of database Objects where each Object represents a
	*               cuisine.
	*/
   public static function retrieve(array $data = array()) {
   
      $sql = 'SELECT * FROM Cuisine';
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
    * Writes a new row to the Cuisine table based on given data. 
	*
	* @param array $data The POSTed data.
	* @return int Returns CuisineID of the inserted row (or throws an Exception)
	*/
   public static function create(array $data) {

	  $sql  = 'INSERT INTO Cuisine (CuisineName) VALUES (?)';
	  $values = array( 
		 $data['CuisineName']
      );

	  try {
         $database = Database::getInstance(); 

         $statement = $database->pdo->prepare($sql);
		 $return = $statement->execute($values); 

		 if ($return) {
		    $CuisineID = $database->pdo->lastInsertId(); 
		 }
		 $database->pdo = null;
	  } catch (PDOException $e) {
        echo $e->getMessage();
		exit; 
	  }
	  if ($return) {
        return $cuisineID;
	  }
	  return false;
   }

   /**
    * Updates an existing row in the Cuisine table based on given data.
	*
	* @param int $CuisineID The row CuisineID of the cusine to update.
	* @param array $data The POSTed data.
	* @return int bool Whether update was successful or not.
	*/
   public static function update($CuisineID, array $data) {

	  $sql = 'UPDATE Cuisine SET CuisineName = ? WHERE CuisineID = ?';
	  $values = array(
         $data['CuisineName'],
		 $CuisineID
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
   
   public static function delete($CuisineID, array $data) {
      
      $sql = 'DELETE FROM Cuisine WHERE CuisineID = ?';
	  $values = array(
	     $data['CuisineName'],
		 $CuisineID  
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