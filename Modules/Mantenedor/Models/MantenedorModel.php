<?php

namespace Modules\Mantenedor\Models;

use CodeIgniter\Model;

class MantenedorModel extends Model {

    public function __construct() {
        parent::__construct();
    }
    


    function getEspecialistas($where = 1, $clauses = [], $ORDER_BY = "", $LIMIT = "") {

        $sql = "SELECT E.ID_ESPECIALISTA AS ID, CONCAT(E.NOMBRE, ' ', E.AP_PATERNO, ' ', E.AP_MATERNO) AS NOMBRE_COMPLETO, E.EDAD AS EDAD, E.NACIONALIDAD AS NACIONALIDAD,
        E.DIRECCION AS DIRECCION, E.RUT AS RUT, C.NOMBRE AS COMUNA, R.NOMBRE AS REGION, G.GENERO AS GENERO, E.CELULAR AS CELULAR,
        E.EMAIL AS EMAIL, E.PUNTUACION AS PUNTUACION, E.EXPERIENCIA AS EXPERIENCIA, E.EDUCACION AS EDUCACION, TC.TIPO_CONSULTA AS TIPO_DE_CONSULTA, E.UBICACION_CONSULTA AS UBICACION_CONSULTA,
        E.TARIFA AS TARIFA, E.ID_ESTADO AS ESTADO, ES.ESTADO AS NESTADO

                        FROM especialista E
                        LEFT JOIN comuna C ON C.ID_COMUNA = E.ID_COMUNA
                        LEFT JOIN region R ON C.ID_REGION = R.ID_REGION
                        LEFT JOIN genero G ON G.ID_GENERO = E.ID_GENERO
                        LEFT JOIN tipo_consulta TC ON TC.ID_TIPO_CONSULTA = E.ID_TIPO_CONSULTA
                        LEFT JOIN estado ES ON ES.ID_ESTADO = E.ID_ESTADO
                        
                        WHERE E.ID_ESTADO IN (1, 2, 3, 4) AND $where  ". $ORDER_BY . " ".$LIMIT;
    
        $query = $this->db->query($sql, $clauses); // Pasar el arreglo directamente sin usar array()
        
        if ($query->getNumRows() > 0)
            return $query;
        else
            return false;

    }

    function getUsuarios($where = 1, $clauses = [], $ORDER_BY = "", $LIMIT = "") {

        $sql = "SELECT U.ID_USUARIO AS ID, CONCAT(U.NOMBRE, ' ', U.AP_PATERNO, ' ', U.AP_MATERNO) AS NOMBRE_COMPLETO, U.EDAD AS EDAD, U.NACIONALIDAD AS NACIONALIDAD,
        U.DIRECCION AS DIRECCION, U.RUT AS RUT, C.NOMBRE AS COMUNA, R.NOMBRE AS REGION, G.GENERO AS GENERO, U.CELULAR AS CELULAR,
        U.EMAIL AS EMAIL, U.ID_ESTADO AS ESTADO, ES.ESTADO AS NESTADO

                        FROM usuario U
                        LEFT JOIN comuna C ON C.ID_COMUNA = U.ID_COMUNA
                        LEFT JOIN region R ON C.ID_REGION = R.ID_REGION
                        LEFT JOIN genero G ON G.ID_GENERO = U.ID_GENERO
                        LEFT JOIN estado ES ON ES.ID_ESTADO = U.ID_ESTADO
                        
                        WHERE U.ID_ESTADO IN (1, 2, 3, 4) AND $where  ". $ORDER_BY . " ".$LIMIT;
    
        $query = $this->db->query($sql, $clauses); // Pasar el arreglo directamente sin usar array()
        
        if ($query->getNumRows() > 0)
            return $query;
        else
            return false;

    }

    function getEstado() {

        $sql = "SELECT ID_ESTADO AS ID, ESTADO 
                        FROM estado ";
                        
    
        $query = $this->db->query($sql); // Pasar el arreglo directamente sin usar array()
        
        if ($query->getNumRows() > 0)
            return $query;
        else
            return false;

    }



    function insertar($tabla, $data) {
        $query = $this->db->table($tabla)->insert($data);
        if ($query)
            return $this->db->insertID();
        else
            return false;
    }
    
    public function actualizar($tabla, $comparar, $datos, $id) {
        $result = $this->db->table($tabla)->where($comparar, $id)->update($datos);
        

        if ($result)
            return true;
        else
            return false;
    }
//INNER JOIN CLIENTE CL ON C.ID_CLIENTE = CL.ID_CLIENTE
}