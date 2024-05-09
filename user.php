<?php

require __DIR__."/mySqlConnection.php";

class User {
    private $pdo;

    public function __construct() {
        $connection = mySqlConnection::getInstance();
        $this->pdo = $connection->getConnection();
    }

    public function getUser($email) {
        $statement = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $statement->execute(['email' => $email]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return json_encode([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        return json_encode([
            'success' => true,
            'user' => $user
        ]);
    }

    public function createUser($userData) {
        $columns = implode(", ", array_keys($userData));
        $values = ":" . implode(", :", array_keys($userData));

        $statement = $this->pdo->prepare("INSERT INTO users ($columns) VALUES ($values)");
        $success = $statement->execute($userData);

        if ($success) {
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
        $columnsToUpdate = "";
        foreach ($newUserData as $key => $value) {
            $columnsToUpdate .= "$key = :$key, ";
        }
        $columnsToUpdate = rtrim($columnsToUpdate, ", ");

        $statement = $this->pdo->prepare("UPDATE users SET $columnsToUpdate WHERE email = :email");
        $newUserData['email'] = $email;
        $success = $statement->execute($newUserData);

        if ($success) {
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
        $statement = $this->pdo->prepare("DELETE FROM users WHERE email = :email");
        $success = $statement->execute(['email' => $email]);

        if ($success) {
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
        $statement = $this->pdo->query("SELECT * FROM users");
        $users = $statement->fetchAll(PDO::FETCH_ASSOC);

        return json_encode([
            'success' => true,
            'users' => $users
        ]);
    }
}
