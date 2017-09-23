<?php 
    session_start();
    if(isset($_SESSION['user']) && $_SESSION['user']!="unlogged")
    {
        include("db_connect.php");
        
        if(isset($_POST['send']))
        {
            $model=""; 
            $marka="";
            $rok="";
            $cena="";
            $przebieg ="";
            $nadwozie="";
            $skrzynia="";
            $paliwo="";
            $pojemnosc="";
            $moc="";
            $zdjecie="";
            $file_name="";
            $file_tmp_name="";
           
            if($_POST['marka']!="" && $_POST['model']!="" && $_POST['rok']!="" && $_POST['przebieg']!="" && $_POST['nadwozie']!="" && $_POST['skrzynia']!="" && 
                    $_POST['paliwo']!="" && $_POST['pojemnosc']!="" && $_POST['moc']!="" && $_POST['cena']!="")
                    {
                        $marka = htmlentities($_POST['marka'], ENT_QUOTES);
                        $model = htmlentities($_POST['model'], ENT_QUOTES);
                        $rok = htmlentities($_POST['rok'], ENT_QUOTES);
                        $cena = htmlentities($_POST['cena'], ENT_QUOTES);
                        $przebieg = htmlentities($_POST['przebieg'], ENT_QUOTES);
                        $nadwozie= htmlentities($_POST['nadwozie'], ENT_QUOTES);
                        $skrzynia = htmlentities($_POST['skrzynia'], ENT_QUOTES);
                        $paliwo= htmlentities($_POST['paliwo'], ENT_QUOTES);
                        $pojemnosc= htmlentities($_POST['pojemnosc'], ENT_QUOTES);
                        $moc = htmlentities($_POST['moc'], ENT_QUOTES);
                        
                        if(isset($_FILES['zdjecie']))
                        {
                            
                            if($_FILES['zdjecie']['error']>0)
                            {
                                 if(!isset($_GET['id']))
                                 loadContent($marka, $model, $rok, $przebieg, $nadwozie, $skrzynia, $paliwo, $pojemnosc,$moc,$cena,"Dodaje zdjęcie - ".$_FILES['zdjecie']['error']);
                            }
                            else
                            {
                                $validExts = array('jpg', 'jpeg','png');
                                $ext = @end(explode('.',$_FILES['zdjecie']['name']));
                                
                                if($_FILES['zdjecie']['type']=='image/jpeg' || $_FILES['zdjecie']['type']=='image/png' && in_array($ext,$validExts))
                                {
                                    
                                    $_FILES['zdjecie']['name']= date("U").'.'.$ext;
                                   
                                  
                                    $file_tmp_name = $_FILES['zdjecie']['tmp_name'];
                                    $file_name = $_FILES['zdjecie']['name'];
                                    
                                    move_uploaded_file($file_tmp_name, 'img/offer_img/'.$file_name);
                                  }
                                else
                                {
                                    loadContent($marka, $model, $rok, $przebieg, $nadwozie, $skrzynia, $paliwo, $pojemnosc,$moc,$cena,$_FILES['zdjecie']['type']." - Błędny format pliku");
                                }
                                
                            }
                        }
                        
                        
                        if(isset($_GET['id']) && $_GET['id']!="")
                        {
                            $query = "UPDATE offers SET marka = ?, model =?, rok_prod=?, przebieg=?, nadwozie=?, skrzynia_biegow=?, rodzaj_paliwa=?, pojemnosc=?, moc=?, cena=? WHERE id=?";
                             
                            if($file_name!='')
                            {
                                
                                $picQuery = "";
                                
                                if($result= $db->query('SELECT `zdjecie` FROM `offers` WHERE id='.$_GET['id']))
                                {
                                    $row=$result->fetch_object();
                                   
                                   unlink('img/offer_img/'.$row->zdjecie);
                                    
                                }
                                
                                 $query = "UPDATE offers SET marka = ?, model =?, rok_prod=?, przebieg=?, nadwozie=?, skrzynia_biegow=?, rodzaj_paliwa=?, pojemnosc=?, moc=?, cena=?, zdjecie=? WHERE id=?";
                                if($stmt= $db->prepare($query))
                                 {
                                   
                                    $stmt->bind_param("ssiisssdiisi",$marka,$model, $rok,$przebieg,$nadwozie,$skrzynia,$paliwo,$pojemnosc,$moc,$cena,$file_name,$_POST['id']);
                                    $stmt->execute();
                                    $stmt->close();
                                }
                            }
                            else
                            {
                                
                                
                                 if($stmt= $db->prepare($query))
                                 {
                                     
                                    $stmt->bind_param("ssiisssdiii",$marka,$model, $rok,$przebieg,$nadwozie,$skrzynia,$paliwo,$pojemnosc,$moc,$cena,$_POST['id']);
                                    $stmt->execute();
                                    $stmt->close();
                                }
                            }
                             
                           
                            @header("location: panel.php");
                        }
                        else
                        {
                                
                            $query = "INSERT offers(marka, model, rok_prod,przebieg, nadwozie,skrzynia_biegow, rodzaj_paliwa, pojemnosc, moc, cena, zdjecie, data_dodania)
                            VALUES(?,?,?,?,?,?,?,?,?,?,?,?);";
                            if($file_name!="")
                            {
                                
                                if($stmt= $db->prepare($query))
                                {
                                    $stmt->bind_param("ssiisssdiiss",$marka,$model, $rok,$przebieg,$nadwozie,$skrzynia,$paliwo,$pojemnosc,$moc,$cena, $file_name, date("Y-m-d"));
                                    $stmt->execute();
                                    $stmt->close();
                                }
                                @header("location: panel.php");
                            }
                            else
                            {
                                 loadContent($marka, $model, $rok, $przebieg, $nadwozie, $skrzynia, $paliwo, $pojemnosc,$moc,$cena,"Musisz dodać zdjęcie");
                            }
                        }
                }
                else
                {
                    loadContent($marka, $model, $rok, $przebieg, $nadwozie, $skrzynia, $paliwo, $pojemnosc,$moc,$cena,"Musisz wypełić wszystkie pola");
                }
            
            
        }
        else
        {
            if(isset($_GET['id']) && $_GET['id']!="")
            {
                #modyfikacja istniejącej oferty
                
                
                if($stmt = $db->prepare("SELECT marka, model,rok_prod,przebieg,nadwozie,skrzynia_biegow,rodzaj_paliwa,pojemnosc,moc,cena FROM offers WHERE id=? LIMIT 1"))
                {
                    $stmt->bind_param("i",$_GET['id']);
                    $stmt->execute();
                    $stmt->bind_result($marka, $model, $rok, $przebieg, $nadwozie, $skrzynia, $paliwo,$pojemnosc,$moc, $cena);
                    $stmt->fetch();
                    $stmt->close();
                    
                    loadContent($marka, $model, $rok, $przebieg, $nadwozie, $skrzynia, $paliwo, $pojemnosc,$moc,$cena);
                }
                
                
            }
            else 
            {
                #dodanie nowej oferty
            loadContent();
                
            }
        }
        
    }
    else 
    {
        header("location: logout.php");
    }
    
    
    
    function loadContent($mar=null, $mod=null, $rok=null, $prz=null, $nadw=null, $skrz=null, $pal=null, $poj=null,$moc=null,$c=null, $err="")
    {
        ?>
        <!DOCTYPE HTML>
            <html>
                <head>
                    <title>Panel Administracyjny</title>
                    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
                    <link rel="stylesheet" href="styles/panel.css">
                    

                    <!-- jQuery library -->
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

                    <!-- Latest compiled JavaScript -->
                    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

                    <script src="scripts/layout_anims.js"></script>
                </head>
                <body>
                    
                        <div class="container-fluid" id="contact">
                              <p class="user-info">Użytkownik: [<a href="user_account.php"><?php echo $_SESSION['user'];?></a>]<a href="logout.php">Wyloguj</a></p>
                        <h2 class="text-center">Komis samochodowy - Panel administracyjny</h2>
                        <h3 class="text-center">
                        
                            <?php 
                            if(isset($_GET['id']))
                            {
                                echo "Zaktualizuj ofertę";
                             }
                              else {
                                  echo " Dodaj ofertę";
                                  }
                                  ?>
                                  </h3>
                            
                            <hr>
                            <div class="row text-center">
                             <div class="col-sm-3"  ></div> 
                             
                            <div class="col-sm-5">
                                <p class="text-danger"><?php echo $err; ?></p> 
                                <form method="post" enctype="multipart/form-data">
                                <input type='hidden' name="id" <?php if(isset($_GET['id'])) echo "value='".$_GET['id']."'";?> />
                                <div class="form-group">
                                    <label for="marka">Marka:</label>
                                    <input type="text" class="form-control" name="marka" placeholder="np: Volkswagen" <?php ($mar!=null)? print "value='".$mar."'" : print "value=''"?> /><br />
                                </div>
                                
                                <div class="form-group">
                                        <label for="model">Model samochodu:</label>
                                        <input type="text" class="form-control" name="model" placeholder="np: Golf V" <?php ($mod!=null)? print "value='".$mod."'" : print "value=''"?> /><br />
                                </div>

                                <div class="form-group">
                                            <label for="rok">Rok produkcji:</label>
                                            <input type="text" class="form-control" name="rok" placeholder="np: 2010" <?php ($rok!=null)? print "value='".$rok."'" : print "value=''"?>/><br />
                                        </div>

                                <div class="form-group">
                                            <label for="przebieg">Przebieg:</label> [km]
                                            <input type="text" class="form-control" name="przebieg" placeholder="np: 230000" <?php ($prz!=null)? print "value='".$prz."'" : print "value=''"?>/><br />
                                        </div>

                                <div class="form-group">
                                            <label for="nadwozie">Rodzaj nadwozia:</label>
                                            <select name="nadwozie" id="carBody" onchange="cbodyChanged()" class="form-control">
                                                <option  value="">Wybierz </option>
                                                <option <?php checkSelection($nadw,"Hatchback");?> value="Hatchback">Hatchback</option>
                                                <option <?php checkSelection($nadw,"Sedan");?> value="Sedan">Sedan</option>
                                                <option <?php checkSelection($nadw,"SUV");?> value="SUV">SUV</option>
                                                <option <?php checkSelection($nadw,"Coupe");?> value="Coupe">Coupe</option>
                                            </select><br />
                                        </div>

                                <div class="form-group">
                                            <label for="skrzynia">Skrzynia biegów:</label>
                                            <input type="text" class="form-control" name="skrzynia" placeholder="np: manualna" <?php ($skrz!=null)? print "value='".$skrz."'" : print "value=''"?>/><br />
                                        </div>

                                <div class="form-group">
                                            <label for="paliwo">Rodzaj paliwa:</label>
                                            <select name="paliwo" id="filter_fuel" onchange="fuelChanged()" class="form-control">
                                                <option value="">Wybierz</option>
                                                <option <?php checkSelection($pal,"Benzyna");?> value="Benzyna">Benzyna</option>
                                                <option <?php checkSelection($pal,"Benzyna+gaz");?> value="Benzyna+gaz">Benzyna+gaz</option>
                                                <option <?php checkSelection($pal,"Diesel");?> value="Diesel">Diesel</option>
                                            </select><br />
                                        </div>

                                <div class="form-group">
                                            <label for="pojemnosc">Pojemność silnika:</label> [cm3]
                                            <input type="text" class="form-control" name="pojemnosc" placeholder="np: 1600" <?php ($poj!=null)? print "value='".$poj."'" : print "value=''"?> /><br />
                                        </div>

                                <div class="form-group">
                                            <label for="moc">Moc:</label> [KM]
                                            <input type="text" class="form-control" name="moc" placeholder="np: 360" <?php ($moc!=null)? print "value='".$moc."'" : print "value=''"?>/><br />
                                        </div>

                                <div class="form-group">
                                            <label for="cena">Cena:</label> [PLN]
                                            <input type="text" class="form-control" name="cena" placeholder="np: 23000" <?php ($c!=null)? print "value='".$c."'" : print "value=''"?> /><br />
                                    </div>

                                <div class="form-group">
                                            <label for="zdjecie">Dodaj zdjęcie:</label>
                                            <input type="file" class="form-control" name="zdjecie" placeholder="" /><br />
                                    </div>

                                    
                                    <input type="submit" name="send" value="Dodaj" class="btn btn-default" />
                                    </form>
                                </div>

                            </div>
                            <hr/>
                            
                            <footer class="container-fluid text-center" id="down">
                                
                       <p>
                            Komis samochodowy<br />
                            Projekt i realizacja Mateusz Mierzyński
                        </p>
                    </footer>
            </body>
        </html>
        <?php
    }
    
    function checkSelection($selected, $optionValue)
    {
        if($selected == $optionValue)
        {
            echo "selected= 'selected' ";
        }
    }
?>