<?php
define("BASEPATH", $_SERVER["DOCUMENT_ROOT"]);
include_once(BASEPATH . "/src/common/config.php");
include_once(BASEPATH . "/src/common/utils.php");
include_once(BASEPATH . "/src/common/database.php");

// Always revalidate app responses so browser refresh gets latest server output.
if (!headers_sent()) {
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    header('Expires: 0');
}

if (isset($_SESSION["isAuthenticated"]) && $_SESSION["isAuthenticated"]){
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0){
        $data = $result->fetch_assoc();
        $hasActivePlan = !empty($data["plan_id"]) && !empty($data["plan_end"]);
        $isPlanValid = $hasActivePlan && (new DateTime() < new DateTime($data["plan_end"]));
        $_SESSION["plan_id"] = $data["plan_id"];
        $_SESSION["planable"] = $isPlanValid;
    }
}

$request = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$routes = [
    'stripe/webhook' => 'controllers/stripeWebHook.php',
    //Landing
    '' => 'Landing/index.php',
    'new' => 'Landing/new_page.php',
    // 'api/removeperson' => 'controllers/removal_process/removeperson.php',
    // 'api/tasklist' => 'controllers/removal_process/tasklist.php',
    // 'api/init_stripe' => 'controllers/init_stripe.php',
    // 'api/update_price_manual' => 'controllers/updatePrice.php',
    'api/delete_account' => 'controllers/delete_account.php',
    // 'api/removal' => 'controllers/removal_result.php',
    // 'api/privacy' => 'controllers/privacy.php',
    // 'api/privacypros' => 'controllers/privacypros.php',
    'inboundProcess'=> 'controllers/inboundProcess.php',
    'api/specialinfo' => 'controllers/specialinfo.php',
    'api/image' => 'controllers/image.php',
    
    //Signup
    'signup' => 'Signup/index.php',
    'signupinfo' => 'Signup/info.php',
    'freescaning' => 'Signup/scaning.php',
    'result' => 'Signup/result.php',
    
    'freeScan'=> 'controllers/freeScan.php',
    'signupProcess'=> 'controllers/signupProcess.php',
    'new_signup' => 'NewSignup/index.php',
    'new_signup_process' => 'controllers/new_signup_process.php',
    'new_signin' => 'NewSignin/index.php',
    'new_signin_code' => 'NewSigninCode/index.php',
    'new_signin_password' => 'controllers/new_signin_password.php',
    'new_login_code' => 'controllers/new_login_code.php',
    'new_forgot_password_code' => 'controllers/new_forgot_password_code.php',
    'new_reset_password' => 'NewResetPassword/index.php',
    'new_reset_password_save' => 'controllers/new_reset_password_save.php',
    'success'=> 'controllers/success.php',
    'verify' => 'Emailverify/index.php',

    //Login & logout
    'login' => 'Login/index.php',
    'loginProcess' => 'Login/process.php',
    'logout' => 'controllers/logout.php',

    //information type
    'specialinfo' => 'Specialinfo/index.php',
    
    'restoration' => 'Restoration/index.php',
    'insurance' => 'Insurance/index.php',
    //Pricing
    'pricing' => 'Pricing/index.php',
    'payment'=> 'Payment/index.php',
    'paymentverify'=> 'Payment/verify.php',

    //Sites we cover
    'sites-we-cover' => 'Sitecover/index.php',

    //Policy
    'policy' => 'Policy/index.php',

    //Personalized service
    'personalized-service' => 'Personalized/index.php',
    
    //Family
    'family' => 'Family/index.php',
    //Blog
    'blog' => 'Blog/index.php',
    'blog/detail'=>'Blog/detail/index.php',

    //Business
    'business'=>'Business/index.php',
    'business/link'=>'Business/link.php',
    'business/link/verify'=>'Business/linkverify.php',
    'speaksales'=>'Business/speaksales/index.php',
    'speaksales/verify'=>'Business/speaksales/verify.php',
    'business/quote/signup'=>'Business/quote/signup.php',
    'business/quote/signupinfo'=>'Business/quote/signupinfo.php',
    'business/quote/login'=>'Business/quote/login.php',
    'business/quote/logininfo'=>'Business/quote/logininfo.php',
    'business/dashboard'=>'Business/dashboard/index.php',
    'business/verify'=>'Business/quote/signupVerify.php',
    'business/logout'=>'controllers/business/logout.php',

    
    'business/content/dashboard/main'=>'Business/dashboard/main/index.php',
    'business/content/dashboard/main/mindmap'=>'Business/dashboard/main/mindmap.php',
    'business/content/dashboard/main/list'=>'Business/dashboard/main/list.php',
    
    'business/content/dashboard/support'=>'Business/dashboard/support/index.php',
    'business/content/dashboard/speaksales'=>'Business/dashboard/speaksales/index.php',
    
    'business/content/dashboard/settings/general'=>'Business/dashboard/settings/general/index.php',
    'business/content/dashboard/settings/account'=>'Business/dashboard/settings/account/index.php',
    'business/content/dashboard/settings/security'=>'Business/dashboard/settings/security/index.php',
    'business/content/dashboard/settings/team'=>'Business/dashboard/settings/team/index.php',
    'business/content/dashboard/settings/billing'=>'Business/dashboard/settings/billing/index.php',
    'business/content/dashboard/settings/integrations'=>'Business/dashboard/settings/integrations/index.php',
    'business/content/dashboard/settings/notifications'=>'Business/dashboard/settings/notifications/index.php',
    'business/content/dashboard/settings/privacy'=>'Business/dashboard/settings/privacy/index.php',
    'business/content/dashboard/settings/apikeys'=>'Business/dashboard/settings/apikeys/index.php',
    
    'business/signupprocess'=>'controllers/business/signup.php',
    'business/signinprocess'=>'controllers/business/signin.php',
    "business/account_info"=>"controllers/business/getinfo.php",
    "business/update_account_info"=>"controllers/business/updateinfo.php",
    'business/dashboard/main/getMindmap'=>'controllers/business/getMindmap.php',
    'business/dashboard/main/createMindmap'=>'controllers/business/createMindmap.php',
    'business/dashboard/main/editMindmapname'=>'controllers/business/editMindmapname.php',
    'business/dashboard/main/addMember'=>'controllers/business/addMember.php',
    'business/dashboard/main/changePositionMember'=>'controllers/business/changePositionMember.php',
    'business/dashboard/main/deleteMember'=>'controllers/business/deleteMember.php',
    'business/deleteAccount'=>'controllers/business/deleteAccount.php',
    'business/speaksalesProcess'=>'controllers/business/speaksales.php',
    // 'business/quote/login'=>'Business/quote/login.php',
    // 'business/quote/signupemail'=>'Business/quote/signupemail.php',

    //Dashboard
    'dashboard/content/personal'=> 'Dashboard/personal.php',
    'business/dashboard(?:/(\w+))?'=> 'Business/dashboard/index.php',
    'business/dashboard/settings(?:/(\w+))?'=> 'Business/dashboard/index.php',

    'dashboard/content/editinfo'=> 'Dashboard/EditInfo/index.php',
    'dashboard/content/concierge'=> 'Dashboard/concierge.php',
    'dashboard/content/custom'=> 'Dashboard/Custom/index.php',
    'dashboard/content/plans'=> 'Dashboard/plans/index.php',
    'dashboard/content/family'=> 'Dashboard/Family/index.php',
    'dashboard/content/account'=> 'Dashboard/EditInfo/index.php',
    'new_dashboard/content/account'=> 'NewDashboard/Account/index.php',
    'dashboard/content/work'=> 'Dashboard/Work/index.php',
    'dashboard/content'=> 'Dashboard/Main/index.php',

    'signup_info_save'=> 'controllers/dashboard/signup_info_save.php',
    //dashboard_main
    'dashboard/content/databrokers/primary' => 'Dashboard/Main/databrokers/databrokers_primary.php',
    'dashboard/content/databrokers/custom' => 'Dashboard/Main/databrokers/databrokers_custom.php',
    'dashboard/content/databrokers/face' => 'Dashboard/Main/databrokers/databrokers_face.php',
    'get_results' => 'controllers/dashboard/main/get_results.php',
    'toggle_manual_removal' => 'controllers/dashboard/main/toggle_manual_removal.php',
    'scan_api/upload' => 'controllers/dashboard/main/upload.php',
    'removal_api/upload' => 'controllers/dashboard/main/removal_upload.php',
    'googleScan_api/upload' => 'controllers/dashboard/main/uploadGoogleScan.php',
    //dashoboard_family
    'get_members' => 'controllers/dashboard/family/get_members.php',
    'invite_member'=>'controllers/dashboard/family/invite.php',
    'delete_member_info'=>'controllers/dashboard/family/delete_member_info.php',
    'check_status'=>'controllers/dashboard/family/check_status.php',
    'get_discount_price'=>'controllers/dashboard/family/get_discount_price.php',
    'invite_paymentverify'=>'Dashboard/Family/verify.php',
    'invite_payment_mark_complete'=>'controllers/dashboard/family/invite_payment_mark_complete.php',
    'invite_payment_begin_checkout'=>'controllers/dashboard/family/invite_payment_begin_checkout.php',
    'invite_payment_save_pending'=>'controllers/dashboard/family/invite_payment_save_pending.php',
    'invite_payment_finalize_pending'=>'controllers/dashboard/family/invite_payment_finalize_pending.php',
    'invite_payment_stripe_return'=>'Dashboard/Family/stripe_return.php',
    //dashboard_plans
    'plans'=> 'controllers/dashboard/plans/plans.php',
    //dashboard_custom
    'get_messages' => 'controllers/dashboard/custom/get_messages.php',
    'send_message' => 'controllers/dashboard/custom/send_message.php',
    'emergency_stop' => 'controllers/dashboard/custom/emergency_stop.php',
    //dashboard_edityourinfo
    'get_user_info'=>'controllers/dashboard/editinfo/get_user_info.php',
    'get_user_info_by_id'=>'controllers/dashboard/editinfo/get_user_info_by_id.php',
    'update_user_info'=>'controllers/dashboard/editinfo/update_user_info.php',
    'add_user_address'=>'controllers/dashboard/editinfo/add_user_address.php',
    'delete_user_address'=>'controllers/dashboard/editinfo/delete_user_address.php',

    'book-call'=> 'BookCall/index.php',
    'book_call_set_intent'=> 'controllers/book_call_set_intent.php',
    'book_call_submit'=> 'controllers/book_call_submit.php',
    'book_call_skip'=> 'controllers/book_call_skip.php',
    'book_call_reminders'=> 'controllers/book_call_reminders.php',
    'smtp_health'=> 'controllers/smtp_health.php',
    'odoo_removal_export'=> 'controllers/odoo_removal_export.php',
    'temp_backfill_removal_sites'=> 'controllers/temp_backfill_removal_sites.php',
    'removal_metrics'=> 'controllers/removal_metrics.php',

    'new_dashboard(?:/(\w+))?'=> 'NewDashboard/index.php',

    'dashboard(?:/(\w+))?'=> 'Dashboard/index.php',
    
];

foreach ($routes as $pattern => $file) {
    if (preg_match("#^$pattern$#", $request, $matches)) {
        $GLOBALS['matches'] = $matches;
        require __DIR__ . "/src/pages/$file";
        exit;
    }
}

http_response_code(404);
echo "404 - Page not found";

?>