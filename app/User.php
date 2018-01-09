<?php

namespace App;

// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;
    // use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    protected $fillable = ['id', 'first_name', 'last_name', 'company', 'email', 'username', 'password', 'phone', 'address_1', 'address_2', 'city_id', 'state_id', 'zipcode', 'profile_image', 'type_id', 'user_role_id', 'profile_status', 'status', 'remember_token', 'created_at', 'updated_at', 'profile_picture', 'latitude', 'longitude','customer_type_id','available_zipcodes','office_notes' ];


    public function customerType()
    {
        return $this->belongsTo(\App\CustomerType::class, 'customer_type_id');
    }

    public function userType()
    {
        return $this->belongsTo(\App\UserType::class, 'type_id');
    }

    public function userRole()
    {
        return $this->belongsTo(\App\UserRole::class, 'user_role_id');
    }

    public function city()
    {
        return $this->belongsTo(\App\City::class, 'city_id');
    }

    public function state()
    {
        return $this->belongsTo(\App\State::class, 'state_id');
    }
    public function maintenanceRequest()
    {
        return $this->hasMany(\App\MaintenanceRequest::class, 'customer_id');
    }

    public function assignRequest()
    {
        return $this->hasMany(\App\AssignRequest::class, 'vendor_id');
    }

    public function specialPrice()
    {
        return $this->hasMany(\App\SpecialPrice::class, 'customer_id');
    }

    public function asset()
    {
        return $this->hasMany(\App\Asset::class, 'customer_id');
    }

    public function vendorService()
    {
        return $this->hasMany(\App\VendorService::class, 'vendor_id');
    }

    public function orderVendor()
    {
        $user_type = UserType::where('title', '=', 'vendors');
        $user_type_id = $user_type->id;
        return $this->hasMany(\App\Order::class, 'vendor_id')->where('type_id', '=', $user_type_id);
    }

    public function orderCustomer()
    {
        $user_type = UserType::where('title', '=', 'customer');
        $user_type_id = $user_type->id;
        return $this->hasMany(\App\Order::class, 'customer_id')->where('type_id', '=', $user_type_id);
    }

    public static function createUser($data)
    {
        $users = User::create($data);
        return $users->id;
    }

    public static function profile($data, $id)
    {
        $save = User::find($id)->update($data);
        return $save;
    }

    public static function getProfileStatusById($id)
    {
        $user = self::find($id);
        return $user->profile_status; //return user type
    }

    public static function getUserByEmail($email) 
    {
        $user = self::where('email', $email)->get();
        return $user;
    }
    public static function getUserStatusById($id)
    {
        $user = self::find($id);
        return $user->status; //return user type
    }
    public static function getUserNameArray($id)
    {
        $user_details = self::find($id);

        $user_array = [];
        if (isset($user_details->first_name))
        {
            $user_array['first_name'] = $user_details->first_name;
        } else 
        {
            $user_array['first_name'] = '';
        }

        if (isset($user_details->last_name))
        {
            $user_array['last_name'] = $user_details->last_name;
        }
        else
        {
            $user_array['last_name'] = '';
        }
        return $user_array;
    }
    public static function getUserFullName($id)
    {
        $user_detail = self::find($id);
        if (sizeof($user_detail) > 0) {
            $data = $user_detail->first_name . ' ' . $user_detail->last_name;
        } else { // show user not found
            $data = "User Not Found";
        }

        return $data;
    }

    // Defining Function to get User Status by ID. ---- End

    public static function updateAdminUser($user_data, $user_id)
    {
        $user = self::find($user_id);
        $user->first_name = $user_data['first_name'];
        $user->last_name = $user_data['last_name'];
        $user->email = $user_data['email'];
        $user->user_role_id = $user_data['role_id'];
        $save = $user->save();
        return ($save) ? true : false;
    }

    public static function getAllCustomers()
    {
        $user_type_id = UserType::where('title', '=', 'customer')->first();
        $cust = [];
        $customers = self::where('type_id', '=', $user_type_id->id)->get();
        foreach ($customers as $cus) {
            $cust[$cus->id] = $cus->first_name . ' ' . $cus->last_name;
        }
        return $cust;
    }

    public static function getAllVendors()
    {
        $usertype = UserType::where('title', '=', 'vendors')->first();
        $vendors = self::where('type_id', '=', $usertype->id)->orderBy('id', 'desc')->get();

        return $vendors;
    }

    public static function getCustomers()
    {
        $user_type_id = UserType::where('title', '=', 'customer')->first();
        $customers = self::where('type_id', '=', $user_type_id->id)->orderBy('id', 'desc')->get();
        return $customers;
    }

    public static function getAdminUser()
    {
        $users = self::where('type_id', '=', 4)->get();
        return $users;
    }

    public static function getAdminUsersId()
    {
        $users = self::whereIn('type_id', [1, 4])->get();
        $user_ids = [];
        foreach ($users as $user) {
            $user_ids[] = $user->id;
        }
        return $user_ids;
    }
    public static function getOnlyAdminUsersId()
    {
           $users = self::whereIn('type_id', [1])->get();
           $user_ids = [];
        foreach ($users as $user) {
            $user_ids['id'] = $user->id;
            $user_ids['email'] = $user->email;
        }
           return $user_ids;
    }


    public static function getAllUsersEmailByIds($ids)
    {
        $user_email = [];
        foreach ($ids as $id) {
            $user_emails[] = self::getEmail($id);
        }

        return $user_emails;
    }

    public function getTable()
    {
        return $this->table;
    }

    public static function getEmail($id)
    {
        $user = self::find($id);
        return $user->email; //return user type
    }

    /*
     * Function Name : getUserByTypeId
     * @params :$type_id
     * @description : This function is being used for getting all available vendors with respect to the following conditions
     * 1.	All the nearest vendors according to zipcodes
      2.	All the vendors previously worked for this Asset
      3.	All vendors previously assigned this same request with cross sign will be available so
     */

    public function getUserByTypeId($type_id = 1, $lat = 0, $lon = 0, $nearest_dist = 50, $RequestedServiceIDS = [])
    {

       /*
       * * 1. All the nearest vendors according to zipcodes
       */

        $users_nearest = self::select(
            DB::raw("*,( 6371 * acos( cos( radians(?) ) *
            cos( radians(latitude ) )
                            * cos( radians(longitude ) - radians(?)
            ) + sin( radians(?) ) *
          sin( radians( latitude ) ) )
          ) AS distance")
        )
        ->having("distance", "<", $nearest_dist)
        ->having("type_id", "=", $type_id)
        ->orderBy("distance")
        ->setBindings([$lat, $lon, $lat])
        ->get();
        $nearest_user_ids = [];

        foreach ($users_nearest as $nearest) {
            $VendorServices =   VendorService::where('vendor_id', '=', $nearest->id)->get();

            $VendorServicesIDS=[];
            foreach ($VendorServices as $value) {
                $VendorServicesIDS[]=$value['service_id'];
            }


            $resultFlag = array_intersect($RequestedServiceIDS, $VendorServicesIDS);

            if (!empty($resultFlag)) {
                $nearest_user_ids[] = $nearest->id;
            }
        }

        /*
       * 2.   All the vendors previously worked for this Asset
       */
        $vendors_previously_worked_for_this_assset = MaintenanceRequest::getMaintenanceRequestByAssetId(1);
        $vendors = [];


        foreach ($vendors_previously_worked_for_this_assset as $assign_request_data) {
            foreach ($assign_request_data->assignRequest as $vendor_data) {
                $vendors[] = $vendor_data->vendor_id;
            }
        }
        $vendors_ids = array_unique($vendors);





        $all_users_ids = array_unique(array_merge($vendors_ids, $nearest_user_ids));




        /*
       * Getting users with  respect to all posible conditions
       $users = self::select("*")->whereIn('id', $all_users_ids)->get();
       */
        if (!empty($all_users_ids)) {
            $users = self::select("*")->where('type_id', '=', 3)->where('status', '=', '1')->get();
        } else {
            $users = self::select("*")->where('type_id', '=', 3)->where('status', '=', '1')->get();
        }


        return $users;
    }


    public static function getNearestUsers($type_id = 1, $lat = 0, $lon = 0, $nearest_dist = 50)
    {

        /*
      * * 1. All the nearest vendors according to zipcodes
      */

        $users_nearest = self::select(
            DB::raw("*,( 6371 * acos( cos( radians(?) ) *
            cos( radians(latitude ) )
                            * cos( radians(longitude ) - radians(?)
            ) + sin( radians(?) ) *
          sin( radians( latitude ) ) )
          ) AS distance")
        )
        ->having("distance", "<", $nearest_dist)
        ->having("type_id", "=", $type_id)
        ->orderBy("distance")
        ->setBindings([$lat, $lon, $lat])
        ->get();
        return $users_nearest;
    }
    public static function getCustomerCompanyById($id)
    {
        $user = self::find($id);
        return $user->company;
    }

    public static function getVendors($type_id = 3)
    {
        $technicians = self::where('type_id', '=', $type_id)
        ->where("status", "=", 1)
        ->orderBy("first_name", "ASC")
        ->get();
        return      $technicians ;
    }

    public static function getAllVendorsInDatatable()
    {

        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
        * Easy set variables
        */

        /* Array of database columns which should be read and sent back to DataTables. Use a space where
        * you want to insert a non-database field (for example a counter or static image)
        */
        $aColumns = ['id', 'first_name', 'last_name', 'company', 'email', 'username', 'password', 'phone', 'address_1', 'address_2', 'zipcode', 'profile_image', 'type_id', 'user_role_id', 'customer_type_id', 'profile_status', 'status', 'remember_token', 'created_at', 'updated_at', 'city_id', 'state_id', 'profile_picture', 'latitude', 'longitude', 'deleted_at', 'available_zipcodes', 'office_notes'];
        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "id";

        /* DB table to use */
        $sTable = "users";

        $gaSql['user']       = config('database.connections.mysql.username');
        $gaSql['password']   = config('database.connections.mysql.password');
        $gaSql['db']         = config('database.connections.mysql.database');
        $gaSql['server']     = config('database.connections.mysql.host');


        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
        * If you just want to use the basic configuration for DataTables with PHP server-side, there is
        * no need to edit below this line
        */

        /*
        * Local functions
        */
        function fatal_error($sErrorMessage = '')
        {
            header($_SERVER['SERVER_PROTOCOL'] .' 500 Internal Server Error');
            die($sErrorMessage);
        }


        /*
        * MySQL connection
        */
        if (! $gaSql['link'] = mysql_pconnect($gaSql['server'], $gaSql['user'], $gaSql['password'])) {
            fatal_error('Could not open connection to server');
        }

        if (! mysql_select_db($gaSql['db'], $gaSql['link'])) {
            fatal_error('Could not select database ');
        }


        /*
        * Paging
        */
        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT ".intval($_GET['iDisplayStart']).", ".
                    intval($_GET['iDisplayLength']);
        }


        /*
        * Ordering
        */
        $sOrder = "";
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            for ($i=0; $i<intval($_GET['iSortingCols']); $i++) {
                if ($_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true") {
                    $sOrder .= "`".$aColumns[ intval($_GET['iSortCol_'.$i]) ]."` ".
                        ($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }


        /*
        * Filtering
        * NOTE this does not match the built-in DataTables filtering which does it
        * word by word on any field. It's possible to do here, but concerned about efficiency
        * on very large tables, and MySQL's regex functionality is very limited
        */
        $sWhere = "";
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $aWords = preg_split('/\s+/', trim($_GET['sSearch']));
            // $sWhere = "WHERE (";
            $sWhere = "WHERE";
            for ($j=0; $j<count($aWords); $j++) {
                if ($aWords[$j] != "") {
                    if (substr($aWords[$j], 0, 1) == "!") {
                        $notString = substr($aWords[$j], 1);
                        $sWhere .= "(";
                        for ($i=0; $i<count($aColumns); $i++) {
                            $sWhere .= $aColumns[$i]." NOT LIKE '%".mysql_real_escape_string($notString)."%' AND ";
                        }
                        $sWhere = substr_replace($sWhere, "", -4);
                    } else {
                        $sWhere .= "(";
                        for ($i=0; $i<count($aColumns); $i++) {
                            $sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($aWords[$j])."%' OR ";
                        }
                        $sWhere = substr_replace($sWhere, "", -3);
                    }
                    if ($j == count($aWords) - 1) {
                        $sWhere .= ")";
                    } else {
                        $sWhere .= ") AND ";
                    }
                }
            }

            // $sWhere = "WHERE (";
            // for ( $i=0 ; $i<count($aColumns) ; $i++ )
            // {
            //     $sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string( trim($_GET['sSearch']) )."%' OR ";
            // }
            // $sWhere = substr_replace( $sWhere, "", -3 );
            // $sWhere .= ')';
        }

        /* Individual column filtering */
        for ($i=0; $i<count($aColumns); $i++) {
            if (isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '') {
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string(trim($_GET['sSearch_'.$i]))."%' ";
            }
        }


        /*
        * SQL queries
        * Get data to display
        */
        $sQuery = "
    SELECT SQL_CALC_FOUND_ROWS `".str_replace(" , ", " ", implode("`, `", $aColumns))."`
            FROM   $sTable
            $sWhere
            $sOrder
            $sLimit
            ";
        $rResult = mysql_query($sQuery, $gaSql['link']) or fatal_error('MySQL Error: ' . exit($sQuery));

        /* Data set length after filtering */
        $sQuery = "
    SELECT FOUND_ROWS()
";
        $rResultFilterTotal = mysql_query($sQuery, $gaSql['link']) or fatal_error('MySQL Error: ' . mysql_errno());
        $aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
        $iFilteredTotal = $aResultFilterTotal[0];

        /* Total data set length */
        $sQuery = "
    SELECT COUNT(`".$sIndexColumn."`)
            FROM   $sTable
            ";
        $rResultTotal = mysql_query($sQuery, $gaSql['link']) or fatal_error('MySQL Error: ' . mysql_errno());
        $aResultTotal = mysql_fetch_array($rResultTotal);
        $iTotal = $aResultTotal[0];


        /*
        * Output
        */if (empty($_GET['sEcho'])) {
            $_GET['sEcho'] = 1;
} else {
    $output = [
    "sEcho" => intval($_GET['sEcho']),
    "iTotalRecords" => $iTotal,
    "iTotalDisplayRecords" => $iFilteredTotal,
    "aaData" => []
    ];
}
while ($aRow = mysql_fetch_array($rResult)) {
    $row = [];
    for ($i=0; $i<count($aColumns); $i++) {
        if ($aColumns[$i] == "version") {
            /* Special output formatting for 'version' column */
            $row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $aRow[ $aColumns[$i] ];
        } else if ($aColumns[$i] != ' ') {
            /* General output */
            $row[] = $aRow[ $aColumns[$i] ];
        }
    }
    $output['aaData'][] = $row;
}
        return $output;
    }
}
