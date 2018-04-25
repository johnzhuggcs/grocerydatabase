<?php

/**
 * Created by PhpStorm.
 * User: johnz
 * Date: 2018-03-27
 * Time: 12:06 PM
 */

class ApplicationController
{
    private $SQLExecution;
    private $Utility;
    private $EmployeeOrCustomer;
    private static $instance;

    //Includes all classes


    function ApplicationController($sqlExecution, $utility, $whichUser){

        //echo ('<div class="card container text-center" ><div class="card-body"><h5>Create App Control</h5></div></div>');
        $this->SQLExecution = $sqlExecution;
        $this->Utility = $utility;
        //echo ('<div class="card container text-center" ><div class="card-body"><h5>Create App Control</h5></div></div>');
        $this->accountNoToBool($whichUser);
        //echo ('<div class="card container text-center" ><div class="card-body"><h5>Create App Control</h5></div></div>');
        //$_SESSION["AccountID"] = $whichUser;
    }

    // Takes in account number chosen and determines whether employee or customer
    private function accountNoToBool($whichUser){
        //echo ('<div class="card container text-center" ><div class="card-body"><h5>Create App Control</h5></div></div>');
        $cOrE = substr($whichUser, 0, 1);
        //echo $cOrE;
        switch($cOrE){ //If = 1, it is Employee, if = 0, it is Customer
            case "C":
                $this->EmployeeOrCustomer = 0;
                //echo ('<div class="card container text-center" ><div class="card-body"><h5>This Customer</h5></div></div>');
                break;
            case "E":
                $this->EmployeeOrCustomer = 1;
                //echo ('<div class="card container text-center" ><div class="card-body"><h5>This Employee</h5></div></div>');
                break;
        }

    }

    public static function getApplicationInstance($sqlExecution, $utility, $whichUser){
        if(!isset(self::$instance)){
            self::$instance = new ApplicationController($sqlExecution, $utility, $whichUser);
        }
        return self::$instance;
    }

    function start(){
        global $db_conn, $success;


        if ($db_conn) {
            //echo ('<div class="card container text-center" ><div class="card-body"><h5>Waiting</h5></div></div>');
            if(array_key_exists('logoff', $_POST)){
                echo ('<div class="card container text-center" ><div class="card-body"><h5>Log Off</h5></div></div>');
                $_SESSION['Begin_App'] = 1;
                $AccountInitializer = new AccountInitializer($this->SQLExecution, $this->Utility);
                $AccountInitializer->start();

                $employeeArray = array();
                $counter = 0;
                while($tempResultArray = OCI_Fetch_Array($AccountInitializer->getAllEmployees(), OCI_BOTH)){
                    $employeeArray[$counter] = $tempResultArray[0];
                    $counter++;
                    //echo ('<div class="card container text-center" ><div class="card-body"><h5>'.$tempResultArray[0].'</h5></div></div>');
                }

                //echo ('<div class="card container text-center" ><div class="card-body"><h5>'.$resultArray.'</h5></div></div>');

                $customerArray = array();
                $counter = 0;
                while($tempResultArray = OCI_Fetch_Array($AccountInitializer->getAllCustomers(), OCI_BOTH)){
                    $customerArray[$counter] = $tempResultArray[0];
                    $counter++;
                    //echo ('<div class="card container text-center" ><div class="card-body"><h5>'.$tempResultArray[0].'</h5></div></div>');
                }

                //echo ('<div class="card container text-center" ><div class="card-body"><h5>'.$resultArray.'</h5></div></div>');

                $_SESSION['customerArray'] = $customerArray;
                $_SESSION['employeeArray'] = $employeeArray;



                /*echo "<script type=\'text/javascript\'>
	var customerArray_js =";
                echo "<?php echo json_encode($_SESSION['customerArray']);?>";
	echo "var employeeArray_js =";
	echo "<?php echo json_encode($_SESSION['employeeArray']); ?>";
	echo "var productArray_js =";
                echo "<?php json_encode($_SESSION['products']);?>";
    echo "var cartArray_js =";
                echo "<?php json_encode($_SESSION['cart']);?>";
    echo "</script>";*/

            }
            else if (array_key_exists('reset', $_POST)) {

                //echo ('<div class="card container text-center" ><div class="card-body"><h5>Waiting</h5></div></div>');
                $AccountInitializer = new AccountInitializer($this->SQLExecution, $this->Utility);
                $AccountInitializer->reset();

            } else if (array_key_exists('getProducts', $_POST)){
                //echo ('<div class="card container text-center" ><div class="card-body"><h5>Waiting</h5></div></div>');
                $productResult = $this->SQLExecution->executePlainSQL("select * from product_discount");
                mysqli_commit($db_conn);
                //echo ('<div class="card container text-center" ><div class="card-body"><h5>Waiting</h5></div></div>');

                $productArray = array();
                $counter = 0;
                while($tempResultArray = OCI_Fetch_Array($productResult, OCI_BOTH)){
                    $productArrayX = array();

                    for($x = 0; $x< 12; $x++){
                        $productArray[$counter][$x] = $tempResultArray[$x];
                    }
                    $counter++;
                    //echo ('<div class="card container text-center" ><div class="card-body"><h5>'.$tempResultArray[0].'</h5></div></div>');
                }

                $_SESSION['products'] = $productArray;
            } else if($this->EmployeeOrCustomer){

                $EmployeeExecution = EmployeeExecution::getEmployeeInstance($this->SQLExecution, $this->Utility);
                $EmployeeExecution->start();
            }else if(!$this->EmployeeOrCustomer){


                $CustomerExecution = CustomerExecution::getCustomerInstance($this->SQLExecution, $this->Utility);
                $CustomerExecution->start();
            }

            if ($_POST && $success) {
                //POST-REDIRECT-GET -- See http://en.wikipedia.org/wiki/Post/Redirect/Get
                $employeeResult = $this->SQLExecution->executePlainSQL("select * from Employee");
                $this->Utility->printEmployee($employeeResult);
                $customerResult = $this->SQLExecution->executePlainSQL("select * from Customer");
                $this->Utility->printCustomer($customerResult);
                $employeeResult = $this->SQLExecution->executePlainSQL("select * from product_discount");
                $this->Utility->printResult($employeeResult);
                $orderAllResult = $this->SQLExecution->executePlainSQL("select * from Order_placedby_shippedwith");
                $this->Utility->printOrder($orderAllResult);
                $orderAllResult = $this->SQLExecution->executePlainSQL("select * from Contains");
                $this->Utility->printContains($orderAllResult);
                $shippingInfoOnlyResult = $this->SQLExecution->executePlainSQL("select * from Shipping_info");
                $this->Utility->printShipping($shippingInfoOnlyResult);
                $shippingInfowithCustomer = $this->SQLExecution->executePlainSQL("select * from owns");
                $this->Utility->printShippingOwns($shippingInfowithCustomer);
                mysqli_commit($db_conn);
                header("location: index.php");
            }
            else {
                // Select data...
                $employeeResult = $this->SQLExecution->executePlainSQL("select * from Employee");
                $this->Utility->printEmployee($employeeResult);
                $customerResult = $this->SQLExecution->executePlainSQL("select * from Customer");
                $this->Utility->printCustomer($customerResult);
                $employeeResult = $this->SQLExecution->executePlainSQL("select * from product_discount");
                $this->Utility->printResult($employeeResult);
                $orderAllResult = $this->SQLExecution->executePlainSQL("select * from Order_placedby_shippedwith");
                $this->Utility->printOrder($orderAllResult);
                $orderAllResult = $this->SQLExecution->executePlainSQL("select * from Contains");
                $this->Utility->printContains($orderAllResult);
                $shippingInfoOnlyResult = $this->SQLExecution->executePlainSQL("select * from Shipping_info");
                $this->Utility->printShipping($shippingInfoOnlyResult);
                $shippingInfowithCustomer = $this->SQLExecution->executePlainSQL("select * from owns");
                $this->Utility->printShippingOwns($shippingInfowithCustomer);
                mysqli_commit($db_conn);
            }

            //Commit to save changes...
            mysqli_close($db_conn);
        } else {
            echo ('<div class="card container text-center" ><div class="card-body"><h5>cannot connect</h5></div></div>');

            $e = OCI_Error(); // For OCILogon errors pass no handle
            echo ('<div class="card container text-center" ><div class="card-body"><h5>'.htmlentities($e['message']).'</h5></div></div>');

        }
    }


}