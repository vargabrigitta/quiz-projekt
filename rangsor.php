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
        <div id="main-container pd-0" class="container align-self-center">
            <h1 id="cim" title="tájtül" style="margin-bottom:1rem;">Felhasználók eredményei</h1>
            <div class="btn-container" style="margin:0 auto;text-align:center;"><button type="button" class="btn btn-rangsor" onclick="window.location = './index.php';">Visszalépés</button></div>
            <div class="container">
                <div class="rows">
                    <div class="row thead">
                        <div class="col-4 fcol">
                            Felhasználó
                        </div>
                        <div class="col-4 scol">
                            Pontszám
                        </div>
                        <div class="col-4 tcol">
                            Dátum
                        </div>
                    </div>
                    <?php
                        $sql = 'SELECT * FROM eredmenyek ORDER BY pontszam DESC, datum ASC;';
                        $result = $connection->query($sql);
                        $temp = 0;
                        foreach($result as $i){
                            $temp++;
                            echo '<div class="row">';
                            echo '<div class="col-4 fcol">' . $i['nev'] . '</div>';
                            echo '<div class="col-4 scol">' . $i['pontszam'] . '</div>';
                            echo '<div class="col-4 tcol">' . $i['datum'] . '</div>';
                            echo '</div>';
                        }
                        if($temp == 0){
                            echo '<h2>Még nincs rögzített felhasználó</h2>';
                        }
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>