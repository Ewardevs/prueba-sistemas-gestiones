<?php

namespace Lib;

use App\Models\Tarea;

class Validadores
{
    protected $model;
    public function __construct()
    {
        $this->model = new Tarea();
    }
    public static function validar(array $data, array $reglas)
    {
        $errores = [];

        foreach ($reglas as $campo => $reglasCampo) {
            $existe = array_key_exists($campo, $data);
            $valor = $data[$campo] ?? null;

            foreach (explode('|', $reglasCampo) as $regla) {
                if ($regla === 'required' && (!$existe || $valor === null || $valor === '')) {
                    $errores[] = "El campo '$campo' es obligatorio.";
                }

                if ($regla === 'int' && $valor !== null && filter_var($valor, FILTER_VALIDATE_INT) === false) {
                    $errores[] = "El campo '$campo' debe ser un número entero.";
                }
                if ($regla === 'bool' && $valor !== null && !in_array($valor, [true, false, 1, 0, '1', '0', 'true', 'false'], true)) {
                    $errores[] = "El campo '$campo' debe ser booleano.";
                }

                if ($regla === 'string' && $valor !== null && !is_string($valor)) {
                    $errores[] = "El campo '$campo' debe ser texto.";
                }
                if ($regla === 'email' && $valor !== null && !filter_var($valor, FILTER_VALIDATE_EMAIL)) {
                    $errores[] = "El campo '$campo' debe ser un correo electrónico válido.";
                }
                if ($regla === 'url' && $valor !== null && !filter_var($valor, FILTER_VALIDATE_URL)) {
                    $errores[] = "El campo '$campo' debe ser una URL válida.";
                }
                if ($regla === "exists" && !is_null($valor)) {
                    $tareaModel = new Tarea();

                    // Suponiendo que getUser busca por ID u otro campo
                    if (!$tareaModel->getUser($valor)) {
                        $errores[] = "El valor del campo '$campo' no existe en la base de datos.";
                    }
                }
            }
        }

        return $errores;
    }
}
