<?php

    // configuration
    require("../includes/config.php"); 

    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // get symbol and share data from portfolio database
        $rows = CS50::query("SELECT symbol, shares FROM portfolios WHERE user_id = ?", $_SESSION["id"]);
        $cashquery = CS50::query("SELECT cash FROM users WHERE id = ?", $_SESSION["id"]);
        
        $cash = 0;
        
        if (count($cashquery) == 1)
        {
            $cash = $cashquery[0];
        }
        else
        {
            print("Couln't get the cash value from query");
        }
        
        $positions = [];
        foreach ($rows as $row)
        {
            $stock = lookup($row["symbol"]);
            if ($stock != false)
            {
                $positions[] = [
                    "name" => $stock["name"],
                    "price" => number_format($stock["price"], 2),
                    "shares" => $row["shares"],
                    "symbol" => $row["symbol"],
                    "total" => number_format($stock["price"] * $row["shares"], 2)
                ];
            }
        }
        
        // render portfolio
        render("portfolio.php", ["title" => "Portfolio", "positions" => $positions, "cash" => $cash]);
    }
    

?>