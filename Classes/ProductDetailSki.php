<?php

namespace snow;
use snow\Database;

class ProductDetailSki extends ProductSki{

    public $product_detail = array();

    public function __construct()
    {
        parent:: __construct();
    }

    public function getProductDetail($id){

        $product_query= "SELECT product_id, name, description, price, color
                         FROM product_ski
                         WHERE product_id = ?";
    
        $statement= $this -> connection -> prepare($product_query);
        $statement -> bind_param('i', $id);

        if($statement -> execute()){
            $result= $statement -> get_result();
            $row = $result -> fetch_assoc();
            $this -> product_detail['product'] = $row;
            $this -> product_detail['images'] = $this -> getProductImages($id);
            $this -> product_detail['sizes'] = $this -> getProductSizes($id);
        }

        return $this -> product_detail;
    }

    private function getProductImages($id){

        $images_query= "SELECT product_image_ski.image_id, image_file_name
                        FROM product_image_ski
                        INNER JOIN image
                        ON product_image_ski.image_id = image.image_id
                        WHERE product_id = ?";
        
        $statement= $this -> connection -> prepare($images_query);
        $statement -> bind_param('i', $id);
        $product_images = array();

        if($statement -> execute()){
            $result = $statement -> get_result();
            
            while($row = $result -> fetch_assoc()){
                array_push($product_images, $row);
            }
        }

        return $product_images;
    }

    private function getProductSizes($id){

        $sizes_query= "SELECT product_ski_size.product_id, size
                        FROM product_ski_size
                        INNER JOIN product_ski
                        ON product_ski.product_id = product_ski_size.product_id
                        WHERE product_ski.product_id = ?";
        
        $statement= $this -> connection -> prepare($sizes_query);
        $statement -> bind_param('i', $id);
        $product_sizes = array();

        if($statement -> execute()){
            $result = $statement -> get_result();
            
            while($row = $result -> fetch_assoc()){
                array_push($product_sizes, $row);
            }
        }

        return $product_sizes;
    }

}

?>