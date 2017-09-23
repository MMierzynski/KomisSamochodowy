<?php 
    session_start();
    if(isset($_SESSION['user']) && $_SESSION['user']!="unlogged" && $_SESSION['admin']==1)
    {
        include("db_connect.php");
        
        $id ="";
        $login=""; 
        $email="";
        $numer="";
        $isAdmin =0;

        if(isset($_POST['send']))
        {
           
           
           
            if($_POST['login']!="" && $_POST['email']!="" && $_POST['numer']!="")
            {
                if(isset($_POST['id']) && is_numeric($_POST['id']))
                {
                    $id = $_POST['id'];
                }

                $login = trim($_POST['login']);
                $login = strip_tags($login);
                $login = htmlspecialchars($login);

                $email = trim($_POST['email']);
                $email = strip_tags($email);
                $email = htmlspecialchars($email);

                $numer = trim($_POST['numer']);
                $numer = strip_tags($numer);
                $numer = htmlspecialchars($numer);

                if(isset($_POST['admin']) && $_POST['admin']=='on')
                {
                    $isAdmin = 1;
                } 

                $error_msg ="";
               
                if(strlen($login)<5)
                {
                    $error_msg = "Login musi mieć co najmniej 5 znaków.";
                }
                else if(!preg_match('/^[a-zA-Z0-9]+$/', $login))
                {   
                    $error_msg = "Login musi może składać się wyłącznie z małych oraz wielkich liter i cyrf";
                }
                else if(!filter_var($email,FILTER_VALIDATE_EMAIL))
                {
                    $error_msg = "Niepoprawny adres e-mail";
                }
                else if(!preg_match('/^[0-9]{9,12}$/', $numer))
                {
                    $error_msg = "Niepoprawny numer telefonu";
                }
                else if($login == $_SESSION['user'] && $isAdmin==0)
                {
                    $error_msg= "Nie możesz odebrać sobie uprawnień administratora";
                }

                if($error_msg!="")
                    loadContent($id,$login, $email, $numer, $isAdmin,$error_msg);
                else
                {
                    if(isset($_GET['id']) && $_GET['id']!="")
                    {
                        $query = "UPDATE users SET login = ?, email =?, phone=?, isAdmin=? WHERE id=?";
                         
                            
                        if($stmt= $db->prepare($query))
                        {
                             
                            $stmt->bind_param("sssii",$login,$email, $numer,$isAdmin,$id);
                            $stmt->execute();
                            $stmt->close();
                            header("location: users.php");
                        }
                       
                    }
                    else
                    {
                        $select_query = "SELECT id FROM users WHERE email =?";

                        $stmt=$db->prepare($select_query);
                        $stmt->bind_param("s",$email);
                        $stmt->execute();
                        $stmt->bind_result($user_id);
                        $stmt->fetch();

                        if($user_id!= null)
                        {
                           
                            loadContent($id,$login, $email, $numer, $isAdmin,"Adres email jest już wykorzystywany.");
                        }
                        else
                        {
                            $select_query = "SELECT id FROM users WHERE login = ?";
                            $stmt = $db->prepare($select_query);
                            $stmt->bind_param('s',$login);
                            $stmt->execute();
                            $stmt->bind_result($user_id);
                            $stmt->fetch();

                            if($user_id!=null){
                                loadContent($id,$login, $email, $numer, $isAdmin,"Już istnieje użytkownik o podanym loginie");
                            }
                            else
                            {
                                $pass = "password123";
                                $pass=(password_hash($pass, PASSWORD_DEFAULT));
                                $passChanged = 0;


                            
                               $query = "INSERT INTO users(login, password, email,phone, isAdmin)
                                VALUES(?,?,?,?,?);";
                                
                                $stmt= $db->prepare($query);
                                $stmt->bind_param("ssssi",$login,$pass, $email,$numer,$isAdmin);
                                $stmt->execute();
                                echo $db->error;
                                $stmt->close();
                                header("location: users.php");
                            }
                        }
                    }
                }
            }
            else
            {
                loadContent($id,$login, $email, $numer, $isAdmin,"Musisz wypełić wszystkie pola");
            }   
        }
        else
        {
            if(isset($_GET['id']) && $_GET['id']!="")
            {
                #modyfikacja istniejącej oferty
                
                
                if($stmt = $db->prepare("SELECT login, email, phone, isAdmin FROM users WHERE id=? LIMIT 1"))
                {
                    $stmt->bind_param("i",$_GET['id']);
                    $stmt->execute();
                    $stmt->bind_result($login, $email, $numer, $isAdmin);
                    $stmt->fetch();
                    $stmt->close();
                    
                    loadContent($_GET['id'],$login, $email, $numer, $isAdmin);
                }
                
                
            }
            else 
            {
                #dodanie nowego użytkownika
            loadContent();
                
            }
          
        }
        
    }
    else 
    {
        header("location: login.php");
    }
    

    function loadContent($id=null,$log=null, $email=null, $nr=null, $admin=null, $err="")
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
                          <p class="user-info">Użytkownik: [<a href="user_account.php?id=<?php echo $_SESSION['user_id'];?>"><?php echo $_SESSION['user'];?></a>]<a href="logout.php">Wyloguj</a></p>
                            <h2 class="text-center">Komis samochodowy - Panel administracyjny</h2>
                    
                            <h3 class="text-center">
                            <?php 
                            if(isset($_GET['id']))
                            {
                                echo "Edytuj użytkownika";
                             }
                              else {
                                  echo " Dodaj użytkownika";
                                  }
                                  ?>
                                  </h3>
                            
                            <hr>
                            <div class="row text-center">
                             <div class="col-sm-3"  ></div> 
                             
                            <div class="col-sm-5">
                                <p class="text-danger"><?php echo $err; ?></p> 
                                <form method="post">
                                <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                                <div class="form-group">
                                    <label for="login">Login</label>
                                    <input type="text" class="form-control" name="login" placeholder="Wpisz login" <?php ($log!=null)? print "value='".$log."'" : print "value=''"?> /><br />
                                </div>


                                <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="text" class="form-control" name="email" placeholder="Podaj adres email" <?php ($email!=null)? print "value='".$email."'" : print "value=''"?>/><br />
                                        </div>

                                <div class="form-group">
                                            <label for="numer">Nuner telefonu</label> 
                                            <input type="text" class="form-control" name="numer" placeholder="np: 230000" <?php ($nr!=null)? print "value='".$nr."'" : print "value=''"?>/><br />
                                        </div>

                               
                                <div class="form-group">
                                            <label for="admin">Administrator</label>
                                            <input type="checkbox" class="form-control" name="admin" <?php ($admin!=null)? print "checked" : print ""?>/><br />
                                        </div>
 
                                    <input type="submit" name="send" value="<?php if(isset($_GET['id'])){echo 'Zmień';}else{ echo 'Dodaj';}?>" class="btn btn-default" />
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