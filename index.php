<?php
    include "_connexionBD.php";
    $reqJedis = $bd->prepare("SELECT * FROM `heros` WHERE secondaire=0 AND cote_obscur=0 ORDER BY premiere_apparition;");
    $reqJedis->execute();
    $reqSiths = $bd->prepare("SELECT * FROM `heros` WHERE secondaire=0 AND cote_obscur=1 ORDER BY premiere_apparition;");
    $reqSiths->execute();
    session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Jedis</title>
</head>
<body>
    <div id="jedis">
        <h2>Jedis</h2>
        <?php

            echo "<div id='jedis_list'>";
            foreach($reqJedis as $jedis){
                $jedi_name = $jedis['nom'];
                $jedi_nickname = $jedis['surnom'];
                echo "<p class='jedis_names'>$jedi_name";
                if (!empty($jedis['surnom'])){ echo " \"$jedi_nickname\"";} 
                echo "</p>";}

            echo "</div>";

            echo "<div id='siths_list'>";
            foreach($reqSiths as $siths){
                $sith_name = $siths['nom'];
                $sith_nickname = $siths['surnom'];
                echo "<p class='siths_names'>$sith_name";
                if (!empty($siths['surnom'])){ echo " \"$sith_nickname\"";} 
                echo "</p>";}
                
            echo "</div>";
        ?>
    </div>
</body>
</html>