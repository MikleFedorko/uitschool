<?php

$content = '
<div class="table_container">
    <div class="float-right">
        <a class="btn btn-primary pull-right" href="/">Main</a>
        <a class="btn btn-primary pull-right" href="/logout">Logout</a>
    </div>
</div>
<div class="auth_container" style="top:10px">
<h1>Profile</h1>
<form method="post" enctype="multipart/form-data">
  <input type="hidden" name="action" value="profile">
  <button disabled class="btn-danger col-md-12">' . $errorMessage . '</button>
  <div class="form-group avatar" title="Click to upload photo">
    <input name="avatar" type="file" id="avatar_input" style="display: none" onchange="ajaxSubmit()">
    <img id="avatar" height="150" src="' . $destination . '" />
  </div>
  <div class="form-group">
    <input name="name" class="form-control" id="name" placeholder="User name" value="' . $name . '">
  </div>
  <div class="form-group">
      <button type="submit" class="btn btn-primary">Save</button>
  </div>
</form>
</div>
';

require_once('../view/layout.php');
