<?php //seccion de mensajes del sistema.
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
    
    $server=$_SERVER['SERVER_NAME']; // nombre del servidor web
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $view=$_GET["view"];	
    $pagina=$pag.'?view='.$view;

    //Conexion a la base de datos
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

    if (isset($_GET['rif_empresa'])){
        $datos_modificar= $_GET['rif_empresa'];

        //se le hace el llamado a la funcion de insertar.	
        $datos_consulta = pg_query("SELECT * FROM empresa where rif_empresa = '$datos_modificar'") or die("No se pudo realizar la consulta a la Base de datos");

        $resultados1=pg_fetch_array($datos_consulta);
        pg_free_result($datos_consulta);
        pg_close();
    }
 
    if (isset($_POST[save])){//se resive los datos a ser modificados
        $rif_empresa = $_POST['rif_empresa'];
        $nombre_empresa = $_POST['nombre_empresa'];
        $nombre_administrador = $_POST['nombre_administrador'];
        $ciudad = $_POST['ciudad'];
        $telefono_oficina = $_POST['telefono_oficina'];
        $telefono_fax = $_POST['telefono_fax'];
        $pagina_web = $_POST['pagina_web'];
        $correo_electronico = $_POST['correo_electronico'];
        $direccion_empresa = $_POST['direccion_empresa'];
        $siglas_empresa = $_POST['siglas_empresa'];
        $send_sms = $_POST['send_sms'];
        $send_email = $_POST['send_email'];
        $sms_nueva_solicitud = $_POST['sms_nueva_solicitud'];
        $sms_programar_ticket = $_POST['sms_programar_ticket'];
        $sms_reprogramar_ticket = $_POST['sms_reprogramar_ticket'];
        $sms_escalar_ticket = $_POST['sms_escalar_ticket'];
        $sms_completar_ticket = $_POST['sms_completar_ticket'];
        $sms_cancelar_ticket = $_POST['sms_cancelar_ticket'];
        $sms_anular_ticket = $_POST['sms_anular_ticket'];

        $datos_logo = $_POST['$logo_empresa']; //este es para saber si la caja de texto tiene un logo nuevo para ser modificado

        //aqui es para los logos de la empresa
        $logo_empresa = $_POST['rif_empresa']; //este es para copiar el archivo del logo de la empresa
        $prefijo = $logo_empresa; //logo que va a ser modificado

        $logo_name = $HTTP_POST_FILES['logo_empresa']['name'];
        $tipo_archivo = $HTTP_POST_FILES['logo_empresa']['type'];
        $tamano_archivo = $HTTP_POST_FILES['logo_empresa']['size']; 

        //se le hace el llamado a la funcion de insertar.	
        $query = "SELECT update_empresa('$rif_empresa','$nombre_empresa','$nombre_administrador','$ciudad','$telefono_oficina','$telefono_fax','$pagina_web','$correo_electronico','$logo_empresa','$direccion_empresa','$siglas_empresa','$send_sms','$send_email','$sms_nueva_solicitud','$sms_programar_ticket','$sms_reprogramar_ticket','$sms_escalar_ticket','$sms_completar_ticket','$sms_cancelar_ticket','$sms_anular_ticket')";		
        $result = pg_query($query)or die(pg_last_error());
        $result_update=pg_fetch_array($result);
        pg_free_result($result);

        $error="bien";
        
        if ($datos_logo != ""){	
            unlink("logo_empresa/$datos_borrar");
        }else{
            // guardamos el archivo a la carpeta files
            $destino =  "logo_empresa/".$prefijo;
            if (copy($_FILES['logo_empresa']['tmp_name'],$destino)){
                $status = "Archivo subido: <b>".$logo_name."</b>";
            }
        }
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
                ACTUALIZAR DATOS DE LA EMPRESA
            </div>

<?php if ((isset($_POST[save])) and ($error=="bien")){  ?> <!-- Mostrar Mensaje -->

            <div class="form-group" align="center"> 
                <h3 class="info">   
                    <font size="2">                     
                        <h1>Datos Actualizado con &eacute;xito</h1>
                        <br />
                        <script type="text/javascript">
                            function redireccionar(){
                                window.location="?view=empresa";
                            }  
                            setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                        </script>                       
                        [<a href="?view=empresa" name="Continuar"> Continuar </a>]
                    </font>                         
                </h3>
            </div> 

<?php }else{ ?>   <!-- Mostrar formulario Original --> 

            <div class="panel-body">
                <div class="row">
                    <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">
                        <input value="<?php echo $resultados1[rif_empresa]; ?>" type="hidden" id="rif_empresa" name="rif_empresa">
                        <div class="col-lg-6">
                            <h1>Datos de la Empresa</h1>
                            <div class="form-group" autofocus="true">
                                <label>Rif de la Empresa</label>
                                <input class="form-control" disabled value="<?php echo $resultados1[rif_empresa]; ?>" type="text" id="rif_empresa1" name="rif_empresa1">
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>Nombre de la Empresa</label>
                                <input  class="form-control" autofocus="true" value="<?php echo $resultados1[nombre_empresa]; ?>" type="text" id="nombre_empresa" name="nombre_empresa" maxlength="80" size="80"/>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>Siglas de la Empresa</label>
                                <input class="form-control" value="<?php echo $resultados1[siglas_empresa];?>" type="text" id="siglas_empresa" name="siglas_empresa" maxlength="25" size="25"/>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label> Nombre del Administrador</label>
                                <input class="form-control" value="<?php echo $resultados1[nombre_administrador];?>" type="text" id="nombre_administrador"  name="nombre_administrador" maxlength="25" size="25"/>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>Ciudad</label>
                                <input class="form-control" value="<?php echo $resultados1[ciudad];?>" type="text" id="ciudad" name="ciudad" maxlength="20" size="20"/>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>Telefono de Oficina</label>
                                <input value="<?php echo $resultados1[telefono_oficina];?>" class="form-control" placeholder="(0212)-1234567" title="Ej.: (0212)-1234567" type="text" id="telefono_oficina" name="telefono_oficina" maxlength="12" size="12"/>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>Telefono Fax</label>
                                <input value="<?php echo $resultados1[telefono_fax];?>" class="form-control" placeholder="(0212)-1234567" title="Ej.: (0212)-1234567" type="text" id="telefono_fax" name="telefono_fax" maxlength="12" size="12"/>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>Pagina Web</label>
                                <input value="<?php echo $resultados1[paginia_web];?>" class="form-control" placeholder="www.nombredeldominio.com" type="text" id="pagina_web" name="pagina_web" maxlength="25" size="25"/>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>Correo Electronico</label>
                                <input value="<?php echo $resultados1[correo_electronico];?>" class="form-control" placeholder="minombre@ejemplo.com" type="text" id="correo_electronico" name="correo_electronico" maxlength="30" size="30"/>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>Direcci&oacute;n de Empresa</label>
                                <textarea class="form-control" name="direccion_empresa" id="direccion_empresa" cols="70" rows="3"><?php echo $resultados1[direccion_empresa];?></textarea>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>Logo de la Empresa</label>
                                <input type="file" id="logo_empresa" name="logo_empresa" maxlength="30" size="30" class="inputbox">
                                <p class="help-block">(.jpg, m&aacute;ximo 100Kb)</p>
                            </div>

                            <div class="form-group" autofocus="true">
                                <img src="images/logo_empresa/<?php echo $resultados1[rif_empresa];?>" height="110"/>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <h1>SMS y EMAIL</h1>
                            <div class="form-group" autofocus="true">
                                <label>Activar SMS</label>
                                <select class="form-control" id="send_sms" name="send_sms">
                                    <?php
                                        if($resultados1[send_sms]=="1") {
                                            echo '<option value="'.$resultados1[send_sms].'" selected="selected">Si</option>';
                                            echo '<option value="0">No</option>';
                                        }else{
                                            echo '<option value="'.$resultados1[send_sms].'" selected="selected">No</option>';
                                            echo '<option value="1">Si</option>';
                                        }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>Activar Email</label>
                                <select class="form-control" id="send_email" name="send_email">
                                    <?php
                                        if($resultados1[send_email]=="1") {
                                            echo '<option value="'.$resultados1[send_email].'" selected="selected">Si</option>';
                                            echo '<option value="0">No</option>';
                                        }else{
                                            echo '<option value="'.$resultados1[send_email].'" selected="selected">No</option>';
                                            echo '<option value="1">Si</option>';
                                        }
                                    ?>
                                    </select>   
                            </div>
                            
                            <div class="form-group" autofocus="true">
                                <label>SMS Para Nueva Solicitud</label>
                                <textarea maxlength="140" class="form-control" name="sms_nueva_solicitud" id="sms_nueva_solicitud" cols="70" rows="2"><?php echo $resultados1[sms_nueva_solicitud];?></textarea>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>SMS Para Programar Ticket</label>
                                <textarea maxlength="140" class="form-control" name="sms_programar_ticket" id="sms_programar_ticket" cols="70" rows="2"><?php echo $resultados1[sms_programar_ticket];?></textarea>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>SMS Para Reprogramar Ticket</label>
                                <textarea maxlength="140" class="form-control" name="sms_reprogramar_ticket" id="sms_reprogramar_ticket" cols="70" rows="2"><?php echo $resultados1[sms_reprogramar_ticket];?></textarea>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>SMS Para Escalar Ticket</label>
                                <textarea maxlength="140" class="form-control" name="sms_escalar_ticket" id="sms_escalar_ticket" cols="70" rows="2"><?php echo $resultados1[sms_escalar_ticket];?></textarea>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>SMS Completar Ticket</label>
                                <textarea maxlength="140" class="form-control" name="sms_completar_ticket" id="sms_completar_ticket" cols="70" rows="2"><?php echo $resultados1[sms_completar_ticket];?></textarea>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>SMS Cancelar Ticket</label>
                                <textarea maxlength="140" class="form-control" name="sms_cancelar_ticket" id="sms_cancelar_ticket" cols="70" rows="2"><?php echo $resultados1[sms_cancelar_ticket];?></textarea>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>SMS Anular Ticket</label>
                                <textarea maxlength="140" class="form-control" name="sms_anular_ticket" id="sms_anular_ticket" cols="70" rows="2"><?php  echo $resultados1[sms_anular_ticket];?></textarea>
                            </div>
                            <button type="submit" name="save" class="btn btn-default">Guardar</button>
                            <input  class="button" type="button" onclick="javascript:window.location.href='?view=empresa'" value="Cerrar" name="cerrar" />
                        </div>
                    </form>  
                </div>
            </div>
<?php } ?> 
        </div>
    </div>
</div>