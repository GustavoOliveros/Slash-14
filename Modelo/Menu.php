<?php
class Menu extends BaseDatos{
    private $id;
    private $nombre;
    private $descripcion;
    private $objMenuPadre; // Si no tiene padre, el obj tiene id -1
    private $deshabilitado;
    private $mensajeOperacion;
    
    /////////////////////////////
    // CONSTRUCTOR //
    /////////////////////////////

    /**
     * Método constructor
     */
    public function __construct()
    {
        $objMenu = new Menu();
        $objMenu->setId(-1); 

        parent::__construct();
        $this->id = -1;
        $this->nombre = "";
        $this->descripcion = "";
        $this->objMenuPadre = $objMenu;
        $this->deshabilitado = "";
    }

    /////////////////////////////
    // SET Y GET //
    /////////////////////////////

    /**
     * Carga datos al obj
     * @param int $id
     * @param string $nombre
     * @param string $descripcion
     * @param Menu $objMenuPadre
     * @param string $deshabilitado
     */
    public function cargar($id, $nombre, $descripcion, $objMenuPadre, $deshabilitado){
        $this->setId($id);
        $this->setNombre($nombre);
        $this->setDescripcion($descripcion);
        $this->setObjMenuPadre($objMenuPadre);
        $this->setDeshabilitado($deshabilitado);
    }

    public function getId(){
        return $this->id;
    }
    public function setId($id){
        $this->id = $id;
    }
    public function getNombre(){
        return $this->nombre;
    }
    public function setNombre($nombre){
        $this->nombre = $nombre;
    }
    public function getDescripcion(){
        return $this->descripcion;
    }
    public function setDescripcion($descripcion){
        $this->descripcion = $descripcion;
    }
    public function getObjMenuPadre(){
        return $this->objMenuPadre;
    }
    public function setObjMenuPadre($objMenuPadre){
        $this->objMenuPadre = $objMenuPadre;
    }
    public function getDeshabilitado(){
        return $this->deshabilitado;
    }
    public function setDeshabilitado($deshabilitado){
        $this->deshabilitado = $deshabilitado;
    }
    public function getMensajeOperacion(){
        return $this->mensajeOperacion;
    }
    public function setMensajeOperacion($mensajeOperacion){
        $this->mensajeOperacion = $mensajeOperacion;
    }

    /////////////////////////////
    // INTERACCION CON LA DB //
    /////////////////////////////

    /**
     * Busca en la db por clave primaria
     * @param int $id
     */
    public function buscar($id){
        $encontro = false;
        $consulta = "SELECT * FROM menu WHERE idmenu = '" . $id . "'";

        if($this->Iniciar()){
            if($this->Ejecutar($consulta)){
                if($fila = $this->Registro()){
                    $objMenu = $this->getObjMenuPadre();
                    if($fila["idpadre"] != -1){
                        $objMenu->buscar($fila["idpadre"]);
                    }

                    $this->cargar(
                        $fila["idmenu"],
                        $fila["menombre"],
                        $fila["medescripcion"],
                        $objMenu,
                        $fila["medeshabilitado"]
                    );

                    $encontro = true;
                }
            }else{$this->setMensajeOperacion("menu->buscar: ".$this->getError());}
        }else{$this->setMensajeOperacion("menu->buscar: ".$this->getError());}

        return $encontro;
    }

    /**
     * Lista los menus de la db
     * @param string $condicion (opcional)
     * @return array
     */
    public function listar($condicion = ""){
        $arreglo = null;
        $consulta = "SELECT * FROM menu";

        if($condicion != ""){
            $consulta .= " WHERE " . $condicion;
        }

        if($this->Iniciar()){
            if($this->Ejecutar($consulta)){
                $arreglo = [];
                while($fila = $this->Registro()){
                    $objMenu = new Menu;
                    if($fila["idpadre"] != -1){
                        $objMenu->buscar($fila["idpadre"]);
                    }else{
                        $objMenu->setId(-1);
                    }
                    array_push($arreglo, $objMenu);
                }
            }else{$this->setMensajeOperacion("menu->listar: ".$this->getError());}
        }else{$this->setMensajeOperacion("menu->listar: ".$this->getError());}

        return $arreglo;
    }

    /**
     * Inserta a la db
     * @return boolean
     */
    public function insertar(){
        $resp = null;
        $resultado = false;

        $consulta = "INSERT INTO menu(menombre, medescripcion, idpadre, medeshabilitado)
        VALUES ('" . $this->getNombre() . "', '". $this->getDescripcion() ."','". $this->getObjMenuPadre()->getId() ."',
        '". $this->getDeshabilitado() ."');";

        if($this->Iniciar()){
            $resp = $this->Ejecutar($consulta);
            if ($resp) {
                $this->setId($resp);
                $resultado = true;
            }else{$this->setmensajeoperacion("menu->insertar: ".$this->getError());}
        }else{$this->setMensajeOperacion("menu->insertar: ".$this->getError());}

        return $resultado;
    }

    /**
     * Modifica la db
     * @return boolean
     */
    public function modificar(){
        $seConcreto = false;

        $consulta = "UPDATE menu SET menombre = '". $this->getNombre() ."', medescripcion = '". $this->getDescripcion() ."',
        idpadre = '". $this->getObjMenuPadre()->getId() ."', medeshabilitado = '".$this->getDeshabilitado()."'
        WHERE idmenu = '" . $this->getId(). "'";

        if($this->Iniciar()){
            if($this->Ejecutar($consulta)){
                $seConcreto = true;
            }else{$this->setMensajeOperacion("menu->modificar: ".$this->getError());}
        }else{$this->setMensajeOperacion("menu->modificar: ".$this->getError());}

        return $seConcreto;
    }

    /**
     * Elimina de la db
     * @return boolean
     */
    public function eliminar(){
        $seConcreto = false;

        $consulta = "DELETE FROM menu WHERE idmenu = '" . $this->getId() ."'";

        if($this->Iniciar()){
            if($this->Ejecutar($consulta)){
                $seConcreto = true;
            }else{$this->setMensajeOperacion("menu->eliminar: ".$this->getError());}
        }else{$this->setMensajeOperacion("menu->eliminar: ".$this->getError());}

        return $seConcreto;
    }
}


?>