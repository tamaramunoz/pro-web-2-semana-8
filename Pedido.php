<?php
class Pedido {
  public $descripcion;
  public $tipo_pedido;
  public $producto;
  public $unidades;
  public $observaciones;

  // método para inicializar las variables al instanciar el objeto
  public function __construct($descripcion, $tipo_pedido, $producto, $unidades, $observaciones) {
    $this->descripcion = $descripcion;
    $this->tipo_pedido = $tipo_pedido;
    $this->producto = $producto;
    $this->unidades = (int)$unidades;
    $this->observaciones = $observaciones;
  }

  // método para simplificar la renderización de datos
  public function obtenerResumen() {
    return "Pedido de {$this->unidades} unidad(es) de '{$this->producto}' bajo la modalidad: [{$this->tipo_pedido}].";
  }

  // método de búsqueda personalizada
  public function coincideConBusqueda($termino) {
    $terminoLimpio = strtolower(trim($termino));
    if (strpos(strtolower($this->producto), $terminoLimpio) !== false || 
      strpos(strtolower($this->descripcion), $terminoLimpio) !== false ||
      strpos(strtolower($this->tipo_pedido), $terminoLimpio) !== false) {
      return true;
    }
    return false;
  }
}
?>
