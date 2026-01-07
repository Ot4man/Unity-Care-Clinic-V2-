<?php
class Medication {
    protected $id;
    protected $name;
    protected $instructions;

    public function __construct($name, $instructions, $id = null) {
        $this->id = $id;
        $this->name = $name;
        $this->instructions = $instructions;
    }

    // Getters 
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getInstructions() { return $this->instructions; }
}
