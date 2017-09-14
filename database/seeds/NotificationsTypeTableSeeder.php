<?php

class NotificationsTypeTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('notification_types')->delete();
        DB::table('notification_types')->insert(['id' => 1, 'activity_type' => 'New Customer Registered', 'status' => 1, 'email_tamplate' => "emails.notifications.new_registration"]);
        DB::table('notification_types')->insert(['id' => 2, 'activity_type' => 'New Vendor Registered', 'status' => 1, 'email_tamplate' => "emails.notifications.new_registration"]);
        DB::table('notification_types')->insert(['id' => 3, 'activity_type' => 'New User Registered', 'status' => 1, 'email_tamplate' => "emails.notifications.new_registration"]);
    }
}
