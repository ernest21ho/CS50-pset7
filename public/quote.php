<?php

    //configuation
    require("../includes/config.php");
    
    //render view if method get
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        render("quote_form.php", ["title" => "Quote"]);
    }
    
    //lookup symbol if method post
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $stock = lookup($_POST["symbol"]);
        
        if($stock == false)
        {
            apologize("That's not a valid symbol");
        }
        
        render("quote_output.php", ["title" =>"Quote Output", "symbol" => $stock["symbol"], 
        "name" => $stock["name"], "price" => number_format($stock["price"], 2)]);
        
    }

?>