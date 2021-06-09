<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Ingresar</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/styles.css" type="text/css"/>
    <script src="js/jquery.js"></script>
    <script src="js/login.js"></script>
  </head>
  <body>
    <div id="container_login">
      <div id="login"><img src="img/imagen.png" alt="Imagen"/>
        <form action="main.php">
          <p style="text-align: center;">
          <span id="msg" style="color: red; align-content: center;"></span>
          </p>
          <input type="text" name="username" id="usuarioI" placeholder="Usuario" class="inputs"/>
          <input type="password" name="password" id="passI" placeholder="Contraseña" class="inputs"/>
          <input type="button" value="ACCEDER" id="iniciar" class="boton-verde"/>
        </form>
      </div>
    </div>
  </body>
</html>