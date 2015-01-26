<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Access Token for ToDo Client</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#generate_token').on('click',function(){
                console.log("clicked");
                var clientId = $('#client_id').html();
                var clientToken = $('#client_secret').html();
                $.ajax({
                    url: 'oauth/access_token',
                    type: 'POST',
                    contentType: 'application/x-www-form-urlencoded',
                    data: 'grant_type=client_credentials&client_id='+clientId+'&client_secret='+clientToken,
                    success: function(data) {
                        $('#response').html("Your access token as been generated");
                        $('#response').append("<div><b>"+data.access_token+"</b></div>")
                        $('#generate_token').remove();
                    },
                    error: function(e) {
                        data = $.parseJSON(e.responseText);
                        $('#response').html("There has been an error while generating access token");
                        $('#response').append("<div>"+data.error+" ----- "+data.error_description+"</div>")
                    }
                });
            });
        });
    </script>
</head>
<body>
<div>
    <label>Client ID : </label><span id="client_id"><?php echo $client_data[0]; ?></span>
    <br/>
    <label>Client Secret : </label><span id="client_secret"><?php echo $client_data[1]; ?></span>
    <div><a href="#" id="generate_token">Generate Token</a></div>
    <div id="response">

    </div>
</div>
</body>
</html>