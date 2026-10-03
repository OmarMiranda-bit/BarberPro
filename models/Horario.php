<?php

namespace Model;

class Horario extends ActiveRecord
{
    protected static $tabla = 'horarios_empleados';
    protected static $columnasDB = ['id', 'empleado_id', 'dia_semana', 'hora_inicio', 'hora_fin', 'activo'];

    public $id;
    public $empleado_id;
    public $dia_semana;
    public $hora_inicio;
    public $hora_fin;
    public $activo;

    public function __construct($args = [])
    {
        $this->id          = $args['id'] ?? null;
        $this->empleado_id = $args['empleado_id'] ?? '';
        $this->dia_semana   = $args['dia_semana'] ?? '';
        $this->hora_inicio = $args['hora_inicio'] ?? '10:00:00';
        $this->hora_fin    = $args['hora_fin'] ?? '20:00:00';
        $this->activo      = $args['activo'] ?? 1;
    }
}