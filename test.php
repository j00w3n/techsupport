<?php
    $numbers = range(1,100);
    var_dump($numbers);
    $primenumbers = array();
    echo "<br>Prime numbers are: <br>";
    for($i=0;$i < count($numbers); $i++){
        if($numbers[$i]%2 !==0){
        $primenumbers[] = $numbers[$i];
        echo $numbers[$i]."<br>";
        }
    }
?>