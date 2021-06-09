<div id="openModal" class="modalDialog">
  <div class="advertencia" id="r_cliente">
    <div class="span">
      <header>
        <h1>EDITAR CLIENTE</h1>
      </header>
      <form action="#" id="FormClientes">
        <table>
          <tr>
            <td>
              <h4>Cedula</h4>
              <input type="number" name="cedula" id="Ccedula" class="inputs form" autocomplete="off">
            </td>
          </tr>
          <tr>
            <td>
              <h4>Nombre</h4>
              <input type="text" name="nombre" id="Cnombre" class="inputs form" autocomplete="off">
            </td>
          </tr>
          <tr>
            <td>
              <h4>Apellido</h4>
              <input type="text" name="apellidos" id="Capellidos" class="inputs form" autocomplete="off">
            </td>
          </tr>
          <tr>
            <td>
              <h4>Telefono</h4>
              <input type="number" name="telefono" id="Ctelefono" class="inputs form" autocomplete="off">
            </td>
          </tr>
          <tr>
            <td>
              <h4>Direccion</h4>
              <input type="text" name="direccion" id="Cdireccion" class="inputs form" autocomplete="off">
            </td>
          </tr>
        </table>
      </form>
      <div class="options">
        <input onclick="location.href='main.php'" type="submit" value="CANCELAR" class="boton-rojo"/>
        <input onclick="guardarCliente()" type="submit" value="GUARDAR" class="boton-verde"/>
      </div>
    </div>
  </div>
</div>
