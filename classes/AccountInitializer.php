<?php
/**
 * Created by PhpStorm.
 * User: johnz
 * Date: 2018-03-28
 * Time: 11:21 PM
 */

class AccountInitializer
{
    private $SQLExecution;
    private $Utility;
    private $EmployeeResults;
    private $CustomerResults;



    function AccountInitializer($sqlExecution, $utility){

        $this->SQLExecution = $sqlExecution;
        $this->Utility = $utility;
    }

    function start(){
        global $db_conn, $success;
        if($db_conn){

            //Also checks if tables have been created already, if so it won't try to recreate them
            if (!isset($_SESSION['Initialized_table'])){
                $_SESSION['Initialized_table'] = 1;
                $TablePopulator = new TablePopulation($this->SQLExecution);

                // Drop old table...
                //echo ('<div class="card container text-center" ><div class="card-body"><h5>New Session</h5></div></div>');
                $TablePopulator->dropAll();

                //echo ('<div class="card container text-center" ><div class="card-body"><h5>Delete Session Variables</h5></div></div>');
                $_SESSION['customerNo'] = null;
                $_SESSION['order_no'] = null;
                $_SESSION['ProductId'] = null;
                $_SESSION['DID'] = null;
                $_SESSION['cart'] = null;
                $_SESSION['shipping_info_no'] = null;
                // Create new table...
                //echo ('<div class="card container text-center" ><div class="card-body"><h5>Create New Table</h5></div></div>');
                $TablePopulator->populateAll();

                //echo ('<div class="card container text-center" ><div class="card-body"><h5>Import Existing Customers and Employers</h5></div></div>');
                $TablePopulator->insertEmployeeCustomer();


            }
            $this->EmployeeResults = $this->SQLExecution->executePlainSQL("select Employee_ID from Employee");
            mysqli_store_result($db_conn);
            $this->CustomerResults = $this->SQLExecution->executePlainSQL("select Account_no from Customer");
            mysqli_store_result($db_conn);
            //$this->Utility->printResult($this->CustomerResults);
            mysqli_commit($db_conn);

            if ($_POST && $success) {

            }else{

            }
            //Commit to save changes...
            mysqli_close($db_conn);
        }else {
            echo "cannot connect";
            $e = mysqli_error(); // For OCILogon errors pass no handle
            echo htmlentities($e['message']);
        }

    }

    function getAllEmployees(){
        return $this->EmployeeResults;
    }

    function getAllCustomers(){
        return $this->CustomerResults;

    }

    function reset(){
        $_SESSION['Initialized_table'] = null;
        $_SESSION['Begin_App'] = null;
        $_SESSION['customerNo'] = null;
        $_SESSION['order_no'] = null;
        $_SESSION['ProductId'] = null;
        $_SESSION['cart'] = null;
        $_SESSION['DID'] = null;
        $_SESSION['shipping_info_no'] = null;

        $TablePopulator = new TablePopulation($this->SQLExecution);

        // Drop old table...
        $TablePopulator->dropAll();

        // Create new table...
        $TablePopulator->populateAll();

        $TablePopulator->insertEmployeeCustomer();
    }
}