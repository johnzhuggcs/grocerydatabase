<?php

class CustomerExecution
{
    private $SQLExecution;
    private $Utility;
    private static $instance;
    private $shippingCounter;
    private $orderCounter;
    private $accountNoCounter;

    //Includes all classes


    function CustomerExecution($sqlExecution, $utility){

        $this->SQLExecution = $sqlExecution;
        $this->Utility = $utility;
        if (isset($_SESSION['order_no'])) {

            $this->orderCounter = $_SESSION['order_no'];
        } else {
            $_SESSION['order_no'] = 1;
            $this->orderCounter = $_SESSION['order_no'];
        }

        if (isset($_SESSION['shipping_info_no'])) {

            $this->shippingCounter = $_SESSION['shipping_info_no'];
        } else {
            $_SESSION['shipping_info_no'] = 1;
            $this->shippingCounter = $_SESSION['shipping_info_no'];
        }

        if (isset($_SESSION['customerNo'])) {

            $this->accountNoCounter = $_SESSION['customerNo'];
        } else {
            $_SESSION['customerNo'] = 4;
            $this->accountNoCounter = $_SESSION['customerNo'];
        }

    }

    public static function getCustomerInstance($sqlExecution, $utility){
        if(!isset(self::$instance)){
            self::$instance = new CustomerExecution($sqlExecution, $utility);
        }
        return self::$instance;
    }

    function start(){
        global $db_conn, $success;
			// reset all the tables

        $tempOrderNum = strval($this->orderCounter);
        $tempOrderNum = str_pad($tempOrderNum, 4, '0', STR_PAD_LEFT);
        $newOrderID = "O" . $tempOrderNum;

        $tempShippingNum = strval($this->shippingCounter);
        $tempShippingNum = str_pad($tempShippingNum, 4, '0', STR_PAD_LEFT);
        $newShippingID = "S" . $tempShippingNum;

        $tempAccountNum = strval($this->accountNoCounter);
        $tempAccountNum = str_pad($tempAccountNum, 4, '0', STR_PAD_LEFT);
        $newCustomerID = "C" . $tempAccountNum;

        if(array_key_exists('create_shipinfo', $_POST)){
            $tuple = array (
                //this needs shipping info no
                ":bind0" => $newShippingID,
                ":bind1" => $_POST['Phone_number'],
                ":bind2" => $_POST['Billing_address'],
                ":bind3" => $_POST['Shipping_address'],
                ":bind4" => $_POST['Shipping_method'],
                ":bind5" => $_POST['delivery_type']
            );
            $alltuples = array (
                $tuple
            );

            $shippingArray = array(
                ":bind0" => $_SESSION['AccountID'],
                ":bind1" => $newShippingID
            );

            $bigShippingTuple = array(
                $shippingArray
            );

            $tempAccount = $_SESSION['AccountID'];
            //insert into ship info table
            $this->SQLExecution->executeBoundSQL("insert into shipping_info values (:bind0,:bind1,:bind2,:bind3,:bind4,:bind5)", $alltuples);
            //insert into owns table

            // this need shipping no
            $this->SQLExecution->executeBoundSQL("insert into owns values (:bind0, :bind1)", $bigShippingTuple);

            $_SESSION["shipping_info_no"] = $_SESSION["shipping_info_no"]+1;
            mysqli_commit($db_conn);

            $shippingAllResult = $this->SQLExecution->executePlainSQL("select Shipping_info.Shipping_info_no, Shipping_info.Phone_number, Shipping_info.Billing_address, Shipping_info.Shipping_address, Shipping_info.delivery_type, Shipping_info.Shipping_method from owns INNER JOIN Shipping_info ON Shipping_info.Shipping_info_no = owns.Shipping_info_no WHERE owns.Account_no = '$tempAccount'");
            //$this->Utility->printResult($orderAllResult);

            $newShipping = array(array());

            //$this->Utility->printResult($shippingAllResult);
            $countery = 0;
            while($tempResultArray = mysqli_fetch_array($shippingAllResult)){
                for($x = 0; $x< 6; $x++){
                    $newShipping[$countery][$x] = $tempResultArray[$x];
                }

                $countery++;
                //echo ('<div class="card container text-center" ><div class="card-body"><h5>'.$tempResultArray[0].'</h5></div></div>');
            }

            $_SESSION['shipping_addresses'] = $newShipping;


            // place order
        } else if(array_key_exists('place_order', $_POST)){

            //here needs order_no ???
            //get orginal price
            $original_price = $this->SQLExecution->executePlainSQL
            ("Select sum(price) from Product_discount p,Contains c,Order_placedby_shippedwith os 
					Where p.pid = c.pid and c.Order_no=os.Order_no and os.Order_no ='$newOrderID'");
            $price = mysqli_fetch_array($original_price);
            //print_r($price);
            //get shipping info no
            //here needs customer id
            $tempAccount = $_SESSION['AccountID'];
            $shipping_info = $this->SQLExecution->executePlainSQL
            ("Select shipping_info_no from Owns Where Account_no = '$tempAccount'");
            $ship = mysqli_fetch_array($shipping_info);

            if($price[0]>100){
                $freeshipping = 1;
                //needs both cid and order no

            }else{
                $freeshipping = 0;
                //needs both cid and order no

            }

            $orderTotal = array();
            $counter = 0;
            while($tempResultArray = mysqli_fetch_array($original_price)){
                $orderTotal[$counter] = $tempResultArray[0];
                $counter++;
                //echo ('<div class="card container text-center" ><div class="card-body"><h5>'.$tempResultArray[0].'</h5></div></div>');
            }

            $customerShipping = array();
            $counter = 0;
            /*while($tempResultArray = mysqli_fetch_array($shipping_info)){
                $customerShipping[$counter] = $tempResultArray[0];
                $counter++;
                //echo ('<div class="card container text-center" ><div class="card-body"><h5>'.$tempResultArray[0].'</h5></div></div>');
            }*/



            $tuple = array (
                //this needs shipping info no
                ":bind0" => $newOrderID,
                ":bind1" => '2018-4-6',
                ":bind2" => $freeshipping,
                ":bind3" => "processing",
                ":bind4" => $orderTotal[0],
                ":bind5" => $_POST['Payment_method'],
                ":bind6" => $orderTotal[0] * 0.5,
                ":bind7" => $_SESSION['AccountID'],
                ":bind8" => $customerShipping[0]
            );

            $alltuples = array (
                $tuple
            );

            $tempShip = $ship[0];
            $tempPayment = $_POST['Payment_method'];
            $tempOrderTotal = $price[0];
            $tempPoints = $price[0]*0.5;
            $tempAccount = $_SESSION['AccountID'];
            $this->SQLExecution->executePlainSQL
            ("update Order_placedby_shippedwith Set Free_shipping = '$freeshipping', Payment_method = '$tempPayment', Points_awarded = '$tempPoints', order_total = '$tempOrderTotal', shipping_info_no = '$tempShip' ");

            //update stock_quantity after decrement
            //needs order no ???
            $this->SQLExecution->executeBoundSQL
            ("update Product_discount set stock_quantity=stock_quantity-1
					where pid in(select pid from Contains where Order_no =$newOrderID)");
            $_SESSION["order_no"] = $_SESSION["order_no"]+1;
            mysqli_commit($db_conn);

            $orderAllResult = $this->SQLExecution->executePlainSQL("select * from Order_placedby_shippedwith WHERE Account_no = '$tempAccount'");
            //$this->Utility->printResult($orderAllResult);

            $newOrder = array(array());

            $countery = 0;
            while($tempResultArray = mysqli_fetch_array($orderAllResult)){
                for($x = 0; $x< 2; $x++){
                    $newOrder[$countery][$x] = $tempResultArray[$x];
                }

                $countery++;
                //echo ('<div class="card container text-center" ><div class="card-body"><h5>'.$tempResultArray[0].'</h5></div></div>');
            }

            $_SESSION['placed_order'] = $newOrder;

            //update shipping address

        } else if(array_key_exists('update_shipping_address', $_POST)){
            $tuple = array (
                ":bind1" => $_POST['new_address'],
                ":bind2" => $_POST['shipping_info_no'] //this is from inputted in POST, not $_SESSION
            );
            $alltuples = array (
                $tuple
            );
            $tempAccount = $_SESSION['AccountID'];
            $tempShippingForm = $_POST['shipping_info_no'];
            $tempCustomerArray = $this->SQLExecution->executePlainSQL("select Shipping_info_no from owns where Account_no = '$tempAccount'");
            $tempCustomer = mysqli_fetch_array($tempCustomerArray);
            ;
            $tempAddress = $_POST['new_address'];
            $this->SQLExecution->executePlainSQL("update Shipping_info set shipping_address='$tempAddress' where shipping_info_no='$tempShippingForm'");
            mysqli_commit($db_conn);

            $shippingAllResult = $this->SQLExecution->executePlainSQL("select Shipping_info.Shipping_info_no, Shipping_info.Phone_number, Shipping_info.Billing_address, Shipping_info.Shipping_address, Shipping_info.delivery_type, Shipping_info.Shipping_method from owns INNER JOIN Shipping_info ON Shipping_info.Shipping_info_no = owns.Shipping_info_no WHERE owns.Account_no = '$tempAccount'");
            //$this->Utility->printResult($orderAllResult);

            $newShipping = array(array());

            //$this->Utility->printResult($shippingAllResult);
            $countery = 0;
            while($tempResultArray = mysqli_fetch_array($shippingAllResult)){
                for($x = 0; $x< 6; $x++){
                    $newShipping[$countery][$x] = $tempResultArray[$x];
                }

                $countery++;
                //echo ('<div class="card container text-center" ><div class="card-body"><h5>'.$tempResultArray[0].'</h5></div></div>');
            }

            $_SESSION['shipping_addresses'] = $newShipping;

            // Modify Customer Premium qualification
        } else if(array_key_exists('modify_prem', $_POST)){

            $tempAccount = $_SESSION['AccountID'];
            //TODO: must do on specific customer
            $this->SQLExecution->executePlainSQL("Update Customer set Premium = 1 where Reward_Points >= 1000 and Account_no = '$tempAccount' ");
            $this->SQLExecution->executePlainSQL("Update Customer set Premium = 0 where Reward_Points < 1000 and Account_no = '$tempAccount'");


            // Select product into shopping cart
        } else if(array_key_exists('add_to_cart', $_POST)){

            $tuple = array (
                ":bind1" => $_POST['pid'],
                ":bind2" => $newOrderID
            );
            $alltuples = array (
                $tuple
            );

            $orderTuple = array (
                //this needs shipping info no
                ":bind0" => $newOrderID,
                ":bind1" => '2018-4-6',
                ":bind2" => null,
                ":bind3" => "processing",
                ":bind4" => null,
                ":bind5" => null,
                ":bind6" => null,
                ":bind7" => $_SESSION['AccountID'],
                ":bind8" => null
            );



            $allOrderstuples = array (
                $orderTuple
            );

            //this needs order_no in ???

            $checkRecord = $this->SQLExecution->executePlainSQL
            ("select order_no from Order_placedby_shippedwith WHERE order_no = '$newOrderID'");;
            $checkRecordArray = mysqli_fetch_array($checkRecord);

            if(count($checkRecordArray) <= 1){
                $this->SQLExecution->executeBoundSQL("insert into Order_placedby_shippedwith values(:bind0, :bind1, :bind2, :bind3, :bind4, :bind5, :bind6, :bind7, :bind8)", $allOrderstuples);
                //echo ('<div class="card container text-center" ><div class="card-body"><h5>"hey im here"</h5></div></div>');
                //$_SESSION['order_no'] = $_SESSION['order_no'] +1;
            }


            $tempProduct = $_POST['pid'];
            $this->SQLExecution->executePlainSQL("insert into Contains (PID, order_no) values('$tempProduct', '$newOrderID')");
            mysqli_commit($db_conn);
            $orderAllResult = $this->SQLExecution->executePlainSQL("select * from Contains WHERE order_no = '$newOrderID'");
            //$this->Utility->printResult($orderAllResult);

            $newCart = array(array());

            $countery = 0;
            while($tempResultArray = mysqli_fetch_array($orderAllResult)){
                for($x = 0; $x< 2; $x++){
                    $newCart[$countery][$x] = $tempResultArray[$x];
                }

                $countery++;
                //echo ('<div class="card container text-center" ><div class="card-body"><h5>'.$tempResultArray[0].'</h5></div></div>');
            }
            
            $_SESSION['cart'] = $newCart;

        }
        //select an attribute where the products are lower than selected price
        else if(array_key_exists('select_view', $_POST)){
            $bind1 = $_POST['attr'];
            $bind2 = $_POST['sel_price'];

            $_SESSION['select_view']= $this->SQLExecution->executePlainSQL("select $bind1 from product_discount where price< $bind2");
            //print result

            mysqli_commit($db_conn);
        }
        else if (array_key_exists('item_all_order', $_POST)) {
            $result = $this->SQLExecution->executePlainSQL("select PID, name from product_discount p where p.PID = (Select PID from Product_discount where PID not in (SELECT PID FROM ((select order_no,PID from (select PID from product_discount) cross join (select order_no from Contains)) Minus (select order_no, PID from Contains))))");

            $this->Utility->printDivision($result);
            mysqli_commit($db_conn);

        } //find the most popular item / most purchased item
        else if (array_key_exists('item_popular', $_POST)) {

            $popularresult = $this->SQLExecution->executeBoundSQL("select PID, name from product_discount p where p.PID = (select PID from Contains group by PID HAVING count(order_no) >= all (select count(order_no) from Contains group by PID))");
            /*echo "<br> The item in every order is: <br>";
            while ($row = mysqli_fetch_array($result)) {
                echo "<tr><td>" . $row["PID"] . "</td><td>" . $row["name"] . "</td></tr>";
            }*/
            print_r($popularresult);
            $this->Utility->printPopular($popularresult);
            mysqli_commit($db_conn);
        }

            //Commit to save changes...
            //OCILogoff($db_conn);

    }


}