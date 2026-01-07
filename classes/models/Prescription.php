<?php
class Prescription {
    protected $id;
    protected $date;
    protected $doctorId;
    protected $patientId;
    protected $medicationId;
    protected $dosageInstructions;

    public function __construct($date, $doctorId, $patientId, $medicationId, $dosageInstructions, $id = null) {
        $this->id = $id;
        $this->date = $date;
        $this->doctorId = $doctorId;
        $this->patientId = $patientId;
        $this->medicationId = $medicationId;
        $this->dosageInstructions = $dosageInstructions;
    }

    // prescription detailes
    public function getSummary() {
        return "prescription of patients : #{$this->patientId}:\n take this medications  #{$this->medicationId}  with dose of '{$this->dosageInstructions}'";
    }

    // Getters 
    public function getId() { return $this->id; }
    public function getDate() { return $this->date; }
    public function getDoctorId() { return $this->doctorId; }
    public function getPatientId() { return $this->patientId; }
    public function getMedicationId() { return $this->medicationId; }
    public function getDosageInstructions() { return $this->dosageInstructions; }
}
