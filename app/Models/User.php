<?php

namespace App\Models;

use App\Database;
use PDO;

class User extends Model
{
    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM users";
        $result = $this->db->query($sql)->fetchAll();
        return $result ?? [];
    }

    public function getById(int $id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByEmail(string $email, array $queryFields = [])
    {
        $queryFields = $this->convertQueryFieldsToString($queryFields);

        $sql = "SELECT " . $queryFields . " FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function updateToken(int $id, string $token): bool
    {
        $sql = "UPDATE users SET token = :token WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['token' => $token, 'id' => $id]);
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'role' => $data['role'],
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
