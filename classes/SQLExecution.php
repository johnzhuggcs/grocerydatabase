<?php
/**
 * Created by PhpStorm.
 * User: johnz
 * Date: 2018-03-26
 * Time: 11:12 PM
 */

class SQLExecution
{

    function SQLExecution(){

    }

    function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
        //echo ('<div class="card container text-center" ><div class="card-body"><h5>running ".$cmdstr."</h5></div></div>');
        global $db_conn, $success;
        $statement = mysqli_prepare($db_conn, $cmdstr); //There is a set of comments at the end of the file that describe some of the OCI specific functions and how they work

        if (!$statement) {
            //echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
            $e = mysqli_error($db_conn); // For OCIParse errors pass the
            // connection handle
            echo ('<div class="card container text-center" ><div class="card-body"><h5>'.htmlentities($e['message']).'</h5></div></div>');
            $success = False;
        }

        $r = mysqli_execute($statement);

        if (!$r) {
            // echo "<div class='card'><br>Cannot execute the following command: " . $cmdstr . "<br></div>";
            echo ('<div class="card container text-center" ><div class="card-body"><h5>Cannot execute the following command:'.$cmdstr.'</h5></div></div>');
            $e = mysqli_error($statement); // For OCIExecute errors pass the statementhandle
            echo ('<div class="card container text-center" ><div class="card-body"><h5>'.htmlentities($e['message']).'</h5></div></div>');
            $success = False;
        } else {

        }
        return $statement;

    }

    function executeBoundSQL($cmdstr, $list, $type) {
        /* Sometimes a same statement will be excuted for severl times, only
         the value of variables need to be changed.
         In this case you don't need to create the statement several times;
         using bind variables can make the statement be shared and just
         parsed once. This is also very useful in protecting against SQL injection. See example code below for       how this functions is used */
        //echo ('<div class="card container text-center" ><div class="card-body"><h5>1</h5></div></div>');
        //echo ('<div class="card container text-center" ><div class="card-body"><h5>'.$cmdstr.'</h5></div></div>');
        global $db_conn, $success;
        $statement = mysqli_prepare($db_conn, $cmdstr);

        if (!$statement) {
            echo "<div class='card'><br>Cannot parse the following command: " . $cmdstr . "<br></div>";
            echo ('<div class="card container text-center" ><div class="card-body"><h5>Cannot parse the following command:'.$cmdstr.'</h5></div></div>');
            $e = mysqli_error($db_conn);
            echo ('<div class="card container text-center" ><div class="card-body"><h5>Cannot parse the following command:'.htmlentities($e['message']).'</h5></div></div>');
            $success = False;
        }
        foreach ($list as $tuple) {
            foreach ($tuple as $bind) {
                //echo $val;

                //echo ('<div class="card container text-center" ><div class="card"><br>".$bind."<br></div></div>');
                //echo ('<div class="card container text-center" ><div class="card-body"><h5>'.$bind.'</h5></div></div>');
                mysqli_stmt_bind_param($statement, "s", $bind);
                //OCIBindByName($statement, $bind, $val);
                unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype

            }

            foreach ($tuple as $bind => $val) {
                //echo $val;

                //echo ('<div class="card container text-center" ><div class="card"><br>".$bind."<br></div></div>');
                //echo ('<div class="card container text-center" ><div class="card-body"><h5>'.$bind.'</h5></div></div>');
                mysqli_stmt_bind_param($statement, "s", $bind);
                $bind = $val;
                //OCIBindByName($statement, $bind, $val);
                unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype

            }

            $r = mysqli_execute($statement);
            if (!$r) {
                echo "<div class='card'><br>Cannot execute the following command: " . $cmdstr . "<br>";
                echo ('<div class="card container text-center" ><div class="card-body"><h5>Cannot execute the following command:'.$cmdstr.'</h5></div></div>');
                $e = OCI_Error($statement); // For OCIExecute errors pass the statementhandle
                echo ('<div class="card container text-center" ><div class="card-body"><h5>'.htmlentities($e['message']).'</h5></div></div>');
                $success = False;
            }
        }

    }
}