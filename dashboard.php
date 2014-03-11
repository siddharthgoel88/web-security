<form action='src/dashboard_helper.php' method='post' name='helper_form'>
<?php
$encode_url= urlencode("/plugins/dashboard_plugins/dashboard_plugins.php");
?>
<input type='hidden' name='redirect_url' value="<?php echo $encode_url; ?>">
</form>
<script language="JavaScript">
document.helper_form.submit(); 
</script>

