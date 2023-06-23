<?php

namespace Repositories;

use PDO;
use PDOException;
use Repositories\Repository;
use Models\User;
use Models\Role;

class UserRepository extends Repository
{
    function getAll($offset = NULL, $limit = NULL)
    {
        try {
            $query = "SELECT * FROM user";
            if (isset($limit) && isset($offset)) {
                $query .= " LIMIT :limit OFFSET :offset ";
            }
            $stmt = $this->connection->prepare($query);
            if (isset($limit) && isset($offset)) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            }
            $stmt->execute();

            $users = [];

            while ($row = $stmt->fetch()) {
                $user = new User();
                $user->setId($row['id']);
                $user->setEmail($row['email']);
                $user->setPassword($row['password']);
                $user->setRole($row['role']);

                $users[] = $user;
            }

            return $users;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function getById($id)
    {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM user WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $user = null;
            
            while ($row = $stmt->fetch()) {
                $user = new User();
                $user->setId($row['id']);
                $user->setEmail($row['email']);
                $user->setPassword($row['password']);
                $user->setRole($row['role']);
            }
            return $user;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function getByEmail(string $email) {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM user WHERE email = :email");

            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            $user = null;

            while ($row = $stmt->fetch()) {
                $user = new User();
                $user->setId($row['id']);
                $user->setEmail($row['email']);
                $user->setPassword($row['password']);
                $user->setRole($row['role']);
            }

            return $user;

        } catch (PDOException $e) {
            echo $e;
        }
    }

    function create(User $user) {
        try {
            $stmt = $this->connection->prepare("INSERT INTO user (email, password, role) 
            VALUES (:email, :password, :role)");

            $stmt->bindValue(':email', $user->getEmail(), PDO::PARAM_STR);
            $stmt->bindValue(':password', $this->hashPassword($user->getPassword()), PDO::PARAM_STR);
            $stmt->bindValue(':role', $user->getRole()->toInt(), PDO::PARAM_INT);

            $stmt->execute();

            return $user;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function update(User $user, $id) {
        try {
            $stmt = $this->connection->prepare("UPDATE user 
            SET email = :email, password = :password, role = :role 
            WHERE id = :id");

            $stmt->bindValue(':email', $user->getEmail(), PDO::PARAM_STR);
            $stmt->bindValue(':password', $this->hashPassword($user->getPassword()), PDO::PARAM_STR);
            $stmt->bindValue(':role', $user->getRole()->toInt(), PDO::PARAM_INT);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            $stmt->execute();

            return $user;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function delete($id):bool {
        try {
            $stmt = $this->connection->prepare("DELETE FROM user 
            WHERE id = :id");

            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e;
            return false;
        }
    }

    function checkEmailPassword($email, $password)
    {
        try {
            // retrieve the user with the given username
            $stmt = $this->connection->prepare("SELECT id, email, password, role FROM user WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $user = null;

            while ($row = $stmt->fetch()) {
                $user = new User();
                $user->setId($row['id']);
                $user->setEmail($row['email']);
                $user->setPassword($row['password']);
                $user->setRole($row['role']);
            }

            if (!$user) {
                return false;
            }

            // verify if the password matches the hash in the database
            $result = $this->verifyPassword($password, $user->getPassword());

            if (!$result)
                return false;

            $user->setPassword("");

            return $user;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    function verifyPassword($input, $hash)
    {
        return password_verify($input, $hash);
    }
}
