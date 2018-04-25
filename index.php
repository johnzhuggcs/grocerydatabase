<?php
//ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();

// orting(-1);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);*/

// ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
// session_start();
//phpinfo();

//debugging info

//this tells the system that it's no longer just parsing 
//html; it's now parsing PHP

//Includes all classes

?>

<script type="text/javascript">
	var customerArray_js = <?php echo json_encode($_SESSION['customerArray']); ?>;
	var employeeArray_js = <?php echo json_encode($_SESSION['employeeArray']); ?>;
	var productArray_js = <?php echo json_encode($_SESSION['products']); ?>;
    var cartArray_js = <?php echo json_encode($_SESSION['cart']); ?>;
</script>

<?php

include('index.html');
set_include_path ( "./classes" );
spl_autoload_register(function ($class) {
	include 'classes/' . $class . '.php';
});


?>

<script type="text/javascript">
	var customerArray_js = <?php echo json_encode($_SESSION['customerArray']); ?>;
	var employeeArray_js = <?php echo json_encode($_SESSION['employeeArray']); ?>;
	var productArray_js = <?php echo json_encode($_SESSION['products']); ?>;
    var cartArray_js = <?php echo json_encode($_SESSION['cart']); ?>;
    var placedOrderArray_js = <?php echo json_encode($_SESSION['placed_order']); ?>;
    var shippingArray_js = <?php echo json_encode($_SESSION['shipping_addresses']); ?>;
</script>

<?php
//class is automatically loaded from ./classes/myclass.php

$success = true;
$db_conn;
$connectionController = ConnectionController::getConnectionInstance(); //Initializes connection to database

$SQLConnection = new SQLExecution(); //Executing SQL Statements
$Utility = new Utility(); //To print
$AccountInitializer = new AccountInitializer($SQLConnection, $Utility);

//echo "before begin app\n\n";
if (!isset($_SESSION['Begin_App']) || array_key_exists('reset', $_POST)){
    if(array_key_exists('reset', $_POST)){
        echo ('<div class="card container text-center" ><div class="card-body"><h5>Welcome!</h5></div></div>');
        $_SESSION['Initialized_table'] = null;
    }
    $_SESSION['Begin_App'] = 1;
    //echo "<p>begin app is now == ".$_SESSION['Begin_App']."</p>";
    $AccountInitializer->start();

    $employeeArray = array();
    $counter = 0;
    while($tempResultArray = mysqli_fetch_array($AccountInitializer->getAllEmployees(), MYSQLI_BOTH)){
        $employeeArray[$counter] = $tempResultArray[0];
        $counter++;
        //echo ('<div class="card container text-center" ><div class="card-body"><h5>'.$tempResultArray[0].'</h5></div></div>');
    }

    //echo ('<div class="card container text-center" ><div class="card-body"><h5>'.$resultArray.'</h5></div></div>');

    $customerArray = array();
    $counter = 0;
    while($tempResultArray = mysqli_fetch_array($AccountInitializer->getAllCustomers(), MYSQLI_BOTH)){
        $customerArray[$counter] = $tempResultArray[0];
        $counter++;
        //echo ('<div class="card container text-center" ><div class="card-body"><h5>'.$tempResultArray[0].'</h5></div></div>');
    }

    //echo ('<div class="card container text-center" ><div class="card-body"><h5>'.$resultArray.'</h5></div></div>');

    $_SESSION['customerArray'] = $customerArray;
    $_SESSION['employeeArray'] = $employeeArray;


    //echo ('<div class="card container text-center" ><div class="card-body"><h5>'.$_SESSION['customerArray'].'</h5></div></div>');

    // echo $customerArray[0];
//$customer should be C0001 or some other
//Pretend we chose some account in the front end for $customer
    //$customer = $_SESSION['customerArray'][0];

    //$_SESSION["AccountID"] = $customer;


    header("location: index.php");


}else if($_SESSION['Begin_App'] == 1){
    if($db_conn){

        if(array_key_exists("create_customer", $_POST)){

            $CustomerCreator = new CustomerCreation($SQLConnection, $Utility);
            $CustomerCreator->start();
            echo "loggin on in index.php\n\n";
            echo "<p>Logged On: </p>";
            echo "<p>".$_SESSION["AccountID"]." is the Logged On account </p>";
            $_SESSION['Begin_App'] = 2;
            //$productResult = $this->SQLExecution->executePlainSQL("select * from product_discount");
            //$_SESSION['products'] = $this->Utility->sessionResult($productResult);
            $ApplicationController = new ApplicationController($SQLConnection, $Utility, $_SESSION["AccountID"]);//controls the application, checks when to create table/execute sql queriess
            $ApplicationController->start();
        }else
        if(array_key_exists('selectAccount', $_POST)){
            //echo "loggin on in index.php\n\n";
            echo ('<div class="card container text-center" ><div class="card-body"><h5>Logged On</h5></div></div>');
            //echo "<p>Logged On: </p>";
            //echo ('<div class="card container text-center" ><div class="card-body"><h5>".$_POST["nameSelect"]." is the Logged On accoun</h5></div></div>');
            //echo "<p>".$_POST["nameSelect"]." is the Logged On account </p>";
            $_SESSION["AccountID"] = $_POST["nameSelect"];

            echo ('<div class="card container text-center" ><div class="card-body"><h5>Waiting</h5></div></div>');
            $_SESSION['Begin_App'] = 2;
            $ApplicationController = new ApplicationController($SQLConnection, $Utility, $_SESSION["AccountID"]);//controls the application, checks when to create table/execute sql queriess
            $ApplicationController->start();
        }
    }else{
        echo "cannot connect";
        $e = mysqli_error(); // For OCILogon errors pass no handle
        echo htmlentities($e['message']);
    }
} else{
    // echo "application start";
    $ApplicationController = ApplicationController::getApplicationInstance($SQLConnection, $Utility, $_SESSION["AccountID"]);
    $ApplicationController->start();
}


//$ApplicationController = ApplicationController::getApplicationInstance($SQLConnection, $Utility, "C0001"); //controls the application, checks when to create table/execute sql queriess


// Connect Oracle...






/* OCILogon() allows you to log onto the Oracle database
     The three arguments are the username, password, and database
     You will need to replace "username" and "password" for this to
     to work. 
     all strings that start with "$" are variables; they are created
     implicitly by appearing on the left hand side of an assignment 
     statement */

/* OCIParse() Prepares Oracle statement for execution
The two arguments are the connection and SQL query. */
/* OCIExecute() executes a previously parsed statement
      The two arguments are the statement which is a valid OCI
      statement identifier, and the mode. 
      default mode is OCI_COMMIT_ON_SUCCESS. Statement is
      automatically committed after OCIExecute() call when using this
      mode.
      Here we use OCI_DEFAULT. Statement is not committed
      automatically when using this mode */

/* OCI_Fetch_Array() Returns the next row from the result data as an  
     associative or numeric array, or both.
     The two arguments are a valid OCI statement identifier, and an 
     optinal second parameter which can be any combination of the 
     following constants:

     OCI_BOTH - return an array with both associative and numeric 
     indices (the same as OCI_ASSOC + OCI_NUM). This is the default 
     behavior.  
     OCI_ASSOC - return an associative array (as OCI_Fetch_Assoc() 
     works).  
     OCI_NUM - return a numeric array, (as OCI_Fetch_Row() works).  
     OCI_RETURN_NULLS - create empty elements for the NULL fields.  
     OCI_RETURN_LOBS - return the value of a LOB of the descriptor.  
     Default mode is OCI_BOTH.  */
     ?>

<script type="text/javascript">
    var customerArray_js = <?php echo json_encode($_SESSION['customerArray']); ?>;
    var employeeArray_js = <?php echo json_encode($_SESSION['employeeArray']); ?>;
    var productArray_js = <?php echo json_encode($_SESSION['products']); ?>;
    var cartArray_js = <?php echo json_encode($_SESSION['cart']); ?>;
    var placedOrderArray_js = <?php echo json_encode($_SESSION['placed_order']); ?>;
    var shippingArray_js = <?php echo json_encode($_SESSION['shipping_addresses']); ?>;
</script>
