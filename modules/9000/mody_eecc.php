<style type="text/css">
  .boton{
        font-size:10px;
        font-family:Verdana,Helvetica;
        font-weight:bold;
        color:white;
        background:#005177;
        border:0px;
        width:80px;
        height:19px;
       }
</style>
<?php

 ?><div class="section">
   <div class="outerbox">
   <div class="mainHeading"><h2>EECC a cambiar</h2></div>
<form method="post" action="?menu=9011" id="idfm" class='data-ro'>
<div><input type="text" id="busqotecc" name="busqotecc" size="10" maxlength='10'style="width:200px;height:15px" tabindex="1" title=""/></div>
<div class="actionbar">
  <button class="boton" type="submit" id="buscaroteecc" name="buscaroteecc">Buscar</button>
</div>
</form>

 <table cellspacing="0" cellpadding="0" class="data-table" border="1">
   <thead>
  <tr>
    <th>id orden</th>
      <th>EECC actual</th>
      <th>EECC Nuevo</th>
      <th>Accion</th>
  </tr>
</thead>
    <tbody id="bandejaot">
      <?php
      $idot=$_POST['busqotecc'];
      if($idot==''){
        $idot='0';
      }else{
      $idot=$_POST['busqotecc'];
    }
      $sql="SELECT o.id, o.idcontrato, o.idzona, e.nombre FROM ordenes o inner join contratos c on c.id=o.idcontrato and c.idzona=o.idzona inner join eecc e on c.ideecc=e.id WHERE o.id=$idot";
      $row=db_query($sql);
      $bandeja=mysqli_fetch_array($row);
        ?>
        <tr>
          <td><?php echo htmlspecialchars($bandeja[0]);?></td>
          <td><?php echo htmlspecialchars($bandeja[3]);?></td>
          <td>
            <select name="eccotb" id="eccotb" tabindex="1">
            <option value=''>---SELECCIONE---</option>
            <?php
            $val = @db_query("SELECT c.id, c.nombre,c.active FROM eecc c where c.active='Si'".$appuser->getDeptoFilterVB("id","d."));
             if (mysqli_num_rows($val) > 0){
             while($row = mysqli_fetch_array($val)){
              echo "<option value='".htmlspecialchars($row[id])."'>".htmlspecialchars($row[nombre])."</option>";
             }
           }
            ?>
          </td>
          <td><div class="actionbuttons">
    				<button class="boton" type="button" id="cambiaeecc" name="cambiaeecc" value="<?php echo htmlspecialchars($bandeja[0]); ?>">Adicionar</button>
    			</div></td>
        </tr>
    </tbody>
 </table>
</div>
</div>
