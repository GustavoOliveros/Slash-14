<?php
class AbmProducto{
    //Espera como parametro un arreglo asociativo donde las claves coinciden con los uspasss de las variables instancias del objeto

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * @param array $param
      * @return Producto|null
     */
    private function cargarObjeto($param){
        $obj = null;

        if(

            array_key_exists('nombre',$param)
            and array_key_exists('detalle',$param)
            and array_key_exists('cantstock',$param)

        ){
            $obj = new Producto();

            $obj->cargar(null, $param["nombre"], $param["detalle"],  $param["cantstock"]);
        }
        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto que son claves
     * @param array $param
     * @return Rol|null
     */
    private function cargarObjetoConClave($param){
        $obj = null;

        if(isset($param['id'])){
            $obj = new Producto();
            $obj->buscar($param["id"]);
        }
        return $obj;
    }


    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
     * @param array $param
     * @return boolean
     */

    private function seteadosCamposClaves($param){
        $resp = false;
        if (isset($param['id']))
            $resp = true;
        return $resp;
    }

    /**
     * Permite dar de alta un objeto
     * @param array $param
     */
    public function alta($param){
        $resp = array();
        $elObjtTabla = $this->cargarObjeto($param);


        if ($elObjtTabla!=null and $elObjtTabla->insertar()){
            $this->subirArchivo($param);
            $resp = array('resultado'=> true,'error'=>'', 'obj' => $elObjtTabla);
        }else {
            $resp = array('resultado'=> false,'error'=> $elObjtTabla->getmensajeoperacion());
        }

        return $resp;

    }


    /**
     * Sube un archivo
     */
    public function subirArchivo($param){
        $dir = "../../../Control/Subidas/";
        $resp = false;

        if ($param['imagen']['imagen']['error'] <= 0 && $param['imagen']['imagen']['type'] == "image/jpeg") {
            if (copy($param['imagen']['imagen']['tmp_name'], $dir . md5($param["id"]). ".jpg")) {
                $resp = true;
            }
        }

        return $resp;
    }

    /**
     * Permite eliminar un objeto
     * @param array $param
     * @return boolean
     */
    public function baja($param){
        $resp = false;
        if ($this->seteadosCamposClaves($param)){
            $elObjtTabla = $this->cargarObjetoConClave($param);
            if ($elObjtTabla!=null and $elObjtTabla->eliminar()){
                $resp = true;
            }
        }

        return $resp;
    }

    /**
     * Permite modificar un objeto
     * @param array $param
     * @return boolean
     */
    public function modificacion($param){
        $resp = false;
        if ($this->seteadosCamposClaves($param)){
            $elObjtTabla = $this->cargarObjeto($param);
            $elObjtTabla->setId($param["id"]);
            if($elObjtTabla!=null and $elObjtTabla->modificar()){
                $this->subirArchivo($param);
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * Permite buscar un objeto
     * @param array $param
     * @return array
     */
    public function buscar($param){
        $where = " true ";
        $claves = ["id"];
        $db = ["idproducto"];


        if ($param<>null){
            for($i = 0; $i < count($claves); $i++){
                if(isset($param[$claves[$i]])){
                    $where.= " and " . $db[$i] . " = '". $param[$claves[$i]]  ."'";
                }
            }
        }

        $obj = new Producto();
        $arreglo = $obj->listar($where);
        return $arreglo;
    }

  }



?>
