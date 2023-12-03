<?php
session_start();
require_once '../config/connect.php';
if(!isset($_SESSION['email']) & empty($_SESSION['email'])){
    header('location: login.php');
}


$location = 'uploads/'; // Provide a default value for $location
$name = $description = $category = $price = ''; // Initialize variables

if(isset($_POST) & !empty($_POST)){
    $prodname = mysqli_real_escape_string($connection, $_POST['productname']);
    $description = mysqli_real_escape_string($connection, $_POST['productdescription']);
    $category = mysqli_real_escape_string($connection, $_POST['productcategory']);
    $price = mysqli_real_escape_string($connection, $_POST['productprice']);

    if(isset($_FILES) & !empty($_FILES)){
        $name = $_FILES['productimage']['name'];
        $size = $_FILES['productimage']['size'];
        $type = $_FILES['productimage']['type'];
        $tmp_name = $_FILES['productimage']['tmp_name'];
       
        $max_size = 1000000;
        $extension = substr($name, strpos($name, '.') +1);

        if(isset($name) & !empty($name)){
                if(($extension == 'jpg' || $extension == 'jpeg') && $type == 'image/jpeg' && $size<=$max_size){
                if(move_uploaded_file($tmp_name, $location.$name)){
                    // $smsg =  "Uploaded Successfully";
                    
                    $sql = "INSERT INTO products (name,description,catid,price,thumb) VALUES ('$prodname', '$description', '$category', '$price', '$location$name')";
                    $res = mysqli_query($connection, $sql);
                    if($res){
                        $smsg = "Product Created Successfully!";
                    }else{
                        $fmsg = "Failed to Create Product";
                    }
                }
            } else {
                $fmsg = "Only JPG files are allowed and should be less than 1MB";
            }
        } else {
            $fmsg = "Please select a file";
        }
         
}else{
    


    $sql = "INSERT INTO products (name,description,catid,price) VALUES ('$prodname', '$description', '$category', '$price')";
    $res = mysqli_query($connection, $sql);
    if($res){
        $smsg = "Product Created Successfully!";
    }else{
        $fmsg = "Failed to Create Product";
    }
}

}




?>
<?php include 'inc/header.php'?>
<?php include 'inc/nav.php'?>

<section id="content">
    <div class="content-blog">
        <div class="container">
        <?php if(isset($fmsg)){ ?><div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
        <?php if(isset($smsg)){ ?><div class="alert alert-success" role="alert"> <?php echo $smsg; ?> </div><?php } ?>  
            <form method="post" enctype="multipart/form-data">
                <div class="form-group" >
                    <label for="Productname">Product Name </label>
                    <input type="text" class="form-control" name="productname" id="Productname" placeholder="Product Name">
                </div>
                <div class="form-group">
                    <label for="productdescription">Product Description</label>
                    <textarea class="form-control" name="productdescription" rows="3"></textarea>
    	        </div>
                <div class="form-group">
                    <label for="productcategory">Product Category </label>
                    <select class="form-control" name="productcategory" id="productcategory">
                        <option value="">---SELECT CATEGORY---</option>
                        <?php
                        $sql = "SELECT * FROM category";
                        $res = mysqli_query($connection, $sql);
                        while ($r = mysqli_fetch_assoc($res)) {

                ?>
                 <option value="<?php echo $r['id']; ?>"><?php echo $r['name']; ?></option>
                        
                <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="productprice">Product Price </label>
                    <input type="text" class="form-control" name="productprice" id="productprice" placeholder="Product Price">
                </div>
                <div class="form-group">
                    <label for="productimage">Product Image </label>
                    <input type="file" name="productimage" id="productimage" placeholder="Product Image">
                    <p class="help-block">Only jpg/png are allowed.</p>
                </div>

                <button type="submit" class="btn btn-default">Submit</button>
            </form>
        </div>
    </div>
</section>


<?php include 'inc/footer.php' ?>
