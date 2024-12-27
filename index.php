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
    <h1>Jedis</h1>
    <div id="heros">
        <?php

            echo "<div id='jedis_list'>";
            foreach($reqJedis as $jedis){
                $jedi_id = $jedis['id_heros'];
                $jedi_name = $jedis['nom'];
                $jedi_nickname = $jedis['surnom'];
                $sabres = explode(';', $jedis['sabres']);
                

                echo "<a class='heros_names' href='index.php?page=" . $jedi_id . "'>" . $jedi_name;
                if (!empty($jedis['surnom'])){ echo " \"$jedi_nickname\"";} 
                

                foreach ($sabres as $saber) {
                    if (!empty($jedis['sabres'])){echo "<span class='saber_line' style='color: $saber;'>|</span>";}
                }
                echo "</a>";
            }

            echo "</div>";

            echo "<div id='siths_list'>";
            foreach($reqSiths as $siths){
                $sith_id = $siths['id_heros'];
                $sith_name = $siths['nom'];
                $sith_nickname = $siths['surnom'];
                $sabres = explode(';', $siths['sabres']);

                echo "<a class='heros_names' href='index.php?page=" . $sith_id . "'>" . $sith_name;
                if (!empty($siths['surnom'])){ echo " \"$sith_nickname\"";} 
                
                foreach ($sabres as $saber) {
                    if (!empty($siths['sabres'])){echo "<span class='saber_line' style='color: $saber;'>|</span>";}
                }
                echo "</a>";
            }
            echo "</div>";
        ?>
    </div>
    <div id="citations">
        <?php

        ?>
    </div>
</body>
</html>