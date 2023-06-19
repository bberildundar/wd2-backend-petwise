<?php

namespace Repositories;

use PDO;
use PDOException;
use Repositories\Repository;
use Models\Vet;

class VetRepository extends Repository
{
    function getAll($offset = NULL, $limit = NULL)
    {
        try {
            $query = "SELECT * FROM vet";
            if (isset($limit) && isset($offset)) {
                $query .= " LIMIT :limit OFFSET :offset ";
            }
            $stmt = $this->connection->prepare($query);
            if (isset($limit) && isset($offset)) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            }
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Vet');
            $vets = $stmt->fetchAll();

            return $vets;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function getById($id)
    {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM vet WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Vet');
            $product = $stmt->fetch();

            return $product;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function create(Vet $vet) {
        try {
            $stmt = $this->connection->prepare("INSERT INTO vet (firstName, lastName, specialization, imageURL) 
            VALUES (:firstName, :lastName, :specialization, :imageURL)");

            $stmt->bindValue(':firstName', $vet->getFirstName(), PDO::PARAM_STR);
            $stmt->bindValue(':lastName', $vet->getLastName(), PDO::PARAM_STR);
            $stmt->bindValue(':specialization', $vet->getSpecialization(), PDO::PARAM_STR);
            $stmt->bindValue(':imageURL', $vet->getImageURL(), PDO::PARAM_STR);

            $stmt->execute();

            return $vet;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function update(Vet $vet, $id) {
        try {
            $stmt = $this->connection->prepare("UPDATE vet 
            SET firstName = :firstName, lastName = :lastName, specialization = :specialization, imageURL = :imageURL 
            WHERE id = :id");

            $stmt->bindValue(':firstName', $vet->getFirstName(), PDO::PARAM_STR);
            $stmt->bindValue(':lastName', $vet->getLastName(), PDO::PARAM_STR);
            $stmt->bindValue(':specialization', $vet->getSpecialization(), PDO::PARAM_STR);
            $stmt->bindValue(':imageURL', $vet->getImageURL(), PDO::PARAM_STR);

            $stmt->execute();

            return $vet;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function delete($id):bool {
        try {
            $stmt = $this->connection->prepare("DELETE FROM vet 
            WHERE id = :id");

            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo $e;
            return false;
        }
    }
}