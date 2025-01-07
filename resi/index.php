<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <title>Cek Resi</title>
   </head>
   <body>
      <form method="get" action="hasil.php">
         <span>No Resi: </span><input type="text" placeholder="No resi..." max="30" name="waybill" autofocus>
         <select name="courier">
            <option value="sicepat">Sicepat Express</option>
            <option value="anteraja">Anteraja</option>
            <option value="pos">POS</option>
            <option value="wahana">Wahana Express</option>
            <option value="jnt">JNT Express</option>
            <option value="sap">SAP Express</option>
            <option value="jet">JET Express</option>
            <option value="ninja">Ninja Express</option>
         </select>
         <input type="submit" class="btn btn-success btn-submit" name="submit" value="Cek Resi">
      </form>
      <hr>
   </body>
</html>
