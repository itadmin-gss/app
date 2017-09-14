<?php

/*

Class is Created for Email Notifications

This class will be based on singleton pattern so each property has is own value

*/

class EmailNotification extends BaseTenantModel
{



    //  Email when user will click on register

    public static $user_email_template="Thank you for Registering! You will receive an email confirmation once your registration is approved. 

	If you need assistance in the meantime, please call us directly at 855-787-4672.";



    //  When admin will approve the customer or vendor



    public static $user_email_approved_template="Your registration has been approved!";



    //  When Customer or Vendor will complete the profile

    public static $user_email_completeness_template="Congratulations, your profile is now complete! You may begin using the system to request services, track your properties, and much more!";

    public static $underReviewNotficationForVendor="Admin has changed the status to under review please see the work order";
}
