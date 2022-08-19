<?php 
$host="localhost";
$bd="sitio";
$usuario="root";
$contracenia="";


try {
    $conexion=new PDO("mysql:host=$host;dbname=$bd" ,$usuario,$contracenia); 
    
} catch ( Exception $ex) {
   
    ECHO $ex->getMessage();
}
?>