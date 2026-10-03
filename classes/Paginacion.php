<?php
namespace Classes;

class Paginacion{
    public $pagina_actual;
    public $registros_por_pagina;
    public $total_registros;
    public $parametros; // Arreglo opcional para filtros

    public function __construct($pagina_actual = 1, $registros_por_pagina = 10, $total_registros = 0, $parametros = [])
    {
        $this->pagina_actual = (int) $pagina_actual; //castear: si llega un string lo pasa a un entero
        $this->registros_por_pagina = (int) $registros_por_pagina;
        $this->total_registros = (int) $total_registros;
        $this->parametros = $parametros; // Asignar parámetros si existen

    }

    // Genera la URL sin afectar a otros controladores
    private function generarUrl($pagina) {
        $params = $this->parametros; 
        $params['page'] = $pagina;
        return '?' . http_build_query($params);
    }
//calcula de 10 en 10 lo que se muestra en la paginacion
    public function offset(){
        return $this->registros_por_pagina * ($this->pagina_actual - 1);
    }
//calcula el total de la paginas y lo divide entre 10 redondeando hacia arriba 
    public function total_paginas(){
        return ceil($this->total_registros / $this->registros_por_pagina); //ceil redondea hacia arriba
    }
//Para regresar a la pagina anterior pero no a la 0 ni a -1
    public function pagina_anterior(){
        $anterior = $this->pagina_actual -1;
        return ($anterior > 0) ? $anterior : false; //si es mayor a 0 regresa el numero, si no regresa false
    }
//Para avanzar a la siguiente pagina pero no mas alla del total de paginas
    public function pagina_siguiente(){
        $siguiente = $this->pagina_actual +1;
        return ($siguiente <= $this->total_paginas()) ? $siguiente : false; //si es menor o igual a total de paginas regresa el numero, si no regresa false
    }
//Genera el enlace de la pagina anterior codigo HTML
    public function enlace_anterior(){
        $html = '';
        if($this->pagina_anterior()){
            $html .= "<a class='paginacion__enlace paginacion__enlace--texto' href='?page=" . $this->pagina_anterior() . "'>&laquo; Anterior</a>";
        }
        return $html;
    }
//Genera el enlace de la pagina siguiente codigo HTML
    public function enlace_siguiente() {
            $html = '';
 
            if($this->pagina_siguiente()) {
                $html .= "<a class='paginacion__enlace paginacion__enlace--texto' href='?page=" . $this->pagina_siguiente() . "'>Siguiente &raquo;</a>";
            }
            return $html;
        }
    //Genera la paginacion con numeros 
    public function nunmeros_pagina(){
        $html = '';
        for($i = 1; $i <= $this->total_paginas(); $i++){
            if($i === $this->pagina_actual){
                $html .= "<span class='paginacion__enlace paginacion__enlace--actual'>{$i}</span>";
            }else{
                $html .= "<a class='paginacion__enlace paginacion__enlace--numero' href='?page={$i}'>{$i}</a>";
            }
        }
        return $html;
    }

    public function paginacion(){
        $html = '';
        if($this->total_registros > 1){
            $html .= '<div class="paginacion">';
            $html .= $this->enlace_anterior();
            $html .= $this->nunmeros_pagina();
            $html .= $this->enlace_siguiente();
            $html .= '</div>';
        }
        return $html;
    }
}