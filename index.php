<?php
    require_once './libs/egyeb.php';
?>
<!DOCTYPE html>
<html lang="hu">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="./css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="./css/style.css" />
        <script src="./js/jquery.min.js"></script>
        <script src="./js/bootstrap.min.js"></script>
        <script src="./js/script.js"></script>
        <title>Brigi Quiz</title>
    </head>
    <body class="d-flex">
        <div id="main-container" class="container align-self-center">
            <?php echo isset($_SESSION['username'])?'<h1 id="cim" title="tájtül">Welcome ' . $_SESSION['username'] . '!</h1>':'<h1 id="cim" title="tájtül">Bejelentkezés</h1>'?>
            <div class="container">
                <?php
                    if(isset($_SESSION['username'])){
                        $kerdesek = array();
                        $handle = fopen('./data/kerdesek.csv','r');
                        $kerdesek_sum = 0;
                        while(($data = fgetcsv($handle)) !== false){
                            $kerdesek_sum++;
                            $newdata = explode(';',$data[0]);
                            $_kerdes = array(
                                'kerdes'=>$newdata[0],
                                'valasz1'=>$newdata[1],
                                'valasz2'=>$newdata[2],
                                'valasz3'=>$newdata[3],
                                'valasz4'=>$newdata[4],
                                'megoldas'=>$newdata[5]
                            );
                            if(!in_array($_kerdes['kerdes'],$_SESSION['valaszolt-kerdesek'])){
                                array_push($kerdesek,$_kerdes);
                            }else{
                                // csak azokat rakja bele a $kerdesek tömbbe, amik még nem lettek megválaszolva
                            }
                        }
                        if(empty($kerdesek)){
                            $_SESSION['vege'] = true;
                            echo '<h2 style="margin-bottom:1.5rem;">Eredmény: ' . $_SESSION['pontok'] . '/' . $kerdesek_sum . ' (' . ceil($_SESSION['pontok']*100/$kerdesek_sum) . '%)</h2>';
                            echo '
                            <form method="post" action="">
                                <div class="btn-container" style="margin:0 auto;text-align:center;"><button type="button" onclick="endQuiz();" class="btn btn-end">Quiz befejezése</button> <button type="button" onclick="rangsor();" class="btn btn-rangsor">Rangsor</button></div>
                            </form>';

                            $sql_query = 'SELECT * FROM eredmenyek WHERE nev = :nev';
                            $sth_query = $connection->prepare($sql_query,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                            $sth_query->execute(array(':nev' => $_SESSION['username']));
                            if(count($sth_query->fetchAll()) == 0){
                                $sql_insert = 'INSERT INTO eredmenyek (nev,pontszam,datum) VALUES (:nev,:pontok,NOW())';
                                $sth_insert = $connection->prepare($sql_insert, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                                $sth_insert->execute(array(':nev' => $_SESSION['username'], ':pontok' => $_SESSION['pontok']));
                            }else{
                                $sql_query_2 = 'SELECT * FROM eredmenyek WHERE nev = :nev AND pontszam < :pontok';
                                $sth_query_2 = $connection->prepare($sql_query_2,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                                $sth_query_2->execute(array(':nev' => $_SESSION['username'], ':pontok' => $_SESSION['pontok']));
                                if(count($sth_query_2->fetchAll()) > 0){
                                    
                                    $sql_delete = 'DELETE FROM eredmenyek WHERE nev = :nev';
                                    $sth_delete = $connection->prepare($sql_delete, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                                    $sth_delete->execute(array(':nev' => $_SESSION['username']));

                                    $sql_insert = 'INSERT INTO eredmenyek (nev,pontszam,datum) VALUES (:nev,:pontok,NOW())';
                                    $sth_insert = $connection->prepare($sql_insert, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                                    $sth_insert->execute(array(':nev' => $_SESSION['username'], ':pontok' => $_SESSION['pontok']));

                                }
                            }
                        }else{
                            shuffle($kerdesek);
                            $kerdes = $kerdesek[0];
                            $_SESSION['kerdes'] = $kerdes['kerdes'];
                            $megoldas = ($kerdes['megoldas']=='minden')?'__minden__':$kerdes[$kerdes['megoldas']];
                            $_SESSION['megoldas'] = $megoldas;
                            echo '
                            <form method="post" action="">
                                <h2 id="kerdes">' . $kerdes['kerdes'] . ' (' . ($kerdesek_sum+1-count($kerdesek)) . '/' . $kerdesek_sum . ')</h2>
                                <ul class="valaszok">
                            ';
                            for($i=1;$i<=4;$i++){
                                $valasz = $kerdes['valasz'.$i];
                                echo '<li><input type="radio" value="' . $valasz . '" name="valasz" id="valasz' . $i . '_id" required /><label for="valasz' . $i . '_id">&nbsp;' . $kerdes['valasz'.$i] . '</li>';
                            }
                            //echo '<h3>Megoldás: ' . $megoldas . '</h3>';
                            echo '
                                </ul>
                                <div class="btn-container" style="margin:0 auto;text-align:center;"><button type="button" class="btn btn-next" onclick="window.location = \'./valasz.php?valasz=\'+$(\'input[name=valasz]:checked\').val();" name="nextBtn">Következő kérdés</button> <button type="button" onclick="endQuiz();" class="btn btn-end">Kilépés</button></div>
                            </form>';
                        }
                    }else{
                        if(isset($_POST['startBtn'])){
                            $username = str_replace(' ','',$_POST['username']);
                            if(empty($username)){
                                echo '<div class="alert alert-danger" role="alert"><p>A felhasználónév nem lehet üres</p></div>';
                            }else{
                                $_SESSION['username'] = $_POST['username'];
                                header('location: index.php');
                            }
                        }
                        echo '
                        <form method="post" action="">
                            <div class="form-group">
                                <h2><label for="username">Add meg a felhasználóneved:</label></h2>
                                <input type="text" class="form-control username" id="username" name="username" maxlength="32" placeholder="" required/>
                            </div>
                            <div class="btn-container" style="margin:0 auto;text-align:center;"><button type="submit" class="btn btn-end" name="startBtn" value="ok">Quiz kezdése</button> <button type="button" onclick="rangsor();" class="btn btn-rangsor" name="rangsorBtn" value="ok">Rangsor</button></div>
                        </form>';
                    }
                ?>
            </div>
        </div>
    </body>
</html>