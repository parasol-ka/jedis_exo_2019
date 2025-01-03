<?php
    include "_connexionBD.php";
    $reqJedis = $bd->prepare("SELECT * FROM `heros` WHERE secondaire=0 AND cote_obscur=0 ORDER BY premiere_apparition;");
    $reqJedis->execute();

    $reqSiths = $bd->prepare("SELECT * FROM `heros` WHERE secondaire=0 AND cote_obscur=1 ORDER BY premiere_apparition;");
    $reqSiths->execute();

    if(isset($_GET["page"])){
        $reqName = $bd->prepare("SELECT nom from heros WHERE id_heros=:hero_id;");
        $reqName->bindvalue(':hero_id', intval($_GET['page']));
        $reqName->execute();

        $reqCitations = $bd->prepare("SELECT h.id_heros, h.nom, f.titre, c.id_citation, c.citation FROM heros as h JOIN citations AS c on h.id_heros=c.id_heros JOIN films AS f ON c.id_film=f.id_film WHERE h.id_heros=:hero_id ORDER BY f.annee;");
        $reqCitations->bindvalue(':hero_id', intval($_GET['page']));
        $reqCitations->execute();
    }
    
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
                

                echo "<a class='heros_names' href='index.php?page=$jedi_id";
                
                if(isset($_GET["page"])){
                    if ( $_GET["page"]==$jedi_id ) {
                        $s = "style='font-weight: bold;'";
                    }else { $s="" ;}
                }else { $s="" ;}
                
                echo "' $s>". $jedi_name;
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

                echo "<a class='heros_names' href='index.php?page=" . $sith_id ;
                
                if ( $_GET["page"]==$sith_id ) {
                    $s = "style='font-weight: bold;'";
                } else { $s="" ;}

                echo "' $s>". $sith_name;
                if (!empty($jedis['surnom'])){ echo " \"$jedi_nickname\"";} 
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
        if (isset($_GET['page'])){
            $hero_id = $_GET['page'];
            $hero_name=$reqName->fetch();

            if ($hero_name['nom'] != '') {
                echo "<h2>Citation de " . $hero_name['nom'] . "</h2>";
            }else {echo "<h2>Héros n'as pas de citations</h2>";}
            
            while ( $citations=$reqCitations->fetch() ) {
                $citation=$citations['citation'];
                $film=$citations['titre'];
                echo "<p class='citation'> \"$citation\" - <span class='film_name'> $film  </span></p>";
            }
            echo "<a href='citation_ajout.php' id='add_button'>Ajouter une citation</a>";
        }
        ?>
    </div>
</body>
</html>