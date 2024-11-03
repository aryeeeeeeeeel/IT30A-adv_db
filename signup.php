<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
  <div class="container">
    <div class="form-box box">
      <header>Sign Up</header>
      <hr>

      <form action="#" method="POST">
        <div class="form-box">
          <?php
          session_start();
          include "connection.php";

          if (isset($_POST['register'])) {
            $name = $_POST['username'];
            $email = $_POST['email'];
            $department = $_POST['department'];
            $pass = $_POST['password'];
            $cpass = $_POST['cpass'];

            $check = "SELECT * FROM users WHERE email='{$email}'";
            $res = mysqli_query($conn, $check);
            $passwd = password_hash($pass, PASSWORD_DEFAULT);

            if (mysqli_num_rows($res) > 0) {
              echo "<div class='message'>
                      <p>This email is already in use, try another one!</p>
                    </div><br>";
              echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button></a>";
            } else {
              if ($pass === $cpass) {
                $sql = "INSERT INTO users(username, email, department, password) VALUES('$name', '$email', '$department', '$passwd')";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                  header("Location: login.php");
                  exit();
                } else {
                  echo "<div class='message'>
                          <p>Error occurred during registration. Please try again.</p>
                        </div><br>";
                  echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button></a>";
                }
              } else {
                echo "<div class='message'>
                        <p>Passwords do not match.</p>
                      </div><br>";
                echo "<a href='signup.php'><button class='btn'>Go Back</button></a>";
              }
            }
          } else {
            ?>
            <div class="input-container">
              <i class="fa fa-user icon"></i>
              <input class="input-field" type="text" placeholder="Username" name="username" required>
            </div>

            <div class="input-container">
              <i class="fa fa-envelope icon"></i>
              <input class="input-field" type="email" placeholder="Email Address" name="email" required>
            </div>
            
            <div class="input-container">
              <i class="fa fa-building icon"></i>
              <select class="input-field" name="department" required>
              <option value="">Select Department</option>
              <option value="CCS">CCS</option>
              <option value="BA">BA</option>
              <option value="TEP">TEP</option>
              </select>
            </div>

            <div class="input-container">
              <i class="fa fa-lock icon"></i>
              <input class="input-field password" type="password" placeholder="Password" name="password" required>
              <i class="fa fa-eye icon toggle"></i>
            </div>

            <div class="input-container">
              <i class="fa fa-lock icon"></i>
              <input class="input-field" type="password" placeholder="Confirm Password" name="cpass" required>
              <i class="fa fa-eye icon"></i>
            </div>
          </div>

          <center><input type="submit" name="register" id="submit" value="Signup" class="btn"></center>

          <div class="links">
            Already have an account? <a href="login.php">Login</a>
          </div>
        </form>
      </div>
      <?php
          }
          ?>
  </div>

  <script>
    const toggle = document.querySelector(".toggle"),
      input = document.querySelector(".password");
    toggle.addEventListener("click", () => {
      if (input.type === "password") {
        input.type = "text";
        toggle.classList.replace("fa-eye-slash", "fa-eye");
      } else {
        input.type = "password";
      }
    });
  </script>
</body>

</html> 