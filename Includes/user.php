<?php

class User
{
    private Database $db;

    private const PASSWORD_ALGO = PASSWORD_ARGON2ID;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function createUser(string $name, string $email, string $password, string $confirm_password): bool
    {
        // Check if the password and confirm password match
        if ($password !== $confirm_password) {
            return false;
        }

        $pdo = $this->db->connect();
        if ($pdo === null) {
            throw new Exception('Database connection failed');
        }

        if ($this->emailExists($pdo, $email)) {
            return false;
        }

        return $this->insertUser($pdo, $name, $email, $password);
    }

    public function authenticateUser(string $email, string $password): ?array
    {

        $user = $this->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return null;
    }


    private function emailExists(PDO $pdo, string $email): bool
    {
        // Prepare and execute the SQL query
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);

        // Check if the query returned a result
        return $stmt->fetch() !== false;
    }

    private function insertUser(PDO $pdo, string $name, string $email, string $password): bool
    {

        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");

        $passwordHash = password_hash($password, self::PASSWORD_ALGO);

        return $stmt->execute(['name' => $name, 'email' => $email, 'password' => $passwordHash]);
    }


    private function getUserByEmail(string $email): ?array
    {

        $pdo = $this->db->connect();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);

        // Fetch the user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return null if no user was found
        return $user === false ? null : $user;
    }
}