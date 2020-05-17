<?php
include_once "../conexion.php";
error_reporting(0);
if (isset($_POST))
{

  $componente = $_POST['componente_id2'];

  }
  @$sql4 = "SELECT * from accesorio_componente where componente_id = $componente " or die ("error". mysqli_error($conection));
  @$result4 = mysqli_query($conection, $sql4);
  ?>

  <!DOCTYPE html>
  <html lang="en">
  <head>


  </head>
  <body>


          <form >
  					<label for="accesorio">Seleccione el accesorio:</label>

  					<div class="multiselect">
  						<div class="selectBox"  onclick="showCheckboxes()">
  								<select id="accesorio_id" name="accesorio_id">
  										<option>Seleccione accesorio</option>
            			</select>
  								<div class="overSelect"></div>
  						 </div>
  						 <div id="checkboxes">

  							 <?php


   								while ($accesorio = mysqli_fetch_array($result4))
   								{

   										echo '<laber for=accesorio_componente><input type="checkbox" id="id_accesorio" name="checkbox[]" value='.$accesorio['accesorio_componente_descripcion'].'>'. $accesorio['accesorio_componente_descripcion'] .'</label>';

   								}
   							?>
  						 </div>
  					</div>


  					<div id="form_alert"></div>
            </div>


  				</form>
  				<script>
  						var expanded=false;
  						function showCheckboxes(){
  							var checkboxes = document.getElementById("checkboxes");
  							if (!expanded)
  							{
  									checkboxes.style.display="block";
  									expanded=true;
  							}else{
  								checkboxes.style.display="none";
  								expanded=false;
  							}
  						}

  	</section>
  </body>
  </html>
