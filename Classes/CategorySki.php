<?php

namespace snow;
use snow\Database;
use Exception;

class CategorySki extends Database{
    
    public $categories= array();

    public function __construct(){

        parent::__construct();
    }

    public function getCategories(){

        $query= "SELECT category_id, category_name
                    FROM category
                    WHERE category_id > 3 AND category_id < 7";
        
        $statement= $this -> connection -> prepare($query);
        
        try{
            if($statement -> execute() == false){
                throw(new Exception('Category error!'));
            }
            else{
                $result= $statement -> get_result();

                $category_items= array();

                while($row= $result -> fetch_assoc()){
                    array_push($category_items, $row);
                }
                
                $this -> categories['items']= $category_items;
                $this -> categories['active']= $this -> getActiveCategory();
            }
            return $this -> categories;
        }
        catch(Exception $ex){
            echo $ex -> getMessage();
        }

    }

    public function getActiveCategory(){
        
        return(isset($_GET['category_id'])) ? $_GET['category_id'] : null;

    }

}

?>