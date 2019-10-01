<?php

namespace snow;

class Database{

    protected $connection;

    public function __construct(){

        try{
            $this -> connection = mysqli_connect('localhost', 'root', '', 'advanced_web');

            //echo "Database connection successful!";
        }
        catch(Exception $error) {
            echo $sql . "<br>" . $error->getMessage();
        }
    }
        
}

?>