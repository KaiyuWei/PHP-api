<?php

namespace App\Models;

use App\Database;
use App\QueryFilters\GeneralQueryFilter;
use App\QuerySorters\GeneralQuerySorter;
use PDO;

class User extends Model
{
    public function __construct() {
        parent::__construct();
    }

    protected function initializeFilterAndSorter(): void
    {
        $this->filter = new GeneralQueryFilter();
        $this->sorter = new GeneralQuerySorter();
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM users";
        $result = $this->db->query($sql)->fetchAll();
        return $result ?? [];
    }

    public function getById(int $id, array $queryFields = [])
    {
        $queryFields = $this->convertQueryFieldsToString($queryFields);

        $sql = "SELECT " . $queryFields . " FROM users WHERE id = :id";
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

    public function getByToken(string $token, array $queryFields = [])
    {
        $queryFields = $this->convertQueryFieldsToString($queryFields);

        $sql = "SELECT " . $queryFields . " FROM users WHERE token = :token";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['token' => $token]);
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

    public function getRole(int $id): string
    {
        $sql = "SELECT role FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
