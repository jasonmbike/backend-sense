<?php

namespace Modules\Documentos\Models;

use CodeIgniter\Model;

class DocumentosModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    
    function getDocumentos() {
        $query = $this->db->query("SELECT D.ID_DOCUMENTO, E.EMAIL,
                                    D.RUTA_ARCHIVO, ES.ESTADO, TD.NOMBRE
                                    FROM documento D
                                    INNER JOIN especialista E ON D.ID_ESPECIALISTA = E.ID_ESPECIALISTA
                                    INNER JOIN estado ES ON D.ID_ESTADO = ES.ID_ESTADO
                                    INNER JOIN tipo_documento TD ON D.ID_TIPODOC = TD.ID_TIPODOC");
    
        if ($query) {
            return $query;
        } else {
            return false; // O maneja el error de otra manera si es necesario
        }
    }


  
    public function actualizarEstadoDocumento($id_documento) {
        $query = $this->db->query ("UPDATE documento SET ID_ESTADO = 1 WHERE ID_DOCUMENTO = $id_documento");
        
        
        if ($query) {
            return $query;
        } else {
            return false; // O maneja el error de otra manera si es necesario
        }
    }

    }
    
