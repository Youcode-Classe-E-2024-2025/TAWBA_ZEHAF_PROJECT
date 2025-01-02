<?php

namespace App\Models;

use App\Helpers\SecurityHelper;

class User {
    private int $id;
    private string $name;
    private string $email;
    private string $password;
    private int $roleId;
    private bool $isVerified;
    private ?string $verificationToken;
    private ?string $passwordResetToken;
    private ?string $passwordResetExpires;

    public function __construct(string $name, string $email, string $password, int $roleId) {
        $this->setName($name);
        $this->setEmail($email);
        $this->setPassword($password);
        $this->setRoleId($roleId);
        $this->isVerified = false;
        $this->verificationToken = bin2hex(random_bytes(16));
    }

    public function setName(string $name): void {
        if (strlen($name) < 2 || strlen($name) > 100) {
            throw new \InvalidArgumentException("Le nom doit contenir entre 2 et 100 caractères.");
        }
        $this->name = $name;
    }

    public function setEmail(string $email): void {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("L'adresse e-mail n'est pas valide.");
        }
        $this->email = $email;
    }

    public function setPassword(string $password): void {
        if (strlen($password) < 8) {
            throw new \InvalidArgumentException("Le mot de passe doit contenir au moins 8 caractères.");
        }
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function setRoleId(int $roleId): void {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT id FROM roles WHERE id = :id");
        $stmt->execute(['id' => $roleId]);
        if (!$stmt->fetch()) {
            throw new \InvalidArgumentException("Le rôle spécifié n'existe pas.");
        }
        $this->roleId = $roleId;
    }

    public function save(): void {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO users (name, email, password, role_id, is_verified, verification_token) VALUES (:name, :email, :password, :role_id, :is_verified, :verification_token)");
        $stmt->execute([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'role_id' => $this->roleId,
            'is_verified' => $this->isVerified,
            'verification_token' => $this->verificationToken
        ]);
        $this->id = $db->lastInsertId();
    }

    public static function findByEmail(string $email) {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public static function findByEmailWithRole(string $email) {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("
        SELECT u.*, r.name as role_name 
        FROM users u 
        JOIN roles r ON u.role_id = r.id 
        WHERE u.email = :email
    ");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public static function isAdmin(int $userId): bool {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("
        SELECT r.name as role_name 
        FROM users u 
        JOIN roles r ON u.role_id = r.id 
        WHERE u.id = :id
    ");
        $stmt->execute(['id' => $userId]);
        $result = $stmt->fetch();
        return $result && $result['role_name'] === 'admin';
    }

    public static function verifyEmail(string $token): bool {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE users SET is_verified = 1, verification_token = NULL WHERE verification_token = :token");
        $stmt->execute(['token' => $token]);
        return $stmt->rowCount() > 0;
    }

    public static function requestPasswordReset(string $email): ?string {
        $user = self::findByEmail($email);
        if (!$user) {
            return null;
        }

        $token = bin2hex(random_bytes(16));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE users SET password_reset_token = :token, password_reset_expires = :expires WHERE id = :id");
        $stmt->execute([
            'token' => $token,
            'expires' => $expires,
            'id' => $user['id']
        ]);

        return $token;
    }

    public static function resetPassword(string $token, string $newPassword): bool {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE password_reset_token = :token AND password_reset_expires > NOW()");
        $stmt->execute(['token' => $token]);
        $user = $stmt->fetch();

        if (!$user) {
            return false;
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE users SET password = :password, password_reset_token = NULL, password_reset_expires = NULL WHERE id = :id");
        $stmt->execute([
            'password' => $hashedPassword,
            'id' => $user['id']
        ]);

        return true;
    }
}