<?php
    $redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $type=$_GET["view"];
    $pagina=$pag.'?view='.$type;

    //Conexion a la base de datos
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

    //codigo para colocar la hora.
    $hora=date("h").":".date("i")." ".date("a");

if (!isset($_GET['accion']))
{
    $datos_consulta = pg_query("SELECT * FROM niveles_acceso order by niveles_acceso.codigo_nivel") or die("No se pudo realizar la consulta a la Base de datos");
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
                NIVELES DE ACCESO REGISTRADOS
            </div>

<!--Estructura de Tabla de Contedinos de la Tabla usuario-->
            <div class="panel-body">
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-gestion">
                    <thead>
                        <tr>
                            <th>Codigo</th>
                            <th>Nivel de Acceso</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

<?php
$xxx=0;
while($resultados = pg_fetch_array($datos_consulta))
{
    $xxx=$xxx+1;
?>
                    <tbody>
                        <tr class="gradeA">
                            <td  align="center">
                                 <?php echo $resultados[codigo_nivel];?>
                            </td>

                            <td>
                                <?php echo $resultados[nombre_nivel];?>
                            </td>

                            <td align="center"> 
                                <a onclick="return confirm('Esta seguro que desea eliminar el registro?');" href="index2.php?view=nivel_acceso_drop&codigo_nivel=<?php echo $resultados[codigo_nivel];?>" title="Pulse para eliminar el registro">
                                    <img border="0" src="images/borrar28.png" alt="borrar">
                                </a>
                                <a href="index2.php?view=nivel_acceso_update&codigo_nivel=<?php echo $resultados[codigo_nivel];?>" title="Pulse para Modificar el Nivel de Acceso">
                                    <img border="0" src="images/modificar.png" alt="borrar">
                                </a>  
                            </td>
                        </tr>
                    </tbody>

<?php } ?>

                    <tfoot>
                        <tr align="center">
                            <th colspan="6" align="center">
                                <div id="cpanel">
                                    <div style="float:right;">
                                        <div class="icon">
                                            <a href="index2.php?view=empresa" class="btn btn-default">
                                            Salir
                                            </a>
                                        </div>
                                    </div>

                                    <div style="float:right;">
                                        <div class="icon">
                                            <a href="index2.php?view=nivel_acceso_add" class="btn btn-default">
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
}
?>