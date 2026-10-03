<?php
namespace Model;

class AdminCita extends ActiveRecord{
    protected static $tabla = 'citasServicios';
    protected static $columnasDB = ['id', 'hora', 'cliente', 'email', 'telefono', 'servicio', 'precio', 'estado', 'clienteId'];

    public $id;
    public $hora;
    public $cliente;
    public $email;
    public $telefono;
    public $servicio;
    public $precio;
    public $estado;
    public $duracion;
    public $clienteId;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->hora = $args['hora'] ?? '';
        $this->cliente = $args['cliente'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->servicio = $args['servicio'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->estado = $args['estado'] ?? '';
        $this->duracion = $args['duracion'] ?? '';
        $this->clienteId = $args['clienteId'] ?? '';
    }
}