<?php
    
    // configuration
    require("../includes/config.php");
    
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        render("deposit_form.php", ["title" => "Deposit"]);
    }
    
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if ($_POST["amount"] < 0)
        {
            apologize("You have to enter a positive amount");
        }
        
        else if (is_numeric($_POST["amount"]) == false)
        {
            apologize("You have to enter a number");
        }
        
        $updated = CS50::query("UPDATE users SET cash = cash + ? WHERE id = ?", $_POST["amount"], $_SESSION["id"]);
        if ($updated == false)
        {
            apologize("Failed to deposit funds");
        }
        redirect("/");
    }
    
?>