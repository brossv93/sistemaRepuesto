<nav>
  <ul>
    <li><a href="index.php"><i class="fas fa-home"></i> Inicio</a></li>
    <?php
      if ($_SESSION['rol']==1)
      {

     ?>

    <li class="principal">
      <a href="#"><i class="fas fa-users"></i> Usuarios</a>
      <ul>
        <li><a href="registro_usuario.php"><i class="fas fa-user-plus"></i> Nuevo Usuario</a></li>
        <li><a href="lista_usuario.php"><i class="fas fa-clipboard-list"></i> Lista de Usuarios</a></li>
      </ul>
    </li>

    <?php  } ?>

    <?php
      if ($_SESSION['rol']==1)
      {

     ?>
    <li class="principal">
      <a href="#"><i class="fas fa-truck"></i> Lote</a>
      <ul>
        <li><a href="registro_lote.php"><i class="fas fa-user-plus"></i> Nuevo Lote</a></li>
        <li><a href="lista_lote.php"><i class="fas fa-clipboard-list"></i> Lista de Lote</a></li>
      </ul>
    </li>
      <?php  } ?>
    <li class="principal">
      <a href="#"><i class="fas fa-cart-plus"></i> Productos</a>
      <ul>
        <li><a href="registro_producto.php"><i class="fas fa-cart-plus"></i> Accesorio Minorista</a></li>
        <li><a href="registro_producto_mayorista.php"><i class="fas fa-cart-plus"></i> Nuevo Producto Mayorista</a></li>
        <li><a href="registro_modelo.php"><i class="fas fa-cart-plus"></i> Registro Modelo</a></li>
        <li><a href="registro_marca.php"><i class="fas fa-cart-plus"></i> Registro Marca</a></li>
        <li><a href="registro_lado.php"><i class="fas fa-cart-plus"></i> Registar Lado</a></li>
        <li><a href="registro_componente.php"><i class="fas fa-cart-plus"></i> Registro componente</a></li>
        <li><a href="lista_accesorio_componente.php"><i class="fas fa-clipboard-list"></i> Lista de Acceso. Compone.</a></li>
        <li><a href="lista_componente.php"><i class="fas fa-clipboard-list"></i> Lista Componente</a></li>
        <li><a href="lista_producto.php"><i class="fas fa-clipboard-list"></i> Lista de Accesorios</a></li>
        <li><a href="lista_modelo.php"><i class="fas fa-clipboard-list"></i> Lista de modelo</a></li>
        <li><a href="lista_marca.php"><i class="fas fa-clipboard-list"></i> Lista de marca</a></li>
        <li><a href="lista_lado.php"><i class="fas fa-clipboard-list"></i> Lista de lado</a></li>
      </ul>
    </li>
    <li class="principal">
      <a href="#"><i class="fas fa-book"></i> Venta</a>
      <ul>
        <li><a href="nueva_venta.php"><i class="fas fa-file-alt"></i> Nueva Venta</a></li>
        <li><a href="ventas.php"><i class="fas fa-newspaper"></i> Lista de Ventas</a></li>
      </ul>
    </li>
  </ul>
</nav>
