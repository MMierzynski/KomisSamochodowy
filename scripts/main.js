function loadBrands()
{
    var car_body = $("#carBody").val();

    $.get("data/cars.xml",function(data,status)
    {
        //alert(status);
        var brands = "<option value=''>Marka</option>";
        var brand_tab = [];
        var brand;
        var offer = $(data).find("offer");
        var i;
       
        offer.each(function()
        {
            brand = $(this).find("brand").text();
            
            var body_boolean = true;
            
            if(car_body!== "")
            {
                body_boolean = $(this).find("body").text()===car_body;
            }
            
            console.log("TAB LEN: "+brand_tab.length);
            if(body_boolean)
            {
                if(brand_tab.length==0)
                {
                    brand_tab[0] = brand;
                    brands += "<option value='"+brand+"'>"+brand+"</option>";
                }
                else
                {
                    var isAdded = false;
                    for(i = 0; i<brand_tab.length;i++)
                    {
                        if(brand===brand_tab[i])
                        {
                            isAdded=true;
                            break;
                        }
                    }

                    if(!isAdded)
                    {
                        brands += "<option value='"+brand+"'>"+brand+"</option>";
                        brand_tab[brand_tab.length] = brand;
                    }
                }
                console.log(brand);
            }
            });
        
        console.log(brands);
        $("#filter_brand").removeClass("filled").html(brands);
    });
}

function loadModels()
{
    var brand = $("#filter_brand").val();
    var car_body = $("#carBody").val();
    $.get("data/cars.xml",function(data,status)
    {
        var models = "<option value=''>Model</option>";
        var model_tab = [];
        var model;
        var offer = $(data).find("offer");
        var i;
       
        offer.each(function()
        {
            model = $(this).find("model").text();
            
            var body_boolean = true;
            
            if(car_body!== "")
            {
                body_boolean = $(this).find("body").text()===car_body;
            }
            
            if($(this).find("brand").text()===brand && body_boolean)
            {
                
                console.log("modelTAB LEN: "+model_tab.length);
                if(model_tab.length==0)
                {
                    models += "<option value='"+model+"'>"+model+"</option>";
                    model_tab[0] = model;
                }
                else
                {
                    var isAdded = false;
                    for(i = 0; i<model_tab.length;i++)
                    {
                        if(model===model_tab[i])
                        {
                            isAdded=true;
                            break;
                        }
                    }
                
                    if(!isAdded)
                    {
                        models += "<option value='"+model+"'>"+model+"</option>";
                        model_tab[model_tab.length] = model;
                    }
                }
                console.log(model);
            }
        });
        console.log(models);
        $("#filter_model").removeClass("filled").html(models);
    });
}

function loadFuel()
{
    var brand = $("#filter_brand").val();
    var model = $("#filter_model").val();
    var car_body = $("#carBody").val();
    
    $.get("data/cars.xml",function(data,status)
    {
        var fuels = "<option value=''>Rodzaj paliwa:</option>";
        var fuel_tab = [];
        var fuel;
        var offer = $(data).find("offer");
        var i;
       
        offer.each(function()
        {
            fuel = $(this).find("fuel").text();
            
            var brand_boolean = true;
            var model_boolean = true;
            var body_boolean = true;
            
            if(car_body!== "")
            {
                body_boolean = $(this).find("body").text()===car_body;
            }
            
            if(brand!="")
            {
                brand_boolean = $(this).find("brand").text()===brand;
            }
            if(model!="")
            {
                model_boolean = $(this).find("model").text()===model;
            }
            
            if(brand_boolean && model_boolean && body_boolean)
            {
                
                
                console.log("fuelTAB LEN: "+fuel_tab.length);
                if(fuel_tab.length==0)
                {
                    fuel_tab[0] = fuel;
                    fuels += "<option value='"+fuel+"'>"+fuel+"</option>";
                }
                else
                {
                    var isAdded = false;
                    for(i = 0; i<fuel_tab.length;i++)
                    {
                        if(fuel==fuel_tab[i])
                        {
                            
                            isAdded = true;
                            break;
                        }
                    }
                
                    if(!isAdded)
                    {
                        fuels += "<option value='"+fuel+"'>"+fuel+"</option>";
                        fuel_tab[fuel_tab.length] = fuel;
                    }
                    else
                    {
                         //alert(fuel+" został(a) dodana wcześniej");
                    }
                }
                console.log(fuel);
            }
        });
        console.log(fuels);
        $("#filter_fuel").removeClass("filled").html(fuels);
    });
}

function filterOffers()
{
    var bodyType = "";
    
    
    var car_body_form = $("#carBody").val();
    var brand_form = $("#filter_brand").val();
    var model_form = $("#filter_model").val();
    var fuel_form = $("#filter_fuel").val();
    var from_year_form = $("#filter_from_year").val();
    var to_year_form = $("#filter_to_year").val();
    var from_price_form = $("#filter_from_price").val();
    var to_price_form = $("#filter_to_price").val();
    var from_run_form = $("#filter_from_run").val();
    var to_run_form = $("#filter_to_run").val();
    
    if(car_body_form==="")
    {
        car_body_form = getURLData();
    }
    
    var body_boolean = true;
    var brand_boolean = true;
    var model_boolean = true;
    var fuel_boolean = true;
    var from_year_boolean = true;
    var to_year_boolean = true;
    var from_price_boolean = true;
    var to_price_boolean = true;
    var from_run_boolean = true;
    var to_run_boolean = true;
    
    var form_vals = car_body_form+" -- "+brand_form+" -- "+model_form+" -- "+fuel_form+" -- "+from_year_form+" -- "+to_year_form+" -- "+from_run_form+" -- "+to_run_form+" -- "+from_price_form+" -- "+to_price_form;
    console.log(form_vals);
    var html_data = "";
    
    var offer_count = 0;
    $.get("data/cars.xml",function(data)
    {
        var offer = $(data).find("offer");
        offer.each(function()
        {
            var body=$(this).find("body").text();
            var brand =$(this).find("brand").text();
            var model =$(this).find("model").text();
            var fuel =$(this).find("fuel").text();
            var year =$(this).find("date").text();
            var price =$(this).find("price").text();
            var run =$(this).find("run").text();
            var transmission =$(this).find("transmission").text();
            var capacity =$(this).find("engineCapacity").text();
            var power =$(this).find("enginePower").text();
            var picture = $(this).find("picture").text();
            
                
            if(car_body_form!=="")
            {
                body_boolean = car_body_form === body;   
            }
            if(brand_form !=="")
            {
                brand_boolean = brand_form=== brand;   
            }
            if(model_form!=="")
            {
                model_boolean = model_form=== model;   
            }
            if(fuel_form!=="")
            {
                fuel_boolean = fuel_form=== fuel;   
            }
            if(from_year_form!=="")
            {
                from_year_boolean = parseInt(from_year_form) <= parseInt(year);
            }
            if(to_year_form!=="")
            {
                to_year_boolean = parseInt(to_year_form) >= parseInt(year);
            }
            if(from_price_form!=="")
            {
                from_price_boolean = parseInt(from_price_form) <= parseInt(price);
            }
            if(to_price_form!=="")
            {
                to_price_boolean = parseInt(to_price_form) >= parseInt(price);
            }
            
            if(from_run_form!=="")
            {
                from_run_boolean = parseInt(from_run_form) <= parseInt(run);
            }
            if(to_run_form!=="")
            {
                to_run_boolean = parseInt(to_run_form) >= parseInt(run);
            }
            
            
            if(body_boolean && brand_boolean && model_boolean && fuel_boolean && from_year_boolean && to_year_boolean &&from_price_boolean && to_price_boolean && from_run_boolean && to_run_boolean)
            {
                
                var tab_data="<td class='offerItem'><div class='singleOffer'><img class='mainPreview' src='"+picture+"'/><div class='carName'><span class='brand'>"+brand+"</span><br/><span class='model'>"+model+"</span></div><table class='offerDetails'><tr><th>Rok produkcji</th><td>"+year+"</td></tr><tr><th>Przebieg</th><td>"+run+" km</td></tr><tr><th>Rodzaj paliwa</th><td>"+fuel+"</td></tr><tr> <th>Typ nadwozia</th><td>"+body+"</td></tr><tr> <th>Skrzynia biegów</th><td>"+transmission+"</td></tr><tr> <th>Pojemność</th><td>"+capacity+"cm³</td></tr><tr> <th>Moc</th><td>"+power+"KM</td></tr><tr><th>Cena</th><td>"+price+" zł</td></tr></table></div></td>";
                if(offer_count==0)
                {
                    html_data+="<tr>";
                    html_data+=tab_data;
                    offer_count++;
                }
                else if(offer_count == 1)
                {
                    html_data+=tab_data;
                    html_data+="</tr>"
                    offer_count = 0;
                }
            }
            
        });
        
        $(".mainResultTable").html(html_data);
    });
    
}

function cbodyChanged()
{
    loadBrands();
    brandChanged();
    checkSelectFill("#carBody");
    
}

function brandChanged()
{
    loadModels();
    loadFuel();
    checkSelectFill("#filter_brand");
}

function modelChanged()
{
    loadFuel();
    checkSelectFill("#filter_model");
}

function fuelChanged()
{
    checkSelectFill("#filter_fuel");
}

function checkSelectFill(id)
{
    
    var selector = $(id);
    
    if(selector.val()!=="")
    {
        selector.addClass("filled");
    }
    else
    {
        selector.removeClass();
    }
    
        
}

function clearInput(id)
{
    $(id).val("");
}

function getURLData()
{
    var urlData = window.location.href.split('?');
    
    if(urlData.length==2)
    {
        return urlData[1].split('=')[1];
        
    }
    
    return "";
}



$(document).ready(function()
{
    
    $("#carBody").val(getURLData());
    
    loadBrands();
    brandChanged();
    filterOffers();
});


