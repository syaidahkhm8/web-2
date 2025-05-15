<?php
require_once 'Database.php';

class Pegawai {
    private $conn;
    private $table = "pegawai";

    public function __construct(){
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($nip, $nama, $jenis_kelamin, $jabatan){
        $query = "INSERT INTO $this->table (nip, nama, jenis_kelamin, jabatan) VALUES (:nip, :nama, :jenis_kelamin, :jabatan)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nip', $nip);
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':jenis_kelamin', $jenis_kelamin);
        $stmt->bindParam(':jabatan', $jabatan);
        return $stmt->execute();
    }

    public function readAll(){
        $query = "SELECT * FROM $this->table";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function update($id, $nip, $nama, $jenis_kelamin, $jabatan){
        $query = "UPDATE $this->table SET nip=:nip, nama=:nama, jenis_kelamin=:jenis_kelamin, jabatan=:jabatan WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nip', $nip);
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':jenis_kelamin', $jenis_kelamin);
        $stmt->bindParam(':jabatan', $jabatan);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function delete($id){
        $query = "DELETE FROM $this->table WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>