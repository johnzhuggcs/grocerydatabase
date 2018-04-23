<?php
/**
 * Created by PhpStorm.
 * User: johnz
 * Date: 2018-03-29
 * Time: 7:18 PM
 */

class CustomerCreation
{
    private $SQLExecution;
    private $Utility;
    private $accountNoCounter;

    //Includes all classes


    function CustomerCreation($sqlExecution, $utility)
    {

        $this->SQLExecution = $sqlExecution;
        $this->Utility = $utility;
        if (isset($_SESSION['customerNo'])) {

            $this->accountNoCounter = $_SESSION['customerNo'];
        } else {
            $_SESSION['customerNo'] = 4;
            $this->accountNoCounter = $_SESSION['customerNo'];
        }
    }


    function start()
    {
        global $db_conn, $success;
        // reset all the tables

            $tempAccountNum = strval($this->accountNoCounter);
            $tempAccountNum = str_pad($tempAccountNum, 4, '0', STR_PAD_LEFT);
            $newCustomerID = "C" . $tempAccountNum;
            $tuple = array(
                ":bind1" => $newCustomerID,
                ":bind2" => $_POST["Name"],
                ":bind3" => $_POST["Email"],
                ":bind4" => 0,
                ":bind5" => 0
            );
            $alltuples = array(
                $tuple
            );
            $this->SQLExecution->executeBoundSQL("insert into customer values (:bind1,:bind2,:bind3,:bind4,:bind5)", $alltuples);
            OCICommit($db_conn);
            $_SESSION['customerNo'] = $_SESSION['customerNo'] + 1;
            $_SESSION["AccountID"] = $newCustomerID;

            // create shipping info


        //Commit to save changes...
        //OCILogoff($db_conn);

    }
}