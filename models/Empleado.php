<?php

namespace Model;

class Empleado extends ActiveRecord
{

    protected static $tabla = 'empleados';
    protected static $columnasDB = ['id', 'usuario_id', 'puesto', 'imagen', 'redes', 'fecha_ingreso', 'activo'];

    public $id;
    public $usuario_id;
    public $puesto;
    public $imagen;
    public $redes;
    public $fecha_ingreso;
    public $activo;
    public $nombre;
    public $apellido;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->usuario_id = $args['usuario_id'] ?? '';
        $this->puesto = $args['puesto'] ?? 'Barbero';
        $this->imagen = $args['imagen'] ?? '';
        $this->redes = $args['redes'] ?? '';
        $this->fecha_ingreso = $args['fecha_ingreso'] ?? date('Y-m-d');
        $this->activo = $args['activo'] ?? 1;
    }

    // Obtener el usuario relacionado a este empleado
    public function obtenerUsuario()
    {
        return Usuario::find($this->usuario_id);
    }

    // Calcular la antigüedad del empleado
    public function calcularAntiguedad()
    {
        $fecha_ingreso = new \DateTime($this->fecha_ingreso);
        $fecha_actual = new \DateTime();

        $intervalo = $fecha_ingreso->diff($fecha_actual);

        $años = $intervalo->y;
        $meses = $intervalo->m;
        $dias = $intervalo->d;
        // Devolver formato legible
        $resultado = '';

        if ($años > 0) {
            $resultado .= $años . ' ' . ($años == 1 ? 'año' : 'años');
        }

        if ($meses > 0) {
            if ($resultado) $resultado .= ', ';
            $resultado .= $meses . ' ' . ($meses == 1 ? 'mes' : 'meses');
        }

        if ($dias > 0) {
            if ($resultado) $resultado .= ', ';
            $resultado .= $dias . ' ' . ($dias == 1 ? 'día' : 'días');
        }

        // Si es menor a un día
        if (!$resultado) {
            $resultado = 'Menos de 1 día';
        }

        return $resultado;
    }

    // Obtener el estado en texto
    public function obtenerEstado()
    {
        return $this->activo == 1 ? 'Activo' : 'Inactivo';
    }
}
