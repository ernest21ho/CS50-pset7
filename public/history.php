<?php
    
    // configuration
    require("../includes/config.php");
    
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        $rows = CS50::query("SELECT trans_type, time, symbol, shares, price FROM history WHERE user_id = ?", $_SESSION["id"]);
        
        $transactions = [];
        foreach ($rows as $row)
        {
            $transactions[] = [
                "type" => $row["trans_type"],
                "time" => $row["time"],
                "symbol" => $row["symbol"],
                "shares" => $row["shares"],
                "price" => $row["price"]
            ];
        }
        render("history_view.php", ["title" => "History", "transactions" => $transactions]);
    }
    
?>