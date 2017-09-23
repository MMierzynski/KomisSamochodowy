<?php
    session_start();
    if(isset($_SESSION['user']))
    {
        if($_SESSION['user']!='unlogged')
        {
            if(isset($_GET['id']) && $_GET['id']!="")
            {
                include('db_connect.php');
                
                if($stmt = $db->prepare("DELETE FROM offers WHERE id=? LIMIT 1"))
                {
                    $stmt->bind_param("i",$_GET['id']);
                    $stmt->execute();
                    $stmt->close();
                }
                $db->close();
                header("location: panel.php");
            }
            else 
            {
                header('location:panel.php');
            }
        }
        else 
        {
            header('location:login.php');
        }
    }
    else {
        header('location:login.php');
    }
    
    
    
?>