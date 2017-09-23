<?php
    session_start();
    $_SESSION['user']="unlogged";
    
    include("db_connect.php");
    
    $error_message="";
    $login="";
    $password="";
    
    
    
    if($_SESSION['user']=="unlogged")
    {  
        if(isset($_POST['send']))
        {
            if($_POST['login']!="" && $_POST['password']!="")
            {
              $error_message="";
              $login= trim($_POST['login']);
              $login= strip_tags($login);
              $login - htmlspecialchars($login);


              $password = trim($_POST['password']);
              $password= strip_tags($password);
              $password - htmlspecialchars($password);

            
               if(strlen($login)<5)
                {
                  //  $error = true;
                    $error_message = "Login musi mieć co najmniej 5 znaków.";
                }
                else if(!preg_match('/^[a-zA-Z0-9]+$/', $login))
                {   
                   // $error = true;
                    $error_message = "Login musi może składać się wyłącznie z małych oraz wielkich liter i cyrf";
                }
                else if((strlen($password)<5) || (strlen($password)>12))
                {
                   // $error = true;
                    $error_message = "Hasło musi mieć długość 5-12 znaków.";
                }
                else
                {
                    if($stmt = $db->prepare("SELECT id, password, isPassChanged, isAdmin FROM users WHERE login=?"))
                      {
                          $stmt->bind_param("s",$login);
                          
                          $stmt->execute();
                          
                          $stmt->bind_result($id, $pass,$passChanged,$admin);
                          $stmt->fetch();
                          
                          $verify = password_verify($password, $pass);
                        
                          
                           if(!$verify)
                                $error_message .= " Błędne dane logowania";
                            else
                            {
                                $_SESSION['user'] = $login;
                                $_SESSION['admin'] = $admin;
                                $_SESSION['user_id'] = $id;
                                $_SESSION['passChanged'] = $passChanged;

                                
                                $stmt->close();
                                $db->close();
                                

                                if($_SESSION['passChanged']==0)
                                {
                                    header('Location:user_account.php?a=chngpswrd'); 
                                }
                                else
                                    header('Location:panel.php'); 
                                  
                            }
                               
                      }
                }
              
              
            }
            else 
            {
                $error_message="Musisz uzupełnić wszystkie pola";    
            }
        }
    
        showLoginForm($login,$error_message); 
    }
    else {
        header('Location:panel.php');
    } 
     
    
    function showLoginForm($login="", $error="") 
    {
        ?>
            <!DOCTYPE html>
            <html>
                <head>
                    <title>Logowanie użytkownika</title>
                    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

                    <!-- jQuery library -->
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

                    <!-- Latest compiled JavaScript -->
                    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

                    <script src="scripts/layout_anims.js"></script>
                </head>
                <body>
                    <h2 class="text-center">Komis samochodowy - Panel Administracyjny</h2>
                    <h3 class="text-center">Logowanie użytkownika</h3>
                    <div class="col-sm-4"></div>
                    <div class="row">
                        <div class="col-sm-4">
                            
                            <form method="post" role="form">
                                <div class="form-group">
                                    <label for="login" class="label-control">Login</label>
                                    <input type="text" name="login" class="form-control" placeholder="Nazwa użytkownika" value=<?php echo '"'.$login.'"';?>/>
                                </div>
                                
                                <div class="form-group">
                                    <label for="password" class="label-control">Hasło</label>
                                    <input type="password" name="password" class="form-control" placeholder="Hasło"/>
                                </div>
                                
                                <div class="form-group">
                                    <input type="submit" name="send" value="Zaloguj" class="btn btn-info "/>
                                </div>
                                <p class="text-danger text-center"><?php print_r ($error); ?></p>
                            </form>
                            </div>
                    </div>
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
?>
