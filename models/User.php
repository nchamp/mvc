<<<<<<< HEAD:models/User.php
<?

require_once(LIBRARY_PATH . DS . 'Database.php');

/**
 * This is the User Class.
 *
 *@author Jiang Chen
 */
Class User {

   /**
    * If validation fails, errors are written to this varible.
	*/
   private static $errors;
   
   /**
    * A method for validating the data
	*/
   public static function validates(array &$data) {
      $errors = array();
	  
	  if (!preg_match("/^[A-Za-Z0-9]+$/i", $data['Nickname'])) {
	     $errors['Nickname'] = 'Your nickname must only be characters and numbers.';
	  }
	  if (!isset($data['Nickname']) || empty($data['Nickname'])) {
         $errors['Nickname'] = 'You must provide your nickname.';
		 unset($data['Nickname']);
      }
	  
	  if (!preg_match("/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/", $data['Email'])) {  
         $errors['Email'] = 'Your email must only be right format, e.g. 123456@gmail.com .';
	  }
	  if (!isset($data['Email']) || empty($data['Email'])) {
         $errors['Email'] = 'You must provide your email.';
		 unset($data['Email']);
      }
	  
	  if (!preg_match("/^[\@A-Za-z0-9\!\#\$\%\^\&\*\.\~]{6,18}$/", $data['Password'])) {
	     $errors['Password'] = 'Password length must be between 6 and 18.';
	  }
	  if (!isset($data['Password']) || empty($data['Password'])) {
         $errors['Password'] = 'You must provide your password.';
		 unset($data['Password']);
      }
	  
	  if (!isset($data['Birthdate']) || empty($data['Birthdate'])) {
         $errors['Birthdate'] = 'You must provide your birthdate.';
		 unset($data['Birthdate']);
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
    * A method for retrieving users from the User table.
	* 
	* @param array $data An optional array of key:value pairs to be used as
	*                    parameters in the SQL query.
	* @return array An array of database Objects where each Object represents a 
	*               user.
	*/
   public static function retrieve(array $data = array()) { 

      $sql = 'SELECT * FROM User';
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
    * Writes a new row to the User table based on given data.
	* 
	* @param array $data The POSTed data. 
	* @return int Returns UserID of the inserted row (or throws an Exception)
	*/
   public static function create(array $data) {

      $sql = 'INSERT INTO User (Email, Nickname, Password, Birthdate, AvatarLink, IsAdmin) VALUES (?, ?, ?, ?, ?, ?)';
	  $values = array(
         $data['Email'],
		 $data['Nickname'],
		 $data['Password'],
		 $data['Birthdate'],
         $data['AvatarLink'],
		 $data['IsAdmin']
	  );
	  try {
         $database = Database::getInstance();
		 
		 $statement = $database->pdo->prepare($sql);
		 $return = $statement->execute($values);

         if ($return) {
		    $UserID = $database->pdo->lastInsertId();
		 }
		 $database->pdo = null;
	  } catch (PDOException $e) {
        echo $e->getMessage();
		exit; 
	  }
	  if ($return) {
         return $UserID;
	  }
  	     return false;
   }
   
   /**
    * Updates an existing row in the User table based on given data. 
	*
	* @param int $UserID The row UserID of the user to update.
	* @param array $data The POSTed data.
	* @return int bool Whether update was successful or not. 
	*/
   public static function update($UserID, array $data) {

	  $sql  = 'UPDATE User SET Email = ?, Nickname = ?, Password = ?, Birthdate = ?, AvatarLink = ?, IsAdmin = ? WHERE UserID = ?';
	  $values = array(
         $data['Email'],
		 $data['Nickname'],
		 $data['Password'],
		 $data['Birthdate'],
         $data['AvatarLink'],
		 $data['IsAdmin'],
		 $UserID
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
   
   public static function delete($UserID, array $data) {
   
      $sql = 'DELETE FROM User WHERE UserID = ?';
	  $values = array(
         $data['Email'],
		 $data['Nickname'],
		 $data['Password'],
		 $data['Birthdate'],
         $data['AvatarLink'],
         $data['IsAdmin'],
 		 $UserID
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