<?php 
    include("db_connect.php");
    
    
    $message_content="";
    $message_email="";
    $message_title="";
    $send_message="";
    
    if(isset($_POST['send']))
    {
        
        $message_title = htmlspecialchars($_POST['title']);
        $message_content= htmlspecialchars($_POST['content']);
        $message_email="";
        
        if(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
        {
            $message_email = $_POST['email'];
        
            
        }  
        
        if($_POST['title']!="" && $_POST['content']!="" &&$_POST['email']!="")
        {   
            mail("kontakt@komis-samochodowy.pl",$message_title, $message_content);
            $send_message = "Email został wysłany";
            
            $message_content="";
            $message_email="";
            $message_title="";
            
            
        }
       
    }
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Komis samochodowy</title>

	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <script src="scripts/layout_anims.js"></script>

	<link rel="stylesheet" href="styles/main.css">
</head>
<body data-spy="scroll" data-target=".navbar"  id="top">

	<nav class="navbar navbar-fixed-top navbar-inverse">
		<div class="conntainer-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mainNavbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a href="#top" class="navbar-brand">Komis Samochodowy</a>
			</div>
			<div class="collapse navbar-collapse" id="mainNavbar">
				<ul class="nav navbar-nav navbar-right">
					<li><a href="offers.php">Oferta</a></li>
					<li><a href="#categories">Kategorie</a></li>
					<li><a href="#about">O nas</a></li>
					<li><a href="#contact">Kontakt</a></li>
				</ul>
			</div>
		</div>
	</nav>

	<div class="jumbotron" id="last-offers">
		
		<div id="last-offers-carousel" class="carousel slide" data-ride="carousel">

			<!-- Indicators -->
			<ol class="carousel-indicators">
				<li class="active" data-target="last-offers-carousel" data-slide-to="0"></li>
				<li data-target="last-offers-carousel" data-slide-to="1"></li>
			</ol>
			
			
			<div class="carousel-inner" role="listbox">
				<div class="item active">
                    <div class="row">
                        <div class="col-sm-12">
					        <img src="img/promocja.png" alt="promo" style="width:100%;" class="" />
                        </div> 
                    </div>
				</div>
				<div class="item">
                    <div class="row">
                        <div class="col-sm-12">
                            <img src="img/ubezp.png" alt="promo" style="width:100%;" class="" />
                        </div>
                    </div>
				</div>
				
			</div>

			<!-- controls -->

			<a href="#last-offers-carousel" class="left carousel-control" role="button" data-slide="prev">
				<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a>

			<a href="#last-offers-carousel" class="right carousel-control" role="button" data-slide="next">
				<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a>
		</div>
	</div>


	<div class="container-fluid bg-grey text-center" id="categories">
        <h2>KATEGORIE</h2>
        <h4>Oferujemy samochody w wielu wariantach nadwozia, oto najczęściej wyszukiwane</h4>

        <div class="row text-center">
            <div class="col-sm-4">
                <div class="thumbnail">
                    <a href="offers.php?cbody=Hatchback" class="category" title="Sprawdź naszą ofertę">
                        <img src="img/hatchback.jpg" alt="hatchback"/>
                        <p>Hatchback</p>
                    </a>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="thumbnail">
                    <a href="offers.php?cbody=Sedan" class="category" title="Sprawdź naszą ofertę">
                        <img src="img/sedan.jpg" alt="sedan" />
                        <p>Sedan</p>
                    </a>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="thumbnail">
                    <a href="offers.php?cbody=Kombi" class="category" title="Sprawdź naszą ofertę">
                        <img src="img/kombi.jpg" alt="kombi" />
                        <p>Kombi</p>
                    </a>
                </div>
            </div>

        </div>
        <div class="row text-center">
            <div class="col-sm-4">
                <div class="thumbnail">
                    <a href="offers.php?cbody=SUV" class="category" title="Sprawdź naszą ofertę">
                        <img src="img/suv.jpg" alt="SUV" />
                        <p>SUV</p>
                    </a>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="thumbnail">
                    <a href="offers.php?cbody=Coupe" class="category" title="Sprawdź naszą ofertę">
                        <img src="img/coupe.jpg" alt="Coupe" />
                        <p>Coupe</p>
                    </a>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="thumbnail">
                    <a href="offers.php?cbody=Van" class="category" title="Sprawdź naszą ofertę">
                        <img src="img/van.jpg" alt="Van" />
                        <p>Van</p>
                    </a>
                </div>
            </div>

        </div>
    </div>

	<div class="container-fluid text-center" id="about">
        <h2 class="text-center">O NAS</h2>
        <div class="row text-center">
            <div class="col-sm-8">
                <p>
                    Auto Komis to spłka z ograniczoną odpowiedzilnością utworzona w 1995r. Jesteśmy największym dealerem samochodów używanych w Częstochowie. Posiadamy autoryzowany serwis gwarancyjny i pogwarancyjny marek Volkswagen, Audi oraz BMW.
                </p>

                <p>Oferujemy największy wybór samochodów w regionie, a dzięki naszemu wieloletniemu doświadczeniu oraz pozycji lidera rynku, jesteśmy w stanie dostosować naszą ofertę do indywidualnych potrzeb naszych klientów.</p> 

                <p>Dodatkowo dla naszych klientów oferujemy bezpłatne przeglądy techniczne oraz roczną gwarancję bezawaryjności.</p>
                
            </div>
            <div class="col-sm-4"><img src="img/contact.jpg" /></div>
        </div>

		
	</div>

	<div class="container-fluid" id="contact">
        <h2 class="text-center">KONTAKT</h2>
        <h4 class="text-center">Jeśli masz jakieś pytania lub zainteresowała cię nasza oferta skontaktuj się z nami</h4>
        <div class="row contact-section">
            <div class="col-sm-7  text-center">
                <form method="post" action="index.php#contact">
                    <div class="row">
                        <div class="col-sm-10 form-group">
                            <input type="text" class="form-control" name="title" placeholder="Temat wiadomości" value=<?php echo "'".$message_title."'"; ?> />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-10 form-group">
                            <textarea class="form-control" name="content" placeholder="Treść wiadomości"  rows="5"><?php echo "".$message_content.""; ?></textarea>
                        </div>
                   </div>
                    <div class="row">
                        <div class="col-sm-8 form-group">
                            <input type="text" class="form-control" name="email" value=<?php echo "'".$message_email."'"; ?> placeholder="Twój adres email" />
                        </div>
                        <div class="col-sm-2 form-group">
                            <input type="submit" name="send" value="Wyślij" class="btn btn-default" />
                        </div>
                    </div>
                    <p><?php echo $send_message;?> </p>
                </form>

            </div>
            <div class="col-sm-5">

                <h4><span class="glyphicon glyphicon glyphicon-map-marker">  </span>  Częstochowa, ul. Wesoła 55c</h4>

                <h4><span class="glyphicon glyphicon glyphicon-calendar">  </span>  Godziny otwarcia:</h4>

                <ul>
                    <li><label>pon-piąt.:</label> 08:00 - 20:00</li>
                    <li><label>sobota:</label> 09:00 - 14:00</li>
                    <li><label>niedziela:</label> nieczynne</li>
                </ul>

                <h4><span class="glyphicon glyphicon glyphicon-phone-alt"></span>   Telefon</h4>
                <ul>
                    <li>tel. 34 344-40-10</li>
                    <li>fax. 34 344-40-12</li>
                    <li>tel. kom. 721-123-456</li>
                    <li>tel. kom. 721-123-499</li>
                </ul>
            </div>
        </div>
	</div>
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