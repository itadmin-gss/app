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

// Home Routs

//   Route::get('pdf', function(){
   
//       print_r(Config::get('app.bid_images_before'));
// });	
 Route::get('work-order-info', function () {
      
      echo "<pre>";
      echo "Host:"." ". DB::connection()->getConfig("host");
    echo "<br>";
      echo "Database Name:"." ".DB::connection()->getConfig("database");
    echo "<br>";
      echo "Username:"." ".DB::connection()->getConfig("username");
    echo "<br>";
      echo "Password:"." ".DB::connection()->getConfig("password");
    echo "<br>";
 });
 Route::get('refresh', array('uses' => 'BaseController@notify'));

 Route::get('/', array('uses' => 'UserController@showLogin'));

 Route::get('active-customer/{id}', array('uses' => 'CustomerController@activeCustomer'));

 Route::get('active-user/{id}', array('uses' => 'UserController@activeUser'));

 Route::match(array('GET', 'POST'), 'get-asset-map', array('uses' => 'AssetController@getAssetMap'));

// Swap Routes
   
 Route::get('swap-db', array('uses' => 'AdminController@swapDB'));

 Route::post('swap-db-admin', array('uses' => 'AdminController@swapDBNow'));
  

// Common Routes
// Route::get('hashMake', function() {

//   //$password = Hash::make('johnsanchez$12');
//   print_r($password);

// }); 
   Route::post('show-additional-service-popup', array('uses' => 'AjaxController@getServicePopup'));
 Route::match(array('GET', 'POST'), 'delete-selected-asset/{id}', array('uses' => 'AssetController@deleteSelectedAsset'));

 Route::get('get-quick-workorder/{id}', array('uses' => 'AjaxController@loadWorkorder'));
 Route::get('delete-customer-asset/{id}', array('uses' => 'AssetController@deleteAsset'));
 Route::get('logout', function () {

    Auth::logout();

    Session::put('clientType', "");

    return View::make('home');
 });

  Route::match(array('GET', 'POST'), 'update-additional-service/{id}', array('uses' => 'ServiceController@updateAdditionalItem'));
 Route::match(array('GET', 'POST'), 'additional-service-title-change', array('uses' => 'ServiceController@checkAddedTitle'));
   Route::match(array('GET', 'POST'), 'add-additional-service', array('uses' => 'ServiceController@addAdditionalItem'));
   
   Route::match(array('GET', 'POST'), 'add-requested-service/{id}', array('uses' => 'ServiceController@addRequestedService'));

#########################################################  Admin Routes ####################################################3

 Route::group(array('before' => 'auth|adminCheck|adminRightsCheck'), function () {

    // Login Secure Admin Routes
    


   

    Route::get('login-as/{user_id}', array('uses' => 'UserController@doLoginAsUser'));

    Route::get('admin', array('uses' => 'AdminController@index'));

    Route::get('access-rights', array('uses' => 'AccessRightController@index'));

    Route::match(array('GET', 'POST'), 'add-asset', array('uses' => 'AssetController@addAdminAsset'));

    Route::match(array('GET', 'POST'), 'add-asset/{id}', array('uses' => 'AssetController@addAdminAsset'));

    Route::match(array('GET', 'POST'), 'approved-pagination', array('uses' => 'AdminController@ajaxDashoboardGridOrdersPagination'));

    

    Route::match(array('GET', 'POST'), 'add-service', array('uses' => 'ServiceController@addAdminService'));

    Route::match(array('GET', 'POST'), 'list-assets', array('uses' => 'AssetController@listAdminAssets'));

    Route::match(array('GET', 'POST'), 'list-assets-summary', array('uses' => 'AssetController@listAdminAssetsSummary'));

    Route::match(array('GET', 'POST'), 'status-report', array('uses' => 'OrderController@statusReport'));

   

    Route::match(array('GET', 'POST'), 'property-report', array('uses' => 'AssetController@propertyReport'));

    Route::match(array('GET', 'POST'), 'recurring-report', array('uses' => 'AssetController@recurringReport'));

      Route::match(array('GET', 'POST'), 'reporting', array('uses' => 'AssetController@reporting'));

       Route::match(array('GET', 'POST'), 'whiteboard-reporting', array('uses' => 'AssetController@whiteboardReporting'));

  

    Route::match(array('GET', 'POST'), 'list-vendor-summary', array('uses' => 'AdminController@listVendorsSummary'));

    Route::match(array('GET', 'POST'), 'list-services', array('uses' => 'ServiceController@listAdminServices'));



        Route::match(array('GET', 'POST'), 'list-bid-services', array('uses' => 'AdminController@listBidServices'));

    Route::match(array('GET', 'POST'), 'add-special-prices', array('uses' => 'SpecialPriceController@addSpecialPrice'));

        Route::match(array('GET', 'POST'), 'vendor-add-special-prices', array('uses' => 'SpecialPriceController@vendorAddSpecialPrice'));

    Route::match(array('GET', 'POST'), 'list-special-prices', array('uses' => 'SpecialPriceController@listSpecialPrice'));

      Route::match(array('GET', 'POST'), 'vendor-list-special-prices', array('uses' => 'SpecialPriceController@vendorListSpecialPrice'));

    Route::match(array('GET', 'POST'), 'edit-special-price/{special_price_id}', array('uses' => 'SpecialPriceController@editSpecialPrice'));

    Route::match(array('GET', 'POST'), 'edit-vendor-special-price/{special_price_id}', array('uses' => 'SpecialPriceController@editVendorSpecialPrice'));

    

    Route::match(array('GET', 'POST'), 'edit-service/{service_id}', array('uses' => 'ServiceController@updateAdminService'));

   

    Route::match(array('GET', 'POST'), 'edit-client-type/{clientID}', array('uses' => 'AdminController@editCustomerType'));

   

    Route::match(array('GET', 'POST'), 'edit-asset/{asset_id}', array('uses' => 'AssetController@editAdminAsset'));

    // Admin Routes

    Route::match(array('GET', 'POST'), 'list-user', array('uses' => 'AdminController@listUser'));
    Route::match(array('GET', 'POST'), 'approved-grid-export', array('uses' => 'AjaxController@approvedGridExport'));


    // routes set to city screen by shm

    Route::match(array('GET', 'POST'), 'list-city', array('uses' => 'AdminController@listCity'));

    Route::get('add-city', array('uses' => 'AdminController@addCity'));

    Route::post('addCity', array('uses' => 'AdminController@addNewCity'));

    Route::get('edit-city/{id}', array('uses' => 'AdminController@editCity'));

    Route::post('save-city', array('uses' => 'AdminController@saveCity'));



    Route::match(array('GET', 'POST'), 'list-service-categories', array('uses' => 'AdminController@listServiceCategories'));

    Route::match(array('GET', 'POST'), 'list-job-type', array('uses' => 'AdminController@listJobType'));



    Route::match(array('GET', 'POST'), 'list-customer-type', array('uses' => 'AdminController@listCustomerType'));

          

    Route::match(array('GET', 'POST'), 'add-access-level', array('uses' => 'AdminController@addAccessLevel'));

    Route::get('add-user', array('uses' => 'AdminController@addUser'));

    Route::get('add-service-category', array('uses' => 'AdminController@addServiceCategory'));

        Route::get('add-job-type', array('uses' => 'AdminController@addJobType'));

           Route::get('add-customer-type', array('uses' => 'AdminController@addCustomerType'));

    Route::post('addUser', array('uses' => 'AdminController@addNewUser'));

        Route::post('addServiceCat', array('uses' => 'AdminController@addNewServiceCategory'));

                Route::post('addJobType', array('uses' => 'AdminController@addNewJobType'));

                  Route::post('addCustomerType', array('uses' => 'AdminController@addNewCustomerType'));

                   Route::post('editCustomerType', array('uses' => 'AdminController@editNewCustomerType'));

                

    Route::match(array('GET', 'POST'), 'edit-user/{user_id}', array('uses' => 'AdminController@editUser'));

    Route::get('list-access-level', array('uses' => 'AdminController@listAccessLevel'));

    Route::get('show-add-vendor', array('uses' => 'AdminController@addVendor'));

    Route::post('process-add-vendor', array('uses' => 'AdminController@processAddVendor'));

    Route::match(array('GET', 'POST'), 'list-maintenance-request', array('uses' => 'AdminController@listMaintenanceRequest'));

        Route::match(array('GET', 'POST'), 'list-bidding-request', array('uses' => 'AdminController@listBidRequest'));

    Route::match(array('GET', 'POST'), 'list-assigned-maintenance-request', array('uses' => 'AdminController@listAssignedMaintenanceRequest'));

    Route::match(array('GET', 'POST'), 'view-maintenance-request/{id}', array('uses' => 'AdminController@viewMaintenanceRequest'));

    Route::match(array('GET', 'POST'), 'cancel-maintenance-request/{id}', array('uses' => 'AdminController@cancelMaintenanceRequest'));

    Route::match(array('GET', 'POST'), 'delete-maintenance-request/{id}', array('uses' => 'AdminController@deleteMaintenanceRequest'));

    Route::match(array('GET', 'POST'), 'cancel-bidding-request/{id}', array('uses' => 'AdminController@cancelBiddingRequest'));





    Route::match(array('GET', 'POST'), 'view-bidding-request/{id}/{service_id}', array('uses' => 'AdminController@viewBiddingRequest'));

    Route::match(array('GET', 'POST'), 'view-bidding-request/{id}', array('uses' => 'AdminController@viewBiddingRequest'));


    Route::match(array('GET', 'POST'), 'add-new-customer', array('uses' => 'CustomerController@createCustomerAdmin'));

    Route::get('list-customer', array('uses' => 'CustomerController@listCustomerAdmin'));

    Route::match(array('GET', 'POST'), 'edit-customer/{id}', array('uses' => 'CustomerController@editCustomerAdmin'));
    //Exported Route

    Route::get('list-exported-workorder', array('uses' => 'AdminController@listExportedWorkOrder'));

    //Work Order Routes

    Route::get('list-work-order-admin', array('uses' => 'AdminController@listWorkOrder'));

    Route::get('list-work-order-admin1', array('uses' => 'AdminController@viewonly'));

    Route::get('list-work-order-admin-grid', array('uses' => 'AdminController@listWorkOrderGrid'));



    Route::get('admin-list-completed-order', array('uses' => 'AdminController@listCompletedOrders'));

    Route::get('admin-list-invoice', array('uses' => 'InvoiceController@listAdminInvoices'));

      

    Route::get('admin-list-invoice/{id}', array('uses' => 'InvoiceController@listAdminInvoices'));

     



  //    Route::get('list-completed-order-admin', array('uses' => 'AdminController@listCompletedOrders'));

    //WorkOrder Routes







    Route::get('edit-profile-admin/{id}', array('uses' => 'AdminController@editProfileAdmin'));

    Route::get('edit-job-type/{id}', array('uses' => 'AdminController@editTypeJob'));



    Route::post('save-profile-admin/{id}', array('uses' => 'AdminController@saveUserProfile'));

    Route::match(array('GET', 'POST'), 'assign-service-request', array('uses' => 'MaintenanceRequestController@assignServiceRequest'));

    Route::match(array('GET', 'POST'), 'assign-service-bid', array('uses' => 'MaintenanceRequestController@assignServiceBid'));





    Route::match(array('GET', 'POST'), 'admin-notes', array('uses' => 'MaintenanceRequestController@adminNotes'));

    Route::match(array('GET', 'POST'), 'admin-notes-bid', array('uses' => 'MaintenanceRequestController@adminNotesBid'));

    Route::match(array('GET', 'POST'), 'admin-notes-osr', array('uses' => 'MaintenanceRequestController@adminNotesOsr'));

    Route::match(array('GET', 'POST'), 'customer-notes-osr', array('uses' => 'MaintenanceRequestController@customerNotesOsr'));



    Route::match(array('GET', 'POST'), 'public-notes', array('uses' => 'MaintenanceRequestController@publicNotes'));

    Route::match(array('GET', 'POST'), 'public-notes-bid', array('uses' => 'MaintenanceRequestController@publicNotesBid'));



    Route::match(array('GET', 'POST'), 'customer-notes-bid', array('uses' => 'MaintenanceRequestController@customerNotesBid'));



    Route::match(array('GET', 'POST'), 'edit-access-level/{role_id}', array('uses' => 'AccessLevelController@editAccessLevel'));

    Route::match(array('GET', 'POST'), 'delete-user/{user_id}', array('uses' => 'AdminController@deleteUser'));

    Route::get('admin-add-bid-request', array('uses' => 'AdminController@addBidRequest'));

    Route::get('create-bid/{id}', array('uses' => 'AdminController@createBidRequest'));



    Route::get('admin-bid-requests', array('uses' => 'AdminController@listBidRequests'));



    Route::get('admin-bid-requests/{id}', array('uses' => 'AdminController@listBidRequests'));

    Route::get('admin-approved-bid-requests', array('uses' => 'AdminController@listApprovedBidRequests'));



    Route::get('admin-declined-bid-requests', array('uses' => 'AdminController@listDeclinedBidRequests'));

    Route::post('admin-create-bid-service-request', array('uses' => 'AdminController@createBidServiceRequest'));

    Route::match(array('GET', 'POST'), 'view-admin-bid-request/{id}', array('uses' => 'AdminController@viewBidRequest'));

 

    Route::match(array('GET', 'POST'), 'admin-accept-bid-request/', array('uses' => 'AdminController@acceptBidRequest'));

    Route::match(array('GET', 'POST'), 'admin-decline-bid-request/', array('uses' => 'AdminController@DeclineBidRequest'));

 











    Route::get('admin-add-new-service-request', array('uses' => 'MaintenanceRequestController@viewAdminRequestForm'));

    Route::get('admin-edit-service-request/{id}', array('uses' => 'MaintenanceRequestController@editAdminRequestForm'));

    Route::get('quantity-of-approved-orders', array('uses' => 'AdminController@quantityOfApprovedOrders'));
 });





/* Maintenance Request */

 Route::post('create-service-request', array('uses' => 'MaintenanceRequestController@createServiceRequest'))->before('auth');
 Route::post('create-additional-service-request', array('uses' => 'MaintenanceRequestController@createAdditionalServiceRequest'))->before('auth');

 Route::post('edit-service-request', array('uses' => 'MaintenanceRequestController@editServiceRequest'));

 Route::post('create-bid-service-request', array('uses' => 'VendorController@createBidServiceRequest'));







 Route::get('list-customer-requested-services', array('uses' => 'MaintenanceRequestController@listServiceRequest'));

 Route::get('list-customer-requested-bids', array('uses' => 'MaintenanceRequestController@listServiceRequestBid'));

 Route::get('list-vendors', array('uses' => 'AdminController@listVendors'));
 Route::get('list-vendorss', array('uses' => 'AdminController@listVendorsDynamically'));

 Route::get('view-customer-request-service/{id}', array('uses' => 'MaintenanceRequestController@viewServiceRequest'));

 Route::get('view-customer-request-bid/{id}', array('uses' => 'MaintenanceRequestController@viewServiceBid'));

/* Asset Routes */

 Route::get('add-customer-asset', array('uses' => 'AssetController@showAddAsset'));

 Route::post('create-customer-asset', array('uses' => 'AssetController@createAsset'));

 Route::get('add-new-customer-asset', array('uses' => 'AssetController@showAddAssetNew'));

 Route::get('add-new-customer-asset/{id}', array('uses' => 'AssetController@showAddAssetNew'));

 Route::post('edit-customer-asset/{id}', array('uses' => 'AssetController@editAsset'));

 Route::get('edit-customer-asset/{id}', array('uses' => 'AssetController@editAsset'));





############################################# Admin Routes for ajax call ######################################

 Route::post('update-access-level', array('uses' => 'AccessLevelController@updateUserAccessLevel'));

 Route::post('update-access-rights', array('uses' => 'AccessRightController@updateAccessRights'));

 Route::post('get-role-details', array('uses' => 'AccessRightController@getRoleDetails'));

 Route::post('update-user-status/{user_id}', array('uses' => 'AdminController@updateUserStatus'));



 Route::match(array('GET', 'POST'), 'delete-vendor/{vendor_id}', array('uses' => 'AdminController@deleteVendor'));

 Route::match(array('GET', 'POST'), 'delete-access-level/{role_id}', array('uses' => 'AccessLevelController@deleteAccessLevel'));





 Route::match(array('GET', 'POST'), 'asset-view/{id}', array('uses' => 'AdminController@assetView'));

 Route::match(array('GET', 'POST'), 'show-maintenance-services/{id}', array('uses' => 'AdminController@showMaintenanceServices'));



 Route::match(array('GET', 'POST'), 'show-bid-services/{id}/{service_id}', array('uses' => 'AdminController@showBidServices'));
 Route::match(array('GET', 'POST'), 'show-bid-services/{id}', array('uses' => 'AdminController@showBidServices'));

 Route::match(array('GET', 'POST'), 'show-bid-services/{id}/{flagworkorder}/{customer_bid_price}/{vendor_bid_price}/{requestedServiceBidId}/{due_date}', array('uses' => 'AdminController@showBidServices'));



 Route::group(array('before' => 'auth|customerCheck'), function () {


    Route::get('customer', array('uses' => 'CustomerController@index'));

    Route::get('view-customer-assets', array('uses' => 'AssetController@viewAssetsList'));

    Route::post('create-customer-new-asset', array('uses' => 'AssetController@createAssetNew'));

    Route::get('customer-profile-complete', array('uses' => 'CustomerController@showCompleteProfile'));

    Route::post('customer-profile-add', array('uses' => 'CustomerController@completeProfile'));

    Route::get('view-assets-list', array('uses' => 'AssetController@viewAssetsList'));

    
    
    Route::get('add-new-service-request', array('uses' => 'MaintenanceRequestController@viewRequestForm'));

     Route::get('customer-list-work-orders', array('uses' => 'CustomerController@listWorkOrder'));

     Route::get('customer-list-completed-work-orders', array('uses'=>'CustomerController@listCompletedWorkOrder'));



     Route::get('customer-approval-completion-request', array('uses' => 'CustomerController@listApprovalCompletion'));



     Route::get('customer-process-work-order', array('uses' => 'CustomerController@listProcessWorkOrder'));

     Route::get('customer-bid-requests', array('uses' => 'CustomerController@listBidRequests'));

    Route::get('customer-list-invoice', array('uses' => 'InvoiceController@listCustomerInvoices'));

    

    Route::get('customer-bid-requests/{id}', array('uses' => 'CustomerController@listBidRequests'));

    Route::get('customer-approved-bid-requests', array('uses' => 'CustomerController@listApprovedBidRequests'));

    Route::get('customer-declined-bid-requests', array('uses' => 'CustomerController@listDeclinedBidRequests'));

    Route::match(array('GET', 'POST'), 'view-customer-bid-request/{id}', array('uses' => 'CustomerController@viewBidRequest'));

    Route::match(array('GET', 'POST'), 'accept-bid-request/', array('uses' => 'CustomerController@acceptBidRequest'));

    Route::match(array('GET', 'POST'), 'decline-bid-request/', array('uses' => 'CustomerController@DeclineBidRequest'));

    Route::get('customer-client-type/{id}', array('uses' => 'CustomerController@setClientType'));

    Route::get('destroy-client-type', array('uses' => 'CustomerController@unSetClientType'));





      Route::get('customer-recurring', array('uses' => 'RecurringController@showCustomerrecurring'));
 });









//Ajax Customer Routes



     Route::match(array('GET', 'POST'), 'admin-get-customer-company', array('uses' => 'AdminController@customerCompany'));

 

 Route::post('get-cities-by-state-id', array('uses' => 'AjaxController@getCitiesByState'));

 Route::post('ajax-get-asset-by-asset-id', array('uses' => 'AjaxController@getAssetById'));

 Route::post('ajax-service-information-popup', array('uses' => 'AjaxController@getServicePopup'));

 Route::post('ajax-vendor-service-information-popup', array('uses' => 'AjaxController@getVendorServicePopup'));

 Route::post('ajax-service-information-list', array('uses' => 'AjaxController@getServiceList'));

 Route::post('ajax-service-information-list-review-order', array('uses' => 'AjaxController@getServiceListOrderReivew'));

 Route::post('remove-file-from-directory', array('uses' => 'AjaxController@removeFile'));

 Route::post('ajax-approve-bid-request-status-changed', array('uses' => 'MaintenanceRequestController@approveBidRequestStatusChanged'));

 Route::post('ajax-approve-bid-request', array('uses' => 'MaintenanceRequestController@approveBidRequest'));

 Route::post('ajax-decline-bid-request', array('uses' => 'MaintenanceRequestController@declineBidRequest'));





//Ajax Route end

################################## Vendor Routes #############################################

 Route::group(array('before' => 'auth|vendorCheck'), function () {

    Route::get('vendors', array('uses' => 'VendorController@index'));

    Route::get('vendor-profile-complete', array('uses' => 'VendorController@showCompleteProfile'));

    Route::post('vendor-profile-add', array('uses' => 'VendorController@completeProfile'));

    Route::get('vendor-profile-service', array('uses' => 'ServiceController@index'));

    Route::post('vendor-service-complete', array('uses' => 'ServiceController@assignVendorService'));

    Route::get('vendor-assigned-requests', array('uses' => 'VendorController@listAssignedRequests'));

        Route::get('vendor-assigned-bids', array('uses' => 'VendorController@listAssignedBids'));

    Route::get('vendor-list-orders', array('uses' => 'VendorController@listOrders'));

    Route::get('vendor-list-completed-orders', array('uses' => 'VendorController@listCompletedOrders'));

    Route::match(array('GET', 'POST'), 'view-vendor-maintenance-request/{id}', array('uses' => 'VendorController@viewMaintenanceRequest'));

        Route::match(array('GET', 'POST'), 'view-vendor-bidding-request/{id}', array('uses' => 'VendorController@viewBidRequest'));

    Route::match(array('GET', 'POST'), 'decline-request/', array('uses' => 'VendorController@declineRequest'));

    Route::match(array('GET', 'POST'), 'accept-request/', array('uses' => 'VendorController@acceptRequest'));

        Route::match(array('GET', 'POST'), 'accept-single-request/', array('uses' => 'VendorController@acceptSingleRequest'));

    Route::get('add-osr-request', array('uses' => 'VendorController@addOsrRequest'));
     //Route::get('add-osr-request', array('uses' => 'VendorController@addOsrRequest'));

    Route::match(array('GET', 'POST'), 'add-osr-request/{order_id}', array('uses' => 'VendorController@addBidRequest'));

    Route::get('vendor-bid-requests', array('uses' => 'VendorController@listBidRequests'));
    Route::get('vendor-osr-requests', array('uses' => 'VendorController@listBidRequests'));

     Route::match(array('GET', 'POST'), 'view-vendor-bid-request/{id}', array('uses' => 'VendorController@viewOSR'));

    Route::get('vendor-bid-requests/{id}', array('uses' => 'VendorController@listBidRequests'));

    Route::get('vendor-list-invoice', array('uses' => 'InvoiceController@listVendorInvoices'));

    

    Route::get('vendor-approved-bid-requests', array('uses' => 'VendorController@listApprovedBidRequests'));

    Route::get('vendor-declined-bid-requests', array('uses' => 'VendorController@listDeclinedBidRequests'));

    Route::get('vendor-recurring', array('uses' => 'RecurringController@showVendorrecurring'));
 });







################################## Other Routes #############################################

 Route::match(array('GET', 'POST'), 'change-price', array('uses' => 'InvoiceController@changePrice'));

 Route::match(array('GET', 'POST'), 'completion-date', array('uses' => 'OrderController@completionDate'));



 Route::post('delete-order-before-image', array('uses' => 'OrderController@deleteBeforeImages'));

 Route::post('delete-order-during-image', array('uses' => 'OrderController@deleteDuringImages'));

 Route::post('delete-before-image-id', array('uses' => 'OrderController@deleteImageById'));

 Route::post('delete-before-additional-image-id', array('uses' => 'OrderController@deleteImageByAdditionalItemId'));

 Route::post('save-vendor-note', array('uses' => 'OrderController@saveVendorNote'));

 Route::post('save-additional-vendor-note', array('uses' => 'OrderController@saveAdditionalVendorNote'));

 Route::post('save-billing-note', array('uses' => 'OrderController@saveBillingNote'));

 Route::post('save-admin-note', array('uses' => 'OrderController@saveAdminNote'));

 Route::post('save-admin-qty', array('uses' => 'OrderController@saveAdminQuantity'));

 Route::post('save-customer-note', array('uses' => 'OrderController@saveCustomerNote'));

 Route::post('delete-order-after-image', array('uses' => 'OrderController@deleteAfterImages'));

 Route::post('delete-order-all-before-image', array('uses' => 'OrderController@deleteOrderAllBeforeImages'));

 Route::post('add-before-images', array('uses' => 'OrderController@addBeforeImages'));

 Route::post('add-additional-before-images', array('uses' => 'OrderController@addAdditionalBeforeImages'));

 Route::post('add-before-images-bids', array('uses' => 'VendorController@addBeforeImages'));

 Route::post('add-after-images', array('uses' => 'OrderController@addAfterImages'));

 Route::post('add-additional-after-images', array('uses' => 'OrderController@addAdditionalAfterImages'));

 Route::post('add-during-images', array('uses' => 'OrderController@addDuringImages'));

 Route::post('add-additional-during-images', array('uses' => 'OrderController@addAdditionalDuringImages'));



 Route::post('add-photo-tagging', array('uses' => 'OrderController@photoTag'));

 Route::post('delete-photo-tagging', array('uses' => 'OrderController@deleteTag'));




 Route::post('Export-Images/{images}', array('uses' => 'OrderController@ExportImages'));
 Route::post('download-seleted-images', array('uses' => 'OrderController@downloadSeletedImages'));
 Route::post('download-seleted-additional-images', array('uses' => 'OrderController@downloadSeletedAdditionalItemImages'));

 Route::post('display-export-images', array('uses' => 'OrderController@displayExportImages'));

 Route::post('display-additional-export-images', array('uses' => 'OrderController@displayAdditionalExportImages'));

 Route::post('display-order-additional-items-images', array('uses' => 'OrderController@displayAdditonalItemImages'));

 Route::post('display-order-images', array('uses' => 'OrderController@displayImages'));

 Route::post('display-bid-images', array('uses'   => 'VendorController@displayImages'));

 Route::post('display-order-images-view', array('uses' => 'OrderController@displayViewImages'));

 Route::match(array('GET', 'POST'), 'edit-order/{order_id}', array('uses' => 'OrderController@editOrder'));

 Route::get('view-order/{order_id}', array('uses' => 'OrderController@viewOrder'));



 Route::get('order-images/{order_id}', array('uses' => 'OrderController@orderImage'));

 Route::match(array('GET', 'POST'), 'change-status', array('uses' => 'OrderController@changeStatus'));

 Route::match(array('GET', 'POST'), 'close-property-status', array('uses' => 'OrderController@closePropertyStatus'));

 Route::match(array('GET', 'POST'), 'change-notification-status', array('uses' => 'NotificationController@ChangeNotificationStatus'));

 Route::match(array('GET', 'POST'), 'clear-notification', array('uses' => 'NotificationController@ChangeAllNotificationStatus'));



 Route::match(array('GET', 'POST'), 'edit-invoice/{order_id}', array('uses' => 'InvoiceController@editInvoice'));



 Route::post('generate-asset-number', array('uses' => 'AjaxController@generateAssetNumber'));

 Route::post('user-create', array('uses' => 'UserController@createUser'));

 Route::get('register-page-redirect', array('uses' => 'UserController@whereRedirect'));

//Ajax Customer Routes

 Route::post('get-cities-by-state-id', array('uses' => 'AjaxController@getCitiesByState'));

 Route::post('ajax-get-asset-by-asset-id', array('uses' => 'AjaxController@getAssetById'));

 Route::post('ajax-service-information-popup', array('uses' => 'AjaxController@getServicePopup'));

 Route::post('ajax-service-information-list', array('uses' => 'AjaxController@getServiceList'));

 Route::post('remove-file-from-directory', array('uses' => 'AjaxController@removeFile'));

//Ajax Route end

 Route::post('login', array('uses' => 'UserController@doLogin'));

 Route::get('edit-profile', array('uses' => 'UserController@editProfile'));

 Route::post('save-profile', array('uses' => 'UserController@saveProfile'));

 Route::post('save-job-type', array('uses' => 'AdminController@saveJobType'));

 Route::get('delete-job-type/{id}', array('uses' => 'AdminController@deleteJobType'));



 Route::get('thankyou/{user_id}', array('uses' => 'UserController@showThankYou'));

 Route::match(array('GET', 'POST'), 'update-status', array('uses' => 'CommonController@updateStatus'));

 Route::match(array('GET', 'POST'), 'delete-record', array('uses' => 'CommonController@deleteRecord'));

 Route::get('testing-link', array('uses' => 'AjaxController@testingLink'));







 Route::group(array('before' => 'loginCheck'), function () {

    Route::get('user-register', array('uses' => 'UserController@showRegistration'));
 });







 Route::match(array('POST', 'GET'), 'forgot-password', array('uses' => 'RemindersController@getRemind'));

 Route::controller('password', 'RemindersController');



 Route::get('list-work-notification-url', array('uses' => 'NotificationController@listNotifications'));



 Route::post('ajax-dashoboard-grid-requests', array('uses' => 'AdminController@ajaxDashoboardGridRequests'));

 Route::post('ajax-dashoboard-grid-orders', array('uses' => 'AdminController@ajaxDashoboardGridOrders'));



 Route::post('ajax-delete-service-request', array('uses' => 'AdminController@ajaxDeleteServiceRequest'));



 Route::match(array('GET', 'POST'), 'qb', array('uses' => 'QuickBookController@index'));

 Route::get('cron-job', array('uses' => 'CronJobController@index'));

 Route::get('recurring', array('uses' => 'RecurringController@showrecurring'));



 Route::match(array('GET', 'POST'), 'edit-recurring/{recurring_id}', array('uses' => 'RecurringController@updateAdminRecurring'));

 Route::match(array('GET', 'POST'), 'change-vendor-name', array('uses' => 'RecurringController@updatevendorid'));

 Route::match(array('GET', 'POST'), 'delete-recurring/{recurring_id}', array('uses' => 'RecurringController@deleteAdminRecurring'));

 Route::match(array('GET', 'POST'), 'change-vendor-order', array('uses' => 'OrderController@updatevendorid'));

 Route::match(array('GET', 'POST'), 'saving_under_reviewing_notes', array('uses' => 'OrderController@underreviewnotes'));



 Route::match(array('GET', 'POST'), 'load-service-on-job-type', array('uses' => 'AjaxController@loadServiceOnJobType'));

 Route::match(array('GET', 'POST'), 'save-bid-price', array('uses' => 'AjaxController@saveBidPrice'));

//Route::match(array('GET', 'POST'), 'save-bid-price-vendor', array('uses' => 'AjaxController@saveBidPriceVendor'));

 Route::get('save-bid-price-vendor', array('uses' => 'AjaxController@saveBidPriceVendor'));

 Route::match(array('GET', 'POST'), 'do-request/', array('uses' => 'AdminController@doRequest'));

 Route::match(array('GET', 'POST'), 'change-price-vendor', array('uses' => 'VendorController@changeVendorPrice'));

 Route::match(array('GET', 'POST'), 'change-due-date', array('uses' => 'MaintenanceRequestController@changeDueDate'));

 Route::match(array('GET', 'POST'), 'vendor-notes', array('uses' => 'MaintenanceRequestController@vendorNotesBid'));
