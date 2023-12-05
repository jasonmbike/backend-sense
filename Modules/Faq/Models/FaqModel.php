<?php

namespace Modules\Faq\Models;

use CodeIgniter\Model;

class FaqModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    
    function getFaq() {
        $query = $this->db->query("SELECT ID AS ID, TITULO, DESCRIPCION FROM faq");
    
        if ($query) {
            return $query;
        } else {
            return false; // O maneja el error de otra manera si es necesario
        }
    }
    
    public function actualizarRegistro($tabla, $comparar, $datos, $id) {
        $result = $this->db->table($tabla)->where($comparar, $id)->update($datos);
        

        if ($result)
            return true;
        else
            return false;
    }
//INNER JOIN CLIENTE CL ON C.ID_CLIENTE = CL.ID_CLIENTE
}