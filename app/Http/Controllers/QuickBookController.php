<?php

namespace App\Http\Controllers;

use App\User;




/**

 * QuickBook Controller Class.

 *

 * Handles QuickBook Integration.

 * @copyright Copyright 2014 Invortex Technology Development Team

 * @version $Id: 1.0

 */

class QuickBookController extends Controller
{



    /**

     * Initializing the QuickBook.

     * @params none

     * @return QB Object.

     */

    public function index()
    {


        $this->qbwc();
    }

    

    public function config()
    {
        die('shery');
        $name = 'QuickBooks Demo';          // A name for your server (make it whatever you want)

        $descrip = 'QuickBooks Demo';       // A description of your server



        $appurl = 'https://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/qbwc';      // This *must* be httpS:// (path to your QuickBooks SOAP server)

        $appsupport = $appurl;      // This *must* be httpS:// and the domain name must match the domain name above



        $username = config('quickbooks.quickbooks_user');      // This is the username you stored in the 'quickbooks_user' table by using QuickBooks_Utilities::createUser()



        $fileid = QuickBooks_WebConnector_QWC::fileID();        // Just make this up, but make sure it keeps that format

        $ownerid = QuickBooks_WebConnector_QWC::ownerID();      // Just make this up, but make sure it keeps that format



        $qbtype = QUICKBOOKS_TYPE_QBFS; // You can leave this as-is unless you're using QuickBooks POS



        $readonly = false; // No, we want to write data to QuickBooks



        $run_every_n_seconds = 600; // Run every 600 seconds (10 minutes)



        // Generate the XML file

        $QWC = new QuickBooks_WebConnector_QWC($name, $descrip, $appurl, $appsupport, $username, $fileid, $ownerid, $qbtype, $readonly, $run_every_n_seconds);

        $xml = $QWC->generate();



        // Send as a file download

        header('Content-type: text/xml');

        //header('Content-Disposition: attachment; filename="my-quickbooks-wc-file.qwc"');

        print($xml);

        exit;
    }

    

    public function qbwc()
    {
// 		if (!function_exists('mysqli_init') && !extension_loaded('mysqli')) {
//     echo 'no mysqli :(';
// } else {
//     echo 'we gots it';
// }
// die;

        $user = 'quickbooks';
        $pass = 'password';

        

        $username = config('database.connections.mysql.username');

        $password = config('database.connections.mysql.password');

        // Memory limit

        ini_set('memory_limit', config('quickbooks.quickbooks_memorylimit'));

        

        // We need to make sure the correct timezone is set, or some PHP installations will complain

        if (function_exists('date_default_timezone_set')) {
            // * MAKE SURE YOU SET THIS TO THE CORRECT TIMEZONE! *

            // List of valid timezones is here: http://us3.php.net/manual/en/timezones.php

            date_default_timezone_set(config('quickbooks.quickbooks_tz'));
        }

                

        // Map QuickBooks actions to handler functions

        $map = [

            QUICKBOOKS_ADD_CUSTOMER => [ [ $this, 'addCustomerRequest' ], [ $this, 'addCustomerResponse' ] ],

            ];

        

        // Catch all errors that QuickBooks throws with this function

        $errmap = [

            '*' => [ $this, '_catchallErrors' ],

            ];

        

        // Call this method whenever the Web Connector connects

        $hooks = [

            //QuickBooks_WebConnector_Handlers::HOOK_LOGINSUCCESS => array( array( $this, '_loginSuccess' ) ), 	// Run this function whenever a successful login occurs

            ];

        

        // An array of callback options

        $callback_options = [];

        

        // Logging level

        $log_level = config('quickbooks.quickbooks_loglevel');

        

        // What SOAP server you're using

        //$soapserver = QUICKBOOKS_SOAPSERVER_PHP;			// The PHP SOAP extension, see: www.php.net/soap

        $soapserver = QUICKBOOKS_SOAPSERVER_BUILTIN;        // A pure-PHP SOAP server (no PHP ext/soap extension required, also makes debugging easier)

        

        $soap_options = [      // See http://www.php.net/soap

            ];

        

        $handler_options = [

            'deny_concurrent_logins' => false,

            'deny_reallyfast_logins' => false,

            ];      // See the comments in the QuickBooks/Server/Handlers.php file

        

        $driver_options = [        // See the comments in the QuickBooks/Driver/<YOUR DRIVER HERE>.php file ( i.e. 'Mysql.php', etc. )

            'max_log_history' => 32000, // Limit the number of quickbooks_log entries to 1024

            'max_queue_history' => 1024,    // Limit the number of *successfully processed* quickbooks_queue entries to 64

            ];

        

        // Build the database connection string

        $db_user = config('database.connections.mysql.username');

        $db_pass = config('database.connections.mysql.password');

        $db_host = config('database.connections.mysql.host');

        $db_db = config('database.connections.mysql.database');

        // Build the database connection string

        $dsn = 'mysqli://' . $db_user . ':' . $db_pass  . '@' . $db_host . '/' . $db_db;

        
        $primary_key_of_your_customer = 4;

        $Queue = new QuickBooks_WebConnector_Queue($dsn);
        $Queue->enqueue(QUICKBOOKS_ADD_CUSTOMER, $primary_key_of_your_customer);

        

        // Check to make sure our database is set up

        if (!QuickBooks_Utilities::initialized($dsn)) {
            // Initialize creates the neccessary database schema for queueing up requests and logging

            QuickBooks_Utilities::initialize($dsn);

            

            // This creates a username and password which is used by the Web Connector to authenticate

            QuickBooks_Utilities::createUser($dsn, $user, $pass);
        }

        

        // Set up our queue singleton

        QuickBooks_WebConnector_Queue_Singleton::initialize($dsn);

        

        // Create a new server and tell it to handle the requests

        // __construct($dsn_or_conn, $map, $errmap = array(), $hooks = array(), $log_level = QUICKBOOKS_LOG_NORMAL, $soap = QUICKBOOKS_SOAPSERVER_PHP, $wsdl = QUICKBOOKS_WSDL, $soap_options = array(), $handler_options = array(), $driver_options = array(), $callback_options = array()

        $Server = new QuickBooks_WebConnector_Server($dsn, $map, $errmap, $hooks, $log_level, $soapserver, QUICKBOOKS_WSDL, $soap_options, $handler_options, $driver_options, $callback_options);

        $response = $Server->handle(true, true);
    }

    

    /**

     * Issue a request to QuickBooks to add a customer

     */

    public function addCustomerRequest($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
    {

        $customer = User::find($ID);

        // Do something here to load data using your model

        //$data = $this->yourmodel->getCustomerData($ID);

        

        // Build the qbXML request from $data

        $xml = '<?xml version="1.0" encoding="utf-8"?>

		<?qbxml version="2.0"?>

		<QBXML>

			<QBXMLMsgsRq onError="stopOnError">

				<CustomerAddRq requestID="' . $requestID . '">

					<CustomerAdd>

						<Name>'.$customer->first_name. " ".$customer->last_name.'</Name>

						<CompanyName>'.$customer->company.'</CompanyName>

						<FirstName>'.$customer->first_name.'</FirstName>

						<LastName>'.$customer->last_name.'</LastName>

						<BillAddress>

							<Addr1>'.$customer->address1.'</Addr1>

							<Addr2>'.$customer->address2.'</Addr2>

							<City>'.$customer->city->name.'</City>

							<State>'.$customer->state->name.'</State>

							<PostalCode>'.$customer->zipcode.'</PostalCode>

							<Country>United States</Country>

						</BillAddress>

						<Phone>'.$customer->phone.'</Phone>

						<AltPhone>860-429-0021</AltPhone>

						<Fax>860-429-5183</Fax>

						<Email>'.$customer->email.'</Email>

						<Contact>'.$customer->first_name.' '.$customer->last_name.'</Contact>

					</CustomerAdd>

				</CustomerAddRq>

			</QBXMLMsgsRq>

		</QBXML>';

    

        return $xml;
    }



    /**

     * Handle a response from QuickBooks indicating a new customer has been added

     */

    public function addCustomerResponse($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
    {

        // Do something here to record that the data was added to QuickBooks successfully

        

        return true;
    }

    

    /**

     * Catch and handle errors from QuickBooks

     */

    public function catchallErrors($requestID, $user, $action, $ID, $extra, &$err, $xml, $errnum, $errmsg)
    {

        return false;
    }

    

    /**

     * Whenever the Web Connector connects, do something (e.g. queue some stuff up if you want to)

     */

    public function loginSuccess($requestID, $user, $hook, &$err, $hook_data, $callback_config)
    {

        return true;
    }
}
