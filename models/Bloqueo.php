<?php

namespace Model;

class Bloqueo extends ActiveRecord
{
    protected static $tabla = 'bloqueos_empleados';
    protected static $columnasDB = ['id', 'empleado_id', 'fecha'];

    public $id;
    public $empleado_id;
    public $fecha;
    public $motivo;

    public function __construct($args = [])
    {
        $this->id          = $args['id'] ?? null;
        $this->empleado_id = $args['empleado_id'] ?? '';
        $this->fecha       = $args['fecha'] ?? '';
    }
}