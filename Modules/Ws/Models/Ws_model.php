<?php

namespace Modules\Ws\Models;

class Ws_model
{
    private $db;
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    function almacenarUsuario($rut, $nombre, $appaterno, $apmaterno, $telefono, $email, $clave) {
    
        $sql = "INSERT INTO usuario (rut, nombre, ap_paterno, ap_materno, celular, email, clave, id_estado, id_tipo_usuario) 
                VALUES ('$rut','$nombre', '$appaterno', '$apmaterno', '$telefono','$email', '$clave', '1', '2')";
    
        $query = $this->db->query($sql);
    
        if ($query) {
            return true;  // Éxito al almacenar en la base de datos
        } else {
            return false;  // Error al ejecutar la consulta SQL
        }
    }

    function almacenarEspecialista($rut, $nombre, $appaterno, $apmaterno, $telefono, $email, $clave) {
    
        $sql = "INSERT INTO especialista (rut, nombre, ap_paterno, ap_materno, celular, email, clave, id_estado, id_tipo_usuario) 
                VALUES ('$rut','$nombre', '$appaterno', '$apmaterno', '$telefono','$email', '$clave', '3', '1')";
    
        $query = $this->db->query($sql);
    
        if ($query) {
            return true;  // Éxito al almacenar en la base de datos
        } else {
            return false;  // Error al ejecutar la consulta SQL
        }
    }
    
   

    public function obtenerCredenciales($email) {
        $sql = "SELECT
                    ID_ESPECIALISTA AS ID,
                    EMAIL AS EMAIL,
                    CLAVE AS USUARIO_CLAVE,
                    ID_TIPO_USUARIO
                FROM especialista 
                WHERE EMAIL = ? 
                LIMIT 1";
    
        $query = $this->db->query($sql, [$email]);
    
        if ($query->getNumRows() > 0) {
            $row = $query->getRowArray();
            return $row;
        } else {
            // Si no encontramos en ESPECIALISTA, buscamos en USUARIO
            $sql = "SELECT
                        ID_USUARIO AS ID,
                        EMAIL AS EMAIL,
                        CLAVE AS USUARIO_CLAVE,
                        ID_TIPO_USUARIO
                    FROM usuario 
                    WHERE EMAIL = ?
                    LIMIT 1";
    
            $query = $this->db->query($sql, [$email]);
    
            if ($query->getNumRows() > 0) {
                $row = $query->getRowArray();
                return $row;
            } else {
                return null; // No se encontraron credenciales para el email y clave dados
            }
        }
    }

    public function correoExiste($email) {

        $sql = "SELECT COUNT(*) as count FROM especialista WHERE EMAIL = ?";
        $query = $this->db->query($sql, [$email]);
        $row = $query->getRowArray();
        if ($row && $row['count'] > 0) {
            return true;  // El correo existe en ESPECIALISTA
        }
    
        // Si no se encontró en ESPECIALISTA, busca en USUARIO
        $sql = "SELECT COUNT(*) as count FROM usuario WHERE EMAIL = ?";
        $query = $this->db->query($sql, [$email]);
        $row = $query->getRowArray();
        if ($row && $row['count'] > 0) {
            return true;  // El correo existe en USUARIO
        }
    
        return false;  // El correo no existe en ninguna tabla
    }
    
    public function consultarUsuario($email) {

        $sql = "SELECT COUNT(*) as count FROM especialista WHERE EMAIL = ?";
        $query = $this->db->query($sql, [$email]);
        $row = $query->getRowArray();
        if ($row && $row['count'] > 0) {
            return true;  // El correo existe en ESPECIALISTA
        }
    
        // Si no se encontró en ESPECIALISTA, busca en USUARIO
        $sql = "SELECT COUNT(*) as count FROM usuario WHERE EMAIL = ?";
        $query = $this->db->query($sql, [$email]);
        $row = $query->getRowArray();
        if ($row && $row['count'] > 0) {
            return true;  // El correo existe en USUARIO
        }
    
        return false;  // El correo no existe en ninguna tabla
    }

    public function obtenerEspecialistas() {
        $sql = "SELECT
                    ID_ESPECIALISTA AS ID_ESPECIALISTA,
                    NOMBRE AS NOMBRE_ESPECIALISTA,
                    AP_PATERNO AS AP_PATERNO_ESPECIALISTA,
                    AP_MATERNO AS AP_MATERNO_ESPECIALISTA,
                    EMAIL AS EMAIL_ESPECIALISTA,
                    EDAD AS EDAD_ESPECIALISTA,
                    NACIONALIDAD AS NACIONALIDAD_ESPECIALISTA,
                    IMAGEN_PERFIL AS IMAGEN_PERFIL,
                    PUNTUACION AS PUNTUACION_ESPECIALISTA
                FROM especialista";
    
        $query = $this->db->query($sql);
    
        if ($query->getNumRows() > 0) {
            $rows = $query->getResultArray();
            return $rows;
        } else {
            return null; // No se encontraron especialistas
        }
    }
    


    //FUNCIONES RESERVAS INICIO


    function gethorario($where) {

        $sql = "SELECT horario AS horario, tarifa AS tarifa
                FROM especialista
                WHERE ID_ESPECIALISTA = $where";
                        
    
        $query = $this->db->query($sql); // Pasar el arreglo directamente sin usar array()
        
        if ($query->getNumRows() > 0)
            return $query;
        else
            return false;

    }

    function getBoleta($where) {

        $sql = "SELECT id_boleta as id, monto, estado, orden_compra, numero_tarjeta,codigo_autorizacion, tipo_pago, codigo_respuesta, fecha, id_especialista
                FROM boleta
                WHERE id_especialista = $where";
                        
    
        $query = $this->db->query($sql); // Pasar el arreglo directamente sin usar array()
        
        if ($query->getNumRows() > 0)
            return $query;
        else
            return false;

    }


    public function actualizarReserva($tabla, $comparar, $datos, $id) {
        $result = $this->db->table($tabla)->where($comparar, $id)->update($datos);
        

        if ($result)
            return true;
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

    function insertarmultiple($tabla, $datos) {
        $query = $this->db->table($tabla)->insertBatch($datos);
        if ($query)
            return true;
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

    function borrar($fecha) {
        $sql = "DELETE FROM reserva_temporal WHERE reserva_expiracion < '$fecha'";
        $query = $this->db->query($sql);
        if ($query)
            return true;
        else
            return false;
    }

    function borrarReservaConfirmada($hora,$fecha) {
        $sql = "DELETE FROM reserva_temporal WHERE hora = '$hora' AND fecha = '$fecha'";
        $query = $this->db->query($sql);
        if ($query)
            return true;
        else
            return false;
    }


    function consultarHorariosOcupados($fecha) {
        $sql = "SELECT fecha, hora
                FROM reserva_temporal
                WHERE reserva_expiracion > '$fecha'";
                        
    
        $query = $this->db->query($sql); // Pasar el arreglo directamente sin usar array()
        
        if ($query->getNumRows() > 0)
            return $query;
        else
            return false;


    }


    function consultarReservaTomada($fecha,$hora,$fechaActual) {
        $sql = "SELECT fecha, hora
                FROM reserva_temporal
                WHERE fecha = '$fecha' AND hora = '$hora' AND reserva_expiracion > '$fechaActual'";
                        
    
        $query = $this->db->query($sql); // Pasar el arreglo directamente sin usar array()
        
        if ($query->getNumRows() > 0)
            return true;
        else
            return false;


    }


    // public function obtenerProximasHoras($idUsuario){

    //     print_r($idUsuario);
    //     die();

    //     $sql = "SELECT  ID_RESERVA,
    //                     ID_ESPECIALISTA,
    //                     DATE_FORMAT(FECHA_RESERVA,\"%d %b %Y\") AS FECHA_RESERVA,
    //                     HORA_RESERVA,
    //                     ID_ESTADO,
    //                     ID_TIPO_CONSULTA,
    //                     FECHA_REALIZADA
    //             FROM reserva
    //             WHERE fecha_reserva > CURRENT_DATE() 
    //             AND ID_USUARIO = $idUsuario";
  

    //     $query = $this->db->query($sql);
        

    //     if ($query->getNumRows() > 0) {
    //         $rows = $query->getResultArray();
   
    //         return $rows;
    //     } else {
    //         return null; // No se encontraron perfiles
    //     }

    // }

    // public function obtenerHistorialHoras(int $idUsuario){

    //     $sql = "SELECT  ID_RESERVA,
    //                     ID_ESPECIALISTA,
    //                     DATE_FORMAT(FECHA_RESERVA,\"%d %b %Y\") AS FECHA_RESERVA,
    //                     HORA_RESERVA,
    //                     ID_ESTADO,
    //                     ID_TIPO_CONSULTA,
    //                     FECHA_REALIZADA
    //             FROM reserva
    //             WHERE fecha_reserva < CURRENT_DATE() 
    //             AND ID_USUARIO = ?";
  

    //     $query = $this->db->query($sql, [$idUsuario]);
        

    //     if ($query->getNumRows() > 0) {
    //         $rows = $query->getResultArray();
   
    //         return $rows;
    //     } else {
    //         return null; // No se encontraron perfiles
    //     }

    public function verificarUsuario($id) {
        $sql = "SELECT
                    ID_ESTADO
                FROM especialista WHERE ID_ESPECIALISTA = $id";
    
        $query = $this->db->query($sql);
    
        if ($query->getNumRows() > 0) {
            $rows = $query->getResultArray();
            return $rows;
        } else {
            return null; // No se encontraron especialistas
        }
    }


    //FIN FUNCIONES RESERVAS
   
    //INICIO FUNCIONES PEFIL
    public function obtenerUsuario($tabla,$usuario) {

        

        if ($tabla === 'usuario') {
            $sql = "SELECT
                        ID_USUARIO AS ID_USUARIO,
                        usuario.NOMBRE AS NOMBRE_USUARIO,
                        AP_PATERNO AS AP_PATERNO_USUARIO,
                        AP_MATERNO AS AP_MATERNO_USUARIO,
                        EMAIL AS EMAIL_USUARIO,
                        EDAD AS EDAD_USUARIO,
                        NACIONALIDAD AS NACIONALIDAD_USUARIO,
                        DIRECCION AS DIRECCION_USUARIO,
                        RUT AS RUT_USUARIO,
                        CELULAR AS CELULAR_USUARIO,
                        IMAGEN_PERFIL AS IMAGEN_PERFIL,
                        R.nombre AS REGION,
                        C.nombre AS COMUNA,
                        G.GENERO AS GENERO
                    FROM usuario 
                        LEFT JOIN region R ON R.ID_REGION = usuario.ID_REGION
                        LEFT JOIN comuna C ON C.ID_COMUNA = usuario.ID_COMUNA
                        JOIN genero G ON usuario.ID_GENERO = G.ID_GENERO
                 WHERE ID_USUARIO = $usuario";

        } else {

            $sql = "SELECT
                    ID_ESPECIALISTA AS ID_ESPECIALISTA,
                    especialista.NOMBRE AS NOMBRE_ESPECIALISTA,
                    AP_PATERNO AS AP_PATERNO_ESPECIALISTA,
                    AP_MATERNO AS AP_MATERNO_ESPECIALISTA,
                    EMAIL AS EMAIL_ESPECIALISTA,
                    EDAD AS EDAD_ESPECIALISTA,
                    NACIONALIDAD AS NACIONALIDAD_ESPECIALISTA,
                    PUNTUACION AS PUNTUACION_ESPECIALISTA,
                    DIRECCION AS DIRECCION,
                    RUT AS RUT,
                    CELULAR AS CELULAR,
                    ID_TIPO_USUARIO AS ID_TIPO_USUARIO,
                    TARIFA AS TARIFA,
                    IMAGEN_PERFIL AS IMAGEN_PERFIL,
                    R.nombre AS REGION,
                    C.nombre AS COMUNA,
                    G.GENERO AS GENERO
                FROM especialista 
                    LEFT JOIN region R ON R.ID_REGION = especialista.ID_REGION
                    LEFT JOIN comuna C ON C.ID_COMUNA = especialista.ID_COMUNA
                    JOIN genero G ON especialista.ID_GENERO = G.ID_GENERO
                 WHERE ID_ESPECIALISTA = $usuario";
            }

        $query = $this->db->query($sql);
    
        if ($query->getNumRows() > 0) {
            $rows = $query->getResultArray();
            return $rows;
        } else {
            return null; // No se encontraron perfiles
        }
    }

    //Fin FUNCION PERFIL
 
    public function actualizarUsuario($tabla, $usuario) {
        $sql = "UPDATE $tabla 
                SET nombre = '".$usuario['nombre']."', 
                    ap_paterno = '".$usuario['ap_paterno']."',
                    ap_materno = '".$usuario['ap_materno']."',
                    edad = ".$usuario['edad'].",
                    nacionalidad = '".$usuario['nacionalidad']."',
                    direccion = '".$usuario['direccion']."',
                    rut = '".$usuario['rut']."',
                    id_comuna = ".$usuario['id_comuna'].",
                    id_region = ".$usuario['id_region'].",
                    id_genero = ".$usuario['id_genero'].",
                    celular = ".$usuario['celular'].",
                    email = '".$usuario['email']."',
                    imagen_perfil = 'public/archivos/".$usuario['id_usuario']."/".uniqid().".jpg', 
                    descripcion = NULL,
                    FECHAMODIFICACION = NOW()
                WHERE id_usuario = ".$usuario['id_usuario']."";
    
        $query = $this->db->query($sql);
    
        return $query;
    }
    
    /*
    public function actualizarUsuario($usuario){

        $sql = "UPDATE usuario 
                SET nombre = '".$usuario['nombre']."', 
                    ap_paterno = '".$usuario['ap_paterno']."',
                    ap_materno = '".$usuario['ap_materno']."',
                    edad = ".$usuario['edad'].",
                    nacionalidad = '".$usuario['nacionalidad']."',
                    direccion = '".$usuario['direccion']."',
                    rut = '".$usuario['rut']."',
                    id_comuna = ".$usuario['id_comuna'].",
                    id_region = ".$usuario['id_region'].",
                    id_genero = ".$usuario['id_genero'].",
                    celular = ".$usuario['celular'].",
                    email = '".$usuario['email']."',
                    --imagen_perfil = FROM_BASE64('".$usuario['imagen_perfil']."'),
                    descripcion = NULL,
                    --id_tipo_usuario = ".$usuario['id_tipo_usuario'].",
                    --id_estado = ".$usuario['id_estado'].",
                    --FECHACREACION = NULL,
                    FECHAMODIFICACION = NOW(),
                    --FECHABAJA = NULL  
                    WHERE id_usuario = ".$usuario['id_usuario']."";
    
        $query = $this->db->query($sql);
    
        return $query;

    }*/


    





    //INICIO FUNCION FAQ
    public function obtenerFaq() {
        $sql = "SELECT
                    ID AS ID_FAQ,
                    TITULO,
                    DESCRIPCION
                FROM faq";
    
        $query = $this->db->query($sql);
    
        if ($query->getNumRows() > 0) {
            $rows = $query->getResultArray();
            return $rows;
        } else {
            return null; // No se encontraron especialistas
        }
    }

    
    
    //FIN FUNCION FAQ




    //INICIO FUNCION GENERO
    public function obtenerGeneros() {
        $sql = "SELECT
                    id_genero ,
                    genero                    
                FROM genero";
    
        $query = $this->db->query($sql);
    
        if ($query->getNumRows() > 0) {
            $rows = $query->getResultArray();
            return $rows;
        } else {
            return null; // No se encontraron especialistas
        }
    }

    
    
    //FIN FUNCION GENERO

    //INICIO FUNCION COMUNAS
    public function obtenerComunas($id_region) {
        $sql = "SELECT
                    id_comuna,
                    comuna.nombre,
                    comuna.id_region                   
                FROM comuna
                JOIN region R ON R.id_region = comuna.id_region";

        if ($id_region != null) {
            $sql .= " WHERE R.id_region = '$id_region'";
        }
    
        $query = $this->db->query($sql);
    
        if ($query->getNumRows() > 0) {
            $rows = $query->getResultArray();
            return $rows;
        } else {
            return null; // No se encontraron especialistas
        }
    }
    //FIN FUNCION COMUNAS

    //INICIO FUNCION COMUNAS
    public function obtenerRegiones() {
        $sql = "SELECT
                    id_region ,
                    nombre                    
                FROM region";
    
        $query = $this->db->query($sql);
    
        if ($query->getNumRows() > 0) {
            $rows = $query->getResultArray();
            return $rows;
        } else {
            return null; // No se encontraron especialistas
        }
    }
    //FIN FUNCION COMUNAS






    //FIN FUNCIONES PERFIL


    //INICIO FUNCION DOCUMENTOS
    function almacenarDocumentos($nombre_documento, $archivo_destino, $idEspecialista, $idEstado, $id_tipo_documento) {
    
        $sql = "INSERT INTO documento (nombre_documento, ruta_archivo, id_especialista, id_estado, id_tipodoc) 
                VALUES ('$nombre_documento', '$archivo_destino', '$idEspecialista', '$idEstado', '$id_tipo_documento')";
    
        $query = $this->db->query($sql);
    
        if ($query) {
            return true;  // Éxito al almacenar en la base de datos
        } else {
            return false;  // Error al ejecutar la consulta SQL
        }
    }
        //FIN FUNCION DOCUMENTOS



        //INICIO FUNCION HISTORIAL
        public function obtenerReservasPorUsuario($idUsuario, $tipoUsuario) {
            $sql = "SELECT
                    usuario.NOMBRE AS NOMBRE_USUARIO,
                    usuario.AP_MATERNO AS AP_MATERNO_USUARIO,
                    usuario.AP_PATERNO AS AP_PATERNO_USUARIO,
                    especialista.NOMBRE AS NOMBRE_ESPECIALISTA,
                    especialista.AP_MATERNO AS AP_MATERNO_ESPECIALISTA,
                    especialista.AP_PATERNO AS AP_PATERNO_ESPECIALISTA,
                    reserva.FECHA_RESERVA,
                    reserva.HORA_RESERVA,
                    estado.ESTADO,
                    tipo_consulta.TIPO_CONSULTA
            FROM reserva
            JOIN usuario ON reserva.ID_USUARIO = usuario.ID_USUARIO
            JOIN especialista ON reserva.ID_ESPECIALISTA = especialista.ID_ESPECIALISTA
            JOIN estado ON reserva.ID_ESTADO = estado.ID_ESTADO
            JOIN tipo_consulta ON reserva.ID_TIPO_CONSULTA = tipo_consulta.ID_TIPO_CONSULTA
            WHERE ";
        
            // Añadir la condición para filtrar por ID de usuario o especialista
            if ($tipoUsuario == 1) { // Usuario normal
                $sql .= "especialista.ID_ESPECIALISTA = ?";
            } elseif ($tipoUsuario == 2) { // Especialista
                $sql .= "usuario.ID_USUARIO = ?";
            }
        
            $query = $this->db->query($sql, [$idUsuario]);
        
            if ($query->getNumRows() > 0) {
                $rows = $query->getResultArray();
                return $rows;
            } else {
                return null; // No se encontraron reservas
            }
        }
        
        

        //FIN FUNCION HISTORIAL





        //INICIO FUNCIONES BUSCAR


        function getPersonalidades() {

            $sql = "SELECT personalidad AS personalidad, id_personalidad as id_personalidad
                    FROM personalidad";
                            
        
            $query = $this->db->query($sql); // Pasar el arreglo directamente sin usar array()
            
            if ($query->getNumRows() > 0)
                return $query;
            else
                return false;
    
        }


        function getHobbies() {

            $sql = "SELECT hobbie AS hobbie, id_hobbie as id_hobbie
                    FROM hobbie";
                            
        
            $query = $this->db->query($sql); // Pasar el arreglo directamente sin usar array()
            
            if ($query->getNumRows() > 0)
                return $query;
            else
                return false;
    
        }


        public function getespecialista($tipo_consulta, $genero, $personalidad, $hobbie) {
            $personalidadString = !empty($personalidad) ? "'" . implode("', '", $personalidad) . "'" : '';
            $hobbieString = !empty($hobbie) ? "'" . implode("', '", $hobbie) . "'" : '';

            $sql = "SELECT
                        E.ID_ESPECIALISTA AS ID_ESPECIALISTA,
                        E.NOMBRE AS NOMBRE_ESPECIALISTA,
                        E.AP_PATERNO AS AP_PATERNO_ESPECIALISTA,
                        E.AP_MATERNO AS AP_MATERNO_ESPECIALISTA,
                        E.EMAIL AS EMAIL_ESPECIALISTA,
                        E.EDAD AS EDAD_ESPECIALISTA,
                        E.NACIONALIDAD AS NACIONALIDAD_ESPECIALISTA,
                        E.PUNTUACION AS PUNTUACION_ESPECIALISTA,
                        E.IMAGEN_PERFIL AS IMAGEN_PERFIL,
                        C.NOMBRE AS NOMBRE_COMUNA,
                        R.NOMBRE AS NOMBRE_REGION
                        
                    FROM especialista E
                    LEFT JOIN genero G on E.ID_GENERO = G.ID_GENERO
                    LEFT JOIN tipo_consulta TC on E.ID_TIPO_CONSULTA = TC.ID_TIPO_CONSULTA
                    LEFT JOIN especialista_personalidad EP ON E.ID_ESPECIALISTA = EP.ID_ESPECIALISTA
                    LEFT JOIN personalidad P ON EP.ID_PERSONALIDAD = P.ID_PERSONALIDAD
                    LEFT JOIN especialista_hobbie EH ON E.ID_ESPECIALISTA = EH.ID_ESPECIALISTA
                    LEFT JOIN hobbie H ON EH.ID_HOBBIE = H.ID_HOBBIE
                    LEFT JOIN comuna C ON E.ID_COMUNA = C.ID_COMUNA
                    LEFT JOIN region R ON E.ID_REGION = R.ID_REGION
                    WHERE 1";

            if (!empty($tipo_consulta)) {
                $sql .= " AND TC.TIPO_CONSULTA = '$tipo_consulta'";
            }

            if (!empty($genero)) {
                $sql .= " AND G.GENERO = '$genero'";
            }

            if (!empty($personalidad)) {
                $sql .= " AND P.PERSONALIDAD IN ($personalidadString)";
            }

            if (!empty($hobbie)) {
                $sql .= " AND H.HOBBIE IN ($hobbieString)";
            }

            $sql .= " GROUP BY E.ID_ESPECIALISTA";

           
            
            $query = $this->db->query($sql);
        
            if ($query->getNumRows() > 0) {
                return $query;
            } else {
                return null; // No se encontraron especialistas
            }
        }


        




        //FIN FUNCIONES BUSCAR


        function getPersonalidades2($id) {

            $sql = "SELECT P.personalidad AS personalidad
                    FROM personalidad P
                    JOIN especialista_personalidad EP ON P.ID_PERSONALIDAD = EP.ID_PERSONALIDAD
                    WHERE EP.ID_ESPECIALISTA = $id";       
        
            $query = $this->db->query($sql); // Pasar el arreglo directamente sin usar array()
            
            
                return $query;
           
    
        }


        function getHobbies2($id) {

            $sql = "SELECT H.hobbie AS hobbie
                    FROM hobbie H
                    JOIN especialista_hobbie EH ON H.ID_HOBBIE = EH.ID_HOBBIE
                    WHERE EH.ID_ESPECIALISTA = $id";
                    
                            
        
            $query = $this->db->query($sql); // Pasar el arreglo directamente sin usar array()
            

                return $query;

    
        }


        function borrarpersonalidades($id) {
            $sql = "DELETE FROM especialista_personalidad WHERE id_especialista = $id";
            $query = $this->db->query($sql);
            if ($query)
                return true;
            else
                return false;
        }

        function borrarhobbies($id) {
            $sql = "DELETE FROM especialista_hobbie WHERE id_especialista = $id";
            $query = $this->db->query($sql);
            if ($query)
                return true;
            else
                return false;
        }
}