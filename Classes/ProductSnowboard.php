<?php

namespace snow;
use snow\Database;

class ProductSnowboard extends Database{

    public function __construct(){
        
        parent:: __construct();

    }

    public function getProductsSnowboard(){
        
        $query = "SELECT
                    @product_id := product_snowboarding.product_id as product_id,
                    product_snowboarding.name,
                    product_snowboarding.description,
                    product_snowboarding.price,
                    @image_id := (SELECT image_id
                                    FROM product_image_snowboarding
                                    WHERE product_id = @product_id LIMIT 1) as image_id,
                    (SELECT image_file_name
                    FROM image
                    WHERE image_id = @image_id) as image
                FROM product_snowboarding";
        
        if(isset($_GET['category_id'])){

            $query= $query . " " . "INNER JOIN product_category_snowboarding
                                    ON product_snowboarding.product_id = product_category_snowboarding.product_id
                                    WHERE product_category_snowboarding.category_id = ?";
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