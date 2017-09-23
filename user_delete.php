<?php
    session_start();
    if(isset($_SESSION['user']))
    {
        if($_SESSION['user']!='unlogged' && $_SESSION['admin']==1)
        {
            if(isset($_GET['id']) && $_GET['id']!="")
            {
                $id = trim($_GET['id']);
                $id = strip_tags($id);
                $id= htmlspecialchars($id);

                if($id == $_SESSION['user_id'])
                {
                    header('location:users.php');
                }
                else
                {


                    include('db_connect.php');
                    
                    if($stmt = $db->prepare("DELETE FROM users WHERE id=? LIMIT 1"))
                    {
                        $stmt->bind_param("i",$id);
                        $stmt->execute();
                        $stmt->close();
                    }
                    $db->close();
                    header("location: users.php");
                }
            }
            else 
            {
                header('location:users.php');
            }
        }
        else 
        {
            header('location:logout.php');
        }
    }
    else {
        header('location:login.php');
    }
    
    
    
?>