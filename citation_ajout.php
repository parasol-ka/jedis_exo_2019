<?php
    include "_connexionBD.php";

    $reqNames=$bd->prepare("SELECT id_heros, nom, surnom FROM heros WHERE secondaire=0;");
    $reqNames->execute();

    $reqFilms=$bd->prepare("SELECT id_film, titre FROM films;");
    $reqFilms->execute();

    #Securisation du formulaire

    if (isset($_POST['hero_name_selection']) and isset($_POST['film_name_selection']) and isset($_POST['new_citation'])){
        $hero_name_selection = (int)$_POST['hero_name_selection'];
        $film_name_selection = (int)$_POST['film_name_selection'];
        $new_citation = $_POST['new_citation'];

        #Verification du héro
        $reqName=$bd->prepare("SELECT * from heros WHERE id_heros=:hero_id and secondaire=0;");
        $reqName->bindvalue("hero_id", $hero_name_selection);
        $reqName->execute();
        $name_result = $reqName->fetch();
        if(!empty($name_result['id_heros'])){
            $error_hero = False;
        }else {$error_hero = True;}

        #Verification du nom du film
        $reqFilm=$bd->prepare("SELECT * from films WHERE id_film=:film;");
        $reqFilm->bindvalue("film", $film_name_selection);
        $reqFilm->execute();
        $film_result = $reqFilm->fetch();
        if(!empty($film_result['id_film'])){
            $error_film = False;
        }else {$error_film = True;}

        #Verification du citation
        if(!empty($new_citation)){
            $error_citation = False;
        }else {$error_citation = True;}

        if(!$error_hero and !$error_film and !$error_citation) {
            $insertCitation=$bd->prepare("INSERT INTO citations (id_heros,id_film,citation) VALUES (:hero_id, :film_id, :citation);");
            $insertCitation->bindvalue("hero_id", $hero_name_selection);
            $insertCitation->bindvalue("film_id", $film_name_selection);
            $insertCitation->bindvalue("citation", $new_citation);
            $insertCitation->execute();
            header('Location:index.php?page='.$hero_name_selection);
        }


    }else {
        $error_hero = False;
        $error_film = False;
        $error_citation = False;
    }

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Ajout Citation</title>
</head>
<body>
    <div id="add_citation">
        <h1>Jedis</h1>

        <form action="citation_ajout.php" method="POST" id="add_citation_form">
        <h2>Enregistrer une nouvelle citation</h2>

        <label for='hero_name_selection' <?php if ($error_hero) {echo "class='error'";} ?> >Héro</label>
        <select name='hero_name_selection' id='hero_name_selection' required>
        <?php
            while ( $names=$reqNames->fetch() ){
                $hero_name = $names['nom'];
                $hero_nickname = $names['surnom'];
                $hero_id = $names['id_heros'];

                echo "<option value='$hero_id'>$hero_name";
                if (!empty($names['surnom'])){ echo " \"$hero_nickname\"";} 
                echo "</option>";
            }?>
        </select>

        <label for='film_name_selection' <?php if ($error_film) {echo "class='error'";} ?> >Film</label>
        <select name='film_name_selection' id='film_name_selection' required>
        <?php
            while ( $films=$reqFilms->fetch() ){
                $film_name = $films['titre'];
                $film_id = $films['id_film'];

                echo "<option value='$film_id'>$film_name";
                echo "</option>";
            }?>
        </select>

        <label for="new_citation" <?php if ($error_citation) {echo "class='error'";} ?> >Citation</label>
        <textarea name="new_citation" id="new_citation" rows="4" cols="40" ></textarea>
        
        <input type="submit" value="Enregistrer">
        </form>
        
    </div>
</body>
</html>