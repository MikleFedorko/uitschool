<?php

$content = '
<div class="auth_container">
<h1>Sign up</h1>
<form method="post">
  <input type="hidden" name="action" value="sign_up">
  <div class="form-group">
      <button disabled class="btn-danger col-md-12">'.$errorMessage.'</button>
  </div>
  <div class="form-group">
    <input name="email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
    <small id="emailHelp" class="form-text text-muted">We\'ll never share your email with anyone else.</small>
  </div>
  <div class="form-group">
    <input name="password" type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
  </div>
  <div class="form-group">
    <input name="confirm" type="password" class="form-control" id="exampleInputPassword1" placeholder="Password Confirmation">
  </div>
  <div class="form-group float-right">
    <a href="/">Sign in</a>
  </div>
  <div class="form-group">
      <button type="submit" class="btn btn-primary">Submit</button>
  </div>
</form>
</div>
';

require_once('../view/empty_layout.php');
