<?php

namespace App\Helpers;

################################### Helper Function for Flash Messages #####################

class FlashMessage
{

    public static function DisplayAlert($message, $type)
    {
        return "<div class='alert alert-" . $type . "'>" . $message . "</div>";
    }

// End the DisplayAlert Function

    public static function messages($val)
    {
        $val = explode('.', $val);

        $array = [
            'admin' => [
                'access_level_success' => 'Success! Access Level Created!',
                'access_level_error' => 'Error! Could Not Created!',
                'access_level_deleted' => 'Success! Access Level has been deleted.',
                'user_created' => 'Success! User has been created successfully.',
                'user_updated' => 'Success! User has been updated successfully.',
                'user_deleted' => 'Success! User has been deleted successfully.',
                'user_role_updated' => 'Success! User role has been updated successfully.',
                'user_role_update_error' => 'Error! User role Could not be updated.',
                'vendor_add_success' => 'Successfully ! Vendor Created!',
                'vendor_add_error' => 'Error! Vendor Could Not Created!',
                'service_price_success' => 'Successfully ! Assigned Price!',
                'service_price_error' => 'Error! Price Not Assigned!',
                'special_price_success' => 'Successfully ! Added Special Price!',
                'special_price_already_error' => 'Special Price Already Assigned to Selected Customer Against Selected Service',
                'special_price_already_error_vendor' => 'Special Price Already Assigned to Selected Vendor Against Selected Service',
                'add_new_customer' => 'New customer has been created successfully.',
                'update_customer' => 'Customer is updated successfully.',
                'request_service_add' => 'Your request has been assigned successfully!',
                'city_created' => 'Success! City has been inserted successfully.',
                'city_edit_success' => 'Update City Successfully',
            ],
            'admin_asset' => [
                'asset_updated' => 'Success! Asset has been updated successfully.'
            ],
            'admin_service' => [
                'service_added' => 'Success! Service has been added successfully.',
                'service_updated' => 'Success! Service has been updated successfully.',
                'service_error' => 'Error! Service could not be updated.',
            ],
            'admin_access' => [
            'access_denied' => 'Access Denied! You dont have permissions.',
            ],
            'customer' => [
                'customer_success' => 'Customer Created',
                'customer_error' => 'Could Not Create Cutomer',
                'request_service_add' => 'Thank you for submitting your service request! The status will be updated within 24 hours. You may check the status of your request anytime by logging back into your dashboard.',
                 'request_service_bid_request' => 'Thank you for submitting your bid request!',
                'request_bid_add' => 'Thank you for submitting your OSR! The status will be updated within 24 hours.',
                'add_new_asset_success' => 'New Asset has been added successfully.'
            ],
            'vendor' => [
                'profile_edit_success' => 'Update Profile Successfully',
                'profile_edit_error' => 'Could Not Update Profile',
            ],
            'user' => [
                'user_login_error' => 'Invalid Username or Password',
            ],
        ];

        return $array[$val[0]][$val[1]];
    }
}

// End FlashMessage Class
