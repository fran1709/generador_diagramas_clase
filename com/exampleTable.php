<?php
$conection = pg_connect("host=localhost port=5432 dbname=CTEC user=postgres password=12345");

$query = "Select id,nombre,apellido1,apellido2,genero,fecha_nac,email from admi.usuarios";
$pgQuery = pg_query($conection, $query);

?>
<!DOCTYPE html>
<html lang="en">  
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../media/idenx.png" type="image/x-icon">
    <title>Diagrams MF</title>
</head>

<body>
    <table border="1" >

        <thead>
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th>Primer Apellido</th>
                <th>Segundo Apellido</th>
                <th>Género</th>
                <th>Fecha de Nacimiento</th>
                <th>Email</th>
            </tr>
        </thead>  

        <tbody>
            <?php 
            while($objt=pg_fetch_object($pgQuery)){?>
                <tr>
                    <td><?php echo($objt->id);?></td>
                    <td><?php echo($objt->nombre);?></td>
                    <td><?php echo($objt->apellido1);?></td>
                    <td><?php echo($objt->apellido2);?></td>
                    <td><?php echo($objt->genero);?></td>
                    <td><?php echo($objt->fecha_nac);?></td>
                    <td><?php echo($objt->email);?></td>
                </tr> 
            <?php } ?>
        </tbody> 
    </table>
    
</body>
</html> 