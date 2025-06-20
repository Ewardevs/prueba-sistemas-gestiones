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
    public function todos()
    {

        $tareas = $this->model->getAll();

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
            "usuario_id" => "required|int"
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
            "usuario_id" => "required|int"
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
}
