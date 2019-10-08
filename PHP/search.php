<?php

require('../vendor/autoload.php');

//get user's wishlist total
use snow\WishList;
$wish = new WishList();
$wish_total = $wish -> getWishListTotal();

use snow\Search;

if( isset($_GET['query']) ){
  $search = new Search();
  $ski_search = new Search();
  $result = $search -> getSearchResult();
  $ski_result = $ski_search -> getResults();
}
else{
  $result = '';
}

use snow\Navigation;

$nav = new Navigation();
$nav_items = $nav -> getNavigation();

//create twig loader
$loader = new Twig_Loader_Filesystem('../templates');

//create twig environment
$twig = new Twig_Environment($loader);

//load twig template
$template = $twig -> load('search.twig');

//pass values to twig
echo $template -> render( array(
    'result' => $result,
    'wish' => $wish_total,
    'ski_result' => $ski_result,
    'navigation' => $nav_items,
    'title' => "Search Result for " . $result['query']
  ) );

?>