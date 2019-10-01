<?php

namespace snow;
use snow\Database;

class ProductSki extends Database{

    public function __construct(){
        
        parent:: __construct();

    }

    public function getProductsSki(){

        $query = "SELECT
                    @product_id := product_ski.product_id as product_id,
                    product_ski.name,
                    product_ski.description,
                    product_ski.price,
                    @image_id := (SELECT image_id
                                    FROM product_image_ski
                                    WHERE product_id = @product_id LIMIT 1) as image_id,
                    (SELECT image_file_name
                    FROM image
                    WHERE image_id = @image_id) as image
                FROM product_ski";
        
        if(isset($_GET['category_id'])){

            $query= $query . " " . "INNER JOIN product_category_ski
                                    ON product_ski.product_id = product_category_ski.product_id
                                    WHERE product_category_ski.category_id = ?";
        }

        $statement = $this -> connection -> prepare($query);

        if(isset($_GET['category_id'])){

            $statement -> bind_param('i', $_GET['category_id']);
        }


        if($statement -> execute()){
            $result = $statement -> get_result();
            $product_array = array();
            while($row = $result -> fetch_assoc()){
                array_push($product_array, $row);
            }

            return $product_array;
        }
    }

}

?>