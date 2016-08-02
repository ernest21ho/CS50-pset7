<?php
    
    // configuration
    require("../includes/config.php");
    
    // if the user reached the page via GET 
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        //else render form
        render("register_form.php", ["title" => "Register"]);
    }
    
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate the submission
        if (empty($_POST["username"]))
        {
            apologize("You must provide a username");
        }
        else if(empty($_POST["password"]))
        {
            apologize("You must provide a password");
        }
        
        // make sure passwords are the same
        else if($_POST["password"] != $_POST["confirmation"])
        {
            apologize("Your passwords don't match");
        }
        
        // insert into database
        $result = CS50::query("INSERT IGNORE INTO users (username, hash, cash) VALUES(?, ?, 10000.0000)", $_POST["username"], password_hash($_POST["password"], PASSWORD_DEFAULT));
        
        // if insert failed because of duplicate, apologize
        if ($result == false)
        {
            apologize("That username already exists");
        }
        
        // else log user in
        $rows = CS50::query("SELECT LAST_INSERT_ID() AS id");
        $id = $rows[0]["id"];
        
        $_SESSION["id"] = $id;
        
        redirect("/");
    }
?>