<!DOCTYPE html>
<html lang="en">
<head>
<!-- SB Admin Library -->  
<?php include('/sb-admin-lib.php'); ?>
</head>
<body>        
<iframe id="videoiframe" width="560" height="315" src="https://www.youtube.com/embed/UQ0hFLUiHTg?autoplay=1&start=60&end=70&rel=0" frameborder="0" allowfullscreen></iframe>   
<button type="button" id="btnRefresh" class="btn btn-default">Refresh</button>
<script>
$('#btnRefresh').on('click', function (){
        document.getElementById('videoiframe').src += '';
});
</script>
</body>
