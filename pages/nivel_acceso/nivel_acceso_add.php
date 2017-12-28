<?php

if (isset($_GET['error']))
{
    $error_accion_ms[0]= "La Empresa No puede ser Borrada.<br>Si desea borrarlo, primero cree uno nuevo.";
    $error_accion_ms[1]= "Datos incompletos.";
    $error_accion_ms[2]= "Contrase&ntilde;as no coinciden.";
    $error_accion_ms[3]= "El Nivel de Acceso ha de ser num&eacute;rico.";
    $error_accion_ms[4]= "El Usuario ya est&aacute; registrado.";
    $error_accion_ms[5]= "Ya existe un usuario con el n&uacute;mero de c&eacute;dula que usted introdujo.";
    $error_accion_ms[6]= "El n&uacute;mero de c&eacute;dula que usted introdujo no es v&aacute;lido.";
    $error_cod = $_GET['error'];
}
    $server=$_SERVER['SERVER_NAME']; // nombre del servidor web
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $view=$_GET["view"];	
    $pagina=$pag.'?view='.$view;

    //Conexion a la base de datos
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

if (isset($_POST[save]))
{
    $codigo_nivel = $_POST['codigo_nivel'];
    $nombre_nivel = $_POST['nombre_nivel'];

    if (($nombre_nivel==""))
    {
        $error='<div align="left">
                    <h3 class="error">
                        <font color="red" style="text-decoration:blink;">
                            Error: Datos Incompletos, por favor verifique los datos!
                        </font>
                    </h3>
                </div>';
    }
    else
    {
        require("conexion/aut_config.inc.php");
        $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");	

        $error="bien";

        $inserta_registro = pg_query("insert into niveles_acceso (codigo_nivel, nombre_nivel) values ($codigo_nivel,'$nombre_nivel')") or die("NO SE PUEDE INSERTAR EL NIVEL DE ACCESO EN LA BASE DE DATOS.");	
        $result_insert=pg_fetch_array($inserta_registro);	
        pg_free_result($inserta_registro);
        $resultado_insert=$result_insert[0];
        pg_close();	
        //exit;
    } 		   
}//fin del add        
?>

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
                    <?php $error_accion_ms[$error_cod]?>
                </font>
            </div>  

            <div class="panel-heading">
                REGISTRAR NUEVO NIVEL DE ACCESO:
            </div>
                
<?php if ((isset($_POST[save])) and ($error=="bien")){	?> <!-- Mostrar Mensaje -->
                
            <div class="form-group" align="center"> 
                <h3 class="info">   
                    <font size="2">                     
                        <h1>Datos registrados con &eacute;xito</h1>
                        <br />
                        <script type="text/javascript">
                            function redireccionar(){
                                window.location="?view=nivel_acceso";
                            }  
                            setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                        </script> 						
                        [<a href="?view=nivel_acceso" name="Continuar"> Continuar </a>]
                    </font>							
                </h3>
            </div> 
                
<?php	}else{ 	?>   <!-- Mostrar formulario Original --> 
                
            <div class="panel-body">
                <div class="row">
                    <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">
                        <div class="col-lg-12">
                            <div class="form-group" autofocus="true">
                                <label>CODIGO DEL NIVEL DE ACCESO</label>
                                <input class="form-control" type="text" id="codigo_nivel" name="codigo_nivel" maxlength="20" size="5"/>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>NOMBRE DEL NIVEL DE ACCESO</label>
                                <input class="form-control" type="text" id="nombre_nivel" name="nombre_nivel" maxlength="45" size="45"/>
                            </div>
                            <input type="submit" class="btn btn-default" name="save" value="  Guardar  " >
                            <input  class="btn btn-default" type="button" onclick="javascript:window.location.href='?view=nivel_acceso'" value="Cerrar" name="cerrar" />  
                        </div>
                    </form>
                </div>
            </div>

<?php }  ?> 
                
        </div>
    </div>
</div>