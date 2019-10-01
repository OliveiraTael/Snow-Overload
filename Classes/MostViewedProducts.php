<?php

namespace snow;
use snow\Database;

class MostViewedProducts extends Database{

    public function __construct(){
        
        parent:: __construct();

    }

    public function getSnowboardOnSpecial(){
        
        $query = "SELECT
                    @product_id := product_snowboarding.product_id as product_id,
                    product_snowboarding.name,
                    @image_id := (SELECT image_id
                                    FROM product_image_snowboarding
                                    WHERE product_id = @product_id LIMIT 1) as image_id,
                    (SELECT image_file_name
                    FROM image
                    WHERE image_id = @image_id) as image
                    FROM product_snowboarding
                    INNER JOIN product_category_snowboarding
                    ON product_snowboarding.product_id = product_category_snowboarding.product_id
                    WHERE product_snowboarding.onspecial = 1";

        $statement = $this -> connection -> prepare($query);

        if($statement -> execute()){
            $result = $statement -> get_result();
            $product_array = array();
            while($row = $result -> fetch_assoc()){
                array_push($product_array, $row);
            }

            return $product_array;
        }
    }

    public function getSkiOnSpecial(){
        
        $query = "SELECT
                    @product_id := product_ski.product_id as product_id,
                    product_ski.name,
                    @image_id := (SELECT image_id
                                    FROM product_image_ski
                                    WHERE product_id = @product_id LIMIT 1) as image_id,
                    (SELECT image_file_name
                    FROM image
                    WHERE image_id = @image_id) as image
                    FROM product_ski
                    INNER JOIN product_category_ski
                    ON product_ski.product_id = product_category_ski.product_id
                    WHERE product_ski.onspecial = 1";

        $statement = $this -> connection -> prepare($query);

        if($statement -> execute()){
            $result = $statement -> get_result();
            $ski_array = array();
            while($row = $result -> fetch_assoc()){
                array_push($ski_array, $row);
            }

            return $ski_array;
        }
    }

}

?>