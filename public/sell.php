<?php
    
    // configuration
    require("../includes/config.php");
    
    // render get view
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        //query the database to get all symbols of stocks owned by user
        $rows = CS50::query("SELECT symbol FROM portfolios WHERE user_id = ?", $_SESSION["id"]);
        render("sell_form.php", ["title" => "Sell", "rows" => $rows]);
    }
    
    // render post view and redirect to portfolio
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // lookup the symbol
        $stock = lookup($_POST["symbol"]);
        
        // error check
        if ($stock ==  false)
        {
            apologize("Whoops something went wrong");
        }
        
        // get number of shares from DB
        $rows = CS50::query("SELECT shares FROM portfolios WHERE user_id = ? AND symbol = ?", $_SESSION["id"], $_POST["symbol"]);
        
        $shares = 0;
        
        // update local variable shares
        if (count($rows) == 1)
        {
            $shares = $rows[0];
        }
        
        //calcualte how much to credit the users account
        $credit = $stock["price"] * $shares["shares"];
        
        //sell the shares
        $sold = CS50::query("DELETE FROM portfolios WHERE user_id = ? AND symbol = ?", $_SESSION["id"], $_POST["symbol"]);
        if ($sold == false)
        {
            apologize("Failed to sell shares");
        }
        
        //credit the user with cash from sold shares
        $credited = CS50::query("UPDATE users SET cash = cash + ? WHERE id = ?", $credit, $_SESSION["id"]);
        if ($credited == false)
        {
            apologize("Failed to credit your account");
        }
        // update transaction history
        $updated = CS50::query("INSERT INTO history (user_id, trans_type, symbol, shares, price) VALUES(?, ?, ?, ?, ?)",
        $_SESSION["id"], "SELL", $stock["symbol"], $shares["shares"], $stock["price"]);
        if ($updated == false)
        {
            apologize("Failed to update transaction history");
        }
        
        redirect("/");
        
    }
    
?>