<?php include 'header.php' ?>

<h2>Forgot Your Password</h2>

<form name="login_form" method="post" action="recover_password.php">
  <table width="100%" border="0" cellpadding="3" cellspacing="5">
  <tr>
    <td width="78">Username</td>
    <td width="6">:</td>
    <td width="294"><input name="username" type="text" id="username"></td>
  </tr>
  <tr>
    <td>Email</td>
    <td>:</td>
    <td><input name="email" type="text" id="email"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type="submit" name="Submit" value="Submit"></td>
  </tr>
  </table>
</form>

<?php include 'footer.php' ?>
