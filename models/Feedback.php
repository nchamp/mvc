<<<<<<< HEAD:models/Feedback.php
<?php

require_once(LIBRARY_PATH . DS . 'Database.php');

/**
 * This is the Feedback class.
 *
 * @author Jiang Chen
 */
class Feedback {

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
         $errors['UserID'] = 'You must provide user id.';
		 unset($data['UserID']);
	  }

	  if (!isset($data['RecipeID']) || empty($data['RecipeID'])) {
         $errors['RecipeID'] = 'You must provide recipe id.';
		 unset($data['RecipeID']);
      }

	  if (!isset($data['CommentText']) || empty($data['CommentText'])) {
         $errors['CommentText'] = 'You must provide your comment.';
		 unset($data['CommentText']);
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
    * A method for retrieving feedbacks from the Feedback table. 
	*
	* @param array $data An optional array of key:value pairs to be used as 
	*                    parameters in the SQL query. 
	* @return array An array of database Objects where each Object represents a  
	*               feedback.
	*/
   public static function retrieve(array $data = array()) { 

      $sql = 'SELECT * FROM Feedback';
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
    * Writes a new row to the Feedback table based on given data. 
	*
	* @param array $data The POSTed data.
	* @return int Returns FeedbackID of the inserted row (or throws an Exception)
	*/
   public static function create(array $data) {

	  $sql = 'INSERT INTO Feedback (UserID, RecipeID, CommentText) VALUES (?, ?, ?)';
	  $values = array(  
	     $data['UserID'],
		 $data['RecipeID'],
		 $data['CommentText']
	  );

	  try {
         $database = Database::getInstance();

		 $statement = $database->pdo->prepare($sql);
		 $return = $statement->execute($values);

         if ($return) {
		    $FeedbackID = $database->pdo->lastInsertId();   
		 }
		 $database->pdo = null; 
	  } catch (PDOException $e) {
        echo $e->getMessage();
		exit;   
	  }
	  if ($return) {
         return $FeedbackID; 
      }
	  return false; 
   }

   /**
    * Updates an existing row in the Feedback table based on given data.
	*
	* @param int $FeedbackID The row id of the feedback to update. 
	* @param array $data The POSTed data. 
	* @return int bool Whether update was successful or not.  
	*/
   public static function update($FeedbackID, array $data) {  

	  $sql  = 'UPDATE Feedback SET UserID = ?, RecipeID = ?, CommentText = ? WHERE FeedbackID = ?, DateCreated = ?';
	  $values = array(  
	     $data['UserID'],
		 $data['RecipeID'],
		 $data['CommentText'],
		 $FeedbackID,
		 $DateCreated
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
   
   public static function delete($FeedbackID, array $data) { 
   
      $sql = 'DELETE FROM Feedback WHERE FeedbackID = ?';
	  $values = array( 
	     $data['UserID'],
		 $data['RecipeID'],
		 $data['CommentText'],
		 $FeedbackID,
		 $DateCreated
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