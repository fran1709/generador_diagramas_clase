<?php

//Variables de Inicio de Sesión.
if(isset($_POST['txtHost'])){
    $pHost=$_POST['txtHost'];
    $pPort=$_POST['txtPort'];
    $pDbname=$_POST['txtDbname'];
    $pUser=$_POST['txtUser'];
    $pPass=$_POST['txtPassword'];
    //Proceso de conexion con el servidor Postgres
    $conection = pg_connect("host=$pHost port=$pPort dbname=$pDbname user=$pUser password=$pPass");
    //Si los parametros son correctos incia sesión sino redirige al index
    if(!$conection) {
        header('location: ../login.php');
    }
}
$conection = pg_connect("host=localhost port=5432 dbname=CTEC user=postgres password=12345");


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
    <header>
        <div class="container">
            <p class="logo">Diagrams Generator</p>
            <nav>
                <a href="../index.html">Log Out</a>
            </nav>
        </div>
    </header>

    <section id="hero">
        <h1>USER FUNCTIONS</h1>
        <h2>Create Diagram by Schemas & Tables</h2>
        <form action="./diagram.php" method="get">
            <section id="credenciales">
                <ul id="col">
                    <li>
                        <label for="schema">Schemas by Tables</label>
                        <br>
                        <select multiple name="tables[]" id="table" style="height: 250px; width: 200px;">  
                            <?php 
                            $query = "SELECT schema_name from information_Schema.schemata where schema_name not in ('pg_catalog','information_schema','pg_toast');";
                            //$conection2 = pg_connect("host=$pHost port=$pPort dbname=$pDbname user=$pUser password=$pPass");
                            $pgQuery2 = pg_query($conection,$query);
                            while($objt=pg_fetch_result($pgQuery2, 0)){?> 
                            <optgroup value=<?php echo($objt)?> label=<?php echo($objt)?>>
                                <?php 
                                $queryTables = "SELECT table_name FROM information_schema.tables WHERE table_schema='$objt' AND table_type='BASE TABLE'";
                                $pgQuery1 = pg_query($conection, $queryTables);
                                while($objt1=pg_fetch_result($pgQuery1, 0)) {?>
                                    <option value=<?php echo($objt1)?>> <?php echo($objt1)?></option>
                                <?php } ?>
                            </optgroup>  
                            <?php } ?>   
                        </select>
                    </li>
                    <br>
                    <li>
                        <label for="schema">Atributes by Tables</label>
                        <br>
                        <select multiple name="atribute[]" id="table" style="height: 250px; width: 200px;">  
                            <?php 
                            $query = "SELECT schema_name from information_Schema.schemata where schema_name not in ('pg_catalog','information_schema','pg_toast');";
                            //$conection2 = pg_connect("host=$pHost port=$pPort dbname=$pDbname user=$pUser password=$pPass");
                            $pgQuery2 = pg_query($conection,$query);
                            while($objt=pg_fetch_result($pgQuery2, 0)){?> 
                            <optgroup value=<?php echo($objt)?> label=<?php echo($objt)?>>
                                <?php 
                                $queryTables = "SELECT table_name FROM information_schema.tables WHERE table_schema='$objt' AND table_type='BASE TABLE'";
                                $pgQuery1 = pg_query($conection, $queryTables);
                                while($objt1=pg_fetch_result($pgQuery1, 0)) {?>
                                    <optgroup value=<?php echo($objt1)?> label=---<?php echo($objt1)?> styles="border: thin solid blue;border-radius: 4px;">
                                        <?php
                                        $queryA ="Select column_name from information_Schema.columns where table_schema 
                                        not in ('pg_catalog','information_schema') and table_name = '$objt1';";
                                        //$conection3 = pg_connect("host=$pHost port=$pPort dbname=$pDbname user=$pUser password=$pPass");
                                        $pgQuery3 = pg_query($conection, $queryA);
                                        while($objt3=pg_fetch_result($pgQuery3, 0)){?>
                                            <option value= <?php echo($objt3)?>>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<?php echo($objt3)?>  </option>
                                    <?php } ?> 
                                    </optgroup>
                                <?php } ?>
                            </optgroup>  
                            <?php } ?>   
                        </select>
                    </li>
                    <br>
                    <button type="submit" id="tableSchema" itemid="schema">Generate Diagram</button>
                </ul>
                <h2>Create Relation Between Tables</h2>
                <ul id="col">
                <li>
                    <label for="table1">Table One</label>
                    <br>
                    <select name="table1" id="table1">
                        <?php 
                        $pgQuery = pg_query($conection, $query);
                        while($objt=pg_fetch_result($pgQuery, 0)){?>
                        <optgroup value=<?php echo($objt)?> label=<?php echo($objt)?>>
                            <?php 
                            $queryTables = "SELECT table_name FROM information_schema.tables WHERE table_schema='$objt' AND table_type='BASE TABLE'";
                            $pgQuery1 = pg_query($conection, $queryTables);
                            while($objt1=pg_fetch_result($pgQuery1, 0)) {?>
                                <option value=<?php echo($objt1)?>><?php echo($objt1)?></option>
                            <?php } ?>
                        </optgroup> 
                        <?php } ?>   
                    </select>
                </li>
                <li>
                    <label for="table1">Table Two</label>
                    <br>
                    <select name="table2" id="table2" >
                        <?php 
                        $pgQuery = pg_query($conection, $query);
                        while($objt=pg_fetch_result($pgQuery, 0)){?>
                        <optgroup value=<?php echo($objt)?> label=<?php echo($objt)?>>
                            <?php 
                            $queryTables = "SELECT table_name FROM information_schema.tables WHERE table_schema='$objt' AND table_type='BASE TABLE'";
                            $pgQuery1 = pg_query($conection, $queryTables);
                            while($objt1=pg_fetch_result($pgQuery1, 0)) {?>
                                <option value=<?php echo($objt1)?>><?php echo($objt1)?></option>
                            <?php } ?>
                        </optgroup> 
                        <?php } ?>   
                    </select>
                </li>
                <br>
                <li>
                    <label for="direction">Direction</label>
                    <select  name="direction" id="direction">
                        <option value="Izq">Izquierda</option>
                        <option value="Der">Derecha</option>
                        <option value="Bid">Bidireccional</option>
                    </select>
                    
                    <label for="cardinalidad1">Cardinality One</label>
                    <select name="cardinalidad1" id="cardinalidad1">
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="n">n</option>
                    </select>

                    <label for="cardinalidad2">Cardinality Two</label>
                    <select name="cardinalidad2" id="cardinalidad2">
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="n">n</option>
                    </select>
                    
                </li>
                <br>
                <li>
                    <label for="rol">Rol of R.</label>
                    <select  name="rol" id="rol">
                        <option value="tiene">Tiene</option>
                        <option value="pertenece">Pertenece</option>
                        <option value="esDe">Es de</option>
                    </select>
                    <label for="tipoRelacion">Type of Relation</label>
                    <select  name="tipoRelacion" id="tipoRelacion">
                        <option value="Herencia">Herencia</option>
                        <option value="Asociacion">Asociación</option>
                        <option value="Agregacion">Agregación</option>
                        <option value="Composicion">Composición</option>
                    </select>
                    
                </li>
                <br></br>
                <button type="submit" id="table2" id="table1">Generate Relation</button>
            </ul>
            </section>  
        </form>
    </section>
        
    <section id="createRelation">
        
        <form action="./user.php" method="get">
            
        </form>      
    </section>

    <section id="relationBetweenTables">
        <h2>Seeing Relation Between Tables</h2>
        <ul id="col">
            <li>
                <table border="1" >
                    <thead>
                        <tr>
                            <th>t1.table_schema</th>
                            <th>t1.table_name</th>
                            <th>t1.constraint_name</th>
                            <th>t2.table_schema</th>
                            <th>t2.table_name</th>
                        </tr>
                    </thead>  
                    <tbody>
                        <?php 
                        $t1 = "t1";
                        $t2 = "t2";
                        $queryTablesInfo ="select t1.table_schema as schemaOne ,t1.table_name as tableOne,t1.constraint_name as foreignKey,t2.table_schema as schemaTwo,t2.table_name as tableTwo from information_Schema.key_column_usage
                        as $t1 inner join information_Schema.constraint_column_usage as $t2
                        on t2.constraint_name=t1.constraint_name and t1.constraint_name similar to ('fk%');";
                        //$conection5 = pg_connect("host=$pHost port=$pPort dbname=$pDbname user=$pUser password=$pPass");
                        $pgQuery5 = pg_query($conection, $queryTablesInfo);
                        while($objt=pg_fetch_object($pgQuery5)){?>
                            <tr>
                                <td><?php echo($objt->schemaone);?></td>
                                <td><?php echo($objt->tableone);?></td>
                                <td><?php echo($objt->foreignkey);?></td>
                                <td><?php echo($objt->schematwo);?></td>
                                <td><?php echo($objt->tabletwo);?></td>
                            </tr> 
                        <?php } ?>
                    </tbody> 
                </table>
            </li>
        </ul>
    </section>

    <footer>
        <div class="container">
            <p>&copy; Diagrams Generator 2022</p>
        </div>
    </footer>
</body>
</html>