<?php include("../template/cabecera.php"); ?>

<?php

$txtDNI=(isset($_POST['txtDNI']))?$_POST['txtDNI']:"";
$txtNombre=(isset($_POST['txtNombre']))?$_POST['txtNombre']:"";
$txtCurso=(isset($_POST['txtCurso']))?$_POST['txtCurso']:"";
$txtFecha_entrega=(isset($_POST['txtFecha_entrega']))?$_POST['txtFecha_entrega']:"";
$txtxtFecha_devolucion=(isset($_POST['txtFecha_devolucion']))?$_POST['txtFecha_devolucion']:"";
$accion=(isset($_POST['accion']))?$_POST['accion']:"";

include("../config/bd.php");

switch($accion){

        case"Agregar":
            $sentenciaSQL= $conexion->prepare("INSERT INTO prestamos (dni,nombre,curso,fecha_entrega,fecha_devolucion ) VALUES (:dni,:nombre,:curso,:fecha_entrega,:fecha_devolucion);");
            $sentenciaSQL->bindParam(':dni',$txtDNI);
            $sentenciaSQL->bindParam(':nombre',$txtNombre);
            $sentenciaSQL->bindParam(':curso',$txtCurso);
            $sentenciaSQL->bindParam(':fecha_entrega',$txtFecha_entrega);
            $sentenciaSQL->bindParam(':fecha_devolucion',$txtxtFecha_devolucion);
            $sentenciaSQL->execute();
            break;

            case"Modificar":
            $sentenciaSQL= $conexion->prepare("UPDATE prestamos SET nombre=:nombre , curso=:curso , fecha_entrega=:fecha_entrega , fecha_devolucion=:fecha_devolucion WHERE dni=:dni");  
            $sentenciaSQL->bindParam(':nombre',$txtNombre);
            $sentenciaSQL->bindParam(':curso',$txtCurso);
            $sentenciaSQL->bindParam(':fecha_entrega',$txtFecha_entrega);
            $sentenciaSQL->bindParam(':fecha_devolucion',$txtxtFecha_devolucion);  
            $sentenciaSQL->bindParam(':dni',$txtDNI); 
            $sentenciaSQL->execute();
            break;

        case"Cancelar":
            header("Location:prestamos.php");
            break;

     case"Seleccionar":
            $sentenciaSQL= $conexion->prepare("SELECT * FROM prestamos WHERE dni=:dni");  
            $sentenciaSQL->bindParam(':dni',$txtDNI);
            $sentenciaSQL->execute();
            $prestamos=$sentenciaSQL->fetch(PDO::FETCH_LAZY); 
            
            $txtNombre=$prestamos['nombre'];
            $txtCurso=$prestamos['curso'];
            $txtFecha_entrega=$prestamos['fecha_entrega'];
            $txtxtFecha_devolucion=$prestamos['fecha_devolucion'];
            break;
            

         case"Borrar":
                  //  echo "Presionado botón Borrar";
                  $sentenciaSQL= $conexion->prepare("DELETE FROM prestamos WHERE dni=:dni");
                  $sentenciaSQL->bindParam(':dni',$txtDNI);
                  $sentenciaSQL->execute();
                  $libro=$sentenciaSQL->fetch(PDO::FETCH_LAZY); 
                  $sentenciaSQL= $conexion->prepare("DELETE FROM prestamos WHERE dni=:dni");
                  $sentenciaSQL->bindParam(':dni',$txtDNI);
                  $sentenciaSQL->execute();
                  header("Location:prestamos.php");
                    break;
                    
}


$sentenciaSQL= $conexion->prepare("SELECT * FROM prestamos");
$sentenciaSQL->execute();
$listadeprestamos=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);


?>


<div class="col-md-5">

<div class="card">
    <div class="card-header">
        Datos de Libro
    </div>

    <div class="card-body">
    <form method="POST" enctype="multipart/form-data" >

<div class = "form-group">
<label for="txtDNI">DNI</label>
<input type="text" class="form-control" value="<?php echo $txtDNI?>" name="txtDNI" id="txtDNI" placeholder="DNI">
</div>


<div class = "form-group">
<label for="txtNombre">Nombre:</label>
<input type="text" class="form-control"  value="<?php echo $txtNombre?>" name="txtNombre" id="txtNombre" placeholder="Nombre del Alumno">
</div>

<div class = "form-group">
<label for="txtCurso">Curso:</label>
<input type="text" class="form-control"  value="<?php echo $txtCurso?>" name="txtCurso" id="txtNombre" placeholder="Curso del Alumno">
</div>

<div class = "form-group">
<label for="txtFecha_entrega">Fecha de entrega </label>
<input type="text" class="form-control"  value="<?php echo $txtFecha_entrega?>" name="txtFecha_entrega" id="txtFecha_entrega" placeholder="Fecha de entrega del libro">
</div>

<div class = "form-group">
<label for="txtFecha_devolucion">Fecha de devolucion </label>
<input type="text" class="form-control"  value="<?php echo $txtxtFecha_devolucion?>" name="txtFecha_devolucion" id="txtFecha_devolucion" placeholder="Fecha de devolucion del libro">
</div>

<div class = "form-group">

</div>


<div class="btn-group" role="group" aria-label="">
    <button type="submit" name="accion" value="Agregar" class="btn btn-success">Agregar</button>
    <button type="submit" name="accion" value="Modificar" class="btn btn-warning">Modificar</button>
    <button type="submit" name="accion" value="Cancelar" class="btn btn-info">Cancelar</button>
</div>

</form> 

    </div>

   
</div>
    


</div>



<div class="col-md-7">


<table class="table table-bordered">
    <thead>
             <tr>
                 <th>DNI</th>
                 <th>Nombre</th>
                 <th>Curso</th>
                 <th>Fecha de entrega </th>
                 <th>Fecha de devolucion</th>
                 <th>Accion</th>
            </tr>
            </thead>
            <tbody>
        <?php foreach($listadeprestamos as $prestamos) { ?>
            <tr>
                <td><?php echo $prestamos['dni'];?></td>
                <td><?php echo $prestamos['nombre']; ?></td>
                <td><?php echo $prestamos['curso']; ?></td>
                <td><?php echo $prestamos['fecha_entrega']; ?></td>
                <td><?php echo $prestamos['fecha_devolucion']; ?></td>
                <td>

            Seleccionar | Borrar
          
                <form method="post">

                <input type="hidden" name="txtDNI" id="txtDNI" value="<?php echo $prestamos['dni'];?>" />

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