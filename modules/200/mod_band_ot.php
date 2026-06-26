<?php
$id=decrypt(getVal($_GET['id']));
$id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
 ?>
 <div>
   <div class="outerbox">
   <div class="mainHeading" style="margin-top: 70px;"><h2>Bandejas</h2></div>
 <table cellspacing="0" cellpadding="0" class="data-table" border="1">
   <tr>
     <td class="input"><?php echo getComboBox("SELECT id,nombre,active FROM grupos WHERE id > 1 ORDER BY id ASC",'gruposbto');?></td>
     <td><div class="actionbuttons">
       <button type="button" id="adicionarbto" name="adicionarbto" value="<?php echo $id;?>">Adicionar</button>
     </div></td>
   </tr>
 </table>
 <table cellspacing="0" cellpadding="0" class="data-table" border="1">
   <thead>
  <tr>
    <th>#</th>
    <th>id orden</th>
      <th>tipo bandeja</th>
      <th>Accion</th>
  </tr>
</thead>
    <tbody id="bandejaot">
      <?php
      $sql="SELECT bo.*, g.nombre FROM bandejasot bo inner join grupos g on g.id=bo.idgrupo WHERE idorden=$id";
      $row=db_query($sql);
      $contador=1;
      while ($bandeja=mysqli_fetch_row($row)) {
        ?>
        <tr>
          <td><?php echo $contador;?></td>
          <td><?php echo htmlspecialchars($bandeja[0]);?></td>
          <td><?php echo htmlspecialchars($bandeja[4]);?></td>
          <input type="hidden" id="gpbto" name="gpbto" value="<?php echo $bandeja[1]; ?>"/>
          <td><div class="actionbuttons">
    				<button type="button" id="eliminarbot" name="eliminarbot" class="eliminarbot" value="<?php echo $bandeja[0]; ?>">Eliminar</button>
    			</div></td>
        </tr>
        <?php
        $contador=$contador+1;
      }
      $contador=1
       ?>
    </tbody>
 </table>
</div>
</div>
<div>
  <div class="outerbox">
  <div class="mainHeading"><h2>Bandejas Causacion</h2></div>
<table cellspacing="0" cellpadding="0" class="data-table" border="1">
  <tr>
    <td class="input"><?php echo getComboBox("SELECT id,nombre,active FROM grupos WHERE id > 1 ORDER BY id ASC",'gruposbliq');?></td>
    <td><div class="actionbuttons">
      <button type="button" id="adicionarbliq" name="adicionarbliq" value="<?php echo $id;?>">Adicionar</button>
    </div></td>
  </tr>
</table>
<table cellspacing="0" cellpadding="0" class="data-table" border="1">
  <thead>
 <tr>
   <th>#</th>
   <th>id liquidacion</th>
   <th>id orden</th>
     <th>tipo bandeja</th>
     <th>Accion</th>
 </tr>
</thead>
   <tbody id="bandejaot">
     <?php	 
     $sql="SELECT bliq.*, g.nombre , liq.idorden FROM liquidaciones liq inner join bandejasliq bliq on liq.id=bliq.idliquidacion inner join grupos g on g.id=bliq.idgrupo WHERE liq.idorden=$id";
     $row=db_query($sql);
     $contador=1;
     while ($bandeja=mysqli_fetch_row($row)) {
       ?>
       <tr>
         <td><?php echo $contador;?></td>
         <td><?php echo htmlspecialchars($bandeja[0]);?></td>
         <td><?php echo htmlspecialchars($bandeja[4]);?></td>
         <td><?php echo htmlspecialchars($bandeja[3]);?></td>
         <input type="hidden" id="gpliq" name="gpliq" value="<?php echo $bandeja[1]; ?>"/>
         <td><div class="actionbuttons">
           <button type="button" id="eliminarliq" name="eliminarliq" class="eliminarliq" value="<?php echo htmlspecialchars($bandeja[0]); ?>">Eliminar</button>
         </div></td>
       </tr>
       <?php
       $contador=$contador+1;
     }
     $contador=1
      ?>
   </tbody>
</table>
</div>
</div>

<script type="text/javascript">
  $(function(){
    $("#gruposbto").find("option[value='']").remove();  
    $("#gruposbliq").find("option[value='']").remove(); 
  });
</script>