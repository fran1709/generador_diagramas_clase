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
<?php

function encodep($text) {
    $data = utf8_encode($text);
    $compressed = gzdeflate($data, 9);
    return encode64($compressed);
}

function encode6bit($b) {
    if ($b < 10) {
         return chr(48 + $b);
    }
    $b -= 10;
    if ($b < 26) {
         return chr(65 + $b);
    }
    $b -= 26;
    if ($b < 26) {
         return chr(97 + $b);
    }
    $b -= 26;
    if ($b == 0) {
         return '-';
    }
    if ($b == 1) {
         return '_';
    }
    return '?';
}

function append3bytes($b1, $b2, $b3) {
    $c1 = $b1 >> 2;
    $c2 = (($b1 & 0x3) << 4) | ($b2 >> 4);
    $c3 = (($b2 & 0xF) << 2) | ($b3 >> 6);
    $c4 = $b3 & 0x3F;
    $r = "";
    $r .= encode6bit($c1 & 0x3F);
    $r .= encode6bit($c2 & 0x3F);
    $r .= encode6bit($c3 & 0x3F);
    $r .= encode6bit($c4 & 0x3F);
    return $r;
}

function encode64($c) {
    $str = "";
    $len = strlen($c);
    for ($i = 0; $i < $len; $i+=3) {
           if ($i+2==$len) {
                 $str .= append3bytes(ord(substr($c, $i, 1)), ord(substr($c, $i+1, 1)), 0);
           } else if ($i+1==$len) {
                 $str .= append3bytes(ord(substr($c, $i, 1)), 0, 0);
           } else {
                 $str .= append3bytes(ord(substr($c, $i, 1)), ord(substr($c, $i+1, 1)),
                     ord(substr($c, $i+2, 1)));
           }
    }
    return $str;
}

$conn = pg_connect("host=localhost port=5432 dbname=CTEC user=postgres password=12345");

$atributes = $_GET['atribute']; //LISTA DE atributos
$tablesSelect = $_GET["tables"]; //LISTA DE Tablas

//Atributos ELEGIDOS
$cadena0 = "";
for ($i=0;$i<count($atributes);$i++) {   
     $cadena0 .= "'";
     $cadena0 .= "$atributes[$i]";  
     $cadena0 .= "'";
     if ($i < count($atributes)-1){
          $cadena0 .= ",";
     }
}
//echo($cadena0);

//TABLAS ELEGIDAS
$cadena = "";
for ($i=0;$i<count($tablesSelect);$i++) {   
     $cadena .= "'";
     $cadena .= "$tablesSelect[$i]";  
     $cadena .= "'";
     if ($i < count($tablesSelect)-1){
          $cadena .= ",";
     }
}
//echo($cadena);

$result = pg_query($conn, "Select table_schema,table_name from information_Schema.tables 
where table_type ='BASE TABLE' and table_schema not in ('pg_catalog','information_schema') and table_name in ($cadena)");

$pu="@startuml CTEC\n";
while ($row = pg_fetch_row($result)) {
     $pu=$pu."class $row[0].$row[1]{\n";

     $result_colums = pg_query($conn, "Select column_name, data_type from information_Schema.columns
     where table_schema = '$row[0]' and table_name='$row[1]' and column_name in($cadena0)");

     while ($row_column = pg_fetch_row($result_colums)) {
          $pu=$pu."\t-$row_column[1] $row_column[0]\n";
     }

     $pu=$pu."}\n\n";

}

if(isset($_GET['table1'])) {
     $table1 = $_GET['table1'];
     $table2 = $_GET['table2'];
     $query = "Select table_schema from information_Schema.tables 
     where table_type ='BASE TABLE' and table_schema not in ('pg_catalog','information_schema') 
     and table_name = '$table1'";
     $query2 = "Select table_schema from information_Schema.tables 
     where table_type ='BASE TABLE' and table_schema not in ('pg_catalog','information_schema') 
     and table_name = '$table2'";

     $result1 = pg_query($conn, $query);
     $result2= pg_query($conn,$query2);

     $pDir=$_GET['direction'];
     $pRol=$_GET['rol'];
     $pC1=$_GET['cardinalidad1'];
     $pC2=$_GET['cardinalidad2'];
     $pRel=$_GET['tipoRelacion'];

     $c = '"';
     switch ($row = pg_fetch_row($result1) and $row2= pg_fetch_row($result2)) {
         case ($pRel=="Herencia" & $pDir=="Izq"):
             $pu= $pu."$row[0].$table1 $c$pC1$c --|> $c$pC2$c $row2[0].$table2 : $pRol \n";
             break;
         
             case($pRel=="Herencia" & $pDir=="Der"):
             $pu= $pu."$row2[0].$table2 $c$pC2$c  <|-- $c$pC1$c  $row[0].$table1 : $pRol \n";
             break;
         
             case($pRel=="Composicion" & $pDir=="Izq"):
             $pu= $pu."$row[0].$table1 $c$pC1$c --* $c$pC2$c $row2[0].$table2 : $pRol \n";
             break;
         
             case($pRel=="Composicion" & $pDir=="Der"):
             $pu= $pu."$row2[0].$table2 $c$pC2$c *-- $c$pC1$c $row[0].$table1 : $pRol \n";
             break;
         
             case ($pRel=="Agregacion" & $pDir=="Izq"):
             $pu= $pu."$row[0].$table1 $c$pC1$c --o $c$pC2$c $row2[0].$table2 : $pRol \n";
             break;
         
             case($pRel=="Agregacion" & $pDir=="Der"):
             $pu= $pu."$row2[0].$table2 $c$pC2$c o-- $c$pC1$c $row[0].$table1 : $pRol \n";
             break;
         
             case ($pRel=="Asociacion" & $pDir=="Izq"):
             $pu= $pu."$row[0].$table1 $c$pC1$c --> $c$pC2$c $row2[0].$table2 : $pRol \n";
             break;
         
             case($pRel=="Asociacion" & $pDir=="Der"):
             $pu= $pu."$row2[0].$table2 $c$pC2$c <-- $c$pC1$c $row[0].$table1 : $pRol \n";
             break;
         
             case($pRel=="Asociacion" & $pDir=="Bid"):
             $pu= $pu."$row2[0].$table2 $c$pC2$c -- $c$pC1$c $row[0].$table1 : $pRol \n";
             break;
         
     }
 }
 $pu= $pu."admi.congresos -- admi.actividades : tiene 
          \nadmi.actividades -- admi.ponencias : tiene 
          \nadmi.lugaresactividades -- admi.lugares : tiene 
          \nadmi.lugaresactividades -- admi.actividades : tiene 
          \n";

$pu= $pu."@enduml";

$encode = encodep($pu);
echo "<textarea rows='20' cols='50'>$pu</textarea><br>";
echo "<textarea rows='5' cols='50'>$encode</textarea><br>";
echo "<img src='http://www.plantuml.com/plantuml/img/$encode'>";

$toJsonUml = json_encode($pu);
$pNameDiagram = "diagramTest";
$pUser = "postgress";
$pqueryF= "public.ins_diagrama($pNameDiagram,$pUser,$toJsonUml);";
//pg_query($conn, $pqueryF);

?>
</body>
</html>