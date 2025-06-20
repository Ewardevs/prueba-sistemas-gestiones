<?php

namespace App\Controllers;

use App\Models\Tarea;
use Lib\Validadores;

class TareaController
{

    protected $model;
    public function __construct()
    {
        $this->model = new Tarea();
    }

    public function index()
    {
        return [
            "Welcome <3" => "Bienvenido a la API de tareas",
            "mensaje"=>"Agregar '/tareas' al final de la URL para ver las tareas",
            "status" => 200,
        ];
    }   
    public function todos()
    {

        $tareas = $this->model->getAll();
        foreach ($tareas as &$tarea) {
            $tarea['usuario'] = $this->model->getUser($tarea['usuario_id']);
        }

        return [
            "mensaje" => "Todas las tareas",
            "status" => 200,
            "tareas" => $tareas,
        ];
    }

    public function porId($id)
    {
        $tarea = $this->model->byId($id);
        if (!$tarea) {
            http_response_code(404);
            return ["error" => "Tarea no encontrada", "status" => 404];
        }
        $tarea['usuario'] = $this->model->getUser($tarea['usuario_id']);
        return [
            "mensaje" => "Tarea encontrada",
            "status" => 200,
            "tareas" => $tarea,
        ];
    }

    public function crear($data)
    {
        $reglas = [
            "titulo" => "required|string",
            "descripcion" => "required|string",
            "estado" => "required|bool",
            "usuario_id" => "required|exists|int"
        ];

        $errores = Validadores::validar($data, $reglas);

        if (!empty($errores)) {
            http_response_code(422);
            return ["errores" => $errores];
        }
        $id = $this->model->create([
            "titulo" => $data["titulo"],
            "descripcion" => $data["descripcion"],
            "estado" => $data["estado"],
            "usuario_id" => $data["usuario_id"]
        ]);
        
        if ($id) {

            $tarea = $this->model->byId($id);
            $tarea['usuario'] = $this->model->getUser($tarea['usuario_id']);
            http_response_code(201);
            return [
                "mensaje" => "Tarea creada con éxito",
                "status" => 201,
                "tareas" => $tarea,
            ];
        }
        return "Error al crear la tarea";
    }

    public function actualizar($id, $data)
    {
        $tarea = $this->model->byId($id);
        if (!$tarea) {
            http_response_code(404);
            return ["error" => "Tarea no encontrada", "status" => 404];
        }
        $reglas = [
            "titulo" => "required|string",
            "descripcion" => "required|string",
            "estado" => "required|bool",
            "usuario_id" => "required|exists|int"
        ];

        $errores = Validadores::validar($data, $reglas);

        if (!empty($errores)) {
            http_response_code(400);
            return ["errores" => $errores];
        }

        $updated = $this->model->update($id, [
            "titulo" => $data["titulo"],
            "descripcion" => $data["descripcion"],
            "estado" => $data["estado"],
            "usuario_id" => $data["usuario_id"]
        ]);
        
        if ($updated) {
            $tarea = $this->model->byId($id);
            $tarea['usuario'] = $this->model->getUser($tarea['usuario_id']);
            return ["mensaje" => "Tarea actualizada con éxito", "tarea" => $tarea];
        }
        http_response_code(404);
        return ["error" => "Tarea no encontrada o no actualizada", "status" => 404];
    }

    public function eliminar($id)
    {
        $tarea = $this->model->byId($id);

        if (!$tarea) {
            http_response_code(404);
            return ["error" => "Tarea no encontrada", "status" => 404];
        }
        
        $deleted = $this->model->delete($id);

        if ($deleted) {

            return [
                "mensaje" => "Tarea eliminada con éxito",
                "status" => 200,
                "tareas" => $tarea,
            ];
        }
    }
    public function usuario($id)
    {
        $usuario = $this->model->getUser($id);
        if (!$usuario) {
            http_response_code(404);
            return ["error" => "Usuario no encontrado", "status" => 404];
        }
        return [
            "mensaje" => "Usuario encontrado",
            "status" => 200,
            "usuario" => $usuario,
        ];
    }
    public function todosUsuarios()
    {
        $usuarios = $this->model->getAllUsers();
        if (!$usuarios) {
            http_response_code(404);
            return ["error" => "No se encontraron usuarios", "status" => 404];
        }
        return [
            "mensaje" => "Todos los usuarios",
            "status" => 200,
            "usuarios" => $usuarios,
        ];
    }
}
