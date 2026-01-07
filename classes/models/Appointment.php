<?php
class Appointment {
    protected $id;
    protected $date;
    protected $time;
    protected $doctorId;
    protected $patientId;
    protected $reason;
    protected $status;

    public function __construct($date, $time, $doctorId, $patientId, $reason, $status = 'scheduled', $id = null) {
        $this->id = $id;
        $this->date = $date;
        $this->time = $time;
        $this->doctorId = $doctorId;
        $this->patientId = $patientId;
        $this->reason = $reason;
        $this->status = $status;
    }

    // is appointemnt done 
    public function markAsDone() {
        $this->status = 'done';
    }

    // cancel appointment
    public function cancel() {
        $this->status = 'cancelled';
    }

    // Getters 
    public function getId() { return $this->id; }
    public function getDate() { return $this->date; }
    public function getTime() { return $this->time; }
    public function getDoctorId() { return $this->doctorId; }
    public function getPatientId() { return $this->patientId; }
    public function getReason() { return $this->reason; }
    public function getStatus() { return $this->status; }
}
