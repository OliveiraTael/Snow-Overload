<?php

namespace snow;

use snow\Database;
use Exception;

class Search extends Database{

  public $search_result = array();
  public $search_query = null;
  
  public function __construct(){
    
    parent::__construct();
    if( isset($_GET['query']) ){
      $this -> search_query = $_GET['query'];
    }
  }

  public function getSearchResult(){
    if( isset($this -> search_query) == false ){
      return;
    }

    $search_param = "%" . "%" . $this -> search_query . "%";
    
    $query = "SELECT 
              @product_id := product_snowboarding.product_id as product_id,
              product_snowboarding.name,
              product_snowboarding.price,
              product_snowboarding.color,
              product_snowboarding.description,
              ( SELECT @image_id := product_image_snowboarding.image_id 
                FROM product_image_snowboarding 
                WHERE product_image_snowboarding.product_id = @product_id LIMIT 1 ) as imageID,
              ( SELECT image_file_name 
                FROM image 
                WHERE image_id = @image_id ) as image
              FROM product_snowboarding
              WHERE
              name LIKE ?
              OR
              description LIKE ?
              OR color LIKE ?";

    $statement = $this -> connection -> prepare( $query );
    $statement -> bind_param('sss', $search_param, $search_param, $search_param );
    
    try{
      if( $statement -> execute() == false ){
        throw( new Exception('search error') );
      }
      else{
        $result = $statement -> get_result();
        $items = array();
        while( $row = $result -> fetch_assoc() ){
          array_push( $items, $row );
        }
        $this -> search_result['items'] = $items;
        $this -> search_result['query'] = $this -> search_query;
        return $this -> search_result;
      }
    }
    catch( Exception $exc ){
      echo $exc -> getMessage();
    }
  }

  public function getResults(){
    if( isset($this -> search_query) == false ){
      return;
    }

    $search_param = "%" . "%" . $this -> search_query . "%";
    
    $query = "SELECT 
              @product_id := product_ski.product_id as product_id,
              product_ski.name,
              product_ski.price,
              product_ski.color,
              product_ski.description,
              ( SELECT @image_id := product_image_ski.image_id 
                FROM product_image_ski
                WHERE product_image_ski.product_id = @product_id LIMIT 1 ) as imageID,
              ( SELECT image_file_name 
                FROM image 
                WHERE image_id = @image_id ) as image
              FROM product_ski
              WHERE
              name LIKE ?
              OR
              description LIKE ?
              OR color LIKE ?";

    $statement = $this -> connection -> prepare( $query );
    $statement -> bind_param('sss', $search_param, $search_param, $search_param );
    
    try{
      if( $statement -> execute() == false ){
        throw( new Exception('search error') );
      }
      else{
        $result = $statement -> get_result();
        $items = array();
        while( $row = $result -> fetch_assoc() ){
          array_push( $items, $row );
        }
        $this -> search_result['items'] = $items;
        $this -> search_result['query'] = $this -> search_query;
        return $this -> search_result;
      }
    }
    catch( Exception $exc ){
      echo $exc -> getMessage();
    }
  }

}
?>