<?php
    define('INCLUDE_CHECK',true); // Activamos como Principal, para verificar los de mas formularios como dependientes.
    require("conexion/aut_config.inc.php"); //validar sessiones del usuario
    
    $view=$_GET["view"];
    
    if ($view){ 
        if($view=="logoff"){
            session_name($usuarios_sesion);
            session_start();
            session_unset();
            session_destroy();
        }
    }       
    // Error reporting:
    error_reporting(E_ALL^E_NOTICE);
?> 

<!DOCTYPE html>
<html >
<head>
    <meta charset="UTF-8">
    <title><?php echo $sistema_name;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
    <link rel='stylesheet prefetch' href='css/css.css'>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="images/favicon.png" />
</head>

<body>
    <div class="cont">
        <div align="center">    
            <?php                   
                // Mostrar error de Autentificaci�n.
                include ("aut_mensaje_login.inc.php");
                if (isset($_GET['msg_login'])){
                    $msg=$_GET['msg_login'];
                    //echo '<div class="system-message" align="left"><h2>Error: '. $msg_login[$msg].'</h2></div>';
                    echo '<div align="left"><h3 class="error">Error: '.$msg_login[$msg].'</h3></div>';
                }
            ?>
        </div>

        <div class="demo">
          <div class="login">
            <div class="login__check"></div>
            <form action="index2.php?view=home" method="POST" name="loginForm" id="loginForm">
            <div class="login__form">
                <div class="login__row">
                    <svg class="login__icon name svg-icon" viewBox="0 0 20 20">
                        <path d="M0,20 a10,8 0 0,1 20,0z M10,0 a4,4 0 0,1 0,8 a4,4 0 0,1 0,-8" />
                    </svg>
                    <input name="user" id="user" type="text" class="login__input name" placeholder="USUARIO"/>
                </div>

                <div class="login__row">
                    <svg class="login__icon pass svg-icon" viewBox="0 0 20 20">
                        <path d="M0,20 20,20 20,8 0,8z M10,13 10,16z M4,8 a6,8 0 0,1 12,0" />
                    </svg>
                    <input name="pass" type="password" class="login__input pass" placeholder="PASSWORD"/>
                </div>

                <div class="login__row">
                    <svg class="login__icon pass svg-icon" viewBox="0 0 20 20">
                        <path d="M0,20 20,20 20,8 0,8z M10,13 10,16z M4,8 a6,8 0 0,1 12,0" />
                    </svg>
                    <input name="code" type="text" class="login__input pass" placeholder="Codigo" />
                
                    <img id="siimage" align="left" width="100" height="30" vspace="3" style="padding-right: 0px; padding-top: 0px; padding-bottom: 0px; padding-left: 1px; vertical-align: middle;  margin-top: 0px; border: 0" src="captcha/securimage_show.php?sid=<?php echo md5(time()) ?>"/>
                
                    <a tabindex="-1" style="border-style: none" href="#" title="Actualizar Imagen" onClick="document.getElementById('siimage').src = 'captcha/securimage_show.php?sid=' + Math.random(); return false">
                        <img src="images/refresh.png" alt="Actualizar Imagen" border="0" onClick="this.blur()" align="bottom" style="padding-right: 0px; padding-top: 0px; padding-bottom: 0px; padding-left: 0px; vertical-align: middle;   margin-top: 6px; border: 0" />
                    </a>
                            
                </div>

              <button type="submit" class="login__submit">ENTRAR</button>
              <a href="registrate/index.php" style="font-size: 18px; color:#fff; font-family: Times New Roman;" target="true">REGISTRATE GRATIS AQUI</a> 
            </div>
            </form>
          </div>

          <div class="app__logout">
            <svg class="app__logout-icon svg-icon" viewBox="0 0 20 20">
              <path d="M6,3 a8,8 0 1,0 8,0 M10,0 10,12"/>
            </svg>
          </div>
        </div>
    </div>
</div>
</body>
</html>