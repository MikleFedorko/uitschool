<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Home Task</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css?t=\' . time() . \'">
    <link rel="icon" href="/favicon.ico" type="image/x-icon"/>
    <meta charset="utf-8">
</head>
<body>
<?=$content?>
<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
<script>
  $( document ).ready(function() {
    $("#avatar").click(function() {
      $("input#avatar_input").click();
    });
  });
  
  function ajaxSubmit() {
    var file_data = $("#avatar_input").prop("files")[0];   
    var form_data = new FormData();
    form_data.append("avatar", file_data);
    $.ajax({
        url: "/profile",
        dataType: 'script',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,                         
        type: 'post',
        success: function(response){
            $("img#avatar").attr("src", $.parseJSON(response.replace(/'/g, "")).path);
        }
    });
  }
</script>
</body>
</html>