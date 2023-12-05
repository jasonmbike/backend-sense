<?php
// Encabezado PHP, si es necesario

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Completado</title>
    <style>
        /* Estilos CSS para dispositivos móviles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        header {
            
            color: black;
            text-align: center;
            padding: 10px;
        }
        .container {
            text-align: center;
            align-items: center;
        }
        .container img {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
        }

        .p {
            font-family: Arial, sans-serif;

        }

        .imagen {
            
            
                display: block;
                margin-left: auto;
                margin-right: auto;
                width: 50%;

        }
    </style>
</head>
<body>
    <header>
        <h1>Pago Completado</h1>
    </header>
    <div class="container">
        <img class="imagen" src="<?=base_url()?>/public/img/pago.png" alt="Imagen de Pago Completado">
        <p>Puedes cerrar el navegador</p>
        
       
    </div>
</body>
</html>