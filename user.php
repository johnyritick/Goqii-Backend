<?php

require __DIR__."/MongoDBConnection.php";
use Api\MongoDBConnection;

class User {
  public  function getUser($email){
    $mongo = MongoDBConnection::getInstance();
  
    $user = $mongo->connect()->home->users;
    $result = $user->findOne([
      'email' => $email
    ]);

    if(empty($result)){
      return json_encode([
        'success' => false,
        "message" => "user not found"
      ]);
    }
    $result = json_decode(json_encode($result),true);
    $result = [
      'success' => true,
      'user' => $result
    ];
    return json_encode($result);
  }

   
  public function createUser($userData) {
    $mongo = MongoDBConnection::getInstance();
  
    $userCollection = $mongo->connect()->home->users;
    $insertResult = $userCollection->insertOne($userData);
  
    if(method_exists($insertResult,'getInsertedCount') && $insertResult->getInsertedCount() === 1) {
      return json_encode([
        'success' => true,
        'message' => 'User created successfully'
      ]);
    } else {
      return json_encode([
        'success' => false,
        'message' => 'Failed to create user'
      ]);
    }
  }

  public function updateUser($email, $newUserData) {
    $mongo = MongoDBConnection::getInstance();
  
    $userCollection = $mongo->connect()->home->users;
    $updateResult = $userCollection->updateOne(
      ['email' => $email],
      ['$set' => $newUserData]
    );
    
    if(method_exists($updateResult,'getModifiedCount') && $updateResult->getModifiedCount() === 1) {
      return json_encode([
        'success' => true,
        'message' => 'User updated successfully'
      ]);
    } else {
      return json_encode([
        'success' => false,
        'message' => 'Failed to update user'
      ]);
    }
  }

  public function deleteUser($email) {
    $mongo = MongoDBConnection::getInstance();
  
    $userCollection = $mongo->connect()->home->users;
    $deleteResult = $userCollection->deleteOne(['email' => $email]);
    
    if(method_exists($deleteResult,'getDeletedCount') && $deleteResult->getDeletedCount() === 1) {
      return json_encode([
        'success' => true,
        'message' => 'User deleted successfully'
      ]);
    } else {
      return json_encode([
        'success' => false,
        'message' => 'Failed to delete user'
      ]);
    }
  }
  public function getAllUsers() {
    $mongo = MongoDBConnection::getInstance();
  
    $userCollection = $mongo->connect()->home->users;
    $cursor = $userCollection->find();
    
    $users = [];
    foreach ($cursor as $document) {
      $users[] = json_decode(json_encode($document), true);
    }

    return json_encode([
      'success' => true,
      'users' => $users
    ]);
  }
}
