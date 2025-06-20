<?php

namespace App\Models;

class Tarea
{
    protected $db_host = "localhost";
    protected $db_user = "postgres";
    protected $db_pass = "023148_Er";
    protected $db_name = "postgres";

    protected $connection;

    protected $table = "tareas";

    public function __construct()
    {
        $this->connection = $this->connection();
    }

    public function connection()
    {
        try {
            $dsn = "pgsql:host={$this->db_host};dbname={$this->db_name}";
            $this->connection = new \PDO($dsn, $this->db_user, $this->db_pass);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $this->connection;
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function getAll()
    {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function byId($id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC); // ✅ Esto devuelve la fila como array asociativo
    }

    public function create($data)
    {

        $stmt = $this->connection->prepare("INSERT INTO {$this->table} (titulo, descripcion, estado,usuario_id) VALUES (:titulo, :descripcion, :estado, :usuario_id)");

        $stmt->bindParam(':titulo', $data['titulo']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':estado', $data['estado']);
        $stmt->bindParam(':usuario_id', $data['usuario_id']);

        if ($stmt->execute()) {
            // Devuelve el ID insertado
            return $this->connection->lastInsertId();
        }

        return false;
    }
    public function update($id, $data)
    {
        $stmt = $this->connection->prepare("UPDATE {$this->table} SET titulo = :titulo, descripcion = :descripcion, estado = :estado, usuario_id = :usuario_id WHERE id = :id");

        $stmt->bindParam(':titulo', $data['titulo']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':estado', $data['estado']);
        $stmt->bindParam(':usuario_id', $data['usuario_id']);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);

        return $stmt->execute();
    }
    public function delete($id)
    {
        $stmt = $this->connection->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getUser($id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC); // ✅ Esto devuelve la fila como array asociativo
    }

    public function getAllUsers()
    {
        $stmt = $this->connection->prepare("SELECT * FROM usuarios");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
}
