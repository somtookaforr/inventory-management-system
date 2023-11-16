<?php
	session_start();
	// Redirect the user to login page if he is not logged in.
	if(!isset($_SESSION['loggedIn'])){
		header('Location: login.php');
		exit();
	}
	
	require_once('inc/config/constants.php');
	require_once('inc/config/db.php');
	require_once('inc/header.html');
?>
  <body>
<?php
	require 'inc/navigation.php';
?>
    <!-- Page Content -->
    <div class="container-fluid">
	  <div class="row">
		<div class="col-lg-2 d-none d-lg-block d-xl-block d-xxl-block">
		<h1 class="my-4"></h1>
			<div class="nav flex-column nav-pills bs-secondary-color" id="v-pills-tab" role="tablist" aria-orientation="vertical">
				<a class="nav-link active" id="v-pills-index-tab" data-toggle="pill" href="#v-pills-index" role="tab" aria-controls="v-pills-index" aria-selected="true">Dashboard</a>
			  	<a class="nav-link" id="v-pills-item-tab" data-toggle="pill" href="#v-pills-item" role="tab" aria-controls="v-pills-item" aria-selected="false">Item</a>
			  	<a class="nav-link" id="v-pills-purchase-tab" data-toggle="pill" href="#v-pills-purchase" role="tab" aria-controls="v-pills-purchase" aria-selected="false">Purchase</a>
			  	<a class="nav-link" id="v-pills-vendor-tab" data-toggle="pill" href="#v-pills-vendor" role="tab" aria-controls="v-pills-vendor" aria-selected="false">Vendor</a>
			  	<a class="nav-link" id="v-pills-sale-tab" data-toggle="pill" href="#v-pills-sale" role="tab" aria-controls="v-pills-sale" aria-selected="false">Sale</a>
			  	<a class="nav-link" id="v-pills-customer-tab" data-toggle="pill" href="#v-pills-customer" role="tab" aria-controls="v-pills-customer" aria-selected="false">Customer</a>
			  	<a class="nav-link" id="v-pills-search-tab" data-toggle="pill" href="#v-pills-search" role="tab" aria-controls="v-pills-search" aria-selected="false">Search</a>
			  	<a class="nav-link" id="v-pills-reports-tab" data-toggle="pill" href="#v-pills-reports" role="tab" aria-controls="v-pills-reports" aria-selected="false">Reports</a>
			</div>
		</div>
		 <div class="col-lg-10">
			<div class="tab-content" id="v-pills-tabContent">
			<div class="tab-pane fade show active" id="v-pills-index" role="tabpanel" aria-labelledby="v-pills-index-tab">
				<div class="card card-outline-secondary my-4">
				  <div class="card-header">Dashboard</div>
					<div class="card-body bg-secondary px-lg-5">
						<div class="row gx-5 mb-5">
							<div class="col-md-8 availableItems rounded">
								<?php
								$available_dataPoints = array();
								//Best practice is to create a separate file for handling connection to database
								try{
									// Creating a new connection.
									// Replace your-hostname, your-db, your-username, your-password according to your database
									$link = new \PDO(   'mysql:host=localhost;dbname=shop_inventory;charset=utf8mb4', //'mysql:host=localhost;dbname=canvasjs_db;charset=utf8mb4',
														'root', //'root',
														'', //'',
														array(
															\PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
															\PDO::ATTR_PERSISTENT => false
														)
													);
									
									$handle = $link->prepare('select stock, itemName from item'); 
									$handle->execute(); 
									$result = $handle->fetchAll(\PDO::FETCH_OBJ);
										
									foreach($result as $row){
										array_push($available_dataPoints, array("y"=> $row->stock, "label"=> $row->itemName));
									}

									$link = null;
								}
								catch(\PDOException $ex){
									print($ex->getMessage());
								}
									
								?>
								<div id="availableItem" style="height: 300px; width: 100%;"></div>													
							</div>

							<div class="col-md-4 customerStatus rounded">
								<?php					
								$customer_dataPoints = array();
								//Best practice is to create a separate file for handling connection to database
								try{
									// Creating a new connection.
									// Replace your-hostname, your-db, your-username, your-password according to your database
									$link = new \PDO(   'mysql:host=localhost;dbname=shop_inventory;charset=utf8mb4', //'mysql:host=localhost;dbname=canvasjs_db;charset=utf8mb4',
														'root', //'root',
														'', //'',
														array(
															\PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
															\PDO::ATTR_PERSISTENT => false
														)
													);
									
									
									$handle = $link->prepare('select * from customer where Status = "Active" ');
									$handle->execute();
									
										
									$act = $handle->rowCount();


									$handle = $link->prepare('select * from customer where Status = "Disabled" ');
									$handle->execute();

									$dis = $handle->rowCount();;

									$customer_dataPoints = [
										["y" => intval($act / ($act + $dis) * 100), "label" => "Active"],
										["y" => intval($dis / ($act + $dis) * 100), "label" => "Disabled"]
									];

									$link = null;
								}
								catch(\PDOException $ex){
									print($ex->getMessage());
								}	
								?>
								<div id="customerStatus" style="height: 300px; width: 100%;"></div>	
							</div>
						</div>
						
						<div class="row gx-5 mb-5 nav nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
							<div class="card-body col-md-8 align-self-center px-4 rounded">
								<div class="card-body rounded row bg-info nav nav-pills ">
									<div class="col-md-9 text-white">
									<h3>Search through your Database</h3> 
									<small>Find a needle in a haystack</small>
									</div>
									<div class="col-md-3">
									<a class="nav-link no-hover btn btn-success text-light" id="v-pills-search-tab" data-toggle="pill" href="#v-pills-search" role="tab" aria-controls="v-pills-search" aria-selected="false">Search</a>
									</div>		
								</div>


								<div class="card-body rounnded row bg-info my-5 rounded">
									<div class="col-md-9 text-white">
									<h3>Download your earnings report</h3> 
									<small>There are so many varities of Packages</small>
									</div>
									<div class="col-md-3">
									<a class="nav-link no-hover btn btn-success text-light" id="v-pills-reports-tab" data-toggle="pill" href="#v-pills-reports" role="tab" aria-controls="v-pills-reports" aria-selected="false">Download</a>
									</div>	
								</div>
							</div>

							<div class="col-md-4 vendorStatus rounded">
								<?php					
									$vendor_dataPoints = array();
									//Best practice is to create a separate file for handling connection to database
									try{
										// Creating a new connection.
										// Replace your-hostname, your-db, your-username, your-password according to your database
										$link = new \PDO(   'mysql:host=localhost;dbname=shop_inventory;charset=utf8mb4', //'mysql:host=localhost;dbname=canvasjs_db;charset=utf8mb4',
															'root', //'root',
															'', //'',
															array(
																\PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
																\PDO::ATTR_PERSISTENT => false
															)
														);
										
										
										$handle = $link->prepare('select * from vendor where Status = "Active" ');
										$handle->execute();
										
											
										$act = $handle->rowCount();


										$handle = $link->prepare('select * from vendor where Status = "Disabled" ');
										$handle->execute();

										$dis = $handle->rowCount();;

										$vendor_dataPoints = [
											["y" => intval($act / ($act + $dis) * 100), "label" => "Active"],
											["y" => intval($dis / ($act + $dis) * 100), "label" => "Disabled"]
										];

										$link = null;
									}
									catch(\PDOException $ex){
										print($ex->getMessage());
									}	
								?>
								<div id="vendorStatus" style="height: 300px; width: 100%;"></div>	
							</div>
						</div>

						<div class="col purchaseData">
							<?php
							// Establish a database connection
							try {
								$pdo = new \PDO(
								'mysql:host=localhost;dbname=shop_inventory;charset=utf8mb4',
								'root',
								'',
								array(
								\PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
								\PDO::ATTR_PERSISTENT => false
								)
								);



								// Query the database to count occurrences and calculate the total 'Price'
								$sql = "SELECT `itemName`, SUM(`unitPrice` * `quantity`) as total_price
								FROM purchase
								GROUP BY `itemName`;
								";
								$stmt = $pdo->query($sql);

								$resultArray = array();

								if ($stmt) {
									while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
										$item = $row['itemName'];
										$totalPrice = $row['total_price'];
								
										// Create an object with 'label' and 'y' properties
										$itemData = array(
											"label" => $item,
											"y" => $totalPrice
										);
								
										// Add the object to the result array
										$resultArray[] = $itemData;
									}
								}
							} catch (\PDOException $e) {
								// Handle database connection errors here
								echo "Database connection failed: " . $e->getMessage();
								exit();
							}

							?>
							<div id="purchaseData" style="height: 300px; width: 100%"></div>	
						</div>
						
						
					</div>
				</div>
			</div>
			  <div class="tab-pane fade" id="v-pills-item" role="tabpanel" aria-labelledby="v-pills-item-tab">
				<div class="card card-outline-secondary my-4">
				  <div class="card-header">Item Details</div>
				  <div class="card-body">
					<ul class="nav nav-tabs" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" data-toggle="tab" href="#itemDetailsTab">Item</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle="tab" href="#itemImageTab">Upload Image</a>
						</li>
					</ul>
					
					<!-- Tab panes for item details and image sections -->
					<div class="tab-content">
						<div id="itemDetailsTab" class="container-fluid tab-pane active">
							<br>
							<!-- Div to show the ajax message from validations/db submission -->
							<div id="itemDetailsMessage"></div>
							<form>
							  <div class="form-row">
								<div class="form-group col-md-6" style="display:inline-block">
								  <label for="itemDetailsItemNumber">Item Number<span class="requiredIcon">*</span></label>
								  <input type="text" class="form-control" name="itemDetailsItemNumber" id="itemDetailsItemNumber" autocomplete="off">
								  <div id="itemDetailsItemNumberSuggestionsDiv" class="customListDivWidth"></div>
								</div>
								<div class="form-group col-md-6">
								  <label for="itemDetailsProductID">Product ID</label>
								  <input class="form-control invTooltip" type="number" readonly  id="itemDetailsProductID" name="itemDetailsProductID" title="This will be auto-generated when you add a new item">
								</div>
							  </div>
							  <div class="form-row">
								  <div class="form-group col-md-6">
									<label for="itemDetailsItemName">Item Name<span class="requiredIcon">*</span></label>
									<input type="text" class="form-control" name="itemDetailsItemName" id="itemDetailsItemName" autocomplete="off">
									<div id="itemDetailsItemNameSuggestionsDiv" class="customListDivWidth"></div>
								  </div>
								  <div class="form-group col-md-6">
									<label for="itemDetailsStatus">Status</label>
									<select id="itemDetailsStatus" name="itemDetailsStatus" class="form-control chosenSelect">
										<?php include('inc/statusList.html'); ?>
									</select>
								  </div>
							  </div>
							  <div class="form-row">
								<div class="form-group col-md-12" style="display:inline-block">
								  <!-- <label for="itemDetailsDescription">Description</label> -->
								  <textarea rows="4" class="form-control" placeholder="Description" name="itemDetailsDescription" id="itemDetailsDescription"></textarea>
								</div>
							  </div>
							  <div class="form-row">
								<div class="form-group col-md-3">
								  <label for="itemDetailsDiscount">Discount %</label>
								  <input type="text" class="form-control" value="0" name="itemDetailsDiscount" id="itemDetailsDiscount">
								</div>
								<div class="form-group col-md-3">
								  <label for="itemDetailsQuantity">Quantity<span class="requiredIcon">*</span></label>
								  <input type="number" class="form-control" value="0" name="itemDetailsQuantity" id="itemDetailsQuantity">
								</div>
								<div class="form-group col-md-3">
								  <label for="itemDetailsUnitPrice">Unit Price<span class="requiredIcon">*</span></label>
								  <input type="text" class="form-control" value="0" name="itemDetailsUnitPrice" id="itemDetailsUnitPrice">
								</div>
								<div class="form-group col-md-3">
								  <label for="itemDetailsTotalStock">Total Stock</label>
								  <input type="text" class="form-control" name="itemDetailsTotalStock" id="itemDetailsTotalStock" readonly>
								</div>
								<div class="form-group col-md-3">
									<div id="imageContainer"></div>
								</div>
							  </div>
							  <button type="button" id="addItem" class="btn btn-success">Add Item</button>
							  <button type="button" id="updateItemDetailsButton" class="btn btn-primary">Update</button>
							  <button type="button" id="deleteItem" class="btn btn-danger">Delete</button>
							  <button type="reset" class="btn" id="itemClear">Clear</button>
							  <style>
  .custom-button {
    display: inline-block;
    border-radius: 10px;
    overflow: hidden;
    color: #fff;
    padding: 10px 20px;
    border: none;
    transition: background-color 0.3s, color 0.3s, transform 0.2s;
    cursor: pointer;
  }

  .custom-button-blue {
    border: 2px solid #0074e4; 
    background-color: #0074e4; 
  }

  .custom-button-blue:hover {
    background-color: #fff;
    color: #0074e4;
    transform: scale(1.05);
  }

  .custom-button-yellow {
    border: 2px solid #ffc107; 
    background-color: #ffc107; 
  }

  .custom-button-yellow:hover {
    background-color: #fff;
    color: #ffc107;
    transform: scale(1.05);
  }

  .custom-button-purple {
    border: 2px solid #6f42c1; 
    background-color: #6f42c1; 
  }

  .custom-button-purple:hover {
    background-color: #fff;
    color: #6f42c1;
    transform: scale(1.05);
  }
</style>
							</form>
						</div>
						<div id="itemImageTab" class="container-fluid tab-pane fade">
							<br>
							<div id="itemImageMessage"></div>
							<p>You can upload an image for a particular item using this section.</p> 
							<p>Please make sure the item is already added to database before uploading the image.</p>
							<br>							
							<form name="imageForm" id="imageForm" method="post">
							  <div class="form-row">
								<div class="form-group col-md-4" style="display:inline-block">
								  <label for="itemImageItemNumber">Item Number<span class="requiredIcon">*</span></label>
								  <input type="text" class="form-control" name="itemImageItemNumber" id="itemImageItemNumber" autocomplete="off">
								  <div id="itemImageItemNumberSuggestionsDiv" class="customListDivWidth"></div>
								</div>
								<div class="form-group col-md-8">
									<label for="itemImageItemName">Item Name</label>
									<input type="text" class="form-control" name="itemImageItemName" id="itemImageItemName" readonly>
								</div>
							  </div>
							  <br>
							  <div class="form-row">
								  <div class="form-group col-md-12">
									<label for="itemImageFile">Select Image ( <span class="blueText">jpg</span>, <span class="blueText">jpeg</span>, <span class="blueText">gif</span>, <span class="blueText">png</span> only )</label>
									<input type="file" class="form-control-file btn btn-dark" id="itemImageFile" name="itemImageFile">
								  </div>
							  </div>
							  <br>
							 <button  type="button" id="updateImageButton" class="btn btn-primary">Upload Image</button>
							  <button type="button" id="deleteImageButton" class="btn btn-danger">Delete Image</button>
							  <button type="reset" class="btn">Clear</button>
							  <style>
    #updateImageButton {
      display: inline-block;
      border: 2px solid #007bff; 
      border-radius: 10px;
      overflow: hidden;
      background-color: #007bff; 
      color: #fff;
      padding: 10px 20px;
      border: none;
      transition: background-color 0.3s, color 0.3s, transform 0.2s;
      cursor: pointer;
      margin: 5px;
    }

    #updateImageButton:hover {
      background-color: #fff;
      color: #007bff;
      transform: scale(1.05);
    }

    
    #deleteImageButton {
      display: inline-block;
      border: 2px solid #dc3545; 
      border-radius: 10px;
      overflow: hidden;
      background-color: #dc3545; 
      color: #fff;
      padding: 10px 20px;
      border: none;
      transition: background-color 0.3s, color 0.3s, transform 0.2s;
      cursor: pointer;
      margin: 5px;
    }

    #deleteImageButton:hover {
      background-color: #fff;
      color: #dc3545;
      transform: scale(1.05);
    }
  </style>







							</form>
						</div>
					</div>
				  </div> 
				</div>
			  </div>
			  <div class="tab-pane fade" id="v-pills-purchase" role="tabpanel" aria-labelledby="v-pills-purchase-tab">
				<div class="card card-outline-secondary my-4">
				  <div class="card-header">Purchase Details</div>
				  <div class="card-body">
					<div id="purchaseDetailsMessage"></div>
					<form>
					  <div class="form-row">
						<div class="form-group col-md-4">
						  <label for="purchaseDetailsItemNumber">Item Number<span class="requiredIcon">*</span></label>
						  <input type="text" class="form-control" id="purchaseDetailsItemNumber" name="purchaseDetailsItemNumber" autocomplete="off">
						  <div id="purchaseDetailsItemNumberSuggestionsDiv" class="customListDivWidth"></div>
						</div>
						<div class="form-group col-md-4">
						  <label for="purchaseDetailsPurchaseDate">Purchase Date<span class="requiredIcon">*</span></label>
						  <input type="text" class="form-control datepicker" id="purchaseDetailsPurchaseDate" name="purchaseDetailsPurchaseDate" readonly value="2018-05-24">
						</div>
						<div class="form-group col-md-4">
						  <label for="purchaseDetailsPurchaseID">Purchase ID</label>
						  <input type="text" class="form-control invTooltip" id="purchaseDetailsPurchaseID" name="purchaseDetailsPurchaseID" title="This will be auto-generated when you add a new record" autocomplete="off">
						  <div id="purchaseDetailsPurchaseIDSuggestionsDiv" class="customListDivWidth"></div>
						</div>
					  </div>
					  <div class="form-row"> 
						  <div class="form-group col-md-4">
							<label for="purchaseDetailsItemName">Item Name<span class="requiredIcon">*</span></label>
							<input type="text" class="form-control invTooltip" id="purchaseDetailsItemName" name="purchaseDetailsItemName" readonly title="This will be auto-filled when you enter the item number above">
						  </div>
						  <div class="form-group col-md-4">
							  <label for="purchaseDetailsCurrentStock">Current Stock</label>
							  <input type="text" class="form-control" id="purchaseDetailsCurrentStock" name="purchaseDetailsCurrentStock" readonly>
						  </div>
						  <div class="form-group col-md-4">
							<label for="purchaseDetailsVendorName">Vendor Name<span class="requiredIcon">*</span></label>
							<select id="purchaseDetailsVendorName" name="purchaseDetailsVendorName" class="form-control chosenSelect">
								<?php 
									require('model/vendor/getVendorNames.php');
								?>
							</select>
						  </div>
					  </div>
					  <div class="form-row">
						<div class="form-group col-md-2">
						  <label for="purchaseDetailsQuantity">Quantity<span class="requiredIcon">*</span></label>
						  <input type="number" class="form-control" id="purchaseDetailsQuantity" name="purchaseDetailsQuantity" value="0">
						</div>
						<div class="form-group col-md-2">
						  <label for="purchaseDetailsUnitPrice">Unit Price<span class="requiredIcon">*</span></label>
						  <input type="text" class="form-control" id="purchaseDetailsUnitPrice" name="purchaseDetailsUnitPrice" value="0">
						  
						</div>
						<div class="form-group col-md-4">
						  <label for="purchaseDetailsTotal">Total Cost</label>
						  <input type="text" class="form-control" id="purchaseDetailsTotal" name="purchaseDetailsTotal" readonly>
						</div>
					  </div>
					  <button type="button" id="addPurchase" class="btn custom-button">Add Purchase</button>

<style>
  .custom-button {
    display: inline-block;
    border: 2px solid #28a745; /* Set your desired border color */
    border-radius: 10px; /* Adjust the border-radius for curved edges */
    overflow: hidden;
  }

  .custom-button {
    background-color: #28a745; /* Set your desired background color */
    color: #fff; /* Set your desired text color */
    padding: 10px 20px;
    border: none;
    transition: background-color 0.3s, color 0.3s, transform 0.2s; /* Added transition for text transformation */
    cursor: pointer;
  }

  .custom-button:hover {
    background-color: #fff; /* Set the hover background color */
    color: #28a745; /* Set the hover text color */
    transform: scale(1.05); /* Scale the button on hover */
  }
</style>

<button type="button" id="updatePurchaseDetailsButton" class="btn custom-button">Update</button>

<style>
  .custom-button {
    display: inline-block;
    border: 2px solid #007bff; /* Set your desired border color */
    border-radius: 10px; /* Adjust the border-radius for curved edges */
    overflow: hidden;
  }

  .custom-button {
    background-color: transparent; /* Set the background color to transparent */
    color: #007bff; /* Set your desired text color */
    padding: 10px 20px;
    border: none;
    transition: background-color 0.3s, color 0.3s, transform 0.2s; /* Added transition for text transformation */
    cursor: pointer;
  }

  .custom-button:hover {
    background-color: #fff; /* Set the hover background color */
    color: #007bff; /* Set the hover text color */
    transform: scale(1.05); /* Scale the button on hover */
  }
</style>


<button type="reset" class="btn custom-button">Clear</button>

<style>
  .custom-button {
    display: inline-block;
    border: 2px solid #555; /* Set your desired border color */
    border-radius: 10px; /* Adjust the border-radius for curved edges */
    overflow: hidden;
  }

  .custom-button {
    background-color: #555; /* Set your desired background color */
    color: #fff; /* Set your desired text color */
    padding: 10px 20px;
    border: none;
    transition: background-color 0.3s, color 0.3s, transform 0.2s; /* Added transition for text transformation */
    cursor: pointer;
  }

  .custom-button:hover {
    background-color: #fff; /* Set the hover background color */
    color: #555; /* Set the hover text color */
    transform: scale(1.05); /* Scale the button on hover */
  }
</style>
					</form>
				  </div> 
				</div>
			  </div>
			  
			  <div class="tab-pane fade" id="v-pills-vendor" role="tabpanel" aria-labelledby="v-pills-vendor-tab">
				<div class="card card-outline-secondary my-4">
				  <div class="card-header">Vendor Details</div>
				  <div class="card-body">
				  <!-- Div to show the ajax message from validations/db submission -->
				  <div id="vendorDetailsMessage"></div>
					 <form> 
					  <div class="form-row">
						<div class="form-group col-md-6">
						  <label for="vendorDetailsVendorFullName">Full Name<span class="requiredIcon">*</span></label>
						  <input type="text" class="form-control" id="vendorDetailsVendorFullName" name="vendorDetailsVendorFullName" placeholder="">
						</div>
						<div class="form-group col-md-3">
							<label for="vendorDetailsStatus">Status</label>
							<select id="vendorDetailsStatus" name="vendorDetailsStatus" class="form-control chosenSelect">
								<?php include('inc/statusList.html'); ?>
							</select>
						</div>
						 <div class="form-group col-md-3">
							<label for="vendorDetailsVendorID">Vendor ID</label>
							<input type="text" class="form-control invTooltip" id="vendorDetailsVendorID" name="vendorDetailsVendorID" title="This will be auto-generated when you add a new vendor" autocomplete="off">
							<div id="vendorDetailsVendorIDSuggestionsDiv" class="customListDivWidth"></div>
						</div>
					  </div>
					  <div class="form-row">
						  <div class="form-group col-md-3">
							<label for="vendorDetailsVendorMobile">Phone (mobile)<span class="requiredIcon">*</span></label>
							<input type="text" class="form-control invTooltip" id="vendorDetailsVendorMobile" name="vendorDetailsVendorMobile" title="Do not enter leading 0">
						  </div>
						  <div class="form-group col-md-3">
							<label for="vendorDetailsVendorPhone2">Phone 2</label>
							<input type="text" class="form-control invTooltip" id="vendorDetailsVendorPhone2" name="vendorDetailsVendorPhone2" title="Do not enter leading 0">
						  </div>
						  <div class="form-group col-md-6">
							<label for="vendorDetailsVendorEmail">Email</label>
							<input type="email" class="form-control" id="vendorDetailsVendorEmail" name="vendorDetailsVendorEmail">
						</div>
					  </div>
					  <div class="form-row">
					  <div class="form-group col-6">
						<label for="vendorDetailsVendorAddress">Address<span class="requiredIcon">*</span></label>
						<input type="text" class="form-control" id="vendorDetailsVendorAddress" name="vendorDetailsVendorAddress">
					  </div>
					  <div class="form-group col-6">
						<label for="vendorDetailsVendorAddress2">Address 2</label>
						<input type="text" class="form-control" id="vendorDetailsVendorAddress2" name="vendorDetailsVendorAddress2">
					  </div>
					  </div>
					  <div class="form-row">
						<div class="form-group col-md-6">
						  <label for="vendorDetailsVendorCity">City</label>
						  <input type="text" class="form-control" id="vendorDetailsVendorCity" name="vendorDetailsVendorCity">
						</div>
						<div class="form-group col-md-6">
						  <label for="vendorDetailsVendorDistrict">District</label>
						  <select id="vendorDetailsVendorDistrict" name="vendorDetailsVendorDistrict" class="form-control chosenSelect">
							<?php include('inc/districtList.html'); ?>
						  </select>
						</div>
					  </div>					  
					  <button type="button" id="addVendor" name="addVendor" class="btn btn-success">Add Vendor</button>
					  <button type="button" id="updateVendorDetailsButton" class="btn btn-primary">Update</button>
					  <button type="button" id="deleteVendorButton" class="btn btn-danger">Delete</button>
					  <button type="reset" class="btn">Clear</button>
					  <style>
  .custom-button {
    display: inline-block;
    border: 2px solid #28a745; 
    border-radius: 10px;
    overflow: hidden;
    background-color: #28a745; 
    color: #fff;
    padding: 10px 20px;
    border: none;
    transition: background-color 0.3s, color 0.3s, transform 0.2s;
    cursor: pointer;
  }

  .custom-button:hover {
    background-color: #fff;
    color: #28a745;
    transform: scale(1.05);
  }

  .custom-button-danger {
    display: inline-block;
    border: 2px solid #dc3545; 
    border-radius: 10px;
    overflow: hidden;
    background-color: #dc3545; 
    color: #fff;
    padding: 10px 20px;
    border: none;
    transition: background-color 0.3s, color 0.3s, transform 0.2s;
    cursor: pointer;
  }

  .custom-button-danger:hover {
    background-color: #fff;
    color: #dc3545;
    transform: scale(1.05);
  }
</style>
					 </form>
				  </div> 
				</div>
			  </div>
			    
			  <div class="tab-pane fade" id="v-pills-sale" role="tabpanel" aria-labelledby="v-pills-sale-tab">
				<div class="card card-outline-secondary my-4">
				  <div class="card-header">Sale Details</div>
				  <div class="card-body">
					<div id="saleDetailsMessage"></div>
					<form>
					  <div class="form-row">
						<div class="form-group col-md-3">
						  <label for="saleDetailsItemNumber">Item Number<span class="requiredIcon">*</span></label>
						  <input type="text" class="form-control" id="saleDetailsItemNumber" name="saleDetailsItemNumber" autocomplete="off">
						  <div id="saleDetailsItemNumberSuggestionsDiv" class="customListDivWidth"></div>
						</div>
						<div class="form-group col-md-3">
							<label for="saleDetailsCustomerID">Customer ID<span class="requiredIcon">*</span></label>
							<input type="text" class="form-control" id="saleDetailsCustomerID" name="saleDetailsCustomerID" autocomplete="off">
							<div id="saleDetailsCustomerIDSuggestionsDiv" class="customListDivWidth"></div>
						</div>
						<div class="form-group col-md-6">
						  <label for="saleDetailsCustomerName">Customer Name</label>
						  <input type="text" class="form-control" id="saleDetailsCustomerName" name="saleDetailsCustomerName" readonly>
						</div>
					  </div>
					  <div class="form-row">
						  <div class="form-group col-md-6">
							<label for="saleDetailsItemName">Item Name</label>
							<!--<select id="saleDetailsItemNames" name="saleDetailsItemNames" class="form-control chosenSelect"> -->
								<?php 
									//require('model/item/getItemDetails.php');
								?>
							<!-- </select> -->
							<input type="text" class="form-control invTooltip" id="saleDetailsItemName" name="saleDetailsItemName" readonly title="This will be auto-filled when you enter the item number above">
						  </div>
						<div class="form-group col-md-3">
							<label for="saleDetailsSaleID">Sale ID</label>
							<input type="text" class="form-control invTooltip" id="saleDetailsSaleID" name="saleDetailsSaleID" title="This will be auto-generated when you add a new record" autocomplete="off">
							<div id="saleDetailsSaleIDSuggestionsDiv" class="customListDivWidth"></div>
						</div>
						  <div class="form-group col-md-3">
							  <label for="saleDetailsSaleDate">Sale Date<span class="requiredIcon">*</span></label>
							  <input type="text" class="form-control datepicker" id="saleDetailsSaleDate" value="2018-05-24" name="saleDetailsSaleDate" readonly>
						  </div>
					  </div>
					  <div class="form-row">
						<div class="form-group col-md-3">
								  <label for="saleDetailsTotalStock">Total Stock</label>
								  <input type="text" class="form-control" name="saleDetailsTotalStock" id="saleDetailsTotalStock" readonly>
								</div>
						<div class="form-group col-md-3">
						  <label for="saleDetailsDiscount">Discount %</label>
						  <input type="text" class="form-control" id="saleDetailsDiscount" name="saleDetailsDiscount" value="0">
						</div>
						<div class="form-group col-md-3">
						  <label for="saleDetailsQuantity">Quantity<span class="requiredIcon">*</span></label>
						  <input type="number" class="form-control" id="saleDetailsQuantity" name="saleDetailsQuantity" value="0">
						</div>
						<div class="form-group col-md-3">
						  <label for="saleDetailsUnitPrice">Unit Price<span class="requiredIcon">*</span></label>
						  <input type="text" class="form-control" id="saleDetailsUnitPrice" name="saleDetailsUnitPrice" value="0">
						</div>
						<div class="form-group col-md-6">
						  <label for="saleDetailsTotal">Total</label>
						  <input type="text" class="form-control" id="saleDetailsTotal" name="saleDetailsTotal">
						</div>
					  </div>
					  <div class="form-row">
						  <div class="form-group col-md-3">
							<div id="saleDetailsImageContainer"></div>
						  </div>
					 </div>
					  <button type="button" id="addSaleButton" class="btn btn-success">Add Sale</button>
					  <button type="button" id="updateSaleDetailsButton" class="btn btn-primary">Update</button>
					  <button type="reset" id="saleClear" class="btn">Clear</button>
					  <style>
    .custom-button {
      padding: 10px 20px;
      border: none;
      border-radius: 4px;
      color: #fff;
      cursor: pointer;
      margin: 5px;
      font-size: 16px;
      text-align: center;
      text-decoration: none;
    }

    .success-button {
      background-color: #28a745; 
    }

    .primary-button {
      background-color: #007BFF; 
    }

    .clear-button {
      background-color: #6C757D; 
    }
  </style>
</form>
				  </div> 
				</div>
			  </div>
			  <div class="tab-pane fade" id="v-pills-customer" role="tabpanel" aria-labelledby="v-pills-customer-tab">
				<div class="card card-outline-secondary my-4">
				  <div class="card-header">Customer Details</div>
				  <div class="card-body">
				  <!-- Div to show the ajax message from validations/db submission -->
				  <div id="customerDetailsMessage"></div>
					 <form> 
					  <div class="form-row">
						<div class="form-group col-md-6">
						  <label for="customerDetailsCustomerFullName">Full Name<span class="requiredIcon">*</span></label>
						  <input type="text" class="form-control" id="customerDetailsCustomerFullName" name="customerDetailsCustomerFullName">
						</div>
						<div class="form-group col-md-3">
							<label for="customerDetailsStatus">Status</label>
							<select id="customerDetailsStatus" name="customerDetailsStatus" class="form-control chosenSelect">
								<?php include('inc/statusList.html'); ?>
							</select>
						</div>
						 <div class="form-group col-md-3">
							<label for="customerDetailsCustomerID">Customer ID</label>
							<input type="text" class="form-control invTooltip" id="customerDetailsCustomerID" name="customerDetailsCustomerID" title="This will be auto-generated when you add a new customer" autocomplete="off">
							<div id="customerDetailsCustomerIDSuggestionsDiv" class="customListDivWidth"></div>
						</div>
					  </div>
					  <div class="form-row">
						  <div class="form-group col-md-3">
							<label for="customerDetailsCustomerMobile">Phone (mobile)<span class="requiredIcon">*</span></label>
							<input type="text" class="form-control invTooltip" id="customerDetailsCustomerMobile" name="customerDetailsCustomerMobile" title="Do not enter leading 0">
						  </div>
						  <div class="form-group col-md-3">
							<label for="customerDetailsCustomerPhone2">Phone 2</label>
							<input type="text" class="form-control invTooltip" id="customerDetailsCustomerPhone2" name="customerDetailsCustomerPhone2" title="Do not enter leading 0">
						  </div>
						  <div class="form-group col-md-6">
							<label for="customerDetailsCustomerEmail">Email</label>
							<input type="email" class="form-control" id="customerDetailsCustomerEmail" name="customerDetailsCustomerEmail">
						</div>
					  </div>
					  <div class="form-row">
					  <div class="form-group col-md-6">
						<label for="customerDetailsCustomerAddress">Address<span class="requiredIcon">*</span></label>
						<input type="text" class="form-control" id="customerDetailsCustomerAddress" name="customerDetailsCustomerAddress">
					  </div>
					  <div class="form-group col-md-6">
						<label for="customerDetailsCustomerAddress2">Address 2</label>
						<input type="text" class="form-control" id="customerDetailsCustomerAddress2" name="customerDetailsCustomerAddress2">
					  </div>
					  </div>
					  <div class="form-row">
						<div class="form-group col-md-6">
						  <label for="customerDetailsCustomerCity">City</label>
						  <input type="text" class="form-control" id="customerDetailsCustomerCity" name="customerDetailsCustomerCity">
						</div>
						<div class="form-group col-md-6">
						  <label for="customerDetailsCustomerDistrict">District</label>
						  <select id="customerDetailsCustomerDistrict" name="customerDetailsCustomerDistrict" class="form-control chosenSelect">
							<?php include('inc/districtList.html'); ?>
						  </select>
						</div>
					  </div>					  
					  <button type="button" id="addCustomer" name="addCustomer" class="btn btn-success">Add Customer</button>
					  <button type="button" id="updateCustomerDetailsButton" class="btn btn-primary">Update</button>
					  <button type="button" id="deleteCustomerButton" class="btn btn-danger">Delete</button>
					  <button type="reset" class="btn">Clear</button>
					  <style>
    .custom-button {
      padding: 10px 20px;
      border: none;
      border-radius: 4px;
      color: #fff;
      cursor: pointer;
      margin: 5px;
      font-size: 16px;
      text-align: center;
      text-decoration: none;
    }

    .success-button {
      background-color: #28a745; /* Green color for success */
    }

    .primary-button {
      background-color: #007BFF; /* Blue color for primary actions */
    }

    .danger-button {
      background-color: #dc3545; /* Red color for danger */
    }

    .clear-button {
      background-color: #6C757D; /* Gray color for clearing/resetting */
    }
  </style>
					 </form>
				  </div> 
				</div>
			  </div>
			  
			  <div class="tab-pane fade" id="v-pills-search" role="tabpanel" aria-labelledby="v-pills-search-tab">
				<div class="card card-outline-secondary my-4">
				<div class="card-header">Search Inventory<button id="searchTablesRefresh" name="searchTablesRefresh" class="btn custom-button-warning float-right btn-sm">Refresh</button></div>

				  <div class="card-body">										
					<ul class="nav nav-tabs" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" data-toggle="tab" href="#itemSearchTab">Item</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle="tab" href="#customerSearchTab">Customer</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle="tab" href="#saleSearchTab">Sale</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle="tab" href="#purchaseSearchTab">Purchase</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle="tab" href="#vendorSearchTab">Vendor</a>
						</li>
					</ul>
  
					<!-- Tab panes -->
					<div class="tab-content">
						<div id="itemSearchTab" class="container-fluid tab-pane active">
						  <br>
						  <p>Use the grid below to search all details of items</p>
						  <!-- <a href="#" class="itemDetailsHover" data-toggle="popover" id="10">wwwee</a> -->
							<div class="table-responsive" id="itemDetailsTableDiv"></div>
						</div>
						<div id="customerSearchTab" class="container-fluid tab-pane fade">
						  <br>
						  <p>Use the grid below to search all details of customers</p>
							<div class="table-responsive" id="customerDetailsTableDiv"></div>
						</div>
						<div id="saleSearchTab" class="container-fluid tab-pane fade">
							<br>
							<p>Use the grid below to search sale details</p>
							<div class="table-responsive" id="saleDetailsTableDiv"></div>
						</div>
						<div id="purchaseSearchTab" class="container-fluid tab-pane fade">
							<br>
							<p>Use the grid below to search purchase details</p>
							<div class="table-responsive" id="purchaseDetailsTableDiv"></div>
						</div>
						<div id="vendorSearchTab" class="container-fluid tab-pane fade">
							<br>
							<p>Use the grid below to search vendor details</p>
							<div class="table-responsive" id="vendorDetailsTableDiv"></div>
						</div>
					</div>
				  </div> 
				</div>
			  </div>
			  
			  <div class="tab-pane fade" id="v-pills-reports" role="tabpanel" aria-labelledby="v-pills-reports-tab">
				<div class="card card-outline-secondary my-4">
				<div class="card-header">Search Inventory<button id="searchTablesRefresh" name="searchTablesRefresh" class="btn custom-button-warning float-right btn-sm">Refresh</button></div>

				  <div class="card-body">										
					<ul class="nav nav-tabs" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" data-toggle="tab" href="#itemReportsTab">Item</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle="tab" href="#customerReportsTab">Customer</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle="tab" href="#saleReportsTab">Sale</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle="tab" href="#purchaseReportsTab">Purchase</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle="tab" href="#vendorReportsTab">Vendor</a>
						</li>
					</ul>
  
					<!-- Tab panes for reports sections -->
					<div class="tab-content">
						<div id="itemReportsTab" class="container-fluid tab-pane active">
							<br>
							<p>Use the grid below to get reports for items</p>
							<div class="table-responsive" id="itemReportsTableDiv"></div>
						</div>
						<div id="customerReportsTab" class="container-fluid tab-pane fade">
							<br>
							<p>Use the grid below to get reports for customers</p>
							<div class="table-responsive" id="customerReportsTableDiv"></div>
						</div>
						<div id="saleReportsTab" class="container-fluid tab-pane fade">
							<br>
							<!-- <p>Use the grid below to get reports for sales</p> -->
							<form> 
							  <div class="form-row">
								  <div class="form-group col-md-3">
									<label for="saleReportStartDate">Start Date</label>
									<input type="text" class="form-control datepicker" id="saleReportStartDate" value="2018-05-24" name="saleReportStartDate" readonly>
								  </div>
								  <div class="form-group col-md-3">
									<label for="saleReportEndDate">End Date</label>
									<input type="text" class="form-control datepicker" id="saleReportEndDate" value="2018-05-24" name="saleReportEndDate" readonly>
								  </div>
							  </div>
							  <button type="button" id="showSaleReport" class="btn btn-dark">Show Report</button>
							  <button type="reset" id="saleFilterClear" class="btn">Clear</button>
							</form>
							<br><br>
							<div class="table-responsive" id="saleReportsTableDiv"></div>
						</div>
						<div id="purchaseReportsTab" class="container-fluid tab-pane fade">
							<br>
							<!-- <p>Use the grid below to get reports for purchases</p> -->
							<form> 
							  <div class="form-row">
								  <div class="form-group col-md-3">
									<label for="purchaseReportStartDate">Start Date</label>
									<input type="text" class="form-control datepicker" id="purchaseReportStartDate" value="2018-05-24" name="purchaseReportStartDate" readonly>
								  </div>
								  <div class="form-group col-md-3">
									<label for="purchaseReportEndDate">End Date</label>
									<input type="text" class="form-control datepicker" id="purchaseReportEndDate" value="2018-05-24" name="purchaseReportEndDate" readonly>
								  </div>
							  </div>
							  <button type="button" id="showPurchaseReport" class="btn btn-dark">Show Report</button>
							  <button type="reset" id="purchaseFilterClear" class="btn">Clear</button>
							  <style>
   
    #showPurchaseReport {
      display: inline-block;
      border: 2px solid #343a40; 
      border-radius: 10px;
      overflow: hidden;
      background-color: #343a40; 
      color: #fff;
      padding: 10px 20px;
      border: none;
      transition: background-color 0.3s, color 0.3s, transform 0.2s;
      cursor: pointer;
      margin: 5px;
    }

    #showPurchaseReport:hover {
      background-color: #fff;
      color: #343a40;
      transform: scale(1.05);
    }

  
    #purchaseFilterClear {
      display: inline-block;
      border: 2px solid #6c757d; 
      border-radius: 10px;
      overflow: hidden;
      background-color: #6c757d; 
      color: #fff;
      padding: 10px 20px;
      border: none;
      transition: background-color 0.3s, color 0.3s, transform 0.2s;
      cursor: pointer;
      margin: 5px;
    }

    #purchaseFilterClear:hover {
      background-color: #fff;
      color: #6c757d;
      transform: scale(1.05);
    }
  </style>







							</form>
							<br><br>
							<div class="table-responsive" id="purchaseReportsTableDiv"></div>
						</div>
						<div id="vendorReportsTab" class="container-fluid tab-pane fade">
							<br>
							<p>Use the grid below to get reports for vendors</p>
							<div class="table-responsive" id="vendorReportsTableDiv"></div>
						</div>
					</div>
				  </div> 
				</div>
			  </div>
			</div>
		 </div>
	  </div>
    </div>
<?php
	require 'inc/footer.php';
?>

	<script>
		window.onload = function () {
			
				var availableChart = new CanvasJS.Chart("availableItem", {
					animationEnabled: true,
					exportEnabled: true,
					theme: "light1", // "light1", "light2", "dark1", "dark2"
					title:{
						text: "Column Chart of Available Items"
					},
					data: [{
						type: "column", //change type to bar, line, area, pie, etc  
						dataPoints: <?php echo json_encode($available_dataPoints, JSON_NUMERIC_CHECK); ?>
					}]
				});
				availableChart.render();

				var customerChart = new CanvasJS.Chart("customerStatus", {
				theme: "light2", // "light1", "light2", "dark1", "dark2"
				exportEnabled: true,
				animationEnabled: true,
				title: {
					text: "Active vs Disabled Customers"
				},
				data: [{
					type: "pie",
					startAngle: 25,
					toolTipContent: "<b>{label}</b>: {y}%",
					showInLegend: "true",
					legendText: "{label}",
					indexLabelFontSize: 16,
					indexLabel: "{label} - {y}%",
					dataPoints: <?php echo json_encode($customer_dataPoints, JSON_NUMERIC_CHECK); ?>
					}]
				});
				customerChart.render();

				var vendorChart = new CanvasJS.Chart("vendorStatus", {
				theme: "light2", // "light1", "light2", "dark1", "dark2"
				exportEnabled: true,
				animationEnabled: true,
				title: {
					text: "Active vs Disabled Vendors"
				},
				data: [{
					type: "pie",
					startAngle: 25,
					toolTipContent: "<b>{label}</b>: {y}%",
					showInLegend: "true",
					legendText: "{label}",
					indexLabelFontSize: 16,
					indexLabel: "{label} - {y}%",
					dataPoints: <?php echo json_encode($vendor_dataPoints, JSON_NUMERIC_CHECK); ?>
					}]
				});
				vendorChart.render();

				var purchaseChart = new CanvasJS.Chart("purchaseData", {
				theme: "light2", // "light1", "light2", "dark1", "dark2"
				exportEnabled: true,
				animationEnabled: true,
				title: {
					text: "Product Sales"
				},
				data: [{
					type: "line", //change type to bar, line, area, pie, etc  
					dataPoints: <?php echo json_encode($resultArray, JSON_NUMERIC_CHECK); ?>
				}]
				});
				purchaseChart.render();
				
			}
	</script>
	<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
  </body>
</html>
