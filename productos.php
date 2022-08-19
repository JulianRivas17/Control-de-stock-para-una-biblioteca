<?php include("template/cabecera_libros.php");?>  

<?php
include("administrador/config/bd.php");

$sentenciaSQL= $conexion->prepare("SELECT * FROM libros");
$sentenciaSQL->execute();
$listadeLibros=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<?php foreach($listadeLibros as $libro) { ?>
    <div class="col-md-3">
  <div class="card"> 
  <img class="card-img-top" src="./img/<?php echo $libro['imagen'] ?> " alt="">
  <div class="card-body">
    <h4 class="card-title"><?php echo $libro['nombre'] ?></h4>
    
    <a name="" id="" class="btn btn-primary" href="ver_mas.php?id=<?php echo $libro['id'] ?>" role="button"> ver mas </a>

    </div>
    </div>  
    </div>  
 <?php } ?>   

  
<?php include("template/pie.php");?>  