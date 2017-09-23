<?php 
    session_start();
    
    
    if(isset($_SESSION['user']))
    {
        if($_SESSION['user']!='unlogged')
        {
            loadContent();
        }
        else 
        {
            header("location:login.php");
        }
    }
    else 
    {
        header("location:login.php");
    }
    
    function loadContent()
    {
        ?>
        <!DOCTYPE html>
            <html>
                <head>
                    <title>Panel administracyjny</title>
                    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
                    <link rel="stylesheet" href="styles/panel.css">
                    <!-- jQuery library -->
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

                    <!-- Latest compiled JavaScript -->
                    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

                    <script src="scripts/layout_anims.js"></script>
                </head>
                <body>
                    <div class="container-fluid">
                        <p class="user-info">Użytkownik: [<a href="user_account.php"><?php echo $_SESSION['user'];?></a>]<a href="logout.php">Wyloguj</a></p>
                        <h2 class="text-center">Komis samochodowy - Panel administracyjny</h2>
                         <h3 class="text-center">Ogłoszenia</h3>
                        <hr/>
                        <p><a href="offer_add.php">Dodaj nowe ogłoszenie</a>
                    
                        
                        
                        <?php
                        if($_SESSION['admin'])
                        {?>
                            <a href="users.php">Użytkownicy</a></p>
                       <?php 
                        }

                            
                          //  echo $_SESSION['passChanged'];
                            include('db_connect.php');
                            if($results = $db->query("SELECT * FROM offers"))
                            {
                                $count = 0;
                                while($row = $results->fetch_object())
                                {
                                    if($count%4==0)
                                    {
                                        ?>
                                        <div class="row">
                                        <?php 
                                    }?>
                                    <div class="col-sm-3">
                                        <div class="panel panel-default">
                                            <div class="panel-heading"><img <?php echo'src="img/offer_img/'.$row->zdjecie.'" alt="'.$row->marka." ".$row->model.'"';?> /></div>
                                            <div class="panel-body">
                                                <h3 class="text-center"><?php echo $row->marka." ".$row->model;?></h3>
                                                <h4 class="text-center"> Dodano: <?php echo $row->data_dodania;?></h4>
                                                <h4 class="text-center"> Cena: <?php echo $row->cena;?></h4>
                                            </div>
                                            <div class="panel-footer">
                                                <div class="row">
                                                    <div class="col-sm-6"><a href=<?php echo "offer_add.php?id=".$row->id;?> class="btn btn-primary">Aktualizuj</a></div>
                                                    <div class="col-sm-6"><a href=<?php echo "offer_delete.php?id=".$row->id;?> class="btn btn-danger">Usuń</a></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    if($count%4==3)
                                    {
                                        ?>
                                        </div>
                                        <?php
                                    }
                                    $count++;
                                    
                                }
                            }
                        ?>
                        
                    </div>
                    <hr/>
                    <footer class="container-fluid text-center" id="down">
                        <a href="#top" title="Do góry">
                            <span class="glyphicon glyphicon-chevron-up"></span>
                        </a>
                        <p>
                            Komis samochodowy<br />
                            Projekt i realizacja Mateusz Mierzyński
                        </p>
                </footer>
                </body>
            </html>
    <?php
    }
?>