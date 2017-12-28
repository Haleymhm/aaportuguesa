<?php 
    // Configuracion General del Sistema

    // Nombre de la session (se puede dejar este mismo)
    $usuarios_sesion="autenticador_aap";

    // Datos conexion a la Base de datos (MySql)
    $sql_host="localhost"; // Host, nombre del servidor o IP del servidor Mysql.
    $sql_usuario="postgres"; // Usuario de Mysql O PostgreSQL
    $sql_pass="postgres"; // contrase� de Mysql o PostgreSQL
    $sql_db="aaportuguesa"; // Base de datos que se usuario o PostgreSQL
    $sql_tabla="usuarios"; // Nombre de la tabla que contendra los datos de los usuarios
    $sql_sigla="saap"; //estan son las siglas para estructurar los servidores de producion para las unidades moviles
    $sistema_name="PORTUGUESA DIGITAL"; // Nombre del sistema
    $empresa="Visión Hannah c.a"; // Nombre de la empresa
    $enlace_logo="http://www.visionhannah.com"; // Pagina Web de la empresa
    $id_empresa="G-20000158-5";
    
    // Datos de localidad
    $cod_pais='058';
    $cod_estado='018';
    $cod_municipio='004';
    
    // Datos de Configuracion para los sms y correos electronicos
    $name_email="atencion.soberanoportuguesa@gmail.com";  //    "atencion.sac@gmail.com";
//    $send_sms=1;    // Activar el envio de sms al Usuario
//    $send_email=1;    // Activar el envio de email al Usuario
    // $ip_server="201.249.48.61"; Ip Servidor de Alojamiento
    $ip_server="localhost"; // Ip Servidor de Alojamiento
    $dir_name="aaportuguesa"; // Nombre del Directorio de Alojamiento del Sistema
    $var_iva=12;
    $var_alicuota="30.00";
?>
