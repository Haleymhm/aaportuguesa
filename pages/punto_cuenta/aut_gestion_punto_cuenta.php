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
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
	
    //codigo para colocar la hora.
    $hora=date("h").":".date("i")." ".date("a");

if (!isset($_GET['accion']))
{
    $cedula_usuario=$_SESSION['id'];
    if($_SESSION['nivel']==0){
        $datos_consulta = pg_query("SELECT * FROM punto_cuenta, usuarios WHERE punto_cuenta.cedula_usuario = usuarios.cedula_usuario order by punto_cuenta.id_punto") or die("No se pudo realizar la consulta a la Base de datos");
    }else{
        $datos_consulta = pg_query("SELECT * FROM punto_cuenta, usuarios WHERE punto_cuenta.cedula_usuario = usuarios.cedula_usuario and punto_cuenta.cedula_usuario='$cedula_usuario' order by punto_cuenta.id_punto") or die("No se pudo realizar la consulta a la Base de datos");    
    }

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
                REGISTRO DE PUNTOS DE CUENTAS
            </div>

<!--Estructura de Tabla de Contedinos de la Tabla usuario-->
            <div class="panel-body">
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-gestion">
                    <thead>
                        <tr>
                            <th width="8%">COD</th>
                            <th width="25%">ASUNTO</th>
                            <th>PRESENTANTE</th>
                            <th>FECHA</th>
                            <th width="25%">INSTRUCCION</th>
                            <th>ACCIONES</th>
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
                            <td>
                                <?php echo $resultados[id_punto];?>
                            </td>

                            <td>
                                <?php echo $resultados[asunto];?>
                            </td>

                            <td>
                                <?php echo $resultados[nombre_usuario];?><?php echo " "?><?php echo $resultados[apellido_usuario];?>
                            </td>

                            <td>
                                <?php echo $resultados[fecha_punto];?>
                            </td>

                            <td>
                                <?php echo $resultados[instruccion];?>
                            </td>

                            <td align="center"> 
                                <?php if(($resultados[condicion]==NULL)AND($_SESSION['nivel']<>0)){
                                echo '<a href="index2.php?view=punto_cuenta_drop&id_punto='.$resultados[id_punto].'" title="Pulse para eliminar el registro">
                                    <img border="0" src="images/borrar28.png" alt="borrar">
                                </a>';

                                echo '<a href="index2.php?view=punto_cuenta_update&id_punto='.$resultados[id_punto].'" title="Pulse para Modificar Los Datos">
                                    <img border="0" src="images/modificar.png" alt="borrar">
                                </a>';
                                
                                echo '<a href="index2.php?view=enviar_punto&id_punto='.$resultados[id_punto].'" title="Pulse para Enviar el Punto">
                                    <img border="0" src="images/enviar.png" alt="borrar">
                                </a>';
                                };?>

                                <?php if(($_SESSION['nivel']==0)AND($resultados[condicion]==0)){
                                echo '<a href="index2.php?view=responder_punto_cuenta&id_punto='.$resultados[id_punto].'" title="Pulse para Dar Respuesta">
                                    <img border="0" src="images/aprobar.png" alt="borrar">
                                </a>';
                                };?>

                                <?php if(($_SESSION['nivel']==0)AND($resultados[condicion]==1)){
                                echo '<a href="index2.php?view=responder_punto_cuenta&id_punto='.$resultados[id_punto].'" title="Pulse para Dar Respuesta">
                                    <img border="0" src="images/aprobar.png" alt="borrar">
                                </a>';
                                echo '<a href="index2.php?view=notificar_punto_cuenta&id_punto='.$resultados[id_punto].'" title="Pulse para Dar Notificar">
                                    <img border="0" src="images/notificar.png" alt="borrar">
                                </a>';
                                };?>

                                <?php if($resultados[condicion]==2){
                                echo '<a href="reportes/imprimir_punto_cuenta.php?id_punto='.$resultados[id_punto].'" title="Pulse para Imprimir" target="true">
                                    <img border="0" src="images/printer.png" alt="borrar">
                                </a>';
                                };?>
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
                                            <a href="index2.php?view=home" class="btn btn-default">
                                                Salir
                                            </a>
                                        </div>
                                    </div>
                                    <div style="float:right;">
                                        <div class="icon">
                                            <a href="index2.php?view=punto_cuenta_add" class="btn btn-default">
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