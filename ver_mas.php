<?php include("template/cabecera_libros.php");?>  

<?php
include("administrador/config/bd.php");
$idProducto = $_GET["id"];

$sentenciaSQL= $conexion->prepare("SELECT * FROM libros WHERE id=$idProducto");
$sentenciaSQL->execute();
$listadeLibros=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?> 

<?php foreach($listadeLibros as $libro) { ?>
  
    <div class="cont">
    <h4 class="card-title"><?php echo $libro['nombre'] ?></h4>
    <div class="col-md-3">  
  <div class="card">
  <img class="card-img-top"  src= "./img/<?php echo $libro['imagen'] ?>" alt="">
  <div class="card-body">
    </div>
    </div>  
    </div> 
    </div>
    <h4 class="card-title"><?php echo $libro['descrip'] ?></h4>
    <h4 class="card-title"><?php echo $libro['cantidad'] ?></h4>
</div>
    

 <?php } ?>   
 

    
  
<?php include("template/pie.php");?>  