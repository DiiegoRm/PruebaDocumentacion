<?php
$id=decrypt(getVal($_GET['id']));
$id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
 ?>
 <div>
   <div class="outerbox">
   <div class="mainHeading" style="margin-top: 70px;"><h2>Bandejas</h2></div>
 <table cellspacing="0" cellpadding="0" class="data-table" border="1">
   <tr>
    <td class="input"><?php echo getComboBox("SELECT id,nombre,active FROM grupos WHERE id > 1 ORDER BY id ASC",'gruposbvb');?></td>
     <td><div class="actionbuttons">
       <button type="button" id="adicionarbvb" name="adicionarbvb" value="<?php echo $id;?>">Adicionar</button>
     </div></td>
   </tr>
 </table>
 <table cellspacing="0" cellpadding="0" class="data-table" border="1">
   <thead>
  <tr>
    <th>#</th>
    <th>id viabilidad</th>
    <th>tipo bandeja</th>
    <th>Accion</th>
  </tr>
</thead>
    <tbody id="bandejaot">
      <?php
      $sql="SELECT bo.*, g.nombre FROM bandejasvb bo inner join grupos g on g.id=bo.idgrupo WHERE idviabilidad=$id";
      $row=db_query($sql);
      $contador=1;
      while ($bandeja=mysqli_fetch_row($row)) {
        ?>
        <tr>
          <td><?php echo $contador;?></td>
          <td><?php echo htmlspecialchars($bandeja[0]);?></td>
          <td><?php echo htmlspecialchars($bandeja[4]);?></td>
          <input type="hidden" id="gpbvb" name="gpbvb" value="<?php echo $bandeja[1]; ?>"/>
          <td><div class="actionbuttons">
    				<button type="button" id="eliminarbvb" name="eliminarbvb" class="eliminarbvb" value="<?php echo $bandeja[0]; ?>">Eliminar</button>
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
</div>
<script type="text/javascript">
  $(function(){
    $("#gruposbvb").find("option[value='']").remove();
  });
</script>