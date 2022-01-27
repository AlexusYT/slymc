<?php
/*$servername = "slymcdb.cusovblh0zzb.eu-west-2.rds.amazonaws.com";
$username = "admin";
$password = "nBeXaR8bLByWwyF";

$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";*/
    $connection = mysqli_connect('slymcdb.cusovblh0zzb.eu-west-2.rds.amazonaws.com', 'admin', 'nBeXaR8bLByWwyF', 'slymcdb');
    if ($connection == false) {
        echo "Не удалось подключиться к БД</br>";
        echo mysqli_connect_error();
        exit();
    }
    $getservers = mysqli_query($connection, "SELECT * FROM `getservers`");
    $ch = 0;
    while($row = mysqli_fetch_assoc($getservers)){
        //$row['ServerID'];
        $name=$row['ServerName'];
        $mname=$row['ModpackName'];
        $IPadress=$row['IP-address'];
        $port=$row['Port'];
        $launchtypeid=$row['LaunchTypeID'];
        $ch +=1;
    echo "$name,$mname,$IPadress,$port,$launchtypeid";
    if ($ch < mysqli_num_rows($getservers)) echo ":";
}
    $memory = mysqli_query($connection, "SELECT * FROM `memory`");
    echo "\n";
    while($row2 = mysqli_fetch_assoc($memory)){
        $ModpackName=$row2['ModpackName'];
        $MinMemory=$row2['MinMemory'];
        echo "$ModpackName:$MinMemory\n";
    }
mysqli_close($connection);
?>





