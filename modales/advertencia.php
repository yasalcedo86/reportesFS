<div class="advertencia" id="advertencia">
  <div class="span">
    <header>
      <h1>ADVERTENCIA</h1>
    </header>
    <article>
      <p>Esta a punto de cerrar la sesión, ¿desea continuar?</p>
    </article>
    <div class="options">
      <button class="boton-rojo" onclick="myFunction();">CANCELAR</button>
      <button class="boton-verde" onclick="location.href='cerrar.php'">ACEPTAR</button>
    </div>
  </div>
</div>

<script>
  x = document.getElementById("advertencia");

  function myFunction () {
    x.style.display = "none";
  }
</script>