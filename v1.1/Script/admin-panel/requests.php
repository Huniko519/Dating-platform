<?php
require_once('../bootstrap.php');
include 'function.php';
global $db,$conn;
SessionStart();
use Aws\S3\S3Client;
$f = '';
$s = '';
if (isset($_GET['f'])) {
    $f = Secure($_GET['f'], 0);
}
if (isset($_GET['s'])) {
    $s = Secure($_GET['s'], 0);
}
$hash_id = '';
if (!empty($_POST['hash_id'])) {
    $hash_id = $_POST['hash_id'];
    unset($_POST['hash_id']);
} else if (!empty($_GET['hash_id'])) {
    $hash_id = $_GET['hash_id'];
    unset($_GET['hash_id']);
} else if (!empty($_GET['hash'])) {
    $hash_id = $_GET['hash'];
    unset($_GET['hash']);
} else if (!empty($_POST['hash'])) {
    $hash_id = $_POST['hash'];
    unset($_POST['hash']);
}
$data = array();
header("Content-type: application/json");

if ($f == 'session_status') {
    if (isset( $_SESSION['JWT'])) {
        $data = array(
            'status' => 200
        );
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
if (!isset( $_SESSION['JWT'])) {
    exit("Please login or signup to continue.");
}
if ($f == 'get_lang_key' && auth()->admin == '1') {
    $html  = '';
    $langs = Wo_GetLangDetails($_GET['id']);
    if (count($langs) > 0) {
        foreach ($langs as $key => $wo['langs']) {
            foreach ($wo['langs'] as $wo['key_'] => $wo['lang_vlaue']) {
                $wo['is_editale'] = 0;
                if ($_GET['lang_name'] == $wo['key_']) {
                    $wo['is_editale'] = 1;
                }
                $html .= Wo_LoadAdminPage('edit-lang/form-list');
            }
        }
    } else {
        $html = "<h4>Keyword not found</h4>";
    }
    $data['status'] = 200;
    $data['html']   = $html;
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}

if ($f == "admin_setting" && auth()->admin == '1') {
    if ($s == 'delete_gift') {
        if (!empty($_GET['gift_id'])) {
            if (Wo_DeleteGift($_GET['gift_id']) === true) {
                $data = array(
                    'status' => 200
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete_sticker') {
        if (!empty($_GET['sticker_id'])) {
            if (Wo_DeleteSticker($_GET['sticker_id']) === true) {
                $data = array(
                    'status' => 200
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete_photo') {
        if (!empty($_GET['photo_id'])) {
            $photo_id = Secure($_GET['photo_id']);
            $photo_file = Secure($_GET['photo_file']);
            $avater_file = str_replace('_full.','_avater.', $photo_file);
            $deleted = false;
            Wo_DeletePhoto($photo_id);
            if (DeleteFromToS3( $photo_file ) === true) {
                $deleted = true;
            }
            if (DeleteFromToS3( $avater_file ) === true) {
                $deleted = true;
            }
            if ($deleted === true) {
                $data = array(
                    'status' => 200
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'approve_receipt') {
        if (!empty($_GET['receipt_id'])) {
            $photo_id = Secure($_GET['receipt_id']);
            $receipt = $db->where('id',$photo_id)->getOne('bank_receipts',array('*'));

            if($receipt){

                $membershipType = 0;
                $amount         = 0;
                $realprice      = (int)$receipt['price'];

                if ($receipt['mode'] == 'credits') {
                    if ($realprice == (int)$wo['config']['bag_of_credits_price']) {
                        $amount = (int)$wo['config']['bag_of_credits_amount'];
                    } else if ($realprice == (int)$wo['config']['box_of_credits_price']) {
                        $amount = (int)$wo['config']['box_of_credits_amount'];
                    } else if ($realprice == (int)$wo['config']['chest_of_credits_price']) {
                        $amount = (int)$wo['config']['chest_of_credits_amount'];
                    }
                } else if ($receipt['mode'] == 'membership') {
                    if ($realprice == (int)$wo['config']['weekly_pro_plan']) {
                        $membershipType = 1;
                    } else if ($realprice == (int)$wo['config']['monthly_pro_plan']) {
                        $membershipType = 2;
                    } else if ($realprice == (int)$wo['config']['yearly_pro_plan']) {
                        $membershipType = 3;
                    } else if ($realprice == (int)$wo['config']['lifetime_pro_plan']) {
                        $membershipType = 4;
                    }
                }


                $updated = $db->where('id',$photo_id)->update('bank_receipts',array('approved'=>1,'approved_at'=>time()));
                if ($updated === true) {

                    $Notification = LoadEndPointResource('Notifications');
                    if($Notification) {
                        $Notification->createNotification('', auth()->id, $receipt['user_id'], 'approve_receipt', $wo['config']['currency_symbol'] . $realprice, '/#');
                    }

                    if($receipt['mode'] == 'credits'){
                        $query_one = mysqli_query($conn, "UPDATE `users` SET `balance` = `balance` + {$amount} WHERE `id` = {$receipt['user_id']}");
                    }
                    if($receipt['mode'] == 'membership'){
                        $query_one = mysqli_query($conn, "UPDATE `users` SET `pro_time` = '".time()."', `is_pro` = '1', `pro_type` = '".$membershipType."' WHERE `id` = ".$receipt['user_id']);
                    }

                    $query_one = mysqli_query($conn, "INSERT `payments`(`user_id`,`amount`,`type`,`pro_plan`,`credit_amount`,`via`) VALUES ('{$receipt['user_id']}','{$receipt['price']}','{$receipt['mode']}','{$membershipType}','{$amount}','Bank transfer');");

                    $data = array(
                        'status' => 200
                    );
                }
            }
            $data = array(
                'status' => 200,
                'data' => $receipt
            );
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete_receipt') {
        if (!empty($_GET['receipt_id'])) {
            $user_id = Secure($_GET['user_id']);
            $photo_id = Secure($_GET['receipt_id']);
            $photo_file = Secure($_GET['receipt_file']);

            $Notification = LoadEndPointResource('Notifications');
            if($Notification) {
                $Notification->createNotification('', auth()->id, $user_id, 'disapprove_receipt', '', '/contact');
            }

            $deleted = false;
            $db->where('id',$photo_id)->delete('bank_receipts');
            if (DeleteFromToS3( $photo_file ) === true) {
                $deleted = true;
            }
            if ($deleted === true) {
                $data = array(
                    'status' => 200
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete_reported_content') {
        if (!empty($_GET['id']) && !empty($_GET['type']) && !empty($_GET['report_id'])) {
            $type   = Secure($_GET['type']);
            $id     = Secure($_GET['id']);
            $report = Secure($_GET['report_id']);
            if ($type == 'user') {
                $deleteReport = Wo_DeleteReport($report);
                if ($deleteReport === true) {
                    $data = array(
                        'status' => 200,
                        'html' => Wo_CountUnseenReports()
                    );
                }
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'mark_as_safe') {
        if (!empty($_GET['report_id'])) {
            $deleteReport = Wo_DeleteReport($_GET['report_id']);
            if ($deleteReport === true) {
                $data = array(
                    'status' => 200,
                    'html' => Wo_CountUnseenReports()
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'update_general_setting' && Wo_CheckSession($hash_id) === true) {
        $saveSetting = false;
        foreach ($_POST as $key => $value) {
            $saveSetting = Wo_SaveConfig($key, $value);
        }
        if ($saveSetting === true) {
            $data['status'] = 200;
        }
        echo json_encode($data);
        exit();
    }
    if ($s == 'save-design' && Wo_CheckSession($hash_id) === true) {
        $saveSetting = false;
        if (isset($_FILES['logo']['name'])) {
            $fileInfo = array(
                'file' => $_FILES["logo"]["tmp_name"],
                'name' => $_FILES['logo']['name'],
                'size' => $_FILES["logo"]["size"]
            );
            $media    = UploadLogo($fileInfo);
        }
//        if (isset($_FILES['light-logo']['name'])) {
//            $fileInfo = array(
//                'file' => $_FILES["light-logo"]["tmp_name"],
//                'name' => $_FILES['light-logo']['name'],
//                'size' => $_FILES["light-logo"]["size"],
//                'light-logo' => true
//            );
//            $media    = UploadLogo($fileInfo);
//        }
        if (isset($_FILES['favicon']['name'])) {
            $fileInfo = array(
                'file' => $_FILES["favicon"]["tmp_name"],
                'name' => $_FILES['favicon']['name'],
                'size' => $_FILES["favicon"]["size"],
                'favicon' => true
            );
            $media    = UploadLogo($fileInfo);
        }

        $saveSetting = false;
        foreach ($_POST as $key => $value) {
            $saveSetting = Wo_SaveConfig($key, $value);
        }
        if ($saveSetting === true) {
            $data['status'] = 200;
        }

        $data['status'] = 200;
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete_user' && isset($_GET['user_id']) && Wo_CheckSession($hash_id) === true) {
        $deleted = false;
        $d_user = LoadEndPointResource('users');
        if($d_user) {
            $deleted = $d_user->delete_user(Secure($_GET['user_id']));
        }
        if ($deleted['is_delete'] === true) {
            $data['status'] = 200;
            $data['message'] = 'Deleted';
        }else{
            $data['status'] = 200;
            $data['message'] = 'Not Deleted';
        }
        $data['deleted'] = $deleted;
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'update_terms_setting' && Wo_CheckSession($hash_id) === true) {
        $saveSetting = false;
        foreach ($_POST as $key => $value) {
            $saveSetting = Wo_SaveConfig($key, base64_decode($value));
        }
        if ($saveSetting === true) {
            $data['status'] = 200;
        }
        echo json_encode($data);
        exit();
    }
    if ($s == 'add_new_lang') {
        if (Wo_CheckSession($hash_id) === true) {
            $mysqli = Wo_LangsNamesFromDB();
            if (in_array($_POST['lang'], $mysqli)) {
                $data['status']  = 400;
                $data['message'] = 'This lang is already used.';
            } else {
                $lang_name = Secure($_POST['lang']);
                $lang_name = strtolower($lang_name);
                $query     = mysqli_query($conn, "ALTER TABLE `langs` ADD `$lang_name` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;");
                if ($query) {
                    //$content = file_get_contents('assets/languages/extra/english.php');
                    //$fp      = fopen("assets/languages/extra/$lang_name.php", "wb");
                    //fwrite($fp, $content);
                    //fclose($fp);
                    $english = Wo_LangsFromDB('english');
                    foreach ($english as $key => $lang) {
                        $lang  = Secure($lang);
                        $query = mysqli_query($conn, "UPDATE `langs` SET `{$lang_name}` = '$lang' WHERE `lang_key` = '{$key}'");
                    }
                    $data['status'] = 200;
                }
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'add_new_lang_key') {
        if (Wo_CheckSession($hash_id) === true) {
            if (!empty($_POST['lang_key'])) {
                $lang_key  = Secure($_POST['lang_key']);
                $mysqli    = mysqli_query($conn, "SELECT COUNT(id) as count FROM `langs` WHERE `lang_key` = '$lang_key'");
                $sql_fetch = mysqli_fetch_assoc($mysqli);
                if ($sql_fetch['count'] == 0) {
                    $mysqli = mysqli_query($conn, "INSERT INTO `langs` (`lang_key`) VALUE ('$lang_key')");
                    if ($mysqli) {
                        $_SESSION['language_changed'] = true;
                        $data['status'] = 200;
                        $data['url']    = Wo_LoadAdminLinkSettings('manage-languages');
                    }
                } else {
                    $data['status']  = 400;
                    $data['message'] = 'This key is already used, please use other one.';
                }
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete_lang') {
        $mysqli = Wo_LangsNamesFromDB();
        if (in_array($_GET['id'], $mysqli)) {
            $lang_name = Secure($_GET['id']);
            $query     = mysqli_query($conn, "ALTER TABLE `langs` DROP COLUMN `$lang_name`");
            if ($query) {
                //unlink("assets/languages/extra/$lang_name.php");
                $data['status'] = 200;
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'update_lang_key') {
        if (Wo_CheckSession($hash_id) === true) {
            $array_langs = array();
            $lang_key    = Secure($_POST['id_of_key']);
            $langs       = Wo_LangsNamesFromDB();
            foreach ($_POST as $key => $value) {
                if (in_array($key, $langs)) {
                    $key   = Secure($key);
                    $value = Secure($value);
                    $query = mysqli_query($conn, "UPDATE `langs` SET `{$key}` = '{$value}' WHERE `lang_key` = '{$lang_key}'");
                    if ($query) {
                        $data['status'] = 200;
                        $_SESSION['language_changed'] = true;
                    }
                }
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'test_message') {
        $send_message      = SendEmail(auth()->email,'Test Message From ' . $wo['config']['siteName'],'If you can see this message, then your SMTP configuration is working fine.');
        if ($send_message === true) {
            $data['status'] = 200;
        } else {
            $data['status'] = 400;
            $data['error']  = 'Error while sending email.';
        }
        echo json_encode($data);
        exit();
    }
    if ($s == 'test_sms_message') {
        $message      = 'This is a test message from ' . $wo['config']['siteName'];
        $send_message = SendSMS($wo['config']['sms_phone_number'], $message);
        if ($send_message === true) {
            $data['status'] = 200;
        } else {
            $data['status'] = 400;
            $data['error']  = $send_message;
        }
        echo json_encode($data);
        exit();
    }
    if ($s == 'test_s3') {
        try {
            $s3Client = S3Client::factory(array(
                'version' => 'latest',
                'region' => $wo['config']['region'],
                'credentials' => array(
                    'key' => $wo['config']['amazone_s3_key'],
                    'secret' => $wo['config']['amazone_s3_s_key']
                )
            ));
            $buckets  = $s3Client->listBuckets();
            $result   = $s3Client->putBucketCors(array(
                'Bucket' => $wo['config']['bucket_name'], // REQUIRED
                'CORSConfiguration' => array( // REQUIRED
                    'CORSRules' => array( // REQUIRED
                        array(
                            'AllowedHeaders' => array(
                                'Authorization'
                            ),
                            'AllowedMethods' => array(
                                'POST',
                                'GET',
                                'PUT'
                            ), // REQUIRED
                            'AllowedOrigins' => array(
                                '*'
                            ), // REQUIRED
                            'ExposeHeaders' => array(),
                            'MaxAgeSeconds' => 3000
                        )
                    )
                )
            ));
            if (!empty($buckets)) {
                if ($s3Client->doesBucketExist($wo['config']['bucket_name'])) {
                    $data['status'] = 200;
                    $array          = array(
                        'upload/photos/d-avatar.jpg'
                    );
                    foreach ($array as $key => $value) {
                        $upload = Wo_UploadToS3($value, array(
                            'delete' => 'no'
                        ));
                    }
                } else {
                    $data['status'] = 300;
                }
            } else {
                $data['status'] = 500;
            }
        }
        catch (Exception $e) {
            $data['status']  = 400;
            $data['message'] = $e->getMessage();
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'fake-users') {

        $countries = array('AF' => 'Afghanistan', 'AX' => 'Aland Islands', 'AL' => 'Albania', 'DZ' => 'Algeria', 'AS' => 'American Samoa', 'AD' => 'Andorra', 'AO' => 'Angola', 'AI' => 'Anguilla', 'AQ' => 'Antarctica', 'AG' => 'Antigua And Barbuda', 'AR' => 'Argentina', 'AM' => 'Armenia', 'AW' => 'Aruba', 'AU' => 'Australia', 'AT' => 'Austria', 'AZ' => 'Azerbaijan', 'BS' => 'Bahamas', 'BH' => 'Bahrain', 'BD' => 'Bangladesh', 'BB' => 'Barbados', 'BY' => 'Belarus', 'BE' => 'Belgium', 'BZ' => 'Belize', 'BJ' => 'Benin', 'BM' => 'Bermuda', 'BT' => 'Bhutan', 'BO' => 'Bolivia', 'BA' => 'Bosnia And Herzegovina', 'BW' => 'Botswana', 'BV' => 'Bouvet Island', 'BR' => 'Brazil', 'IO' => 'British Indian Ocean Territory', 'BN' => 'Brunei Darussalam', 'BG' => 'Bulgaria', 'BF' => 'Burkina Faso', 'BI' => 'Burundi', 'KH' => 'Cambodia', 'CM' => 'Cameroon', 'CA' => 'Canada', 'CV' => 'Cape Verde', 'KY' => 'Cayman Islands', 'CF' => 'Central African Republic', 'TD' => 'Chad', 'CL' => 'Chile', 'CN' => 'China', 'CX' => 'Christmas Island', 'CC' => 'Cocos (Keeling) Islands', 'CO' => 'Colombia', 'KM' => 'Comoros', 'CG' => 'Congo', 'CD' => 'Congo, Democratic Republic', 'CK' => 'Cook Islands', 'CR' => 'Costa Rica', 'CI' => 'Cote D\'Ivoire', 'HR' => 'Croatia', 'CU' => 'Cuba', 'CY' => 'Cyprus', 'CZ' => 'Czech Republic', 'DK' => 'Denmark', 'DJ' => 'Djibouti', 'DM' => 'Dominica', 'DO' => 'Dominican Republic', 'EC' => 'Ecuador', 'EG' => 'Egypt', 'SV' => 'El Salvador', 'GQ' => 'Equatorial Guinea', 'ER' => 'Eritrea', 'EE' => 'Estonia', 'ET' => 'Ethiopia', 'FK' => 'Falkland Islands (Malvinas)', 'FO' => 'Faroe Islands', 'FJ' => 'Fiji', 'FI' => 'Finland', 'FR' => 'France', 'GF' => 'French Guiana', 'PF' => 'French Polynesia', 'TF' => 'French Southern Territories', 'GA' => 'Gabon', 'GM' => 'Gambia', 'GE' => 'Georgia', 'DE' => 'Germany', 'GH' => 'Ghana', 'GI' => 'Gibraltar', 'GR' => 'Greece', 'GL' => 'Greenland', 'GD' => 'Grenada', 'GP' => 'Guadeloupe', 'GU' => 'Guam', 'GT' => 'Guatemala', 'GG' => 'Guernsey', 'GN' => 'Guinea', 'GW' => 'Guinea-Bissau', 'GY' => 'Guyana', 'HT' => 'Haiti', 'HM' => 'Heard Island & Mcdonald Islands', 'VA' => 'Holy See (Vatican City State)', 'HN' => 'Honduras', 'HK' => 'Hong Kong', 'HU' => 'Hungary', 'IS' => 'Iceland', 'IN' => 'India', 'ID' => 'Indonesia', 'IR' => 'Iran, Islamic Republic Of', 'IQ' => 'Iraq', 'IE' => 'Ireland', 'IM' => 'Isle Of Man', 'IL' => 'Israel', 'IT' => 'Italy', 'JM' => 'Jamaica', 'JP' => 'Japan', 'JE' => 'Jersey', 'JO' => 'Jordan', 'KZ' => 'Kazakhstan', 'KE' => 'Kenya', 'KI' => 'Kiribati', 'KR' => 'Korea', 'KW' => 'Kuwait', 'KG' => 'Kyrgyzstan', 'LA' => 'Lao People\'s Democratic Republic', 'LV' => 'Latvia', 'LB' => 'Lebanon', 'LS' => 'Lesotho', 'LR' => 'Liberia', 'LY' => 'Libyan Arab Jamahiriya', 'LI' => 'Liechtenstein', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MO' => 'Macao', 'MK' => 'Macedonia', 'MG' => 'Madagascar', 'MW' => 'Malawi', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'ML' => 'Mali', 'MT' => 'Malta', 'MH' => 'Marshall Islands', 'MQ' => 'Martinique', 'MR' => 'Mauritania', 'MU' => 'Mauritius', 'YT' => 'Mayotte', 'MX' => 'Mexico', 'FM' => 'Micronesia, Federated States Of', 'MD' => 'Moldova', 'MC' => 'Monaco', 'MN' => 'Mongolia', 'ME' => 'Montenegro', 'MS' => 'Montserrat', 'MA' => 'Morocco', 'MZ' => 'Mozambique', 'MM' => 'Myanmar', 'NA' => 'Namibia', 'NR' => 'Nauru', 'NP' => 'Nepal', 'NL' => 'Netherlands', 'AN' => 'Netherlands Antilles', 'NC' => 'New Caledonia', 'NZ' => 'New Zealand', 'NI' => 'Nicaragua', 'NE' => 'Niger', 'NG' => 'Nigeria', 'NU' => 'Niue', 'NF' => 'Norfolk Island', 'MP' => 'Northern Mariana Islands', 'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PW' => 'Palau', 'PS' => 'Palestinian Territory, Occupied', 'PA' => 'Panama', 'PG' => 'Papua New Guinea', 'PY' => 'Paraguay', 'PE' => 'Peru', 'PH' => 'Philippines', 'PN' => 'Pitcairn', 'PL' => 'Poland', 'PT' => 'Portugal', 'PR' => 'Puerto Rico', 'QA' => 'Qatar', 'RE' => 'Reunion', 'RO' => 'Romania', 'RU' => 'Russian Federation', 'RW' => 'Rwanda', 'BL' => 'Saint Barthelemy', 'SH' => 'Saint Helena', 'KN' => 'Saint Kitts And Nevis', 'LC' => 'Saint Lucia', 'MF' => 'Saint Martin', 'PM' => 'Saint Pierre And Miquelon', 'VC' => 'Saint Vincent And Grenadines', 'WS' => 'Samoa', 'SM' => 'San Marino', 'ST' => 'Sao Tome And Principe', 'SA' => 'Saudi Arabia', 'SN' => 'Senegal', 'RS' => 'Serbia', 'SC' => 'Seychelles', 'SL' => 'Sierra Leone', 'SG' => 'Singapore', 'SK' => 'Slovakia', 'SI' => 'Slovenia', 'SB' => 'Solomon Islands', 'SO' => 'Somalia', 'ZA' => 'South Africa', 'GS' => 'South Georgia And Sandwich Isl.', 'ES' => 'Spain', 'LK' => 'Sri Lanka', 'SD' => 'Sudan', 'SR' => 'Suriname', 'SJ' => 'Svalbard And Jan Mayen', 'SZ' => 'Swaziland', 'SE' => 'Sweden', 'CH' => 'Switzerland', 'SY' => 'Syrian Arab Republic', 'TW' => 'Taiwan', 'TJ' => 'Tajikistan', 'TZ' => 'Tanzania', 'TH' => 'Thailand', 'TL' => 'Timor-Leste', 'TG' => 'Togo', 'TK' => 'Tokelau', 'TO' => 'Tonga', 'TT' => 'Trinidad And Tobago', 'TN' => 'Tunisia', 'TR' => 'Turkey', 'TM' => 'Turkmenistan', 'TC' => 'Turks And Caicos Islands', 'TV' => 'Tuvalu', 'UG' => 'Uganda', 'UA' => 'Ukraine', 'AE' => 'United Arab Emirates', 'GB' => 'United Kingdom', 'US' => 'United States', 'UM' => 'United States Outlying Islands', 'UY' => 'Uruguay', 'UZ' => 'Uzbekistan', 'VU' => 'Vanuatu', 'VE' => 'Venezuela', 'VN' => 'Viet Nam', 'VG' => 'Virgin Islands, British', 'VI' => 'Virgin Islands, U.S.', 'WF' => 'Wallis And Futuna', 'EH' => 'Western Sahara', 'YE' => 'Yemen', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe');
        $countries_key = array_keys($countries);

        require $_BASEPATH.'lib'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'fake-users'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
        $faker = Faker\Factory::create();
        if (empty($_POST['password'])) {
            $_POST['password'] = '123456789';
        }
        $count_users = $_POST['count_users'];
        $password = $_POST['password'];
        $avatar = $_POST['avatar'];

        Wo_RunInBackground(array('status' => 200));

        $Date1 = date('Y-m-d');
        $Date2 = date('Y-m-d', strtotime($Date1 . " - 19 year"));
        $users      = LoadEndPointResource('users');
        if ($users) {
            for ($i=0; $i < $count_users; $i++) {
                $genders = array("4525", "4526");
                $random_keys = array_rand($genders, 1);
                $gender = array_rand(array("male", "female"), 1);
                $gender = $genders[$random_keys];
                $re_data  = array(
                    'email' => Secure(str_replace(".", "_", $faker->userName) . '_' . rand(111, 999) . "@yahoo.com", 0),
                    'username' => Secure($faker->userName . '_' . rand(111, 999), 0),
                    'password' => Secure($password, 0),
                    'email_code' => Secure(md5($faker->userName . '_' . rand(111, 999)), 0),
                    'src' => 'Fake',
                    'gender' => Secure($gender),
                    'lastseen' => time(),
                    'verified' => 1,
                    'active' => 1,
                    'first_name' => $faker->name,
                    'last_name' => $faker->lastName,
                    'lat' => auth()->lat,
                    'lng' => auth()->lng,
                    'birthday' => $Date2,
                    'country_id' => $countries_key[array_rand($countries_key)],
                    'about' => 'Ut ab voluptas sed a nam. Sint autem inventore aut officia aut aut blanditiis. Ducimus eos odit amet et est ut eum.'
                );
                if ($avatar == 1) {
                    $re_data['avater'] = 'upload/photos/users/'.rand(1,20).'.jpg'; //$users->ImportImageFromLogin($faker->imageUrl($wo['config']['profile_picture_width_crop'], $wo['config']['profile_picture_height_crop'],'people'), 1);
                }
                $regestered_user = $users->register($re_data);
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'add_new_announcement') {
        if (!empty($_POST['announcement_text'])) {
            $html = '';
            $id   = Wo_AddNewAnnouncement(base64_decode($_POST['announcement_text']));
            if ($id > 0) {
                $wo['activeAnnouncement'] = Wo_GetAnnouncement($id);
                $html .= Wo_LoadAdminPage('manage-announcements/active-list');
                $data = array(
                    'status' => 200,
                    'text' => $html
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete_announcement') {
        if (!empty($_GET['id'])) {
            $DeleteAnnouncement = Wo_DeleteAnnouncement($_GET['id']);
            if ($DeleteAnnouncement === true) {
                $data = array(
                    'status' => 200
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'disable_announcement') {
        if (!empty($_GET['id'])) {
            $html                = '';
            $DisableAnnouncement = Wo_DisableAnnouncement(Secure($_GET['id']));
            if ($DisableAnnouncement === true) {
                $wo['inactiveAnnouncement'] = Wo_GetAnnouncement(Secure($_GET['id']));
                $html .= Wo_LoadAdminPage('manage-announcements/inactive-list');
                $data = array(
                    'status' => 200,
                    'html' => $html
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'activate_announcement') {
        if (!empty($_GET['id'])) {
            $html                 = '';
            $ActivateAnnouncement = Wo_ActivateAnnouncement(Secure($_GET['id']));
            if ($ActivateAnnouncement === true) {
                $wo['activeAnnouncement'] = Wo_GetAnnouncement($_GET['id']);
                $html .= Wo_LoadAdminPage('manage-announcements/active-list');
                $data = array(
                    'status' => 200,
                    'html' => $html
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'add_new_gender') {
        if (Wo_CheckSession($hash_id) === true) {
            $insert_data = array();
            $insert_data['ref'] = 'gender';
            $add = false;
            foreach (Wo_LangsNamesFromDB() as $wo['key_']) {
                if (!empty($_POST[$wo['key_']])) {
                    $insert_data[$wo['key_']] = Secure($_POST[$wo['key_']]);
                    $add = true;
                }
            }
            if ($add == true) {
                $id = $db->insert('langs', $insert_data);
                $db->where('id', $id)->update('langs', array('lang_key' => $id));
                $data['status'] = 200;
            } else {
                $data['status'] = 400;
                $data['message'] = 'please check details';
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete_gender') {
        if (!empty($_GET['key']) && in_array($_GET['key'], array_keys(Dataset::gender()))) {
            $db->where('lang_key',Secure($_GET['key']))->delete('langs');
        }
        $data['status'] = 200;
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
}
mysqli_close($conn);
unset($wo);