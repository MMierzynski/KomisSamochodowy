<?php
    session_start();
    include("db_connect.php");  

    if(isset($_SESSION['user']))
    {
        if($_SESSION['user']!='unlogged')
        {
            if($_SESSION['passChanged'] == 0)
            {
                if($stmt = $db->prepare("DELETE FROM users WHERE id=? LIMIT 1"))
                {
                    $stmt->bind_param("i",$_SESSION['user_id']);
                    $stmt->execute();
                    $stmt->close();
                }
                $db->close();
            }

            unset($_SESSION['user']);
            unset($_SESSION['admin']);
            unset($_SESSION['user_id']);
            unset($_SESSION['passChanged']);
            session_destroy();

            
            
            header('location:login.php');
        }
        else {
            header('location:login.php');
        }
    }
    else {
        header('location:login.php');
    }
?>