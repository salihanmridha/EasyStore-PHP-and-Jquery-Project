<?php
include "../db.php";
session_start();
if(!isset($_SESSION["uid"]) or ($_SESSION["role"] != 1)){
  header("location: ../index.php");
}

if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
  $id = $_GET['id'];
} else {
  header("location: ../index.php");
}



 ?>
 <?php
  $sql = "SELECT * FROM `products` WHERE product_id = $id";
    $results = mysqli_query($con, $sql);
    foreach ($results as $result) {
      $prodTitle = $result['product_title'];
      $prod_price = $result['product_price'];
      $prod_desc = $result['product_desc'];
      $prod_keywords = $result['product_keywords'];
      $prod_img = $result['product_image'];
      $prod_cat = $result['product_cat'];
      $prod_brand = $result['product_brand'];
    }

 ?>

 <?php
   if (isset($_POST['addprod_button'])) {
      $prodName = $_POST['prod_name'];
      $prodPrice = $_POST['prod_price'];
      $prodCat = $_POST['select_category'];
      $prodBrand = $_POST['select_brand'];
      $prodDesc = $_POST['prod_desc'];
      $prodKeyw = $_POST['prod_keyw'];


       $file_name = $_FILES['prod_img']['name'];
       $file_size = $_FILES['prod_img']['size'];
       $file_temp = $_FILES['prod_img']['tmp_name'];

       $uniqueImg = substr(md5(time()), 0, 10);
       $prodImg = $uniqueImg.$file_name;
       $uploaded_image = "../product_images/".$prodImg;

       //$prodImg = $file_name;
       move_uploaded_file($file_temp, $uploaded_image);

       if($file_name == ""){
         $prodImg = $prod_img;

       }

    $sql = "UPDATE `products` SET `product_cat` = '$prodCat', `product_brand` = '$prodBrand', `product_title` = '$prodName', `product_price` = '$prodPrice', `product_desc` = '$prodDesc', `product_image` = '$prodImg', `product_keywords` = '$prodKeyw' WHERE `products`.`product_id` = $id";

     if(mysqli_query($con, $sql)){
 			echo "<script type='text/javascript'>
        alert('Product Updated Successfull');
        window.location.href = 'editproduct.php?id=$id';
      </script>";
 		}
   }
 ?>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Easy Store - Edit Product</title>
		<link rel="stylesheet" href="../css/bootstrap.min.css"/>
		<script src="../js/jquery2.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="../main.js"></script>
		<link rel="stylesheet" type="text/css" href="../style.css">
	</head>
<body>

	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<a href="../index.php" class="navbar-brand">Easy Store</a>
			</div>
			<ul class="nav navbar-nav">
				<li><a href="../index.php"><span class="glyphicon glyphicon-home"></span>Home</a></li>
				<li><a href="../index.php"><span class="glyphicon glyphicon-modal-window"></span>Product</a></li>
			</ul>
		</div>
	</div>
	<p><br/></p>
	<p><br/></p>
	<p><br/></p>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8" id="signup_msg">
				<!--Alert from signup form-->
			</div>
			<div class="col-md-2"></div>
		</div>
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<div class="panel panel-primary">
					<div class="panel-heading">Edit Product</div>
					<div class="panel-body">

					<form method="post" action="" enctype="multipart/form-data">
						<div class="row">
							<div class="col-md-6">
								<label for="prod_name">Product Name</label>
								<input type="text" value="<?php echo $prodTitle; ?>" id="prod_name" name="prod_name" class="form-control" required="required" >
							</div>
							<div class="col-md-6">
								<label for="prod_price">Product Price</label>
								<input value="<?php echo $prod_price; ?>"  type="number" id="prod_price" name="prod_price"class="form-control" required="required" >
							</div>
						</div>

            <div class="row">
							<div class="col-md-12">
								<label for="prod_desc">Product Description</label>
                <textarea name="prod_desc" rows="4" cols="80" class="form-control" required="required" ><?php echo $prod_desc; ?></textarea>
							</div>
						</div>

            <div class="row">
							<div class="col-md-12">
								<label for="prod_keyw">Product Keywords</label>
								<input value="<?php echo $prod_keywords; ?>" type="text" id="prod_keyw" name="prod_keyw"class="form-control" required="required" >
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label for="prod_img">Product Image</label>
								<input type="file" id="prod_img" value="Product Image" name="prod_img"class="form-control">
                <label style="font-weight: normal !important;" for="img_src"><?php echo "<b>Image Source:</b> product_images/" . $prod_img; ?></label>
							</div>
						</div>

            <div class="row">
							<div class="col-md-12">
								<label for="address1">Product Category</label>
                <select class="form-control" name="select_category" required="required" >
                  <?php
                    $category_query = "SELECT * FROM categories";
                    $run_query = mysqli_query($con,$category_query) or die(mysqli_error($con));
                    if(mysqli_num_rows($run_query) > 0){
                      while($row = mysqli_fetch_array($run_query)){
                        $cid = $row["cat_id"];
                        $cat_name = $row["cat_title"];
                        if ($cid == $prod_cat) {
                          echo "<option selected value='$cid'>$cat_name</option>";
                        }
                        if ($cid != $prod_cat) {
                          echo "<option value='$cid'>$cat_name</option>";
                        }
                        //echo "<option value='$cid'>$cat_name</option>";
                      }
                    } else {
                      echo "<option>No Category Found!!</option>
                      ";
                    }


                  ?>

                </select>
							</div>
						</div>


            <div class="row">
							<div class="col-md-12">
								<label for="address1">Product Brand</label>
                <select class="form-control" name="select_brand" required="required" >
                  <?php
                    $brand_query = "SELECT * FROM brands";
                    $run_query = mysqli_query($con,$brand_query);
                    if(mysqli_num_rows($run_query) > 0){
                      while($row = mysqli_fetch_array($run_query)){
                        $bid = $row["brand_id"];
                  			$brand_name = $row["brand_title"];
                        if ($bid == $prod_brand) {
                          echo "<option selected value='$prod_brand'>$brand_name</option>";
                        }
                        if ($bid != $prod_brand) {
                          echo "<option value='$bid'>$brand_name</option>";
                        }
                      }
                    } else {
                      echo "<option>No Brand Found!!</option>";
                    }
                  ?>

                </select>
							</div>
						</div>

						<p><br/></p>
						<div class="row">
              <div class="col-md-12">
								<input style="width:100%;" value="Update Product" type="submit" name="addprod_button"class="btn btn-success btn-lg">
							</div>
						</div>

					</div>
					</form>
					<div class="panel-footer"></div>
				</div>
			</div>
			<div class="col-md-2"></div>
		</div>
	</div>
</body>
</html>
