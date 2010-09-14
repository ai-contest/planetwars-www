<?php

include 'header.php';
include 'mysql_login.php';
include 'web_util.php';
require_once "gmail.php";

$password1 = mysql_real_escape_string(stripslashes($_POST['password1']));
$password2 = mysql_real_escape_string(stripslashes($_POST['password2']));

$confirmation_code = mysql_real_escape_string(stripslashes($_POST['confirmation_code']));

// Check that the two passwords given match.
if ($password1 != $password2) {
  $errors[] = "You made a mistake while entering your password. "
            . "The two passwords that you give should match.";
}

// Check that the desired password is long enough.
if (strlen($password1) < 8) {
  $errors[] = "Your password must be at least 8 characters long.";
}

if (count($errors) <= 0) {

  $query = "UPDATE users SET password='".md5($password1)."' WHERE activation_code='$confirmation_code'";

  if (!mysql_query($query)) {
    $errors[] = "Failed to communicate with the registration database. Try " .
	"again in a few minutes. ($query : " . mysql_error() . ")";
  }
}

if (count($errors) > 0) {
?>

<h1>Update Password Failed</h1>
<p>There was a problem with the information that you gave.</p>
<ul>

<?php
foreach ($errors as $key => $error) {
  print "<li>$error</li>";
}
?>

</ul>
<p>Go <a href="reset_password.php?confirmation_code=<?php echo $confirmation_code ?>">back to the update password page</a> and try again.</p>

<?php
} else {
?>

<h1>Password Updated !</h1>

<p><a href="login.php">Sign in.</a></p>

<?php
}  // end if
?>

<?php include 'footer.php'; ?>

  