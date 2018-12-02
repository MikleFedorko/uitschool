<?php

$content = '
<div class="auth_container">
<h1>Sign in</h1>
<form method="post">
  <input type="hidden" name="action" value="auth">
  <button disabled class="btn-danger col-md-12">' . $errorMessage . '</button>
  <div class="form-group">
    <input name="email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
    <small id="emailHelp" class="form-text text-muted">We\'ll never share your email with anyone else.</small>
  </div>
  <div class="form-group">
    <input name="password" type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
  </div>
  <div class="form-group float-right">
    <a href="sign_up">Sign up</a>
  </div>
  <div class="form-group">
      <button type="submit" class="btn btn-primary">Submit</button>
  </div>
</form>
</div>
';

require_once('../view/empty_layout.php');
