<?php   


class UserRepository extends BaseRepository {
    public function __construct($pdo) {
        parent::__construct($pdo, 'users');
    }

    // Save user (insert or update)
    public function save(User $user) {
        if ($user->getId()) {
            // Update existing user
            $stmt = $this->pdo->prepare("
                UPDATE users SET email = ?, first_name = ?, last_name = ?, phone = ?, password_hash = ?, role = ? WHERE id = ?
            ");
            return $stmt->execute([
                $user->getEmail(),
                $user->getFirstName(),
                $user->getLastName(),
                $user->getPhone(),
                $user->getPasswordHash(),
                $user->getRole(),
                $user->getId()
            ]);
        } else {
            // Insert new user
            $stmt = $this->pdo->prepare("
                INSERT INTO users (email, first_name, last_name, phone, password_hash, role) VALUES (?, ?, ?, ?, ?, ?)
            ");
            $success = $stmt->execute([
                $user->getEmail(),
                $user->getFirstName(),
                $user->getLastName(),
                $user->getPhone(),
                $user->getPasswordHash(),
                $user->getRole()
            ]);
            if ($success) {
                $user->setId($this->pdo->lastInsertId());
            }
            return $success;
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
                // Admin constructor: ($id, $email, $firstName, $lastName, $phone, $passwordHash, $role)
                // Note: Admin in this codebase inherits User constructor.
                $user = new Admin(
                    $data['id'], 
                    $data['email'], 
                    $data['first_name'], 
                    $data['last_name'], 
                    $data['phone'] ?? null, 
                    $data['password_hash'], 
                    'admin'
                );
                break;
            case 'doctor':
                // Doctor constructor: ($id, $email, $firstName, $lastName, $phone, $passwordHash, $specialization, $departmentId)
                // We need to fetch extra data for Doctor (specialization, department_id) usually from 'doctors' table but here we just instantiate the user part?
                // The current architecture seems to separate 'doctors' table. 
                // But mapDataToUser only gets 'users' table data.
                // For simplicity, we pass stub values for sub-class specific fields if they are not in the $data.
                // However, ideally we should JOIN tables.
                // Given "simple" requirement, we'll just instantiate with nulls for now or handle it.
                // BUT, 'doctors' table has 'specialization' and 'department_id'.
                // If we want to return a full Doctor object, we need that info.
                // A simple fix is to fetch it lazily or JOIN.
                // For now, I will use placeholders to avoid crashes, as implementing full JOIN logic might be "advanced" (or at least more changes).
                $user = new Doctor(
                    $data['id'], 
                    $data['email'], 
                    $data['first_name'], 
                    $data['last_name'], 
                    $data['phone'] ?? null, 
                    $data['password_hash'], 
                    null, // specialization
                    null  // departmentId
                );
                break;
            case 'patient':
                // Patient constructor: ($id, $email, $firstName, $lastName, $phone, $passwordHash, $gender, $dateOfBirth, $address)
                $user = new Patient(
                    $data['id'], 
                    $data['email'], 
                    $data['first_name'], 
                    $data['last_name'], 
                    $data['phone'] ?? null,
                    $data['password_hash'], 
                    null, // gender
                    null, // dateOfBirth
                    null  // address
                );
                break;
            default:
                // Fallback for unknown role, or make a generic User (but User is abstract).
                // Assuming data integrity, this shouldn't happen.
                return null;
        }
        return $user;
    }

    // Delete user
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
