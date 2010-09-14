<?php
include 'session.php';
include 'mysql_login.php';
include 'header.php';

function get_username_from_confirmation_code($confirmation_code) {
  $query = "SELECT username FROM users WHERE " .
    "activation_code = '$confirmation_code'";
  $result = mysql_query($query);
  if ($row = mysql_fetch_assoc($result)) {
    $username = $row['username'];
    return $username;
  } else {
    return NULL;
  }
}

$confirmation_code = mysql_real_escape_string(stripslashes($_GET['confirmation_code']));

if ($confirmation_code == NULL || strlen($confirmation_code) <= 0) {
  $errors[] = "Reset password token expired. (101)";
} else {
  $username = get_username_from_confirmation_code($confirmation_code);
  if ($username == NULL || strlen($username) <= 0) {
    $errors[] = "Reset password token expired. (102)";
  } 
}

if (count($errors) > 0) {
?>

<h1>Account Activation Failed</h1>
<p>There was a problem while retrieving your account.</p>
<ul>

<?php
foreach ($errors as $key => $error) {
  print "<li>$error</li>";
}
?>

</ul>

<p>Try again in a few minutes.</p>

<?php
} else {
?>

<h1>Reset Your Password</h1>
<p>Welcome back, <?php echo $username ?>. Please provide a new password</p>
<form name="login_form" method="post" action="save_password.php">
  <input type="hidden" name="confirmation_code" value="<?php echo $confirmation_code ?>"/>
  <table width="100%" border="0" cellpadding="3" cellspacing="5">
  <tr>
    <td width="78">Password</td>
    <td width="6">:</td>
    <td width="294"><input name="password1" type="password" id="password1"></td>
  </tr>
  <tr>
    <td>Confirm Password</td>
    <td>:</td>
    <td><input name="password2" type="password" id="password2"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type="submit" name="Submit" value="Save Password"></td>
  </tr>
  </table>
</form>
<?php
}

include 'footer.php'
?>
