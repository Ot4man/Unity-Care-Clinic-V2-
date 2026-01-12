<?php
class Department {
    protected $id;
    protected $name;
    protected $location;

    public function __construct($name, $location, $id = null) {
        $this->id = $id;
        $this->name = $name;
        $this->location = $location;
    }

    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getLocation() { return $this->location; }
}
