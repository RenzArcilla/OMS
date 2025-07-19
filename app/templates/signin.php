<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
</head>
<body>

  <h2>Registration</h2>

  <form action="../controllers/sign_in.php" method="post">
    <?php include "../includes/header.php"; ?>
    <label for="firstname">First Name:</label><br>
    <input type="text" id="firstname" name="firstname" required><br><br>

    <label for="lastname">Last Name:</label><br>
    <input type="text" id="lastname" name="lastname" required><br><br>

    <label for="username">Username:</label><br>
    <input type="text" id="username" name="username" required><br>

    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirm_password">Confirm Password:</label><br>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>