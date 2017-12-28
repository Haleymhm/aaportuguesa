<?php 
// chequear si se llama directo al script.
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        //die('Usted no está autorizado a ejecutar este archivo directamente');
        exit;
    }
    if ($_SERVER['HTTP_REFERER'] == "") {
        echo "<script type='text/javascript'>window.location.href='index.php?view=login&msg_login=5'</script>";
//        echo "<script type='text/javascript'>window.location.href='index.php'</script>";
        exit;
    }
    
    $server=$_SERVER['SERVER_NAME']; // nombre del servidor web
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $view=$_GET["view"];    
    $pagina=$pag.'?view='.$view;

    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
    
    $datos_modificar= strtoupper($_SESSION['id']);

    $query="SELECT * FROM usuarios where cedula_usuario = '$datos_modificar'";
    $result = pg_query($query)or die(pg_last_error());
    $result_usuarios=pg_fetch_array($result);   
    pg_free_result($result);

    if (isset($_POST[save])) {
        $cedula = $_POST['cedula'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $usuario = $_POST['usuario'];
        $unidad = $_POST['cod_unidad']; 

    $query="SELECT update_usuario('$cedula','$nombre','$apellido','$unidad')";
        $result = pg_query($query)or die(pg_last_error());
        $result_update=pg_fetch_array($result);
        pg_free_result($result);
        
        $error="bien";
    }//fin del procedimiento modificar.
?>
<!-- sincronizar mensaje cuando de muestra al usuario -->
<?php if($div_menssage) { ?>                    
    <script type="text/javascript">
        function ver_msg(){
                    Effect.Fade('msg');
        }  
        setTimeout ("ver_msg()", 5000); //tiempo de espera en milisegundos
    </script>
 <?php } ?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div align="center">
                <font color="red" style="text-decoration:blink;">       
                    <?php echo $div_menssage;?>
                </font>
            </div>

            <div class="panel-heading">
                ACTUALIZAR DATOS DEL USUARIO
            </div>

<?php if ((isset($_POST[save])) and ($error=="bien")){  ?> <!-- Mostrar Mensaje -->

            <div class="form-group" align="center"> 
                <h3 class="info">   
                    <font size="2">                         
                        <h1>Datos Modificados con &eacute;xito</h1> 
                        <br />
                        <script type="text/javascript">
                            function redireccionar(){
                                window.location="?view=usuarios";
                            }  
                            setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                        </script>                       
                        [<a href="?view=usuarios" name="Continuar"> Continuar </a>]
                    </font>                         
                </h3>
            </div>

<?php   } else{     ?>   <!-- Mostrar formulario Original -->
                
            <div class="panel-body">
                <div class="row">
                    <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">
                        <input id="cedula" name="cedula" value="<?php echo $result_usuarios[cedula_usuario]; ?>" readonly="true" type="hidden"/>
                        <div class="col-lg-12">
                            <div class="form-group" autofocus="true">
                                <label>Nombres</label>
                                <input autofocus="true" value="<?php echo $result_usuarios[nombre_usuario]; ?>" class="form-control" type="text" id="nombre" name="nombre" maxlength="50" size="25"/>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>Apellidos</label>
                                <input value="<?php echo $result_usuarios[apellido_usuario]; ?>" class="form-control" type="text" id="apellido"  name="apellido" maxlength="50" size="25"/>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>Unidad/Departamento</label>
                                <select id="cod_unidad" readonly name="cod_unidad" size="0" class="form-control">
                                    <option  value="">----</option>         
                                    <?php
                                        $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
                                            $consulta=pg_query("select * from unidades order by cod_unidad");
                                            while ($array_consulta=pg_fetch_array($consulta)) {
                                                        if($array_consulta[0]==$result_usuarios[cod_unidad]){
                                                  echo '<option value="'.$array_consulta[0].'" selected="selected">'.$array_consulta[2].'</option>';
                                                        }else{
                                                          echo '<option value="'.$array_consulta[0].'" >'.$array_consulta[2].'</option>';  
                                                        }
                                            }
                                            pg_free_result($consulta);
                                    ?>
                                </select>   
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>Login Usuario</label>
                                <input value="<?php echo $result_usuarios[usuario]; ?>" type="text" id="usuario" readonly="true" name="usuario" class="form-control" maxlength="50" size="20"/>
                            </div>
                            <input type="submit" class="btn btn-default" name="save" value="  Guardar  " >
                            <input class="btn btn-default" type="button" onclick="javascript:window.location.href='?view=usuarios'" value="Cerrar" name="cerrar" /> 
                        </div>  
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="botones" align="center" >            
                         
                    </td>                                                   
                </tr> 
            <?php }  ?> 
        </table>
    </form>     
    <br>     
    </div>
</div>