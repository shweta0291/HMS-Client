<!DOCTYPE html>

<?php
include("includes/db.php");
?>
<html>
<head>
	<title>Inserting Product</title>
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<script src="https://sdk.amazonaws.com/js/aws-sdk-2.119.0.min.js"></script>
</head>
<body>
<div class="form">
	<form id="myForm" method="post">
			<h2>Insert New Product Here!</h2>
			<b style="margin: 8px 0;">Product Name:</b>
			<input id="name" class="form-control border-0" name="product_name" type="text" required/>
				<b style="margin: 8px 0;">Product Price:</b>
				<input id="price" class="form-control border-0" name="product_cost"  type="text" required/>
				<b style="margin: 8px 0;">Product Description:</b>
				<textarea class="form-control border-0" name="product_description" id="description" cols="20" rows="10"></textarea>
				<b style="margin: 8px 0;">Product Count:</b>
				<input id="count" class="form-control border-0" name="product_count" type="int" required/>
				<b style="margin: 8px 0;">Product Characteristics:</b>
				<input id="characteristics" name="product_characteristic" class="form-control border-0" type="text" required/>
				<b style="margin: 8px 0;">Product Category:</b>
					<select style="margin-bottom: 18px 0; margin: 8px 0;"id="category" name="product_category" required>
						<option>Select a category</option>
						<?php
							$get_cats = "select * from category";
							$run_cats = mysqli_query($con,$get_cats);

							while($row_cats = mysqli_fetch_array($run_cats)){
								$category_id = $row_cats['category_id'];
								$category_name = $row_cats['category_name'];

								echo "<option value = '$category_id'>$category_name</option>";
							}
						?>
					</select>
                    <br>
				<b style="margin: 8px 0;">Product Status:</b>
				<input id="status" name="product_status" class="form-control border-0" type="text" required/>
	</form>
				<b style="margin: 8px 0;">Product Image:</b>
				<div> <input style="margin: 8px 0;" type="file" id="file-chooser" accept="image/*" required/></div>
				<button style="margin: 8px 0;"id="upload-button" class="btn btn-sm btn-primary" onclick="insert()"> Insert Product </button>
		<div id="results"></div>
</div>

	<script type="text/javascript">
    AWS.config.region = 'us-east-1'; // 1. Enter your region

    AWS.config.credentials = new AWS.CognitoIdentityCredentials({
        IdentityPoolId: 'us-east-1:25ac5efe-7d1a-4d3e-8189-6462202a0569' // 2. Enter your identity pool
    });

    AWS.config.credentials.get(function(err) {
        if (err) alert(err);
        console.log(AWS.config.credentials);
    });

    var bucketName = 'pharmacy-product'; // Enter your bucket name
        var bucket = new AWS.S3({
            params: {
                Bucket: bucketName
            }
        });

        var fileChooser = document.getElementById('file-chooser');
        var button = document.getElementById('upload-button');
        var results = document.getElementById('results');
        button.addEventListener('click', function() {

            var file = fileChooser.files[0];
            var nam= file.name;
            if (file) {

                results.innerHTML = '';
                var objKey = file.name;
                var params = {
                    Key: objKey,
                    ContentType: file.type,
                    Body: file,
                    ACL: 'public-read'
                };

                bucket.putObject(params, function(err, data) {
                    if (err) {
                        results.innerHTML = 'ERROR: ' + err;
                    } else {
                    	listObjs();
                    	document.cookie = "file_name = " +nam;
                        //here you can also add your code to update your database(MySQL, firebase whatever you are using)
                    }
                });
            } else {
                alert("Nothing to upload");
            }
        }, false);
        function listObjs() {
            var prefix = 'testing';
            bucket.listObjects({
                Prefix: prefix
            }, function(err, data) {
                if (err) {
                    results.innerHTML = 'ERROR: ' + err;
                } else {
                    var objKeys = "";
                    data.Contents.forEach(function(obj) {
                        objKeys += obj.Key + "<br>";
                    });
                    results.innerHTML = objKeys;
                }
            });
        }
        function insert(){
        	setTimeout(function(){
        		document.forms["myForm"].submit();
},5000);
        	
        }
        </script>
</body>
</html>
<?php 

	if($_POST){
	
		//getting the text data from the fields
		$product_name = $_POST['product_name'];
		$product_cost = $_POST['product_cost'];
		$product_description = $_POST['product_description'];
		
		//getting the image from the field
		$product_image = $_COOKIE['file_name'];

		$product_count = $_POST['product_count'];
		$product_category= $_POST['product_category'];
		$product_characteristic = $_POST['product_characteristic'];
		$product_status = $_POST['product_status'];
	
		
		 $insert_product = "insert into product (product_name, product_cost, product_description, product_image, product_count, product_category, product_characteristic, product_status) values ('$product_name','$product_cost','$product_description','$product_image','$product_count','$product_category','$product_characteristic', '$product_status')";
		 
		 $insert_pro = mysqli_query($con, $insert_product);
		 
		 if($insert_pro){
		 	setcookie ("file_name", "", time() - 3600);
		 echo "<script>alert('Product Has been inserted!')</script>";
		 echo "<script>window.open('index.php?insert_pro','_self')</script>";
		 }
	}


?>

