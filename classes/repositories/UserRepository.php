<?php
require_once 'BaseRepository.php';
require_once '../models/User.php';
require_once '../models/Admin.php';
require_once '../models/Doctor.php';
require_once '../models/Patient.php';

class UserRepository extends BaseRepository {
    public function __construct($pdo) {
        parent::__construct($pdo, 'users');
    }

    // Save user (insert or update)
    public function save(User $user) {
        if ($user->getId()) {
            // Update existing user
            $stmt = $this->pdo->prepare("
                UPDATE users SET email = ?, username = ?, password_hash = ?, role = ? WHERE id = ?
            ");
            return $stmt->execute([
                $user->getEmail(),
                $user->getUsername(),
                $user->passwordHash,
                $user->getRole(),
                $user->getId()
            ]);
        } else {
            // Insert new user
            $stmt = $this->pdo->prepare("
                INSERT INTO users (email, username, password_hash, role) VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $user->getEmail(),
                $user->getUsername(),
                $user->passwordHash,
                $user->getRole()
            ]);
            $user->setId($this->pdo->lastInsertId());
            return true;
        }
    }

    // Find user by email
    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) return $this->mapDataToUser($data);
        return null;
    }

    // Find user by ID
    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) return $this->mapDataToUser($data);
        return null;
    }

    // Map DB row to User object
    private function mapDataToUser($data) {
        switch ($data['role']) {
            case 'admin':
                $user = new Admin($data['email'], $data['username'], $data['password_hash']);
                break;
            case 'doctor':
                $user = new Doctor($data['email'], $data['username'], $data['password_hash'], '', '', '', '', null);
                break;
            case 'patient':
                $user = new Patient($data['email'], $data['username'], $data['password_hash'], '', '', '', '2000-01-01', '', '');
                break;
        }
        $user->setId($data['id']);
        return $user;
    }

    // Delete user
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
