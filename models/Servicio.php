<?php
namespace Model;

class Servicio extends ActiveRecord{
    protected static $tabla = 'servicios';
    protected static $columnasDB = ['id', 'nombre', 'precio', 'imagen', 'descripcion', 'duracion'];

    public $id;
    public $nombre;
    public $precio;
    public $imagen;
    public $descripcion;
    public $duracion;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->imagen = $args['imagen'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->duracion = $args['duracion'] ?? null;

    }

    public function validar() {
        self::$alertas = [];

        if(!$this->nombre) {
            self::$alertas['error'][] = 'El nombre del servicio es obligatorio';
        }
        if(!is_numeric($this->precio)) {
            self::$alertas['error'][] = 'El precio del servicio no es valido';
        }
        if(!$this->descripcion) {
            self::$alertas['error'][] = 'La descripción del servicio es obligatoria';
        }
        if(!is_numeric($this->duracion) || (int)$this->duracion <= 0) {
            self::$alertas['error'][] = 'La duración del servicio es obligatoria';
        }
        return self::$alertas;
    }
}