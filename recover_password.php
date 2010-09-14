<?php

include 'header.php';
include 'mysql_login.php';
include 'web_util.php';
require_once "gmail.php";

// By default, send account confirmation emails.
$send_email = 1;

// Gather the information entered by the user on the signup page.
$username = mysql_real_escape_string(stripslashes($_POST['username']));
$user_email = mysql_real_escape_string(stripslashes($_POST['email']));


//select user by email and username
$sql="SELECT * FROM users WHERE username='$username' and email='$user_email'";
$result = mysql_query($sql);
if (mysql_num_rows($result) == 0) {
  $errors[] = "There is no account for the given username and/or password.";
} else {

 $confirmation_code =
    md5($username . mt_rand() . $username);

 $row = mysql_fetch_assoc($result);

 $query = "UPDATE users SET activation_code='$confirmation_code' WHERE user_id=".$row['user_id'];

 if (mysql_query($query)) {
    // Send confirmation mail to user.
    $mail_subject = "Google AI Challenge!";
    $activation_url = current_url();
    $activation_url = str_replace("recover_password.php",
                                  "reset_password.php",
                                  $activation_url);
    if (strlen($activation_url) < 5) {
      $activation_url = "http://www.ai-contest.com/reset_password.php";
    }
    $mail_content = "Hello! Click the link below in order " .
      "to reset your password.\n\n" .
      $activation_url .
      "?confirmation_code=" . $confirmation_code . "\n\n" .
      "After you change your pasword by clicking the link above, you will " .
      "be able to sign in and start competing. Good luck!\n\n" .
      "Contest Staff\nUniversity of Waterloo Computer Science Club";
    if ( $send_email == 1 ) {
      $mail_accepted = send_gmail($user_email, $mail_subject, $mail_content);
    } else {
      $mail_accepted = true;
    }
    if (intval($mail_accepted) == 0) {
      $errors[] = "Failed to send confirmation email. Try again in a few " .
        "minutes.";
     
    }

  } else {
    $errors[] = "Failed to communicate with the registration database. Try " .
      "again in a few minutes. ($query : " . mysql_error() . ")";
  }

}    

if (count($errors) > 0) {
?>

<h1>Password Recovery Failed</h1>
<p>There was a problem with the information that you gave.</p>
<ul>

<?php
foreach ($errors as $key => $error) {
  print "<li>$error</li>";
}
?>

</ul>
<p>Go <a href="forgot_password.php">back to the forgot password page</a> and try again.</p>

<?php
} else {
?>

<h1>Password Recovery Successful!</h1>
<p>A confirmation
   message will be sent to the email address that you provided.
   You must click the link in that message in order to reset the password for
   your account.</p>
<h2>Check Your Junk Mail Folder</h2>
<p>If you don't see it in five minutes, remember to check your
   junk mail folder. Some free email providers are known to
   mistake confirmation emails for junk mail. Before you even think
   of sending us mail asking for help, <strong>check your junk mail
   folder!</strong></p>
<p><a href="index.php">Back to the home page.</a></p>

<?php

if ($send_email == 0) {
  echo "<p>$confirmation_code</p>";
}

}  // end if
?>

<?php include 'footer.php'; ?> 
