<?php


/*

  |--------------------------------------------------------------------------

  | Application Routes

  |--------------------------------------------------------------------------

  |

  | Here is where you can register all of the routes for an application.

  | It's a breeze. Simply tell Laravel the URIs it should respond to

  | and give it the Closure to execute when that URI is requested.

  |

 */
Route::get('work-order-info', function () {

    echo "<pre>";
    echo "Host:" . " " . DB::connection()->getConfig("host");
    echo "<br>";
    echo "Database Name:" . " " . DB::connection()->getConfig("database");
    echo "<br>";
    echo "Username:" . " " . DB::connection()->getConfig("username");
    echo "<br>";
    echo "Password:" . " " . DB::connection()->getConfig("password");
    echo "<br>";
});

//Route::get('refresh', ['uses' => 'BaseController@notify']);

Route::get('/', ['uses' => 'UserController@showLogin']);

Route::get('active-customer/{id}', ['uses' => 'CustomerController@activeCustomer']);

Route::get('active-user/{id}', ['uses' => 'UserController@activeUser']);

Route::match(['GET', 'POST'], 'get-asset-map', ['uses' => 'AssetController@getAssetMap']);

// Swap Routes

Route::get('swap-db', ['uses' => 'AdminController@swapDB']);

Route::post('swap-db-admin', ['uses' => 'AdminController@swapDBNow']);


// Common Routes
// Route::get('hashMake', function() {

//   //$password = Hash::make('johnsanchez$12');
//   print_r($password);

// });
Route::post('show-additional-service-popup', ['uses' => 'AjaxController@getServicePopup']);
Route::match(['GET', 'POST'], 'delete-selected-asset/{id}', ['uses' => 'AssetController@deleteSelectedAsset']);

Route::get('get-quick-workorder/{id}', ['uses' => 'AjaxController@loadWorkorder']);
Route::get('delete-customer-asset/{id}', ['uses' => 'AssetController@deleteAsset']);
Route::get('logout', function () {

    Auth::logout();

    Session::put('clientType', "");

    return view('home');
});

Route::match(['GET', 'POST'], 'update-additional-service/{id}', ['uses' => 'ServiceController@updateAdditionalItem']);
Route::match(['GET', 'POST'], 'additional-service-title-change', ['uses' => 'ServiceController@checkAddedTitle']);
Route::match(['GET', 'POST'], 'add-additional-service', ['uses' => 'ServiceController@addAdditionalItem']);

Route::match(['GET', 'POST'], 'add-requested-service/{id}', ['uses' => 'ServiceController@addRequestedService']);

#########################################################  Admin Routes ####################################################3

Route::group(['middleware' => 'auth|adminCheck|adminRightsCheck'], function () {

    // Login Secure Admin Routes


    Route::get('login-as/{user_id}', ['uses' => 'UserController@doLoginAsUser']);

    Route::get('admin', ['uses' => 'AdminController@index']);

    Route::get('access-rights', ['uses' => 'AccessRightController@index']);

    Route::match(['GET', 'POST'], 'add-asset', ['uses' => 'AssetController@addAdminAsset']);

    Route::match(['GET', 'POST'], 'add-asset/{id}', ['uses' => 'AssetController@addAdminAsset']);

    Route::match(['GET', 'POST'], 'approved-pagination', ['uses' => 'AdminController@ajaxDashoboardGridOrdersPagination']);


    Route::match(['GET', 'POST'], 'add-service', ['uses' => 'ServiceController@addAdminService']);

    Route::match(['GET', 'POST'], 'list-assets', ['uses' => 'AssetController@listAdminAssets']);

    Route::match(['GET', 'POST'], 'list-assets-summary', ['uses' => 'AssetController@listAdminAssetsSummary']);

    Route::match(['GET', 'POST'], 'status-report', ['uses' => 'OrderController@statusReport']);


    Route::match(['GET', 'POST'], 'property-report', ['uses' => 'AssetController@propertyReport']);

    Route::match(['GET', 'POST'], 'recurring-report', ['uses' => 'AssetController@recurringReport']);

    Route::match(['GET', 'POST'], 'reporting', ['uses' => 'AssetController@reporting']);

    Route::match(['GET', 'POST'], 'whiteboard-reporting', ['uses' => 'AssetController@whiteboardReporting']);


    Route::match(['GET', 'POST'], 'list-vendor-summary', ['uses' => 'AdminController@listVendorsSummary']);

    Route::match(['GET', 'POST'], 'list-services', ['uses' => 'ServiceController@listAdminServices']);


    Route::match(['GET', 'POST'], 'list-bid-services', ['uses' => 'AdminController@listBidServices']);

    Route::match(['GET', 'POST'], 'add-special-prices', ['uses' => 'SpecialPriceController@addSpecialPrice']);

    Route::match(['GET', 'POST'], 'vendor-add-special-prices', ['uses' => 'SpecialPriceController@vendorAddSpecialPrice']);

    Route::match(['GET', 'POST'], 'list-special-prices', ['uses' => 'SpecialPriceController@listSpecialPrice']);

    Route::match(['GET', 'POST'], 'vendor-list-special-prices', ['uses' => 'SpecialPriceController@vendorListSpecialPrice']);

    Route::match(['GET', 'POST'], 'edit-special-price/{special_price_id}', ['uses' => 'SpecialPriceController@editSpecialPrice']);

    Route::match(['GET', 'POST'], 'edit-vendor-special-price/{special_price_id}', ['uses' => 'SpecialPriceController@editVendorSpecialPrice']);


    Route::match(['GET', 'POST'], 'edit-service/{service_id}', ['uses' => 'ServiceController@updateAdminService']);


    Route::match(['GET', 'POST'], 'edit-client-type/{clientID}', ['uses' => 'AdminController@editCustomerType']);


    Route::match(['GET', 'POST'], 'edit-asset/{asset_id}', ['uses' => 'AssetController@editAdminAsset']);

    // Admin Routes

    Route::match(['GET', 'POST'], 'list-user', ['uses' => 'AdminController@listUser']);
    Route::match(['GET', 'POST'], 'approved-grid-export', ['uses' => 'AjaxController@approvedGridExport']);


    // routes set to city screen by shm

    Route::match(['GET', 'POST'], 'list-city', ['uses' => 'AdminController@listCity']);

    Route::get('add-city', ['uses' => 'AdminController@addCity']);

    Route::post('addCity', ['uses' => 'AdminController@addNewCity']);

    Route::get('edit-city/{id}', ['uses' => 'AdminController@editCity']);

    Route::post('save-city', ['uses' => 'AdminController@saveCity']);


    Route::match(['GET', 'POST'], 'list-service-categories', ['uses' => 'AdminController@listServiceCategories']);

    Route::match(['GET', 'POST'], 'list-job-type', ['uses' => 'AdminController@listJobType']);


    Route::match(['GET', 'POST'], 'list-customer-type', ['uses' => 'AdminController@listCustomerType']);


    Route::match(['GET', 'POST'], 'add-access-level', ['uses' => 'AdminController@addAccessLevel']);

    Route::get('add-user', ['uses' => 'AdminController@addUser']);

    Route::get('add-service-category', ['uses' => 'AdminController@addServiceCategory']);

    Route::get('add-job-type', ['uses' => 'AdminController@addJobType']);

    Route::get('add-customer-type', ['uses' => 'AdminController@addCustomerType']);

    Route::post('addUser', ['uses' => 'AdminController@addNewUser']);

    Route::post('addServiceCat', ['uses' => 'AdminController@addNewServiceCategory']);

    Route::post('addJobType', ['uses' => 'AdminController@addNewJobType']);

    Route::post('addCustomerType', ['uses' => 'AdminController@addNewCustomerType']);

    Route::post('editCustomerType', ['uses' => 'AdminController@editNewCustomerType']);


    Route::match(['GET', 'POST'], 'edit-user/{user_id}', ['uses' => 'AdminController@editUser']);

    Route::get('list-access-level', ['uses' => 'AdminController@listAccessLevel']);

    Route::get('show-add-vendor', ['uses' => 'AdminController@addVendor']);

    Route::post('process-add-vendor', ['uses' => 'AdminController@processAddVendor']);

    Route::match(['GET', 'POST'], 'list-maintenance-request', ['uses' => 'AdminController@listMaintenanceRequest']);

    Route::match(['GET', 'POST'], 'list-bidding-request', ['uses' => 'AdminController@listBidRequest']);

    Route::match(['GET', 'POST'], 'list-assigned-maintenance-request', ['uses' => 'AdminController@listAssignedMaintenanceRequest']);

    Route::match(['GET', 'POST'], 'view-maintenance-request/{id}', ['uses' => 'AdminController@viewMaintenanceRequest']);

    Route::match(['GET', 'POST'], 'cancel-maintenance-request/{id}', ['uses' => 'AdminController@cancelMaintenanceRequest']);

    Route::match(['GET', 'POST'], 'delete-maintenance-request/{id}', ['uses' => 'AdminController@deleteMaintenanceRequest']);

    Route::match(['GET', 'POST'], 'cancel-bidding-request/{id}', ['uses' => 'AdminController@cancelBiddingRequest']);


    Route::match(['GET', 'POST'], 'view-bidding-request/{id}/{service_id}', ['uses' => 'AdminController@viewBiddingRequest']);

    Route::match(['GET', 'POST'], 'view-bidding-request/{id}', ['uses' => 'AdminController@viewBiddingRequest']);


    Route::match(['GET', 'POST'], 'add-new-customer', ['uses' => 'CustomerController@createCustomerAdmin']);

    Route::get('list-customer', ['uses' => 'CustomerController@listCustomerAdmin']);

    Route::match(['GET', 'POST'], 'edit-customer/{id}', ['uses' => 'CustomerController@editCustomerAdmin']);
    //Exported Route

    Route::get('list-exported-workorder', ['uses' => 'AdminController@listExportedWorkOrder']);

    //Work Order Routes

    Route::get('list-work-order-admin', ['uses' => 'AdminController@listWorkOrder']);

    Route::get('list-work-order-admin1', ['uses' => 'AdminController@viewonly']);

    Route::get('list-work-order-admin-grid', ['uses' => 'AdminController@listWorkOrderGrid']);


    Route::get('admin-list-completed-order', ['uses' => 'AdminController@listCompletedOrders']);

    Route::get('admin-list-invoice', ['uses' => 'InvoiceController@listAdminInvoices']);


    Route::get('admin-list-invoice/{id}', ['uses' => 'InvoiceController@listAdminInvoices']);


    //    Route::get('list-completed-order-admin', array('uses' => 'AdminController@listCompletedOrders'));

    //WorkOrder Routes


    Route::get('edit-profile-admin/{id}', ['uses' => 'AdminController@editProfileAdmin']);

    Route::get('edit-job-type/{id}', ['uses' => 'AdminController@editTypeJob']);


    Route::post('save-profile-admin/{id}', ['uses' => 'AdminController@saveUserProfile']);

    Route::match(['GET', 'POST'], 'assign-service-request', ['uses' => 'MaintenanceRequestController@assignServiceRequest']);

    Route::match(['GET', 'POST'], 'assign-service-bid', ['uses' => 'MaintenanceRequestController@assignServiceBid']);


    Route::match(['GET', 'POST'], 'admin-notes', ['uses' => 'MaintenanceRequestController@adminNotes']);

    Route::match(['GET', 'POST'], 'admin-notes-bid', ['uses' => 'MaintenanceRequestController@adminNotesBid']);

    Route::match(['GET', 'POST'], 'admin-notes-osr', ['uses' => 'MaintenanceRequestController@adminNotesOsr']);

    Route::match(['GET', 'POST'], 'customer-notes-osr', ['uses' => 'MaintenanceRequestController@customerNotesOsr']);


    Route::match(['GET', 'POST'], 'public-notes', ['uses' => 'MaintenanceRequestController@publicNotes']);

    Route::match(['GET', 'POST'], 'public-notes-bid', ['uses' => 'MaintenanceRequestController@publicNotesBid']);


    Route::match(['GET', 'POST'], 'customer-notes-bid', ['uses' => 'MaintenanceRequestController@customerNotesBid']);


    Route::match(['GET', 'POST'], 'edit-access-level/{role_id}', ['uses' => 'AccessLevelController@editAccessLevel']);

    Route::match(['GET', 'POST'], 'delete-user/{user_id}', ['uses' => 'AdminController@deleteUser']);

    Route::get('admin-add-bid-request', ['uses' => 'AdminController@addBidRequest']);

    Route::get('create-bid/{id}', ['uses' => 'AdminController@createBidRequest']);


    Route::get('admin-bid-requests', ['uses' => 'AdminController@listBidRequests']);


    Route::get('admin-bid-requests/{id}', ['uses' => 'AdminController@listBidRequests']);

    Route::get('admin-approved-bid-requests', ['uses' => 'AdminController@listApprovedBidRequests']);


    Route::get('admin-declined-bid-requests', ['uses' => 'AdminController@listDeclinedBidRequests']);

    Route::post('admin-create-bid-service-request', ['uses' => 'AdminController@createBidServiceRequest']);

    Route::match(['GET', 'POST'], 'view-admin-bid-request/{id}', ['uses' => 'AdminController@viewBidRequest']);


    Route::match(['GET', 'POST'], 'admin-accept-bid-request/', ['uses' => 'AdminController@acceptBidRequest']);

    Route::match(['GET', 'POST'], 'admin-decline-bid-request/', ['uses' => 'AdminController@DeclineBidRequest']);


    Route::get('admin-add-new-service-request', ['uses' => 'MaintenanceRequestController@viewAdminRequestForm']);

    Route::get('admin-edit-service-request/{id}', ['uses' => 'MaintenanceRequestController@editAdminRequestForm']);

    Route::get('quantity-of-approved-orders', ['uses' => 'AdminController@quantityOfApprovedOrders']);
});


/* Maintenance Request */

Route::post('create-service-request', ['uses' => 'MaintenanceRequestController@createServiceRequest'])->middleware('auth');
Route::post('create-additional-service-request', ['uses' => 'MaintenanceRequestController@createAdditionalServiceRequest'])->middleware('auth');

Route::post('edit-service-request', ['uses' => 'MaintenanceRequestController@editServiceRequest']);

Route::post('create-bid-service-request', ['uses' => 'VendorController@createBidServiceRequest']);


Route::get('list-customer-requested-services', ['uses' => 'MaintenanceRequestController@listServiceRequest']);

Route::get('list-customer-requested-bids', ['uses' => 'MaintenanceRequestController@listServiceRequestBid']);

Route::get('list-vendors', ['uses' => 'AdminController@listVendors']);
Route::get('list-vendorss', ['uses' => 'AdminController@listVendorsDynamically']);

Route::get('view-customer-request-service/{id}', ['uses' => 'MaintenanceRequestController@viewServiceRequest']);

Route::get('view-customer-request-bid/{id}', ['uses' => 'MaintenanceRequestController@viewServiceBid']);

/* Asset Routes */

Route::get('add-customer-asset', ['uses' => 'AssetController@showAddAsset']);

Route::post('create-customer-asset', ['uses' => 'AssetController@createAsset']);

Route::get('add-new-customer-asset', ['uses' => 'AssetController@showAddAssetNew']);

Route::get('add-new-customer-asset/{id}', ['uses' => 'AssetController@showAddAssetNew']);

Route::post('edit-customer-asset/{id}', ['uses' => 'AssetController@editAsset']);

Route::get('edit-customer-asset/{id}', ['uses' => 'AssetController@editAsset']);


############################################# Admin Routes for ajax call ######################################

Route::post('update-access-level', ['uses' => 'AccessLevelController@updateUserAccessLevel']);

Route::post('update-access-rights', ['uses' => 'AccessRightController@updateAccessRights']);

Route::post('get-role-details', ['uses' => 'AccessRightController@getRoleDetails']);

Route::post('update-user-status/{user_id}', ['uses' => 'AdminController@updateUserStatus']);


Route::match(['GET', 'POST'], 'delete-vendor/{vendor_id}', ['uses' => 'AdminController@deleteVendor']);

Route::match(['GET', 'POST'], 'delete-access-level/{role_id}', ['uses' => 'AccessLevelController@deleteAccessLevel']);


Route::match(['GET', 'POST'], 'asset-view/{id}', ['uses' => 'AdminController@assetView']);

Route::match(['GET', 'POST'], 'show-maintenance-services/{id}', ['uses' => 'AdminController@showMaintenanceServices']);


Route::match(['GET', 'POST'], 'show-bid-services/{id}/{service_id}', ['uses' => 'AdminController@showBidServices']);
Route::match(['GET', 'POST'], 'show-bid-services/{id}', ['uses' => 'AdminController@showBidServices']);

Route::match(['GET', 'POST'], 'show-bid-services/{id}/{flagworkorder}/{customer_bid_price}/{vendor_bid_price}/{requestedServiceBidId}/{due_date}', ['uses' => 'AdminController@showBidServices']);


Route::group(['middleware' => 'auth|customerCheck'], function () {


    Route::get('customer', ['uses' => 'CustomerController@index']);

    Route::get('view-customer-assets', ['uses' => 'AssetController@viewAssetsList']);

    Route::post('create-customer-new-asset', ['uses' => 'AssetController@createAssetNew']);

    Route::get('customer-profile-complete', ['uses' => 'CustomerController@showCompleteProfile']);

    Route::post('customer-profile-add', ['uses' => 'CustomerController@completeProfile']);

    Route::get('view-assets-list', ['uses' => 'AssetController@viewAssetsList']);


    Route::get('add-new-service-request', ['uses' => 'MaintenanceRequestController@viewRequestForm']);

    Route::get('customer-list-work-orders', ['uses' => 'CustomerController@listWorkOrder']);

    Route::get('customer-list-completed-work-orders', ['uses' => 'CustomerController@listCompletedWorkOrder']);


    Route::get('customer-approval-completion-request', ['uses' => 'CustomerController@listApprovalCompletion']);


    Route::get('customer-process-work-order', ['uses' => 'CustomerController@listProcessWorkOrder']);

    Route::get('customer-bid-requests', ['uses' => 'CustomerController@listBidRequests']);

    Route::get('customer-list-invoice', ['uses' => 'InvoiceController@listCustomerInvoices']);


    Route::get('customer-bid-requests/{id}', ['uses' => 'CustomerController@listBidRequests']);

    Route::get('customer-approved-bid-requests', ['uses' => 'CustomerController@listApprovedBidRequests']);

    Route::get('customer-declined-bid-requests', ['uses' => 'CustomerController@listDeclinedBidRequests']);

    Route::match(['GET', 'POST'], 'view-customer-bid-request/{id}', ['uses' => 'CustomerController@viewBidRequest']);

    Route::match(['GET', 'POST'], 'accept-bid-request/', ['uses' => 'CustomerController@acceptBidRequest']);

    Route::match(['GET', 'POST'], 'decline-bid-request/', ['uses' => 'CustomerController@DeclineBidRequest']);

    Route::get('customer-client-type/{id}', ['uses' => 'CustomerController@setClientType']);

    Route::get('destroy-client-type', ['uses' => 'CustomerController@unSetClientType']);


    Route::get('customer-recurring', ['uses' => 'RecurringController@showCustomerrecurring']);
});


//Ajax Customer Routes


Route::match(['GET', 'POST'], 'admin-get-customer-company', ['uses' => 'AdminController@customerCompany']);


Route::post('get-cities-by-state-id', ['uses' => 'AjaxController@getCitiesByState']);

Route::post('ajax-get-asset-by-asset-id', ['uses' => 'AjaxController@getAssetById']);

Route::post('ajax-service-information-popup', ['uses' => 'AjaxController@getServicePopup']);

Route::post('ajax-vendor-service-information-popup', ['uses' => 'AjaxController@getVendorServicePopup']);

Route::post('ajax-service-information-list', ['uses' => 'AjaxController@getServiceList']);

Route::post('ajax-service-information-list-review-order', ['uses' => 'AjaxController@getServiceListOrderReivew']);

Route::post('remove-file-from-directory', ['uses' => 'AjaxController@removeFile']);

Route::post('ajax-approve-bid-request-status-changed', ['uses' => 'MaintenanceRequestController@approveBidRequestStatusChanged']);

Route::post('ajax-approve-bid-request', ['uses' => 'MaintenanceRequestController@approveBidRequest']);

Route::post('ajax-decline-bid-request', ['uses' => 'MaintenanceRequestController@declineBidRequest']);


//Ajax Route end

################################## Vendor Routes #############################################

Route::group(['middleware' => 'auth|vendorCheck'], function () {

    Route::get('vendors', ['uses' => 'VendorController@index']);

    Route::get('vendor-profile-complete', ['uses' => 'VendorController@showCompleteProfile']);

    Route::post('vendor-profile-add', ['uses' => 'VendorController@completeProfile']);

    Route::get('vendor-profile-service', ['uses' => 'ServiceController@index']);

    Route::post('vendor-service-complete', ['uses' => 'ServiceController@assignVendorService']);

    Route::get('vendor-assigned-requests', ['uses' => 'VendorController@listAssignedRequests']);

    Route::get('vendor-assigned-bids', ['uses' => 'VendorController@listAssignedBids']);

    Route::get('vendor-list-orders', ['uses' => 'VendorController@listOrders']);

    Route::get('vendor-list-completed-orders', ['uses' => 'VendorController@listCompletedOrders']);

    Route::match(['GET', 'POST'], 'view-vendor-maintenance-request/{id}', ['uses' => 'VendorController@viewMaintenanceRequest']);

    Route::match(['GET', 'POST'], 'view-vendor-bidding-request/{id}', ['uses' => 'VendorController@viewBidRequest']);

    Route::match(['GET', 'POST'], 'decline-request/', ['uses' => 'VendorController@declineRequest']);

    Route::match(['GET', 'POST'], 'accept-request/', ['uses' => 'VendorController@acceptRequest']);

    Route::match(['GET', 'POST'], 'accept-single-request/', ['uses' => 'VendorController@acceptSingleRequest']);

    Route::get('add-osr-request', ['uses' => 'VendorController@addOsrRequest']);
    //Route::get('add-osr-request', array('uses' => 'VendorController@addOsrRequest'));

    Route::match(['GET', 'POST'], 'add-osr-request/{order_id}', ['uses' => 'VendorController@addBidRequest']);

    Route::get('vendor-bid-requests', ['uses' => 'VendorController@listBidRequests']);
    Route::get('vendor-osr-requests', ['uses' => 'VendorController@listBidRequests']);

    Route::match(['GET', 'POST'], 'view-vendor-bid-request/{id}', ['uses' => 'VendorController@viewOSR']);

    Route::get('vendor-bid-requests/{id}', ['uses' => 'VendorController@listBidRequests']);

    Route::get('vendor-list-invoice', ['uses' => 'InvoiceController@listVendorInvoices']);


    Route::get('vendor-approved-bid-requests', ['uses' => 'VendorController@listApprovedBidRequests']);

    Route::get('vendor-declined-bid-requests', ['uses' => 'VendorController@listDeclinedBidRequests']);

    Route::get('vendor-recurring', ['uses' => 'RecurringController@showVendorrecurring']);
});


################################## Other Routes #############################################

Route::match(['GET', 'POST'], 'change-price', ['uses' => 'InvoiceController@changePrice']);

Route::match(['GET', 'POST'], 'completion-date', ['uses' => 'OrderController@completionDate']);


Route::post('delete-order-before-image', ['uses' => 'OrderController@deleteBeforeImages']);

Route::post('delete-order-during-image', ['uses' => 'OrderController@deleteDuringImages']);

Route::post('delete-before-image-id', ['uses' => 'OrderController@deleteImageById']);

Route::post('delete-before-additional-image-id', ['uses' => 'OrderController@deleteImageByAdditionalItemId']);

Route::post('save-vendor-note', ['uses' => 'OrderController@saveVendorNote']);

Route::post('save-additional-vendor-note', ['uses' => 'OrderController@saveAdditionalVendorNote']);

Route::post('save-billing-note', ['uses' => 'OrderController@saveBillingNote']);

Route::post('save-admin-note', ['uses' => 'OrderController@saveAdminNote']);

Route::post('save-admin-qty', ['uses' => 'OrderController@saveAdminQuantity']);

Route::post('save-customer-note', ['uses' => 'OrderController@saveCustomerNote']);

Route::post('delete-order-after-image', ['uses' => 'OrderController@deleteAfterImages']);

Route::post('delete-order-all-before-image', ['uses' => 'OrderController@deleteOrderAllBeforeImages']);

Route::post('add-before-images', ['uses' => 'OrderController@addBeforeImages']);

Route::post('add-additional-before-images', ['uses' => 'OrderController@addAdditionalBeforeImages']);

Route::post('add-before-images-bids', ['uses' => 'VendorController@addBeforeImages']);

Route::post('add-after-images', ['uses' => 'OrderController@addAfterImages']);

Route::post('add-additional-after-images', ['uses' => 'OrderController@addAdditionalAfterImages']);

Route::post('add-during-images', ['uses' => 'OrderController@addDuringImages']);

Route::post('add-additional-during-images', ['uses' => 'OrderController@addAdditionalDuringImages']);


Route::post('add-photo-tagging', ['uses' => 'OrderController@photoTag']);

Route::post('delete-photo-tagging', ['uses' => 'OrderController@deleteTag']);


Route::post('Export-Images/{images}', ['uses' => 'OrderController@ExportImages']);
Route::post('download-seleted-images', ['uses' => 'OrderController@downloadSeletedImages']);
Route::post('download-seleted-additional-images', ['uses' => 'OrderController@downloadSeletedAdditionalItemImages']);

Route::post('display-export-images', ['uses' => 'OrderController@displayExportImages']);

Route::post('display-additional-export-images', ['uses' => 'OrderController@displayAdditionalExportImages']);

Route::post('display-order-additional-items-images', ['uses' => 'OrderController@displayAdditonalItemImages']);

Route::post('display-order-images', ['uses' => 'OrderController@displayImages']);

Route::post('display-bid-images', ['uses' => 'VendorController@displayImages']);

Route::post('display-order-images-view', ['uses' => 'OrderController@displayViewImages']);

Route::match(['GET', 'POST'], 'edit-order/{order_id}', ['uses' => 'OrderController@editOrder']);

Route::get('view-order/{order_id}', ['uses' => 'OrderController@viewOrder']);


Route::get('order-images/{order_id}', ['uses' => 'OrderController@orderImage']);

Route::match(['GET', 'POST'], 'change-status', ['uses' => 'OrderController@changeStatus']);

Route::match(['GET', 'POST'], 'close-property-status', ['uses' => 'OrderController@closePropertyStatus']);

Route::match(['GET', 'POST'], 'change-notification-status', ['uses' => 'NotificationController@ChangeNotificationStatus']);

Route::match(['GET', 'POST'], 'clear-notification', ['uses' => 'NotificationController@ChangeAllNotificationStatus']);


Route::match(['GET', 'POST'], 'edit-invoice/{order_id}', ['uses' => 'InvoiceController@editInvoice']);


Route::post('generate-asset-number', ['uses' => 'AjaxController@generateAssetNumber']);

Route::post('user-create', ['uses' => 'UserController@createUser']);

Route::get('register-page-redirect', ['uses' => 'UserController@whereRedirect']);

//Ajax Customer Routes

Route::post('get-cities-by-state-id', ['uses' => 'AjaxController@getCitiesByState']);

Route::post('ajax-get-asset-by-asset-id', ['uses' => 'AjaxController@getAssetById']);

Route::post('ajax-service-information-popup', ['uses' => 'AjaxController@getServicePopup']);

Route::post('ajax-service-information-list', ['uses' => 'AjaxController@getServiceList']);

Route::post('remove-file-from-directory', ['uses' => 'AjaxController@removeFile']);

//Ajax Route end

Route::post('login', ['uses' => 'UserController@doLogin']);

Route::get('edit-profile', ['uses' => 'UserController@editProfile']);

Route::post('save-profile', ['uses' => 'UserController@saveProfile']);

Route::post('save-job-type', ['uses' => 'AdminController@saveJobType']);

Route::get('delete-job-type/{id}', ['uses' => 'AdminController@deleteJobType']);


Route::get('thankyou/{user_id}', ['uses' => 'UserController@showThankYou']);

Route::match(['GET', 'POST'], 'update-status', ['uses' => 'CommonController@updateStatus']);

Route::match(['GET', 'POST'], 'delete-record', ['uses' => 'CommonController@deleteRecord']);

Route::get('testing-link', ['uses' => 'AjaxController@testingLink']);


Route::group(['middleware' => 'loginCheck'], function () {

    Route::get('user-register', ['uses' => 'UserController@showRegistration']);
});


Route::match(['POST', 'GET'], 'forgot-password', ['uses' => 'RemindersController@getRemind']);

Route::get('list-work-notification-url', ['uses' => 'NotificationController@listNotifications']);


Route::post('ajax-dashoboard-grid-requests', ['uses' => 'AdminController@ajaxDashoboardGridRequests']);

Route::post('ajax-dashoboard-grid-orders', ['uses' => 'AdminController@ajaxDashoboardGridOrders']);


Route::post('ajax-delete-service-request', ['uses' => 'AdminController@ajaxDeleteServiceRequest']);


Route::match(['GET', 'POST'], 'qb', ['uses' => 'QuickBookController@index']);

Route::get('cron-job', ['uses' => 'CronJobController@index']);

Route::get('recurring', ['uses' => 'RecurringController@showrecurring']);


Route::match(['GET', 'POST'], 'edit-recurring/{recurring_id}', ['uses' => 'RecurringController@updateAdminRecurring']);

Route::match(['GET', 'POST'], 'change-vendor-name', ['uses' => 'RecurringController@updatevendorid']);

Route::match(['GET', 'POST'], 'delete-recurring/{recurring_id}', ['uses' => 'RecurringController@deleteAdminRecurring']);

Route::match(['GET', 'POST'], 'change-vendor-order', ['uses' => 'OrderController@updatevendorid']);

Route::match(['GET', 'POST'], 'saving_under_reviewing_notes', ['uses' => 'OrderController@underreviewnotes']);


Route::match(['GET', 'POST'], 'load-service-on-job-type', ['uses' => 'AjaxController@loadServiceOnJobType']);

Route::match(['GET', 'POST'], 'save-bid-price', ['uses' => 'AjaxController@saveBidPrice']);

//Route::match(array('GET', 'POST'), 'save-bid-price-vendor', array('uses' => 'AjaxController@saveBidPriceVendor'));

Route::get('save-bid-price-vendor', ['uses' => 'AjaxController@saveBidPriceVendor']);

Route::match(['GET', 'POST'], 'do-request/', ['uses' => 'AdminController@doRequest']);

Route::match(['GET', 'POST'], 'change-price-vendor', ['uses' => 'VendorController@changeVendorPrice']);

Route::match(['GET', 'POST'], 'change-due-date', ['uses' => 'MaintenanceRequestController@changeDueDate']);

Route::match(['GET', 'POST'], 'vendor-notes', ['uses' => 'MaintenanceRequestController@vendorNotesBid']);
