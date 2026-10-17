<?php

namespace Model;

class CitaServicio extends ActiveRecord
{
    protected static $tabla = 'citasservicios';
    protected static $columnasDB = ['id', 'citaId', 'servicioId'];

    public $id;
    public $citaId;
    public $servicioId;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->citaId = $args['citaId'] ?? '';
        $this->servicioId = $args['servicioId'] ?? '';
    }

    public static function eliminarPorCitaId($citaId)
    {
        $idSanitizado = self::$db->escape_string($citaId);
        $query = "DELETE FROM " . static::$tabla . " WHERE citaId = '{$idSanitizado}'";
        return self::$db->query($query);
    }
}
