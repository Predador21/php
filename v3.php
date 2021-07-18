<!DOCTYPE html>
<html>
<body>

<?php

$filename = basename( __FILE__ ) ;
echo "$filename foi modificado em: " . date ("F d Y H:i:s.", filectime($filename));
echo "<BR><BR>";

include 'config.php';

$cookie=$_COOKIE['session'];

if (is_null($cookie) == 1) {
    $cookie=bin2hex(random_bytes(5));
    setcookie('session',$cookie);
}

$conn = new mysqli($host, $username, $password, $database);

$account='g'.bin2hex(random_bytes(3));

setcookie('account',$account);

$sql = "insert into tbl_session ( session, account, status ) values ('".$cookie."','".$account."',1)";
$result = $conn->query($sql);

echo "Usuario: ".$account;
echo "<BR><BR>" ;

$conn->close();

//-------------------------------------------

$conn = new mysqli($host, $username, $password, $database);

$sql = "select url from tbl_url where account = '".$account."' ";
$result = $conn->query($sql);

while($result->num_rows == 0) {
    $result = $conn->query($sql);
    sleep(1) ;
}
$filename = 'arquivo.txt';
$row = $result->fetch_assoc();

$link=$row["url"] ;

echo "<a href=$link target='_blank'>Acessar</a>";

$conn->close();

?>

<BR><BR>

Token: <input type="text" id="token" value="">

<button onclick="myFunction()">Autorizar</button>

<script>
function myFunction() {
 var token = document.getElementById("token").value;
 var allcookies = document.cookie;
 var cookiearray = allcookies.split(';');

 var session = '' ;
 var account = '' ;

 for(var i=0; i<cookiearray.length; i++) {

     var name  = cookiearray[i].split('=')[0];
     var value = cookiearray[i].split('=')[1];

//     if ( nome == "session" ){
//        session = value ;
//     }

     switch(name.trim()) {
     case "session":
           session = value.trim()
     break;
     case "account":
           account = value.trim()
     break;
     default:
           alert ("cookie desconhecido!")
     }
 }


window.location.href = "http://135.148.11.148/access.php?account="+account+"&token="+token ;

}
</script>

</body>
</html>
