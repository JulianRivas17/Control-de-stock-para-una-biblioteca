<?php include("../template/cabecera.php"); ?>

<?php

$txtID=(isset($_POST['txtID']))?$_POST['txtID']:"";
$txtNombre=(isset($_POST['txtNombre']))?$_POST['txtNombre']:"";
$txtFecha=(isset($_POST['txtFecha']))?$_POST['txtFecha']:"";
$txtDescrip=(isset($_POST['txtDescrip']))?$_POST['txtDescrip']:"";
$txtCantidad=(isset($_POST['txtCantidad']))?$_POST['txtCantidad']:"";

$txtImagen=(isset($_FILES['txtImagen']['name']))?$_FILES['txtImagen']['name']:"";
$accion=(isset($_POST['accion']))?$_POST['accion']:"";

include("../config/bd.php");

switch($accion){

        case"Agregar":
            $sentenciaSQL= $conexion->prepare("INSERT INTO libros (nombre,imagen,fecha,descrip,cantidad ) VALUES (:nombre,:imagen,:fecha,:descrip,:cantidad);");
            $sentenciaSQL->bindParam(':nombre',$txtNombre);
            $sentenciaSQL->bindParam(':fecha',$txtFecha);
            $sentenciaSQL->bindParam(':descrip',$txtDescrip);
            $sentenciaSQL->bindParam(':cantidad',$txtCantidad);

            $fecha= new DateTime();
            $nombreArchivo=($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";

            $tmpImagen=$_FILES["txtImagen"]["tmp_name"];
            
            if($tmpImagen!=""){
            
                move_uploaded_file($tmpImagen,"../../img/".$nombreArchivo);

            }

            $sentenciaSQL->bindParam(':imagen',$nombreArchivo);
            $sentenciaSQL->execute();
            header("Location:productos.php");
            break;

        case"Modificar":
            $sentenciaSQL= $conexion->prepare("UPDATE libros SET nombre=:nombre, fecha=:fecha , descrip=:descrip ,cantidad=:cantidad  WHERE id=:id");  
            $sentenciaSQL->bindParam(':nombre',$txtNombre);
            $sentenciaSQL->bindParam(':fecha',$txtFecha);
            $sentenciaSQL->bindParam(':descrip',$txtDescrip);
            $sentenciaSQL->bindParam(':cantidad',$txtCantidad);
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();

        if($txtImagen!=""){
            $fecha= new DateTime();
            $nombreArchivo=($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";
            $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

            move_uploaded_file($tmpImagen,"../../img/".$nombreArchivo);

            $sentenciaSQL= $conexion->prepare("SELECT imagen FROM libros WHERE id=:id");  
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
            $libro=$sentenciaSQL->fetch(PDO::FETCH_LAZY); 

            if( isset($libro["imagen"]) &&($libro["imagen"]!="imagen.jpg") ) {

                if(file_exists("../../img/".$libro["imagen"])){

                    unlink("../../img/".$libro["imagen"]);

                }
            }
                 

            $sentenciaSQL= $conexion->prepare("UPDATE libros SET imagen=:imagen where id=:id");
            $sentenciaSQL->bindParam(':imagen',$nombreArchivo);
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();

        }
            header("Location:productos.php");
                break;

        case"Cancelar":
                    header("Location:productos.php");
                    break;

        case"Seleccionar":
            $sentenciaSQL= $conexion->prepare("SELECT * FROM libros WHERE id=:id");  
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
            $libro=$sentenciaSQL->fetch(PDO::FETCH_LAZY); 

            $txtNombre=$libro['nombre'];
            $txtFecha=$libro['fecha'];
            $txtDescrip=$libro['descrip'];
            $txtCantidad=$libro['cantidad'];
            $txtImagen=$libro['imagen'];
             break;

         case"Borrar":
            $sentenciaSQL= $conexion->prepare("SELECT imagen FROM libros WHERE id=:id");  
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
            $libro=$sentenciaSQL->fetch(PDO::FETCH_LAZY); 

            if( isset($libro["imagen"]) &&($libro["imagen"]!="imagen.jpg") ) {

                if(file_exists("../../img/".$libro["imagen"])){

                    unlink("../../img/".$libro["imagen"]);

                }
            }

            $sentenciaSQL= $conexion->prepare("DELETE FROM libros WHERE id=:id");

            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
            header("Location:productos.php");
            break;
              
}


$sentenciaSQL= $conexion->prepare("SELECT * FROM libros");
$sentenciaSQL->execute();
$listadeLibros=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);


?>


<div class="col-md-5">

<div class="card">
    <div class="card-header">
        Datos de Libro
    </div>

    <div class="card-body">
    <form method="POST" enctype="multipart/form-data" >

<div class = "form-group">
<label for="txtID">ID</label>
<input type="text" class="form-control" value="<?php echo $txtID; ?>" name="txtID" id="txtID" placeholder="ID">
</div>


<div class = "form-group">
<label for="txtNombre">Nombre:</label>
<input type="text" required class="form-control" value="<?php echo $txtNombre; ?>" name="txtNombre" id="txtNombre" placeholder="Nombre del Libro">
</div>

<div class = "form-group">
<label for="txtFecha">Fecha:</label>
<input type="text" required class="form-control" value="<?php echo $txtFecha; ?>" name="txtFecha" id="txtFecha" placeholder="Fecha de emision">
</div>

<div class = "form-group">
<label for="txtDescrip">Descripcion:</label>
<input type="text" required class="form-control" value="<?php echo $txtDescrip; ?>" name="txtDescrip" id="txtDescrip" placeholder="Descripcion del Libro">
</div>

<div class = "form-group">
<label for="txtCantidad">Cantidad:</label>
<input type="text" required class="form-control" value="<?php echo $txtCantidad; ?>" name="txtCantidad" id="txtCantidad" placeholder="Cantidad de ejemplares">
</div>

<div class = "form-group">
<label for="txtNombre">Imagen:</label>

<br/>

<?php 
 if($txtImagen!=""){ ?>

<img class="img-thumbnail rounded" src="../../img/<?php echo $libro['imagen'];?>" width="50" alt="" srcset="">

<?php }?>

<input type="file" class="form-control" name="txtImagen" id="txtImagen" placeholder="Nombre del Libro">
</div>


<div class="btn-group" role="group" aria-label="">
    <button type="submit" name="accion" <?php echo ($accion=="Seleccionar")?"disabled":"";?> value="Agregar" class="btn btn-success">Agregar</button>
    <button type="submit" name="accion" <?php echo ($accion!=="Seleccionar")?"disabled":"";?> value="Modificar" class="btn btn-warning">Modificar</button>
    <button type="submit" name="accion" <?php echo ($accion!=="Seleccionar")?"disabled":"";?> value="Cancelar" class="btn btn-info">Cancelar</button>
</div>

</form> 

    </div>

   
</div>
    


</div>

<div class="col-md-7">


<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Fecha</th>
            <th>Descrip</th>
            <th>Cantidad</th>
            <th>Imagen</th>
            <th>Accion</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($listadeLibros as $libro) { ?>
        <tr>
            <td><?php echo $libro['id'];?></td>
            <td><?php echo $libro['nombre']; ?></td>
            <td><?php echo $libro['fecha']; ?></td>
            <td><?php echo $libro['descrip']; ?></td>
            <td><?php echo $libro['cantidad']; ?></td>
            <td>

            <img class="img-thumbnail rounded" src="../../img/<?php echo $libro['imagen'] ;?>" width="50" alt="" srcset="">

            </td>

            <td>

            
          
            <form method="post">

            <input type="hidden" name="txtID" id="txtID" value="<?php echo $libro['id'];?>" />

            <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary" />

            <input type="submit" name="accion" value="Borrar" class="btn btn-danger" />

            </form>
        
            </td>

        </tr>
        <?php }  ?>
    </tbody>
</table>


</div>


<?php include("../template/pie.php"); ?>