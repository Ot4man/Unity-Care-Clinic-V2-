<?php

class DepartmentRepository extends BaseRepository {
    public function __construct($pdo) {
        parent::__construct($pdo, 'departments');
    }

    public function save(Department $dept) {
        if ($dept->getId()) {
            $stmt = $this->pdo->prepare("UPDATE departments SET name = ?, location = ? WHERE id = ?");
            return $stmt->execute([$dept->getName(), $dept->getLocation(), $dept->getId()]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO departments (name, location) VALUES (?, ?)");
            return $stmt->execute([$dept->getName(), $dept->getLocation()]);
        }
    }
}
