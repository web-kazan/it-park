<script>
    //1
  /*  
fetch('https://fakestoreapi.com/products/')
.then(function(res) {
    return res.json();
})
.then(function(data) {
    console.log(data);
})
.catch(function(err) {
   var error = `
   <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
  <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
  </symbol>
  <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
  </symbol>
  <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
  </symbol>
</svg>
   <div class="alert alert-dismissible alert-danger d-flex align-items-center fade show" role="alert">
  <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
  <div>
    ${err.message}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
</div>
   `;
   document.querySelector('header').insertAdjacentHTML('afterend',error);
})
*/

</script>

<?php 
//2
// $connect = curl_init();
// curl_setopt($connect,CURLOPT_URL,'https://fakestoreapi.com/products/');
// curl_setopt($connect, CURLOPT_POSTFIELDS, http_build_query(['key' => 'hgyj6789hy7']));
// curl_setopt($connect,CURLOPT_RETURNTRANSFER,true);
// $result = curl_exec($connect);
// curl_close($connect);
// jsonStringify = json_encode()
// jsonparse = json_decode()
// $data = json_decode($result,true);
// var_dump($data);

//3
// $opts = [
//     'http' => [
//         'method' => 'POST',
//         'content' => http_build_query(['key' => 'hgyj6789hy7'])
//     ]
//     ];
// $json = file_get_contents('https://fakestoreapi.com/products/',false,stream_context_create($opts));

?>
<?php include 'inc/db.php';?>
<?php //$_SESSION['isInsert'] = false;?>
<?php 
// if (isset($_SESSION['isInsert']) or $_SESSION['isInsert'] == false)
// unset($_SESSION['isInsert']);
function get_data($url) {
  $json = file_get_contents($url);
  $data = json_decode($json,true);
  return $data;
}

function select_products () {
  global $con;
  $query = "SELECT * FROM `products`";
  $res = mysqli_query($con,$query);
  $products = mysqli_fetch_all($res,MYSQLI_ASSOC);
  return $products;
}
function insert_product_table() {
  global $data, $con, $cats;
  foreach ($data as $product) {
    $product_category = str_replace("'","",$product['category']);
    $ind = array_search($product_category,array_column($cats,'name'));
    $category_id = $cats[$ind]['id'];
    $title = $product['title'];
    $price = $product['price'];
    $description = $product['description'];
    $image = $product['image'];
    $rate = $product['rating']['rate'];
    $count = $product['rating']['count'];

    $query = "INSERT INTO `products` SET title='{$title}', price={$price}, description='{$description}', category_id={$category_id}, image='{$image}', rate={$rate}, count={$count}";
    mysqli_query($con,$query);
  }
}
function insert_categories_table() {
  global $data, $con;
  $categories = [];
  foreach ($data as $product) {
    $categories[] = str_replace("'","",$product['category']);
  }
  $categories = array_unique($categories);
  $query = "INSERT INTO `categories` (name) VALUES ('" . implode("'), ('", $categories) . "')";
  mysqli_query($con,$query);
};

function select_categories() {
  global $con;
  $query = "SELECT * FROM `categories`";
  $res = mysqli_query($con,$query);
  $cats = mysqli_fetch_all($res,MYSQLI_ASSOC);
  return $cats;
}

if (!isset($_SESSION['isInsert']) or $_SESSION['isInsert'] != true) {
  $data = get_data('https://fakestoreapi.com/products/');
    insert_categories_table();
    $cats = select_categories();
    insert_product_table();
    $_SESSION['isInsert'] = true;
  }
  $cats = select_categories();

  $products = select_products();




// unset($_SESSION['isInsert']);
   
  
  // var_dump($_SESSION['isInsert']);
  // $query = 'INSERT INTO `categories` (name) VALUES ';
  // foreach ($categories as $i => $category) {
  //   $query .= '("' . $category . '")';
  //   if ($i+1 < count($categories)) {
  //     $query .= ', ';
  //   }  
  // }
  // echo $query;



?>

