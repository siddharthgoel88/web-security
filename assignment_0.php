<html>
<head>
<style>
.title {
  font-weight: bold;
}


body { 
  color: #000000;
  font-family:"helvetica neue";
  font-size: 1212:47 PM 1/10/2013pt;
  font-weight: 300;
  font-style: normal;
  text-align: left;
}

.banner {
  position: relative;
  height: 80px;
  padding-left: 6px;
  background-color: #F2CC4C;

}

.table {
  border: solid 1px #EEE;
}
 
.td {
  font-family: monospace;
  vertical-align: top;
  background: #F9F9F9;
}

</style>

<title>CS5331 Assignment 0</title>

<div class="banner">
<h1>CS5331 - Web Security</h1>
</div>

</head>
<body>
<h2 id="sec-1">Assignment 0: Setup</h2>
<?php 
$name = $_POST["name"];
$matric_no = $_POST["matric_no"];
if (!isset($_POST['submit'])) { // if page is not submitted to itself echo the form
?>
<form method="post" action="<?php echo $PHP_SELF;?>">

<table width="540" border="0">
  <tr>
    <td>Name</td>
    <td>:</td>
    <td><input type="text" size="12" maxlength="50" name="name"></td>
    <td>Example: Lorem Ipsum</td>
  </tr>
  <tr>
    <td>Matric Number</td>
    <td>:</td>
    <td><input type="text" size="12" maxlength="10" name="matric_no"></td>
    <td>Example: A4878822</td>
  </tr>
</table>

<input type="submit" value="Show Secret" name="submit">
</form>
<?
} else {
echo "Hello ".$name. "!<br /> Assignment 0 is complete. The secret is ".hash('md5',$matric_no).". <br /> <br /> <br /> ";

echo "<font color = blue>Please take a screenshot of this from the host machine browser and upload it to IVLE.</font>";
}
?>
</body>
</html>
