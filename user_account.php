<?php
session_start();
include("db_connect.php");    
    
    if(isset($_SESSION['user']))
    {
        if($_SESSION['user']!='unlogged' && isset($_SESSION['user_id']))
        {
        	//include("db_connect.php");
        	$select_data = "SELECT email, phone FROM users WHERE id =? LIMIT 1";

        	$stmt = $db->prepare($select_data);
        	$stmt->bind_param('i',$_SESSION['user_id']);
        	$stmt->execute();
        	$stmt->bind_result($db_email, $db_numer);
        	$stmt->fetch();

        	$err_msg="";




        	if(isset($_POST['save_pass']))
            {
                if($_POST['current_password']!="" && $_POST['new_password']!="" && $_POST['confirm_password']!="")
                {
                    $current_pass="";
                    $new_pass="";
                    $confirm_pass="";
                    

                    $current_pass = trim($_POST['current_password']);
                    $current_pass = strip_tags($current_pass);
                    $current_pass = htmlspecialchars($current_pass);

                    $new_pass = trim($_POST['new_password']);
                    $new_pass = strip_tags($new_pass);
                    $new_pass = htmlspecialchars($new_pass);

                    $confirm_pass = trim($_POST['confirm_password']);
                    $confirm_pass = strip_tags($confirm_pass);
                    $confirm_pass = htmlspecialchars($confirm_pass);

                    

                    if(strlen($new_pass)<5 || strlen($new_pass)>12)
                    {
                    	$err_msg = "Nowe hasło musi mieć dlugość 5-12 znaków";
                    }
                    else if($new_pass != $confirm_pass)
                    {
                    	$err_msg = "Hasła muszą być identyczne";
                    }
                    else if($new_pass == $current_pass)
                    {
                    	$err_msg="Nowe hasło nie może być takie samo jak obecne";
                    }

                    if($err_msg=="")
                    {
                    
                    	//$select_pass = "SELECT email, phone FROM users WHERE id =? LIMIT 1";
                    	//$select_data = "SELECT email, phone FROM users WHERE id =? LIMIT 1";

        				//$stmt = $db->prepare($select_data);
        				//$stmt->bind_param('i',$_SESSION['user_id']);


                    	$select = "SELECT password FROM users WHERE id=? LIMIT 1";
                    	$stmt = $db->stmt_init();
			        	if($stmt= $db->prepare($select))
			        	{
			        		$stmt->bind_param('i',$_SESSION['user_id']);
        					$stmt->execute();
        					$stmt->bind_result($db_pass);
        					$stmt->fetch();
			        		
			        		$verify = password_verify($current_pass, $db_pass);

				        	if($verify)
				        	{
				        		$new_pass = password_hash($new_pass,PASSWORD_DEFAULT);

				        		 $update = "UPDATE users SET password=?, isPassChanged=1 WHERE id=?";

				        		 $stmt = $db->stmt_init();
				        		if($stmt= $db->prepare($update))
			        			{
					        		$stmt->bind_param("si",$new_pass, $_SESSION['user_id']);
		        					$stmt->execute();
		        					 $stmt->close();
		        					$_SESSION['passChanged'] = 1;
 
		        					loadUserAccContent($db_email,$db_numer,"Hasło zostało zmienione");
		        				}
		        				else
					        	{
					        		
					        		echo "PREPARE ERROR";
					        	}

				        	}
				        	else
				        	{
				        		loadUserAccContent($db_email,$db_numer,"Błędne bieżące hasło");
				        	}
			     
			        	}
			        	else
			        	{
			        		echo "PREPARE ERROR";
			        	}
			        		
			        	
                    }
                    else
                    {
                    	loadUserAccContent($db_email,$db_numer,$err_msg);
                    }


                }
                else
                {
                	loadUserAccContent($db_email,$db_numer,"Aby zmienić hasło musisz wypenić wszystkie pola");
                }
            }
            else if(isset($_POST['save_data']))
            {
            	if($_POST['email']!="" && $_POST['numer']!="")
            	{
            		$email = trim($_POST['email']);
            		$email = strip_tags($email);
            		$email = htmlspecialchars($email);

            		$numer= trim($_POST['numer']);
            		$numer= strip_tags($numer);
            		$numer= htmlspecialchars($numer);

            		//$err_msg = "";
            		if(!filter_var($email,FILTER_VALIDATE_EMAIL))
	                {
	                    $err_msg = "Niepoprawny adres e-mail";
	                }
	                else if(!preg_match('/^[0-9]{9,12}$/', $numer))
	                {
	                    $err_msg = "Niepoprawny numer telefonu";
                	}


                	if($err_msg=="")
                	{
                		$query = "UPDATE users SET email =?, phone=? WHERE id=?";
                         
                        $stmt = $db->stmt_init();    
                        if($stmt= $db->prepare($query))
                        {
                             
                            $stmt->bind_param("ssi",$email, $numer,$_SESSION['user_id']);
                            $stmt->execute();
                            echo $stmt->error;
                            $stmt->close();
                           
                          	header("location:user_account.php");
                        }
                	}
                	else
                	{
                		loadUserAccContent($db_email,$db_numer,$err_msg);
                	}

            		
            	}
                else
                {
                	loadUserAccContent($db_email,$db_numer,"Nie możesz usunąć adresu email ani numeru telefonu");
                }
            }
            else
            {
            	if(isset($_GET['a']) && $_GET['a']=="chngpswrd")
            		loadUserAccContent($db_email,$db_numer,"W celu aktywacji konta zmień hasło, w przeciwnym razie zostanie ono usunięte.");
            	else	
            		loadUserAccContent($db_email,$db_numer);

            }
        }
        else 
        {
            header("location:logout.php");
        }
    }
    else 
    {
        header("location:login.php");
    }


    function loadUserAccContent($email=null, $numer=null, $err = null)
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
                        <p class="user-info">Użytkownik: [<a href="user_account.php?id=<?php echo $_SESSION['user_id'];?>"><?php echo $_SESSION['user'];?></a>]<a href="logout.php">Wyloguj</a></p>
                        <h2 class="text-center">Komis samochodowy - Panel administracyjny</h2>
                         <h3 class="text-center">Ustawienia konta użytkownika</h3>
                        <hr/>
						
						<p><a href="panel.php">Ogłoszenia</a>
                    
                        
                        
                        <?php
                        if($_SESSION['admin'])
                        {?>
                            <a href="users.php">Użytkownicy</a></p>
                       <?php 
                        }
                        ?>

						<div class="row">
							 <p class="text-danger text-center"><?php echo $err; ?></p> 
							<div class="col-md-6 ">
								<div class="panel panel-default">	
									<div class="panel-heading"><h3 class="text-center">Dane użytkownika</h3></div>
									<div class="panel-body">
										<form action="user_account.php" method="post">
											<div class="form-group">
												<label for="email">Email</label>
												<input type="email" name="email" id="email" placeholder="Wpisz adres email" class="form-control" value="<?php echo $email; ?>">
											</div>
											<div class="form-group">
												<label for="numer">Numer telefonu</label>
												<input type="tel" name="numer" id="numer" placeholder="Wpisz numer telefonu" class="form-control" value="<?php echo $numer;  ?>">
											</div>
											<div class="form-group">
												<input type="submit" name="save_data" class="btn btn-primary" value="Zapisz">
											</div>

										</form>

									</div>
								</div>
							</div>
							<div class="col-md-6">
									<div class="panel panel-default">
										<div class="panel-heading"><h3 class="text-center">Zmień hasło</h3></div>
										<div class="panel-body">
											<form action="user_account.php" method="post">
											<div class="form-group">
												<label for="current_password">Aktualne hasło</label>
												<input type="password" name="current_password" id="current_password" placeholder="Wpisz aktualne hasło" class="form-control"/>
											</div>
											<div class="form-group">
												<label for="new_password">Nowe hasło</label>
												<input type="password" name="new_password" id="new_password" placeholder="Wpisz nowe hasło" class="form-control"/>
											</div>
											<div class="form-group">
												<label for="confirm_password">Potwierdź hasło</label>
												<input type="password" name="confirm_password" id="confirm_password" placeholder="Potwierdź hasło" class="form-control"/>
											</div>
											<div class="form-group">
												<input type="submit" name="save_pass" class="btn btn-primary" value="Zapisz">
											</div>

										</form>
										</div>
									</div>
								</div>
						</div>


                    </div>
                </body>
    	<?php
    }