<?php

include $_SERVER['DOCUMENT_ROOT']."/header.php";



?>

  <body>
    <div class="login">
      <div class="login-body">
        <a class="login-brand" href="/index.php">
          <img class="img-responsive" src="/dashboard/drive/images/tesla lc.png" alt="Elephant">
        </a>
        <div class="login-form">
          <form action="/includes/login.inc.php" method="POST" data-toggle="validator">
            <div class="form-group">
              <label for="username">Username</label>
              <input id="username" class="form-control" type="text" name="username" spellcheck="false" autocomplete="off" data-msg-required="Please enter your username." required>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input id="password" class="form-control" type="password" name="password" minlength="6" data-msg-minlength="Password must be 6 characters or more." data-msg-required="Please enter your password." required>
            </div>
            <div class="form-group">
              <label class="custom-control custom-control-primary custom-checkbox">
                <input class="custom-control-input" type="checkbox" checked="checked">
                <span class="custom-control-indicator"></span>
                <span class="custom-control-label">Keep me signed in</span>
              </label>
              <span aria-hidden="true"> · </span>
              <a href="password-2.html" class="text-crimson">Forgot password?</a>
            </div>
            <button class="btn my-color btn-block" name="submit" type="submit">Sign in</button>
          </form>
        </div>
      </div>
      <div class="login-footer">
        Don't have an account? <a href="/authentication/sign-up.php" class="text-crimson">Sign Up</a>
      </div>
    </div>
    <script src="/template_vendor/js/vendor.min.js"></script>
    <script src="/template_vendor/js/elephant.min.js"></script>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
      ga('create', 'UA-83990101-1', 'auto');
      ga('send', 'pageview');
    </script>
  </body>

<?php
include $_SERVER['DOCUMENT_ROOT']."/template/footer.php";
?>
