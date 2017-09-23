<?php
    include("db_connect.php");
    
    $cbody="";
    $brand="";
    $model="";
    $fuel="";
    $from_year="";
    $to_year="";
    $from_price="";
    $to_price="";
    $from_run="";
    $to_run="";
    
    if(isset($_GET['cbody']))
        $cbody = $_GET['cbody'];
    if(isset($_GET['brand']))
        $brand = $_GET['brand'];
    if(isset($_GET['model']))
        $model = $_GET['model'];
    if(isset($_GET['fuel']))
        $fuel = $_GET['fuel'];
    if(isset($_GET['from_year']))
        $from_year = $_GET['from_year'];
    if(isset($_GET['to_year']))
        $to_year = $_GET['to_year'];
    if(isset($_GET['from_price']))
        $from_price = $_GET['from_price'];
    if(isset($_GET['to_price']))
        $to_price = $_GET['to_price'];
    if(isset($_GET['from_run']))
        $from_run = $_GET['from_run'];
    if(isset($_GET['to_run']))
        $to_run = $_GET['to_run'];
        
        
function addOffer($offer)
{
    ?>
    <div class="col-sm-4">
                    <div class="panel panel-default offer-item">
                        <div class="panel-heading"><img <?php echo'src="img/offer_img/'.$offer->zdjecie.'" alt="'.$offer->marka." ".$offer->model.'"';?> /></div>
                        <div class="panel-body">
                            <h3 class="text-center"><?php echo $offer->marka." ".$offer->model; ?></h3>
                            <table class="table">
                                <tr>
                                    <td>Rok produkcji</td>
                                    <td><?php echo $offer->rok_prod; ?></td>
                                </tr>
                                <tr>
                                    <td>Przebieg</td>
                                    <td><?php echo $offer->przebieg; ?></td>
                                </tr>
                                <tr>
                                    <td>Rodzaj nadwozia</td>
                                    <td><?php echo $offer->nadwozie; ?></td>
                                </tr>
                                <tr>
                                    <td>Skrzynia biegów</td>
                                    <td><?php echo $offer->skrzynia_biegow; ?></td>
                                </tr>
                                <tr>
                                    <td>Paliwo</td>
                                    <td><?php echo $offer->rodzaj_paliwa; ?></td>
                                </tr>
                                <tr>
                                    <td>Pojemność silnika</td>
                                    <td><?php echo $offer->pojemnosc ?></td>
                                </tr>
                                <tr>
                                    <td>Moc</td>
                                    <td><?php echo $offer->moc." KM"; ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="panel-footer text-center">
                            <p><span>Cena </span><?php echo $offer->cena ?></p>
                        </div>
                    </div>
                </div>
                <?php
}

function checkVariable($field, $column, $output)
{
    if(isset($field) && strlen($field)>0)
    {
        if(strlen($output)>0)
        {
            $output.=" AND ";
        }
        $output.=$column."='".$field."'";
    }
    
    return $output;
}

function checkRange($from, $to, $column, $output)
{
    if(isset($from) && strlen($from)>0)
    {
        if(strlen($output)>0)
        {
            $output.=" AND ";
        }
        $output.=$column.">".$from."";
    }
    
    if(isset($to) && strlen($to)>0)
    {
        if(strlen($output)>0)
        {
            $output.=" AND ";
        }
        $output.=$column."<".$to."";
    }
    
    return $output;
}        
        
function prepareQuery()
{
    $query = "SELECT * FROM offers";
    $selection ="";
    
        if(isset($_GET['cbody']))
            $selection= checkVariable($_GET['cbody'],"nadwozie",$selection);
        if(isset($_GET['brand']))
             $selection= checkVariable($_GET['brand'],"marka",$selection);
        if(isset($_GET['model']))     
            $selection= checkVariable($_GET['model'],"model",$selection);
        if(isset($_GET['fuel']))
            $selection= checkVariable($_GET['fuel'],"rodzaj_paliwa",$selection);
        if(isset($_GET['from_year']))
            $selection= checkRange($_GET["from_year"], $_GET["to_year"], "rok_prod",$selection);
        if(isset($_GET['from_price']))
            $selection= checkRange($_GET["from_price"], $_GET["to_price"], "cena",$selection);
        if(isset($_GET['from_run']))
            $selection= checkRange($_GET["from_run"], $_GET["to_run"], "przebieg",$selection);
 
  
    
    if(strlen($selection)>0)
        $query.=" WHERE ".$selection;
   
    
    return $query;
    
    
}        
      
function checkSelection($selected, $optionValue)
{
    if($selected == $optionValue)
    {
        echo "selected= 'selected' ";
    }
}      
        
        
?>
<!DOCTYPE html>
<html>
<head>
    <title>Nasze oferty</title>
	<meta charset="utf-8" />
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="scripts/layout_anims.js"></script>
    <!--<script src="scripts/main.js"></script>-->

    <link rel="stylesheet" href="styles/main.css" />
    <link rel="stylesheet" href="styles/offers.css">
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
                <a href="index.php" class="navbar-brand">Komis Samochodowy</a>
            </div>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#search-box">Szukaj</a></li>
                    <li><a href="#offers">Oferta</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="jumbotron search-box" id="search-box">
        <form id="filter_offers_form">
            <div class="row">
                <div class="form-group col-sm-3">
                    <label for="cbody" class="control-label">Nadwozie</label>
                    <select name="cbody" id="carBody" onchange="cbodyChanged()" class="form-control">
                        <option value="">Typ nadwozia</option>
                        <option <?php checkSelection($cbody,"Hatchback"); ?> value="Hatchback">Hatchback</option>
                        <option <?php checkSelection($cbody,"Sedan"); ?> value="Sedan">Sedan</option>
                        <option <?php checkSelection($cbody,"SUV"); ?> value="SUV">SUV</option>
                        <option <?php checkSelection($cbody,"Coupe"); ?> value="Coupe">Coupe</option>
                    </select>
                </div>
                <div class="form-group col-sm-3">
                    <label for="brand" class="control-label">Marka</label>
                    <input type="text" class="form-control" name="brand" id="filter_brand" value=<?php echo "'".$brand."'"; ?> placeholder="Marka" />
                </div>
                <div class="form-group col-sm-3">
                    <label for="model" class="control-label">Model</label>
                     <input type="text" class="form-control" name="model" id="filter_model" value=<?php echo "'".$model."'"; ?> placeholder="Model" />
                </div>
                <div class="form-group col-sm-3">
                    <label for="fuel" class="control-label">Paliwo</label>
                    <select name="fuel" id="filter_fuel" onchange="fuelChanged()" class="form-control">
                        <option value="">Rodzaj paliwa</option>
                        <option <?php checkSelection($fuel,"Benzyna"); ?> value="Benzyna">Benzyna</option>
                        <option <?php checkSelection($fuel,"Benzyna+gaz"); ?> value="Benzyna+gaz">Benzyna+gaz</option>
                        <option <?php checkSelection($fuel,"Diesel"); ?> value="Diesel">Diesel</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-3">
                    <label for="from_year" class="control-label">Rok od</label>
                    <input type="text" class="form-control" name="from_year" id="filter_from_year" value=<?php echo "'".$from_year."'"; ?> placeholder="Podaj najstarszy rocznik" onchange="checkSelectFill('#filter_from_year')" onfocus="clearInput('#filter_from_year')" />
                </div>
                <div class="form-group col-sm-3">
                    <label for="to_year" class="control-label">Rok do</label>
                    <input type="text" class="form-control" name="to_year" id="filter_to_year" value=<?php echo "'".$to_year."'"; ?> placeholder="Podaj rocznik" onchange="checkSelectFill('#filter_to_year')" onfocus="clearInput('#filter_to_year')" /><br />
                </div>
                <div class="form-group col-sm-3">
                    <label for="from_price" class="control-label">Cena od</label>
                    <input type="text" class="form-control" name="from_price" id="filter_from_price" value=<?php echo "'".$from_price."'"; ?> placeholder="Wpisz najniższą szukaną cenę" onchange="checkSelectFill('#filter_from_price')" onfocus="clearInput('#filter_from_price')" />
                </div>
                <div class="form-group col-sm-3">
                    <label for="To_price" class="control-label">Cena do</label>
                    <input type="text" class="form-control" name="to_price" id="filter_to_price" value=<?php echo "'".$to_price."'"; ?> placeholder="Wpisz najwyższą możliwą kwotę" onchange="checkSelectFill('#filter_to_price')" onfocus="clearInput('#filter_to_price')" />
                </div>
                
            </div>
            <div class="row">
                <div class="form-group col-sm-3">
                    <label for="from_run" class="control-label">Przebieg od</label>
                    <input type="text" class="form-control" name="from_run" id="filter_from_run" value=<?php echo "'".$from_run."'"; ?> placeholder="Najniższy przebieg" onchange="checkSelectFill('#filter_from_run')" onfocus="clearInput('#filter_from_run')" />
                </div>

                <div class="form-group col-sm-3">
                    <label for="to_run" class="control-label">Przebieg do</label>
                    <input type="text" class="form-control" name="to_run" id="filter_to_run" value=<?php echo "'".$to_run."'"; ?> placeholder="Najwyższy akceptowany przebieg" onchange="checkSelectFill('#filter_to_run')" onfocus="clearInput('#filter_to_run')" />
                </div>



                <button type="submit" value="Filtruj" name="filter"  class="col-sm-3 btn btn-primary filter-offers"><span class="glyphicon glyphicon-search"></span>   Szukaj</button>
            </div>
        
        </form>
    </div>

<div class="container-fluid" id="offers">
        <div class="col-sm-12">
            <h2 class="text-center">NASZA OFERTA</h2>
            <p class="pull-right">
                <a href="#down" class="smooth-scroll "><span class="glyphicon glyphicon-chevron-down" title="Idź w dół"></span></a>
            </p>
        </div>
    <?php
        $results = $db->query(prepareQuery());
       
        
        if($results)
        {
            if($results->num_rows>0)
            {
                $countr = 0;
                while($offer = $results->fetch_object())
                {
                    if($countr%3==0)
                    {
                    ?>
                    <div class="row">
                    <?php 
                    }
                    
                    addOffer($offer);
                    
                    if($countr%3==2)
                    {?>
                        </div>
                        <?php
                    }
                    $countr++;
                }
            }
            else {
                ?>
                <h2 class="text-center">Niestety nie znaleziono pasujących ofert.</h2>
                <?php
            }
            
        }
        
        ?>
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

