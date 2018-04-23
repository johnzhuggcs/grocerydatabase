<?php

/**
 * Created by PhpStorm.
 * User: johnz
 * Date: 2018-03-27
 * Time: 12:00 PM
 */

class TablePopulation
{
    private $SQLExecution;
    private $allCreateTables;
    private $allDropTables;
    private $Employees;
    private $Customers;
    private $Product;
    private $Food;
    private $Beverage;
    private $Personal;
    private $Orders;
    private $Shipping;
    private $Contains;
    private $owns;
    private $deal;

    function TablePopulation($sqlExecution){
        $this->SQLExecution = $sqlExecution;
        $this->allCreateTables = array(
            "create table Employee(
            Employee_ID varchar2(30) primary key,
	sin integer
	)",

"create table Restock(
            Employee_ID varchar2(7),
	Product_ID varchar2(5),
	primary key(Employee_ID,Product_ID),
	foreign key(Employee_ID) references Employee
	)",


"create TABLE Deal
        (DID varchar2(5),
start_date date,
end_date date,
shared_link varchar2(40),
discount DOUBLE precision,
Premium_only varchar2(1),
PRIMARY KEY (DID)
)",

"create table Manage(
            Employee_ID varchar2(7),
	Deal_ID varchar2(5),
	primary key (Deal_ID, Employee_ID),
	FOREIGN KEY (Deal_ID) REFERENCES Deal,
	foreign key (Employee_ID) references Employee
	)",

"create TABLE product_discount
        (PID varchar2(5),
price DOUBLE precision not null,
expire_date date,
Ingredients varchar2(200),
carbon_footprint DOUBLE precision,
origin varchar2(20),
stock_quantity INTEGER not null,
name  varchar2(20),
brand varchar2(20),
description varchar2(100),
reward_points INTEGER,
DID varchar2(5),
PRIMARY KEY (PID),
FOREIGN KEY (DID) REFERENCES Deal
)",


"create TABLE Food(
            PID varchar2(5),
Weight DOUBLE precision,
Allergies varchar2(100),
PRIMARY KEY (PID),
FOREIGN KEY (PID) REFERENCES product_discount
)",

"create TABLE Beverage
        (PID varchar2(5),
Allergies varchar2(100),
Volume DOUBLE precision,
PRIMARY KEY (PID),
FOREIGN KEY (PID) REFERENCES product_discount
	)",

"create TABLE PersonalCare
        (PID varchar2(5),
instruction varchar2(50),
PRIMARY KEY (PID),
FOREIGN KEY (PID) REFERENCES product_discount
)",


"create TABLE Customer
        (Account_no varchar2(5),
Name varchar2(20),
Email varchar2(40),
Reward_Points INTEGER NOT NULL CHECK (Reward_Points >= 0),
Premium varchar2(1),
PRIMARY KEY(Account_no)
)",

"create TABLE Access_to
        (DID varchar2(5),
Account_no varchar2(5),
PRIMARY KEY (DID,Account_no),
FOREIGN KEY (DID) REFERENCES Deal,
FOREIGN KEY (Account_no) REFERENCES Customer
)",



"create TABLE Shipping_info
        (Shipping_info_no varchar2(6) NOT NULL,
Phone_number INTEGER NOT NULL,
 Billing_address varchar2(100) NOT NULL,
  Shipping_address varchar2(100) NOT NULL,
   Shipping_method varchar2(20) NOT NULL,
delivery_type varchar2(20),
PRIMARY KEY(Shipping_info_no)
)",

"create TABLE Order_placedby_shippedwith
        (order_no varchar2(7),
order_date date,
Free_shipping varchar2(1),
Status varchar2(10),
Order_total DOUBLE precision,
Payment_method varchar2(10),
Points_awarded INTEGER,
Account_no varchar2(5) NOT NULL,
Shipping_info_no varchar2(6),
PRIMARY KEY (order_no),
FOREIGN KEY (Account_no) REFERENCES Customer,
FOREIGN KEY (shipping_info_no) REFERENCES Shipping_Info
)",


"create TABLE Contains
        (PID varchar2(5),
  order_no varchar2(7),
PRIMARY KEY (PID,order_no),
FOREIGN KEY (PID) REFERENCES product_discount,
FOREIGN KEY (order_no) REFERENCES Order_placedby_shippedwith
)",


"create TABLE owns
        (Account_no varchar2(5),
Shipping_info_no varchar2(6),
PRIMARY KEY (Account_no, Shipping_info_no),
FOREIGN KEY (Account_no) REFERENCES Customer,
FOREIGN KEY (Shipping_info_no) REFERENCES Shipping_Info
)",);

        $this->allDropTables = array(
        "drop table Employee cascade constraints","drop table Restock cascade constraints",
"drop table Deal cascade constraints",
"drop table Manage cascade constraints",
"drop table product_discount cascade constraints",
"drop table Food cascade constraints",
"drop table Beverage cascade constraints",
"drop table PersonalCare cascade constraints",
"drop table Access_to cascade constraints",
"drop table Customer cascade constraints",
"drop table Contains cascade constraints",
"drop table Shipping_Info cascade constraints",

"drop table Order_placedby_shippedwith cascade constraints",
"drop table owns cascade constraints");

        $Employee1 = array(
            ":bind1" => "EMP0001",
            ":bind2" => "123456789",
        );

        $Employee2 = array(
            ":bind1" => "EMP0002",
            ":bind2" => "123456788",
        );

        $Employee3 = array(
            ":bind1" => "EMP0003",
            ":bind2" => "123456787",
        );

        $Customer1 = array(
            ":bind1" => "C0001",
            ":bind2" => "John Smith",
            ":bind3" => "Jsmith@gmail.com",
            ":bind4" => 13000,
            ":bind5" => 1
        );

        $Customer2 = array(
            ":bind1" => "C0002",
            ":bind2" => "Ryan Reynolds",
            ":bind3" => "Rreynolds@gmail.com",
            ":bind4" => 1340,
            ":bind5" => 0
        );

        $Customer3 = array(
            ":bind1" => "C0003",
            ":bind2" => "Emma Watson",
            ":bind3" => "ewatson@gmail.com",
            ":bind4" => 0,
            ":bind5" => 0
        );


        $this->Employees = array(
            $Employee1, $Employee2, $Employee3
        );

        $this->Customers = array(
            $Customer1, $Customer2, $Customer3
        );

        $Products1 = array(
            ":bind1" => 'P0001',
            ":bind2" => 10.00,
            ":bind3" => '2018-2-1',
            ":bind4" => 'sandwich',
            ":bind5" => 20.00,
            ":bind6" => 'CANADA',
            ":bind7" => 10,
            ":bind8" => 'nameA',
            ":bind9" => 'nike',
            ":bind10" => 'this is sandwich',
            ":bind11" => 10,
            ":bind12" => 'D0001'
        );

        $Products2 = array(
            ":bind1" => 'P0002',
            ":bind2" => 10.00,
            ":bind3" => '2018-2-1',
            ":bind4" => 'sandwich',
            ":bind5" => 20.00,
            ":bind6" => 'CANADA',
            ":bind7" => 10,
            ":bind8" => 'nameA',
            ":bind9" => 'nike',
            ":bind10" => 'this is sandwich',
            ":bind11" => 10,
            ":bind12" => 'D0001'
        );

        $Products3 = array(
            ":bind1" => 'P0003',
            ":bind2" => 10.00,
            ":bind3" => '2018-2-1',
            ":bind4" => 'sandwich',
            ":bind5" => 20.00,
            ":bind6" => 'CANADA',
            ":bind7" => 10,
            ":bind8" => 'nameA',
            ":bind9" => 'nike',
            ":bind10" => 'this is sandwich',
            ":bind11" => 10,
            ":bind12" => 'D0001'
        );

        $Products4 = array(
            ":bind1" => 'P0004',
            ":bind2" => 10.00,
            ":bind3" => '2018-2-1',
            ":bind4" => 'sandwich',
            ":bind5" => 20.00,
            ":bind6" => 'CANADA',
            ":bind7" => 10,
            ":bind8" => 'nameA',
            ":bind9" => 'addidas',
            ":bind10" => 'this is sandwich',
            ":bind11" => 10,
            ":bind12" => 'D0001'
        );


        $Order1 = array(
            ":bind1" => 'O0000',
            ":bind2" => '2018-4-6',
            ":bind3" => 1,
            ":bind4" => 'processing',
            ":bind5" => 10.00,
            ":bind6" => 'Visa',
            ":bind7" => 5.00,
            ":bind8" => 'C0001',
            ":bind9" => 'S0000',
        );

        $Shipping1 = array (
            //this needs shipping info no
            ":bind0" => 'S0000',
            ":bind1" => '6049999999',
            ":bind2" => '4501 W 99th Avenue',
            ":bind3" => '4501 W 99th Avenue',
            ":bind4" => 'Express',
            ":bind5" => 'Home Delivery'
        );

        $Owns1 = array(
            ":bind0" => 'C0001',
            ":bind1" => 'S0000'
        );

        $Contains1 = array(
            ":bind0" => 'P0001',
            ":bind1" => 'O0000'
        );

        $Deals1 = array(
            ":bind1" => 'D0001',
            ":bind2" => '2018-1-1',
            ":bind3" => '2018-2-1',
            ":bind4" => 'url0',
            ":bind5" => 0.2,
            ":bind6" => 'Y'
        );

        $this->Deal = array(
            $Deals1
        );

        $Food1 = array(
            ":bind1" => 'P0001',
            ":bind2" => 100.00,
            ":bind3" => 'none'
        );

        $Beverage1 = array(
            ":bind1" => 'P0002',
            ":bind2" => 'none',
            ":bind3" => 250.00
        );

        $Personal1 = array(
            ":bind1" => 'P0002',
            ":bind2" => 'none',
        );

        $this->Product = array(
            $Products1,
            $Products2,
            $Products3,
            $Products4
        );

        $this->Food = array(
            $Food1
        );
        $this->Beverage = array(
            $Beverage1
        );

        $this->Personal = array(
            $Personal1
        );

        $this->Orders = array(
            $Order1
        );

        $this->Shipping = array(
            $Shipping1
        );

        $this->owns = array(
            $Owns1
        );

        $this->Contains = array(
            $Contains1
        );
    }

    function populateAll(){
        global $db_conn, $success; //later try catching exception if oci_commit doesn't go through

        foreach($this->allCreateTables as $createTable){
            $this->SQLExecution->executePlainSql($createTable);
            OCICommit($db_conn);
        }

    }

    function insertEmployeeCustomer(){
        global $db_conn;

        $this->SQLExecution->executeBoundSQL("insert into Employee values(:bind1, :bind2)", $this->Employees);
        $this->SQLExecution->executeBoundSQL("insert into Customer values(:bind1, :bind2, :bind3, :bind4, :bind5)", $this->Customers);
        OCICommit($db_conn);
        $this->SQLExecution->executeBoundSQL("insert into Deal values(:bind1, :bind2, :bind3, :bind4, :bind5, :bind6)", $this->Deal);
        $this->SQLExecution->executeBoundSQL("insert into product_discount values(:bind1, :bind2, :bind3, :bind4, :bind5, :bind6, :bind7, :bind8, :bind9, :bind10, :bind11, :bind12)", $this->Product);
        OCICommit($db_conn);
        $this->SQLExecution->executeBoundSQL("insert into Food values(:bind1, :bind2, :bind3)", $this->Food);
        $this->SQLExecution->executeBoundSQL("insert into Beverage values(:bind1, :bind2, :bind3)", $this->Beverage);
        $this->SQLExecution->executeBoundSQL("insert into PersonalCare values(:bind1, :bind2)", $this->Personal);
        OCICommit($db_conn);
        $this->SQLExecution->executeBoundSQL("insert into Shipping_info values(:bind0, :bind1, :bind2, :bind3, :bind4, :bind5)", $this->Shipping);
        OCICommit($db_conn);
        $this->SQLExecution->executeBoundSQL("insert into owns values(:bind0, :bind1)", $this->owns);
        OCICommit($db_conn);
        $this->SQLExecution->executeBoundSQL("insert into Order_placedby_shippedwith values(:bind1, :bind2, :bind3, :bind4, :bind5, :bind6, :bind7, :bind8, :bind9)", $this->Orders);
        $this->SQLExecution->executeBoundSQL("insert into Contains values(:bind0, :bind1)", $this->Contains);
        OCICommit($db_conn);
    }

    function dropAll(){
        global $db_conn;
        foreach($this->allDropTables as $dropTable){
            $this->SQLExecution->executePlainSQL($dropTable);
            OCICommit($db_conn);
        }

    }

}