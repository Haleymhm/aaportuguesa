<?php

    // chequear si se llama directo al script.
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        //die('Usted no está autorizado a ejecutar este archivo directamente');
        exit;
    }
    if ($_SERVER['HTTP_REFERER'] == "")	{
        echo "<script type='text/javascript'>window.location.href='index.php?view=login&msg_login=5'</script>";
//        echo "<script type='text/javascript'>window.location.href='index.php'</script>";
        exit;
    }
    
    $redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $type=$_GET["view"];
    $pagina=$pag.'?view='.$type;

    //Conexion a la base de datos
    include("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

    $datos_consulta = pg_query("SELECT * FROM profesion order by id_profesion") or die(pg_last_error());
?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div align="center">
                <font color="red" style="text-decoration:blink;">
                    <?php $error_accion_ms[$error_cod]?>
                </font>
            </div> 

            <div class="panel-heading">
                    PROFESION SOLICITANTE:
            </div>

            <div class="panel-body">
<!--Estructura de Tabla de Contedinos de la Tabla usuario-->
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-gestion">
                    <thead>
                        <tr>
                            <th>C&oacute;digo</th>
                            <th>Profesiones</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

<?php
$xxx=0;
while($resultados = pg_fetch_array($datos_consulta)) {
	$xxx=$xxx+1;
?>
                    <tbody>
                        <tr>
                            <td  align="center">
                                 <?php echo $resultados[id_profesion];?>
                            </td>

                            <td>
                                <?php echo $resultados[nombre_profesion];?>
                            </td>

                            <td align="center"> 
                                <a onclick="return confirm('Esta seguro que desea eliminar el registro?');" href="index2.php?view=profesion_solicitante_drop&id_profesion=<?php echo $resultados[id_profesion];?>" title="Pulse para eliminar el registro">
                                    <img border="0" src="images/borrar28.png" alt="borrar">
                                </a>
                                <a href="index2.php?view=profesion_solicitante_update&id_profesion=<?php echo $resultados[id_profesion];?>" title="Pulse para Modificar los datos registrados">
                                    <img border="0" src="images/modificar.png" alt="borrar">
                                </a>  
                            </td>
                        </tr>
                    </tbody>

<?php } ?>

                    <tfoot>
                        <tr align="center">
                            <th colspan="3" align="center">
                                <div id="cpanel">
                                    <div style="float:right;">
                                        <div class="icon">
                                            <a href="index2.php?view=home" class="btn btn-default">
                                                Salir
                                            </a>
                                        </div>
                                    </div>
                            
                                    <div style="float:right;">
                                        <div class="icon">
                                            <a href="index2.php?view=profesion_solicitante_add" class="btn btn-default">
                                                Agregar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="vendor/jquery/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="vendor/metisMenu/metisMenu.min.js"></script>

<!-- DataTables JavaScript -->
<script src="vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
<script src="vendor/datatables-responsive/dataTables.responsive.js"></script>

<!-- Custom Theme JavaScript -->
<script src="dist/js/sb-admin-2.js"></script>

<!-- Page-Level Demo Scripts - Tables - Use for reference -->
<script>
$(document).ready(function() {
    $('#dataTables-gestion').DataTable({
        responsive: true
    });
});
</script>

<?php
pg_free_result($datos_consulta);
pg_close();
?>
