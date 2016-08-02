<?php 
    
    //config
    require("../includes/config.php");
    
    //render get 
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        render("buy_form.php", ["title" => "Buy"]);
    }
    
    //render post
    else if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // lookup stock
        $stock = lookup($_POST["symbol"]);
        
        if ($stock == false)
        {
            apologize("That's not a valid symbol");
        }
        //check and make sure shares number is valid
        if (preg_match("/^\d+$/", $_POST["shares"]) == false)
        {
            apologize("That's not a valid number of shares");
        }
        
        $shares = $_POST["shares"];
        $rows = CS50::query("SELECT cash FROM users WHERE id = ?", $_SESSION["id"]);
        
        $cash = 0;
        if (count($rows) == 1)
        {
            $cash = $rows[0]["cash"];
        }
        
        if ($stock["price"] * $_POST["shares"] > $cash)
        {
            apologize("You can't afford that.");
        }
        
        $debited = CS50::query("UPDATE users SET cash = cash - ? WHERE id = ?", $stock["price"] * $_POST["shares"], $_SESSION["id"]);
        if ($debited == false)
        {
            apologize("Failed to debit your account");
        }
        
        $bought = CS50::query("INSERT INTO portfolios (user_id, symbol, shares) VALUES(?, ?, ?) 
            ON DUPLICATE KEY UPDATE shares = shares + VALUES(shares)", $_SESSION["id"], $stock["symbol"], $_POST["shares"]);
        if ($bought == false)
        {
            apologize("Failed to buy shares");
        }
        $updated = CS50::query("INSERT INTO history (user_id, trans_type, symbol, shares, price) VALUES(?, ?, ?, ?, ?)",
        $_SESSION["id"], "BUY", $stock["symbol"], $_POST["shares"], $stock["price"]);
        if ($updated == false)
        {
            apologize("Failed to update transaction history");
        }
        
        redirect("/");
    }
?>