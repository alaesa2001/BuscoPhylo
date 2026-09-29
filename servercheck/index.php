<head>
<meta charset="UTF-8">
<title>ServerCheck</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body onload="getResult()">
<script>
jQuery().ready(function(){
    setInterval("getResult()",1000);
});
function getResult(){   
    jQuery.post("server.php",function( data ) {
        jQuery("#msgs").html(data);
    });
}
</script>
<div id="msgs">
</div>
</body>