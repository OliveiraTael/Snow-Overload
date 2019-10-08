<?php

namespace snow;
use snow\Database;

use \Exception;

class ShoppingCart extends Database{

  private $response = array();
  private $errors = array();

  public function __construct(){
    parent::__construct();
  }

  private function getUserAuthStatus(){
    if( session_status() == PHP_SESSION_NONE ){
      session_start();
    }
    return ( isset($_SESSION['auth']) ) ? $_SESSION['auth'] : false;
  }

  public function addItem( $product_id ){
    try{
      if( !$this -> getUserAuthStatus() ){
        throw new Exception('user not authenticated');
      }
      else{
        // get user's cart
        $cart_id = $this -> getCartId( $this -> getUserAuthStatus() );
        //add item to the cart
        if(!$cart_id){
          throw new Exception('cart cannot be found');
        }
      }
    }
    catch( Exception $exc ){
      $this -> errors['cart'] = $exc -> getMessage();
    }
    // query to insert item
    $add_query = " INSERT INTO shopping_cart_item (cart_id,product_id,created) VALUES (?, ?, ?, NOW() ) ";
    
    // database stuff
    try{
      $statement = $this -> connection -> prepare( $add_query );
      if(!$statement){
        throw new Exception('query error');
      }
      if(!$statement -> bind_param('ii', $cart_id, $product_id, $quantity ) ){
        throw new Exception('cannot bind parameter');
      }
      if(!$statement -> execute() ){
        throw new Exception('cannot execute ' . __LINE__ );
      }
    }
    catch( Exception $exc ){
      $this -> errors['database'] = $exc -> getMessage();
    }
    //check for other errors
    try{
      //if the item is already in the database
      if( $this -> connection -> errno == '1062' ){
        throw new Exception('item already in list');
      }
      // if there are other errors
      elseif( $this -> connection -> errno ){
        throw new Exception('error inserting item');
      }
    }
    catch( Exception $exc ){
      $this -> errors['insert'] = $exc -> getMessage();
      
    }
    if( count($this -> errors) == 0 ){
      return true;
    }
    else{
      return false;
    }
  }

  private function getCartId( $account_id, $createnew = true ){

    //find user's cart or create a new one
    $find_query = "SELECT cart_id FROM shopping_cart WHERE account_id = UNHEX( ? )";
    try{
      $statement = $this -> connection -> prepare( $find_query );
      if(!$statement){
        throw new Exception('query error');
      }
      if(!$statement -> bind_param('s', $account_id ) ){
        throw new Exception('cannot bind parameter');
      }
      if(!$statement -> execute() ){
        throw new Exception('cannot bind parameter');
      }
      $result = $statement -> get_result();
    }
    catch( Exception $exc ){
      $this -> errors['database'] = $exc -> getMessage();
      $this -> response['success'] = false;
      $this -> response['errors'] = $this -> errors;
      return false;
    }
    // check result
    if( $result -> num_rows == 0 && $createnew == true ){
      //user does not have a cart so we create it
      $shopping_cart_id = $this -> createShoppingCart( $account_id );
    }
    else{
      $row = $result -> fetch_assoc();
      $shopping_cart_id = $row['cart_id'];
    }
    return $shopping_cart_id;
  }

  private function createShoppingCart( $account_id ){
    
    $create_query = "INSERT INTO shopping_cart (account_id,created,active) VALUES ( UNHEX(?), NOW(), 1 )";
    
    try{
      $statement = $this -> connection -> prepare($create_query);
      if(!$statement){
        throw new Exception('query error');
      }
      if(!$statement -> bind_param('s', $account_id ) ){
        throw new Exception('cannot bind parameter');
      }
      if(!$statement -> execute() ){
        throw new Exception('cannot bind parameter');
      }
      return $this -> connection -> insert_id;
    }
    catch( Exception $exc ){
      return false;
    }
  }

  public function getCartTotal(){
    
    $get_query = "SELECT COUNT(product_id) AS total FROM shopping_cart_item WHERE cart_id = ?";
    
    //get the account id
    $account_id = $this -> getUserAuthStatus();
    if( !$account_id ){
      return false;
    }
    //get the cart id but not create new one
    $cart_id = $this -> getCartId( $account_id, false );
    if( !$cart_id){
      return false;
    }
    $statement = $this -> connection -> prepare( $get_query );
    $statement -> bind_param('i', $cart_id );
    $statement -> execute();
    $result = $statement -> get_result();
    $row = $result -> fetch_assoc();
    return $row['total'];
  }

  public function getCartItemsSnowboards(){
    $items_query = "SELECT @product_id := shopping_cart_item.product_id AS product_id,
    (SELECT @image_id := product_image_snowboarding.image_id 
     FROM product_image_snowboarding WHERE product_image_snowboarding.product_id = @product_id LIMIT 1 ) AS image_id,
    (SELECT image_file_name FROM image WHERE image.image_id = @image_id ) AS image,
        product_snowboarding.name, product_snowboarding.price, product_snowboarding.description
        FROM shopping_cart_item
        INNER JOIN product_snowboarding ON shopping_cart_item.product_id = product_snowboarding.product_id WHERE shopping_cart_item.cart_id = ?";
    
    //get the account id
    $account_id = $this -> getUserAuthStatus();
    if( !$account_id ){
      return false;
    }
    //get the cart id but not create new one
    $cart_id = $this -> getCartId( $account_id, false );
    if( !$cart_id){
      return false;
    }
    $statement = $this -> connection -> prepare( $items_query );
    $statement -> bind_param('i', $cart_id );
    $statement -> execute();
    $result = $statement -> get_result();
    $data = array();
    while( $row = $result -> fetch_assoc() ){
      array_push( $data, $row );
    }
    return $data;
  }

  public function getCartItemsSkis(){
    $items_query = "SELECT @product_id := shopping_cart_item.product_id AS product_id,
    (SELECT @image_id := product_image_ski.image_id 
     FROM product_image_ski WHERE product_image_ski.product_id = @product_id LIMIT 1 ) AS image_id,
    (SELECT image_file_name FROM image WHERE image.image_id = @image_id ) AS image,
        product_ski.name, product_ski.price, product_ski.description
        FROM shopping_cart_item
        INNER JOIN product_ski ON shopping_cart_item.product_id = product_ski.product_id WHERE shopping_cart_item.cart_id = ?";
    
    //get the account id
    $account_id = $this -> getUserAuthStatus();
    if( !$account_id ){
      return false;
    }
    //get the cart id but not create new one
    $cart_id = $this -> getCartId( $account_id, false );
    if( !$cart_id){
      return false;
    }
    $statement = $this -> connection -> prepare( $items_query );
    $statement -> bind_param('i', $cart_id );
    $statement -> execute();
    $result = $statement -> get_result();
    $data = array();
    while( $row = $result -> fetch_assoc() ){
      array_push( $data, $row );
    }
    return $data;
  }

  public function removeItem($product_id){
    
    $delete_query = "DELETE FROM shopping_cart_item WHERE product_id = ? AND cart_id = ?";
    
    //get the account id
    $account_id = $this -> getUserAuthStatus();
    if( !$account_id ){
      return false;
    }
    //get the cart id but not create new one
    $cart_id = $this -> getCartId( $account_id, false );
    if( !$cart_id){
      return false;
    }
    $statement = $this -> connection -> prepare( $delete_query );
    $statement -> bind_param('ii', $product_id, $cart_id );
    if( $statement -> execute() ){
      return true;
    }
    else{
      return false;
    }
  }
}

?>