<?php
	 session_start();
	 include('db_connect.php');
    
    if(isset($_SESSION['user']))
    {
        if($_SESSION['user']!='unlogged' && $_SESSION['admin']==1)
        {
            loadContent();
        }
        else 
        {
        	$_SESSION = array();
        	session_destroy();
            header("location:login.php");
        }
    }
    else 
    {
        header("location:login.php");
    }

    function loadContent()
    {?>
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
                        <h3 class="text-center">Użytkownicy</h3>
                        <hr/>
                        <p>
                            <a href="user_add.php">Dodaj użytkownika</a>
                            <a href="panel.php">Ogłoszenia</a>
                        </p>
						
						<?php
                       	include('db_connect.php');
                        if($result = $db->query("SELECT * FROM users"))
                        {
                        	?>
							<table class="table table-striped col-md-10">
							<tr>
								<th>Użytkownik</th>
								<th>Email</th>
								<th>Numer telefonu</th>
								<th>Administrator</th>
								<th></th>
								<th></th>
							</tr>
                        	<?php
                        	while($row = $result->fetch_object())
                        	{
                        		?>
								<tr>
									<td><a href="user_add.php?id=<?php echo $row->id;?>"><?php echo $row->login;?></a></td>
									<td><?php echo $row->email;?></td>
									<td><?php echo $row->phone;?></td>
									<td><input type="checkbox" name="isCheckbox" <?php if($row->isAdmin) echo'checked';?> disabled/></td>
									<td><a href="user_delete.php?id=<?php echo $row->id;?>">Usuń</a></td>
									<td><a href="user_add.php?id=<?php echo $row->id;?>">Edytuj</a></td>

								</tr>
                        		<?php
                        	}
                        	?>

							</table>
                            </div>
                            <footer class="container-fluid text-center" id="down">
                                <p>
                                    Komis samochodowy<br />
                                    Projekt i realizacja Mateusz Mierzyński
                                </p>
                            </footer>
                            </body>
                        	<?php
                        }
    }
?>