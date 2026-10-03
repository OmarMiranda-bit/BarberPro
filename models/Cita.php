<?php

namespace Model;

class Cita extends ActiveRecord
{
    //Base de datos 
    protected static $tabla = 'citas';
    protected static $columnasDB = ['id', 'fecha', 'hora', 'folio', 'usuarioId', 'empleadoId', 'estado'];

    public $id;
    public $fecha;
    public $hora;
    public $folio;
    public $usuarioId;
    public $empleadoId;
    public $estado;

    //variables virtuales para mostrar en la pagina de detalle de la cita y en el correo de confirmación
    public $cliente;
    public $email;
    public $servicios;
    public $precioTotal;
    public $barberoNombre;
    public $barberoImagen;
    public $serviciosIds;  // ← agregar: "1,3" IDs de los servicios actuales
    public $duracionTotal; // ← agregar: suma de duraciones para calcular slots

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->fecha = $args['fecha'] ?? '';
        $this->hora = $args['hora'] ?? '';
        $this->folio = $args['folio'] ?? '';
        $this->usuarioId = $args['usuarioId'] ?? '';
        $this->empleadoId = $args['empleadoId'] ?? '';
        $this->estado = trim($args['estado'] ?? 'PENDIENTE');
        // inicializar variables virtuales
        $this->cliente = $args['cliente'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->servicios = $args['servicios'] ?? '';
        $this->precioTotal = $args['precioTotal'] ?? '';
        $this->barberoNombre = $args['barberoNombre'] ?? '';
        $this->barberoImagen = $args['barberoImagen'] ?? '';
        $this->serviciosIds = $args['serviciosIds'] ?? '';
        $this->duracionTotal = $args['duracionTotal'] ?? '';
    }

    // public static function obtenerOcupadasPorBarberoYFecha($barberoId, $fecha)
    // {
    //     // Sanitizacion de inputs para evitar SQL Injection
    //     $barberoId = self::$db->escape_string($barberoId);
    //     $fecha     = self::$db->escape_string($fecha);

    //     $query = "
    //     SELECT TIME_FORMAT(c.hora, '%H:%i') AS hora, SUM(s.duracion) AS duracion
    //     FROM citas c
    //     INNER JOIN citasservicios cs ON cs.citaId    = c.id
    //     INNER JOIN servicios s ON s.id = cs.servicioId
    //     WHERE c.empleadoId = '$barberoId'
    //     AND c.fecha = '$fecha'
    //     AND c.estado != 'cancelada'
    //     GROUP BY c.id, c.hora
    //     ORDER BY c.hora ASC
    //     ";

    //     // hace la consulta y devuelve un array de horarios ocupados con su duración total (suma de duración de servicios)
    //     $resultado = self::$db->query($query);

    //     $array = [];
    //     while ($registro = $resultado->fetch_assoc()) {
    //         $array[] = $registro; // array plano, NO objeto
    //     }

    //     $resultado->free();
    //     return $array;

    //     // Devuelve: [
    //     //   ['hora' => '10:00', 'duracion' => '90'],
    //     //   ['hora' => '12:30', 'duracion' => '45'],
    //     // ]
    // }

    public static function obtenerOcupadasPorBarberoYFecha($barberoId, $fecha, $excluirFolio = '')
    {
        // Sanitizacion de inputs para evitar SQL Injection
        $barberoId    = self::$db->escape_string($barberoId);
        $fecha        = self::$db->escape_string($fecha);
        $excluirFolio = self::$db->escape_string($excluirFolio); // <-- Sanitizamos el nuevo parámetro

        // Armamos la base del query
        $query = "
    SELECT TIME_FORMAT(c.hora, '%H:%i') AS hora, SUM(s.duracion) AS duracion
    FROM citas c
    INNER JOIN citasservicios cs ON cs.citaId    = c.id
    INNER JOIN servicios s ON s.id = cs.servicioId
    WHERE c.empleadoId = '$barberoId'
    AND c.fecha = '$fecha'
    AND c.estado != 'cancelada'
    ";

        // 💡 SI VIENE UN FOLIO A EXCLUIR, LO AGREGAMOS AL WHERE antes del GROUP BY
        if (!empty($excluirFolio)) {
            $query .= " AND c.folio != '$excluirFolio' ";
        }

        // Cerramos la estructura del query
        $query .= " GROUP BY c.id, c.hora ORDER BY c.hora ASC ";

        // Hace la consulta y devuelve un array de horarios ocupados
        $resultado = self::$db->query($query);

        $array = [];
        while ($registro = $resultado->fetch_assoc()) {
            $array[] = $registro;
        }

        $resultado->free();
        return $array;
    }

    //folio de la cita para mostrar en el resumen de la cita y en el correo de confirmación
    public function folioCita()
    {
        // Formato: BBR-YYYYMMDD-HHMMSS-RANDOM
        // Ejemplo: BBR-20260112-143052-a5f3c2
        $fecha = date('Ymd');
        $hora = date('His');
        $aleatorio = substr(bin2hex(random_bytes(3)), 0, 6);

        return "BBR-{$fecha}-{$hora}-{$aleatorio}";
    }
}
