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
            echo ('<div class="card container text-center" ><div class="card-body"><h5>'.htmlentities($e).'</h5></div></div>');
            $success = False;
        }

        $r = mysqli_stmt_execute($statement);
        $statement = mysqli_store_result($db_conn);
        //mysqli_stmt_bind_result($statement, $r);

        if (!$r) {
            // echo "<div class='card'><br>Cannot execute the following command: " . $cmdstr . "<br></div>";
            echo ('<div class="card container text-center" ><div class="card-body"><h5>Cannot execute the following command:'.$cmdstr.'</h5></div></div>');
            $e = mysqli_error($db_conn); // For OCIExecute errors pass the statementhandle
            echo ('<div class="card container text-center" ><div class="card-body"><h5>'.htmlentities($e).'</h5></div></div>');
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
            echo ('<div class="card container text-center" ><div class="card-body"><h5>Cannot parse the following command:'.htmlentities($e).'</h5></div></div>');
            $success = False;
        }

        foreach($list as $tuple){
            $list = array_merge(array($type), $tuple);
            $tmp = array();
            foreach($list as $key => $value) $tmp[$key] = &$list[$key];
            call_user_func_array(array($statement, 'bind_param'), $tmp);
            mysqli_stmt_execute($statement);
        }

    }
}