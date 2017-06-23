<?php
    include_once('views/includes/includes_header.php');
    include_once('dbconnect.php');
?>
    <nav class="mdl-navigation">
      <a class="mdl-navigation__link" href="/about">About</a>
      <a class="mdl-navigation__link" href="/contact">Contact</a>
      <a class="mdl-navigation__link" href="/admin">Admin Interface</a>
      <a class="mdl-navigation__link" href="/department">Department Interface</a>
      <a class="mdl-navigation__link" href="/student">Student Interface</a>
    </nav>
  </div>
     
  <main class="mdl-layout__content mdl-color--grey-100">
    <div class="page-content">
    <!-- Your content goes here -->
    <div class="mdl-grid">
<?php
      //Admin password recovery
      
        if ( !empty($_POST['admfor'] && $_POST['g-recaptcha-response'])) {

          $captcha=$_POST['g-recaptcha-response'];
          $captcha = Database::reCAPTCHAvalidate($captcha);

          //checking for the recaptcha value
      if($captcha == 1) {
      
                    //collecting values
                    $username = $_POST['uname'];
                    $mobileno = $_POST['no'];
                    $email = $_POST['email'];
                
                    //Values authentication
                    $check = Database::adminrecovery($username,$mobileno,$email);

                    //checking the return value
                    if($check == 1)  {

                      //generating the new password
                      //calling the new password generating function
                      $newpass = Database::generateRandomString(); //send this to user via email
                      $newpassdb = md5($newpass); //insert this encrypted value in database

                      //Replacing the existing password with new password
                      $pass = Database::adminchangepassword($username,$newpassdb);

                      if($pass == 1)  {
                      //send the new password in mail
                      
                      //subject of the email
                      $subject = "Admin - account recovery email, $username";

                      //message content of the email
                      $message = "Hey, $username\r\nYour request to recover your password is received\r\nYour new password is - $newpass.\r\n";

                      //sending the email
                      $mailit = Database::mailthedetails($email,$subject,$message);

                          if($mailit == 1)  {
                          echo "<p>";
                          echo "Account recovery email sent to $email. <br>";
                          echo "Follow the instructions to reset the password. <br>";
                          echo "</p>";
                          } 
                          else  {
                            echo "Account Recovery mail sending failed<br>";
                            }
                      }
                    }
                    else  {
                    
            ?>
                <!-- Registration successful -->
            <span class="mdl-chip mdl-chip--contact">
                <span class="mdl-chip__contact mdl-color--teal mdl-color-text--white">F</span>
                <span class="mdl-chip__text">Incorrect Input fields <a style="color: blue; text-decoration: none;">Password Recovery Failed.</a></span>
            </span>
            <?php
                }
  }
  else  {
    echo "reCAPTCHA validation failed<br>";
  }
}
      else {
?>
      <div class="mdl-cell mdl-cell--6-col">
      
        <form class="admlog" action="/admin/forget" method="post">
        <h3>Admin Password recovery</h3>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="text" name="uname" pattern="[A-Za-z0-9]{1,15}" placeholder="Letters & Numerics" id="uname" required>
            <label class="mdl-textfield__label" for="uname">Username</label>
            </div>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="email" name="email" id="email" required>
            <label class="mdl-textfield__label" for="email">Email</label>
            </div>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="text" name="no" pattern="[0-9]{10,10}" id="no" required>
            <label class="mdl-textfield__label" for="no">Mobile no.</label>
            </div>
            
            <!-- reCAPTCHA -->
            <div class="g-recaptcha" data-sitekey="6LeITyYUAAAAAMv47yYgyOkPpBI-tr__XTvc0LlQ" align="center"></div><br>

            <!-- Raised button with ripple -->
            <div>
          <button type="submit" name="admfor" value="admfor" class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent mdl-js-ripple-effect">
            Submit
          </button>
          </div>
        </form>
      </div>
      <?php
          }
      ?>


    </div>
  </div>