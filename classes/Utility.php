<?php

/**
 * Created by PhpStorm.
 * User: johnz
 * Date: 2018-03-27
 * Time: 11:49 AM
 */

class Utility
{
    //Prints results and others

    function Utility(){}

    function printResult($result) { //prints results from a select statement
        echo('<div class="card container text-center" >
		<div class="card-body">
				<div class="container">
					<div class="row">
						<div class="col-md-12" style="overflow-x: auto;">
							<table class="table table-bordered" >
								<thead>
									<tr>
										<th>ID</th>
										<th>Price</th>
										<th>Expire date</th>
										<th>Ingredients</th>
										<th>Carbon Footprint</th>
										<th>Origin</th>
										<th>Stock_quantity</th>
										<th>Name</th>
										<th>Brand</th>
										<th>description</th>
										<th>Reward Points</th>
									</tr>
								</thead>');

        while ($row = mysqli_fetch_array($result)) {

            echo('<tbody>
            <tr>
		<td>' . $row[0] . '</td>
		<td>' . $row[1] . '</td>
		<td>' . $row[2] . '</td>
		<td>' . $row[3] . '</td>
		<td>' . $row[4] . '</td>
		<td>' . $row[5] . '</td>
		<td>' . $row[6] . '</td>
		<td>' . $row[7] . '</td>
		<td>' . $row[8] . '</td>
		<td>' . $row[9] . '</td>
		<td>' . $row[10] . '</td>
		</tr>'); //or just use "echo $row[0]"
        }
        echo('</tbody>
							</table>
						</div>
					</div>
				</div>
		</div>
	</div>');

    }


    function printOrder($result) { //prints results from a select statement
        echo('<div class="card container text-center" >
		<div class="card-body">
				<div class="container">
					<div class="row">
						<div class="col-md-12" style="overflow-x: auto;">
							<table class="table table-bordered" >
								<thead>
									<tr>
										<th>Order ID</th>
										<th>Order Date</th>
										<th>Free Shipping?</th>
										<th>Status</th>
										<th>Order Total</th>
										<th>Payment method</th>
										<th>Points awarded</th>
										<th>Account Number</th>
										<th>Shipping Info</th>
									</tr>
								</thead>');

        while ($row = mysqli_fetch_array($result)) {

            echo('<tbody>
            <tr>
		<td>' . $row[0] . '</td>
		<td>' . $row[1] . '</td>
		<td>' . $row[2] . '</td>
		<td>' . $row[3] . '</td>
		<td>' . $row[4] . '</td>
		<td>' . $row[5] . '</td>
		<td>' . $row[6] . '</td>
		<td>' . $row[7] . '</td>
		<td>' . $row[8] . '</td>
		</tr>'); //or just use "echo $row[0]"
        }
        echo('</tbody>
							</table>
						</div>
					</div>
				</div>
		</div>
	</div>');

    }


    function printShipping($result) { //prints results from a select statement
        echo('<div class="card container text-center" >
		<div class="card-body">
				<div class="container">
					<div class="row">
						<div class="col-md-12" style="overflow-x: auto;">
							<table class="table table-bordered" >
								<thead>
									<tr>
										<th>Shipping Info Number</th>
										<th>Phone Number</th>
										<th>Billing Address</th>
										<th>Shipping Address</th>
										<th>Delivery Type</th>
										<th>Shipping Method</th>
									</tr>
								</thead>');

        while ($row = mysqli_fetch_array($result)) {

            echo('<tbody>
            <tr>
		<td>' . $row[0] . '</td>
		<td>' . $row[1] . '</td>
		<td>' . $row[2] . '</td>
		<td>' . $row[3] . '</td>
		<td>' . $row[4] . '</td>
		<td>' . $row[5] . '</td>
		</tr>'); //or just use "echo $row[0]"
        }
        echo('</tbody>
							</table>
						</div>
					</div>
				</div>
		</div>
	</div>');

    }

    function printCustomer($result) { //prints results from a select statement

        echo('<div class="card container text-center" >
		<div class="card-body">
				<div class="container">
					<div class="row">
						<div class="col-md-12" style="overflow-x: auto;">
							<table class="table table-bordered" >
								<thead>
									<tr>
										<th>Account Number</th>
										<th>Name</th>
										<th>Email</th>
										<th>Reward Points</th>
										<th>Premium?</th>
									</tr>
								</thead>');

        while ($row = mysqli_fetch_array($result)) {

            echo('<tbody>
            <tr>
		<td>' . $row[0] . '</td>
		<td>' . $row[1] . '</td>
		<td>' . $row[2] . '</td>
		<td>' . $row[3] . '</td>
		<td>' . $row[4] . '</td>
		</tr>'); //or just use "echo $row[0]"
        }
        echo('</tbody>
							</table>
						</div>
					</div>
				</div>
		</div>
	</div>');

    }


    function printPopular($result) { //prints results from a select statement
    echo ('<div class="card container text-center" ><div class="card-body"><h5>Most Popular Items</h5></div></div>');
    echo('<div class="card container text-center" >
		<div class="card-body">
				<div class="container">
					<div class="row">
						<div class="col-md-12" style="overflow-x: auto;">
							<table class="table table-bordered" >
								<thead>
									<tr>
										<th>Product ID</th>
										<th>Product Name</th>
									</tr>
								</thead>');

    while ($row = mysqli_fetch_array($result)) {

        echo('<tbody>
            <tr>
		<td>' . $row[0] . '</td>
		<td>' . $row[1] . '</td>
		</tr>'); //or just use "echo $row[0]"
    }
    echo('</tbody>
							</table>
						</div>
					</div>
				</div>
		</div>
	</div>');

}
    function printContains($result) { //prints results from a select statement
        //echo ('<div class="card container text-center" ><div class="card-body"><h5>Most Popular Items</h5></div></div>');
        echo('<div class="card container text-center" >
		<div class="card-body">
				<div class="container">
					<div class="row">
						<div class="col-md-12" style="overflow-x: auto;">
							<table class="table table-bordered" >
								<thead>
									<tr>
										<th>Product ID</th>
										<th>Order Number</th>
									</tr>
								</thead>');

        while ($row = mysqli_fetch_array($result)) {

            echo('<tbody>
            <tr>
		<td>' . $row[0] . '</td>
		<td>' . $row[1] . '</td>
		</tr>'); //or just use "echo $row[0]"
        }
        echo('</tbody>
							</table>
						</div>
					</div>
				</div>
		</div>
	</div>');

    }

    function printDivision($result) { //prints results from a select statement
        echo ('<div class="card container text-center" ><div class="card-body"><h5>Product purchased in every order!</h5></div></div>');
        echo('<div class="card container text-center" >
		<div class="card-body">
				<div class="container">
					<div class="row">
						<div class="col-md-12" style="overflow-x: auto;">
							<table class="table table-bordered" >
								<thead>
									<tr>
										<th>Product ID</th>
										<th>Product Name</th>
									</tr>
								</thead>');

        while ($row = mysqli_fetch_array($result)) {

            echo('<tbody>
            <tr>
		<td>' . $row[0] . '</td>
		<td>' . $row[1] . '</td>
		</tr>'); //or just use "echo $row[0]"
        }
        echo('</tbody>
							</table>
						</div>
					</div>
				</div>
		</div>
	</div>');

    }
    function printShippingOwns($result) { //prints results from a select statement
        echo('<div class="card container text-center" >
		<div class="card-body">
				<div class="container">
					<div class="row">
						<div class="col-md-12" style="overflow-x: auto;">
							<table class="table table-bordered" >
								<thead>
									<tr>
										<th>Shipping ID</th>
										<th>Account ID</th>
									</tr>
								</thead>');

        while ($row = mysqli_fetch_array($result)) {

            echo('<tbody>
            <tr>
		<td>' . $row[0] . '</td>
		<td>' . $row[1] . '</td>
		</tr>'); //or just use "echo $row[0]"
        }
        echo('</tbody>
							</table>
						</div>
					</div>
				</div>
		</div>
	</div>');

    }

    function printEmployee($result) { //prints results from a select statement
        echo('<div class="card container text-center" >
		<div class="card-body">
				<div class="container">
					<div class="row">
						<div class="col-md-12" style="overflow-x: auto;">
							<table class="table table-bordered" >
								<thead>
									<tr>
										<th>Employee ID</th>
										<th>SIN</th>
									</tr>
								</thead>');

        while ($row = mysqli_fetch_array($result)) {

            echo('<tbody>
            <tr>
		<td>' . $row[0] . '</td>
		<td>' . $row[1] . '</td>
		</tr>'); //or just use "echo $row[0]"
        }
        echo('</tbody>
							</table>
						</div>
					</div>
				</div>
		</div>
	</div>');

    }


    function sessionResult($result){
        $resultArray = array();
        $counter = 0;
        while($tempResultArray = mysqli_fetch_array($result)){
            $resultArray[$counter] = $tempResultArray[0];
            $counter++;
            echo ('<div class="card container text-center" ><div class="card-body"><h5>'.$tempResultArray[0].'</h5></div></div>');
        }

        //echo ('<div class="card container text-center" ><div class="card-body"><h5>'.$resultArray.'</h5></div></div>');
        return $resultArray;
    }
}