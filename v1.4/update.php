<?php
if (file_exists('./bootstrap.php')) {
    require_once('./bootstrap.php');
} else {
    die('Please put this file in the home directory !');
}
function PT_UpdateLangs($lang, $key, $value) {
    global $conn;
    $update_query         = "UPDATE langs SET `{lang}` = '{lang_text}' WHERE `lang_key` = '{lang_key}'";
    $update_replace_array = array(
        "{lang}",
        "{lang_text}",
        "{lang_key}"
    );
    return str_replace($update_replace_array, array(
        $lang,
        mysqli_real_escape_string($conn, $value),
        $key
    ), $update_query);
}
$updated = false;
if (!empty($_GET['updated'])) {
    $updated = true;
}
if (!empty($_POST['query'])) {
    $query = mysqli_query($conn, base64_decode($_POST['query']));
    if ($query) {
        $data['status'] = 200;
    } else {
        $data['status'] = 400;
        $data['error']  = mysqli_error($conn);
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
if (!empty($_POST['update_langs'])) {
    $data  = array();
    $query = mysqli_query($conn, "SHOW COLUMNS FROM `langs`");
    while ($fetched_data = mysqli_fetch_assoc($query)) {
        $data[] = $fetched_data['Field'];
    }
    unset($data[0]);
    unset($data[1]);
    unset($data[2]);
    unset($data[3]);
    $lang_update_queries = array();
    foreach ($data as $key => $value) {
        $value = ($value);
        if ($value == 'arabic') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'manage_sessions', 'إدارة الجلسات');
    $lang_update_queries[] = PT_UpdateLangs($value, 'platform', 'منصة');
    $lang_update_queries[] = PT_UpdateLangs($value, 'last_seen', 'اخر ظهور');
    $lang_update_queries[] = PT_UpdateLangs($value, 'os', 'THE');
    $lang_update_queries[] = PT_UpdateLangs($value, 'browser', 'المتصفح');
    $lang_update_queries[] = PT_UpdateLangs($value, 'action', 'عمل');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_deleting_session__please_try_again_later.', 'خطأ أثناء حذف الجلسة ، يرجى المحاولة مرة أخرى لاحقًا.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'session_deleted_successfully.', 'تم حذف الجلسة بنجاح.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'two-factor_authentication', 'توثيق ذو عاملين');
    $lang_update_queries[] = PT_UpdateLangs($value, 'turn_on_2-step_login_to_level-up_your_account_s_security__once_turned_on__you_ll_use_both_your_password_and_a_6-digit_security_code_sent_to_your_phone_or_email_to_log_in.', 'قم بتشغيل تسجيل الدخول من خطوتين لتحسين مستوى حسابك');
    $lang_update_queries[] = PT_UpdateLangs($value, 'two-factor_authentication_data_saved_successfully.', 'تم حفظ بيانات المصادقة ثنائية العوامل بنجاح.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'a_confirmation_email_has_been_sent.', '');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_an_email_that_contains_the_confirmation_code_to_enable_two-factor_authentication.', 'لقد أرسلنا رسالة بريد إلكتروني تحتوي على رمز التأكيد لتمكين المصادقة الثنائية.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirmation_code', 'رمز التأكيد');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_check_your_details.', 'يرجى التأكد من تفاصيل معلوماتك.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_e-mail_has_been_successfully_verified.', 'تم التحقق من بريدك الإلكتروني بنجاح.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number_should_be_as_this_format___90..', 'يجب أن يكون رقم الهاتف بهذا الشكل: 90 ..');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_phone_number_and_e-mail_have_been_successfully_verified.', 'تم التحقق من رقم هاتفك والبريد الإلكتروني بنجاح.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'unusual_login', 'تسجيل دخول غير عادي');
    $lang_update_queries[] = PT_UpdateLangs($value, 'to_log_in__you_need_to_verify_your_identity.', 'لتسجيل الدخول ، تحتاج إلى التحقق من هويتك.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_you_the_confirmation_code_to_your_phone_and_to_your_email_address.', 'لقد أرسلنا لك رمز التأكيد إلى هاتفك وعنوان بريدك الإلكتروني.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_you_the_confirmation_code_to_your_email_address.', 'لقد أرسلنا لك رمز التأكيد إلى عنوان بريدك الإلكتروني.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_confirmation_code.', 'الرجاء إدخال رمز التأكيد.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'something_went_wrong__please_try_again_later.', 'هناك شئ خاطئ، يرجى المحاولة فى وقت لاحق.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'wrong_confirmation_code.', 'رمز التأكيد الخاطئ.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_login__please_try_again_later.', 'خطأ أثناء تسجيل الدخول ، يرجى المحاولة مرة أخرى لاحقًا.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_user_id', 'هوية مستخدم غير صالحه');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_confirmation_code', 'رمز التأكيد غير صالح');
    $lang_update_queries[] = PT_UpdateLangs($value, 'find_potential_matches_by_country', 'البحث عن التطابقات المحتملة حسب البلد');
    $lang_update_queries[] = PT_UpdateLangs($value, 'manage_notifications', 'إدارة الإخطارات');
    $lang_update_queries[] = PT_UpdateLangs($value, 'custom_field', 'حقل مخصص');
    $lang_update_queries[] = PT_UpdateLangs($value, 'food', 'طعام');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_media', 'إضافة وسائل الإعلام');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_video', 'أضف فيديو');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_photo', 'إضافة صورة');
    $lang_update_queries[] = PT_UpdateLangs($value, 'upload', 'رفع');
    $lang_update_queries[] = PT_UpdateLangs($value, 'video_title', 'عنوان مقطع الفيديو');
    $lang_update_queries[] = PT_UpdateLangs($value, 'public', 'عامة');
    $lang_update_queries[] = PT_UpdateLangs($value, 'private', 'نشر');
    $lang_update_queries[] = PT_UpdateLangs($value, 'thumbnail', 'ظفري');
    $lang_update_queries[] = PT_UpdateLangs($value, 'my_affiliates', 'الشركات التابعة لي');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_affiliate_link_is', 'الارتباط التابع الخاص بك هو');
    $lang_update_queries[] = PT_UpdateLangs($value, 'my_balance', 'رصيدي');
    $lang_update_queries[] = PT_UpdateLangs($value, 'earn_up_to', 'تكسب ما يصل الى');
    $lang_update_queries[] = PT_UpdateLangs($value, 'for_each_user_your_refer_to_us__', 'لكل مستخدم يرجى الرجوع إلينا!');
    $lang_update_queries[] = PT_UpdateLangs($value, 'joined', 'انضم');
    $lang_update_queries[] = PT_UpdateLangs($value, 'payments', 'المدفوعات');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_balance_is', 'رصيدك هو');
    $lang_update_queries[] = PT_UpdateLangs($value, '__minimum_withdrawal_request_is', '، طلب السحب الأدنى هو');
    $lang_update_queries[] = PT_UpdateLangs($value, 'paypal_email', 'بريد باي بال');
    $lang_update_queries[] = PT_UpdateLangs($value, 'request_withdrawal', 'طلب السحب');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_request_has_been_sent__you__039_ll_receive_an_email_regarding_the_payment_details_soon.', 'تم ارسال طلبك');
    $lang_update_queries[] = PT_UpdateLangs($value, 'requested', 'طلب');
    $lang_update_queries[] = PT_UpdateLangs($value, 'payment_history', 'تاريخ الدفع');
    $lang_update_queries[] = PT_UpdateLangs($value, 'approved', 'وافق');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_amount_value__your_amount_is_', 'قيمة المبلغ غير صالحة ، المبلغ الخاص بك هو:');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_friend', 'أضف صديق');
    $lang_update_queries[] = PT_UpdateLangs($value, 'unfriend', 'unfriend');
    $lang_update_queries[] = PT_UpdateLangs($value, 'friend_request_sent', 'تم ارسال طلب صداقة');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_already_sent_friend_request.', 'لقد قمت بالفعل بإرسال طلب.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'success', 'نجاح');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirm_request_when_someone_follows_you__', 'تأكيد الطلب عندما يتبعك شخص ما؟');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirm_request_when_someone_request_friend_you__', 'تأكيد الطلب عندما يطلب شخص ما أن يكون صديقا معك؟');
    $lang_update_queries[] = PT_UpdateLangs($value, 'created_a_story_with_you.', 'خلق قصة معك.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'accepted_your_friend_request.', 'قبلت طلب صديقك.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'declined_your_friend_request.', 'رفض طلب صديقك.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'send_friend_request_to_you.', 'طلب أن يكون صديقا معك.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'friend_requests', 'طلبات صداقة');
    $lang_update_queries[] = PT_UpdateLangs($value, 'is_now_in_friend_list.', 'هو الآن في قائمة الأصدقاء الخاصة بك.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'decline_request', 'رفض الطلب');
    $lang_update_queries[] = PT_UpdateLangs($value, 'accept_request', 'قبول الطلب');
    $lang_update_queries[] = PT_UpdateLangs($value, 'request_your_friendship.', 'اطلب صداقتك');
    $lang_update_queries[] = PT_UpdateLangs($value, 'can_not_create_notification', 'لا يمكن إنشاء إشعار');
    $lang_update_queries[] = PT_UpdateLangs($value, 'pending_review', 'في انتظار المراجعة');
    $lang_update_queries[] = PT_UpdateLangs($value, 'the_username_is_blacklisted_and_not_allowed__please_choose_another_username.', 'اسم المستخدم مدرج في القائمة السوداء وغير مسموح به ، يرجى اختيار اسم مستخدم آخر.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'the_email_provider_is_blacklisted_and_not_allowed__please_choose_another_email_provider.', 'مزود البريد الإلكتروني مدرج في القائمة السوداء وغير مسموح به ، يرجى اختيار مزود بريد إلكتروني آخر.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'latest__0__users.', 'أحدث {0} مستخدم.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_reach_to_limit_of_media_uploads.', 'لقد وصلت إلى الحد الأقصى لعمليات تحميل الوسائط.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'email_sent_to', 'تم إرسال البريد الإلكتروني إلى');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_sending_emails', 'خطأ أثناء إرسال رسائل البريد الإلكتروني');
    $lang_update_queries[] = PT_UpdateLangs($value, 'under_review', 'قيد المراجعة');
        } else if ($value == 'dutch') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'manage_sessions', 'Sessies beheren');
    $lang_update_queries[] = PT_UpdateLangs($value, 'platform', 'Platform');
    $lang_update_queries[] = PT_UpdateLangs($value, 'last_seen', 'Laatst gezien');
    $lang_update_queries[] = PT_UpdateLangs($value, 'os', 'DE');
    $lang_update_queries[] = PT_UpdateLangs($value, 'browser', 'browser');
    $lang_update_queries[] = PT_UpdateLangs($value, 'action', 'Actie');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_deleting_session__please_try_again_later.', 'Fout tijdens het verwijderen van de sessie. Probeer het later opnieuw.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'session_deleted_successfully.', 'Sessie is succesvol verwijderd.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'two-factor_authentication', 'Twee-factor authenticatie');
    $lang_update_queries[] = PT_UpdateLangs($value, 'turn_on_2-step_login_to_level-up_your_account_s_security__once_turned_on__you_ll_use_both_your_password_and_a_6-digit_security_code_sent_to_your_phone_or_email_to_log_in.', 'Schakel inloggen in twee stappen in om uw account een hoger niveau te geven');
    $lang_update_queries[] = PT_UpdateLangs($value, 'two-factor_authentication_data_saved_successfully.', 'Twee-factor authenticatiegegevens succesvol opgeslagen.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'a_confirmation_email_has_been_sent.', '');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_an_email_that_contains_the_confirmation_code_to_enable_two-factor_authentication.', 'We hebben een e-mail verzonden met de bevestigingscode om tweefactorauthenticatie in te schakelen.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirmation_code', 'Bevestigingscode');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_check_your_details.', 'Check alsjeblieft je gegevens.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_e-mail_has_been_successfully_verified.', 'Uw e-mail is succesvol geverifieerd.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number_should_be_as_this_format___90..', 'Telefoonnummer moet als dit formaat zijn: 90 ..');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_phone_number_and_e-mail_have_been_successfully_verified.', 'Uw telefoonnummer en e-mailadres zijn succesvol geverifieerd.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'unusual_login', 'Ongebruikelijke login');
    $lang_update_queries[] = PT_UpdateLangs($value, 'to_log_in__you_need_to_verify_your_identity.', 'Om in te loggen, moet u uw identiteit verifiëren.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_you_the_confirmation_code_to_your_phone_and_to_your_email_address.', 'We hebben u de bevestigingscode naar uw telefoon en naar uw e-mailadres gestuurd.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_you_the_confirmation_code_to_your_email_address.', 'We hebben u de bevestigingscode naar uw e-mailadres gestuurd.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_confirmation_code.', 'Voer de bevestigingscode in.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'something_went_wrong__please_try_again_later.', 'Er is iets misgegaan. Probeer het later opnieuw.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'wrong_confirmation_code.', 'Verkeerde bevestigingscode.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_login__please_try_again_later.', 'Fout tijdens inloggen, probeer het later opnieuw.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_user_id', 'Ongeldige gebruikersnaam');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_confirmation_code', 'Ongeldige bevestigingscode');
    $lang_update_queries[] = PT_UpdateLangs($value, 'find_potential_matches_by_country', 'Vind potentiële overeenkomsten per land');
    $lang_update_queries[] = PT_UpdateLangs($value, 'manage_notifications', 'Beheer meldingen');
    $lang_update_queries[] = PT_UpdateLangs($value, 'custom_field', 'Aangepast veld');
    $lang_update_queries[] = PT_UpdateLangs($value, 'food', 'voedsel');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_media', 'Voeg media toe');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_video', 'Voeg video toe');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_photo', 'Voeg foto toe');
    $lang_update_queries[] = PT_UpdateLangs($value, 'upload', 'Uploaden');
    $lang_update_queries[] = PT_UpdateLangs($value, 'video_title', 'titel van de video');
    $lang_update_queries[] = PT_UpdateLangs($value, 'public', 'Openbaar');
    $lang_update_queries[] = PT_UpdateLangs($value, 'private', 'Privaat');
    $lang_update_queries[] = PT_UpdateLangs($value, 'thumbnail', 'thumbnail');
    $lang_update_queries[] = PT_UpdateLangs($value, 'my_affiliates', 'Mijn aangeslotenen');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_affiliate_link_is', 'Uw partnerlink is');
    $lang_update_queries[] = PT_UpdateLangs($value, 'my_balance', 'Mijn balans');
    $lang_update_queries[] = PT_UpdateLangs($value, 'earn_up_to', 'Verdien maximaal');
    $lang_update_queries[] = PT_UpdateLangs($value, 'for_each_user_your_refer_to_us__', 'voor elke gebruiker verwijst u naar ons!');
    $lang_update_queries[] = PT_UpdateLangs($value, 'joined', 'toegetreden');
    $lang_update_queries[] = PT_UpdateLangs($value, 'payments', 'betalingen');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_balance_is', 'Je saldo is');
    $lang_update_queries[] = PT_UpdateLangs($value, '__minimum_withdrawal_request_is', ', minimaal opnameverzoek is');
    $lang_update_queries[] = PT_UpdateLangs($value, 'paypal_email', 'Paypal E-mail');
    $lang_update_queries[] = PT_UpdateLangs($value, 'request_withdrawal', 'Verzoek tot intrekking');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_request_has_been_sent__you__039_ll_receive_an_email_regarding_the_payment_details_soon.', 'Uw aanvraag is verzonden, u');
    $lang_update_queries[] = PT_UpdateLangs($value, 'requested', 'aangevraagd');
    $lang_update_queries[] = PT_UpdateLangs($value, 'payment_history', 'Betaalgeschiedenis');
    $lang_update_queries[] = PT_UpdateLangs($value, 'approved', 'goedgekeurd');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_amount_value__your_amount_is_', 'Ongeldige bedragwaarde, uw bedrag is:');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_friend', 'Vriend toevoegen');
    $lang_update_queries[] = PT_UpdateLangs($value, 'unfriend', 'unfriend');
    $lang_update_queries[] = PT_UpdateLangs($value, 'friend_request_sent', 'Vriendschapsverzoek verzonden');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_already_sent_friend_request.', 'U heeft al een aanvraag verzonden.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'success', 'Succes');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirm_request_when_someone_follows_you__', 'Verzoek bevestigen wanneer iemand je volgt?');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirm_request_when_someone_request_friend_you__', 'Verzoek bevestigen wanneer iemand een vriend om jou wil zijn?');
    $lang_update_queries[] = PT_UpdateLangs($value, 'created_a_story_with_you.', 'creëerde een verhaal met jou.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'accepted_your_friend_request.', 'jouw vriendschapsverzoek geaccepteerd.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'declined_your_friend_request.', 'heeft je vriendschapsverzoek afgewezen.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'send_friend_request_to_you.', 'gevraagd om een ​​vriend bij je te zijn.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'friend_requests', 'Vriendschapsverzoeken');
    $lang_update_queries[] = PT_UpdateLangs($value, 'is_now_in_friend_list.', 'staat nu in uw vriendenlijst.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'decline_request', 'Verzoek weigeren');
    $lang_update_queries[] = PT_UpdateLangs($value, 'accept_request', 'Accepteer verzoek');
    $lang_update_queries[] = PT_UpdateLangs($value, 'request_your_friendship.', 'vraag je vriendschap.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'can_not_create_notification', 'kan geen melding maken');
    $lang_update_queries[] = PT_UpdateLangs($value, 'pending_review', 'in afwachting van beoordeling');
    $lang_update_queries[] = PT_UpdateLangs($value, 'the_username_is_blacklisted_and_not_allowed__please_choose_another_username.', 'De gebruikersnaam staat op de zwarte lijst en is niet toegestaan, kies een andere gebruikersnaam.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'the_email_provider_is_blacklisted_and_not_allowed__please_choose_another_email_provider.', 'De e-mailprovider staat op de zwarte lijst en is niet toegestaan. Kies een andere e-mailprovider.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'latest__0__users.', 'Laatste {0} gebruikers.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_reach_to_limit_of_media_uploads.', 'Je hebt de limiet van media-uploads bereikt.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'email_sent_to', 'Email verzonden naar');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_sending_emails', 'Fout tijdens het verzenden van e-mails');
    $lang_update_queries[] = PT_UpdateLangs($value, 'under_review', 'Wordt beoordeeld');
        } else if ($value == 'french') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'manage_sessions', 'Manage Sessions');
    $lang_update_queries[] = PT_UpdateLangs($value, 'platform', 'Plate-forme');
    $lang_update_queries[] = PT_UpdateLangs($value, 'last_seen', 'Vu pour la dernière fois');
    $lang_update_queries[] = PT_UpdateLangs($value, 'os', 'OS');
    $lang_update_queries[] = PT_UpdateLangs($value, 'browser', 'Navigateur');
    $lang_update_queries[] = PT_UpdateLangs($value, 'action', 'Action');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_deleting_session__please_try_again_later.', 'Erreur lors de la suppression de la session, veuillez réessayer plus tard.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'session_deleted_successfully.', 'La session a été supprimée avec succès.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'two-factor_authentication', 'Authentification à deux facteurs');
    $lang_update_queries[] = PT_UpdateLangs($value, 'turn_on_2-step_login_to_level-up_your_account_s_security__once_turned_on__you_ll_use_both_your_password_and_a_6-digit_security_code_sent_to_your_phone_or_email_to_log_in.', 'Activez la connexion en deux étapes pour augmenter votre compte');
    $lang_update_queries[] = PT_UpdateLangs($value, 'two-factor_authentication_data_saved_successfully.', 'Les données d&#39;authentification à deux facteurs ont bien été enregistrées.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'a_confirmation_email_has_been_sent.', 'Un e-mail de confirmation a été envoyé.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_an_email_that_contains_the_confirmation_code_to_enable_two-factor_authentication.', 'Nous avons envoyé un e-mail contenant le code de confirmation pour activer l&#39;authentification à deux facteurs.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirmation_code', 'Confirmation code');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_check_your_details.', 'S&#39;il vous plaît vérifier vos informations.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_e-mail_has_been_successfully_verified.', 'Votre e-mail a été vérifié avec succès.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number_should_be_as_this_format___90..', 'Le numéro de téléphone doit être au format suivant: 90 ..');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_phone_number_and_e-mail_have_been_successfully_verified.', 'Votre numéro de téléphone et votre e-mail ont été vérifiés avec succès.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'unusual_login', 'Connexion inhabituelle');
    $lang_update_queries[] = PT_UpdateLangs($value, 'to_log_in__you_need_to_verify_your_identity.', 'Pour vous connecter, vous devez vérifier votre identité.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_you_the_confirmation_code_to_your_phone_and_to_your_email_address.', 'Nous vous avons envoyé le code de confirmation sur votre téléphone et sur votre adresse e-mail.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_you_the_confirmation_code_to_your_email_address.', 'Nous vous avons envoyé le code de confirmation à votre adresse e-mail.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_confirmation_code.', 'Veuillez saisir le code de confirmation.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'something_went_wrong__please_try_again_later.', 'Quelque chose c&#39;est mal passé. Merci d&#39;essayer plus tard.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'wrong_confirmation_code.', 'Code de confirmation incorrect.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_login__please_try_again_later.', 'Erreur lors de la connexion, veuillez réessayer plus tard.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_user_id', 'Identifiant invalide');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_confirmation_code', 'code de confirmation invalide');
    $lang_update_queries[] = PT_UpdateLangs($value, 'find_potential_matches_by_country', 'Trouver des correspondances potentielles par pays');
    $lang_update_queries[] = PT_UpdateLangs($value, 'manage_notifications', 'Manage Notifications');
    $lang_update_queries[] = PT_UpdateLangs($value, 'custom_field', 'Champ personnalisé');
    $lang_update_queries[] = PT_UpdateLangs($value, 'food', 'nourriture');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_media', 'Ajouter des médias');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_video', 'Ajouter une vidéo');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_photo', 'Ajouter une photo');
    $lang_update_queries[] = PT_UpdateLangs($value, 'upload', 'Télécharger');
    $lang_update_queries[] = PT_UpdateLangs($value, 'video_title', 'titre de la vidéo');
    $lang_update_queries[] = PT_UpdateLangs($value, 'public', 'Public');
    $lang_update_queries[] = PT_UpdateLangs($value, 'private', 'Privé');
    $lang_update_queries[] = PT_UpdateLangs($value, 'thumbnail', 'La vignette');
    $lang_update_queries[] = PT_UpdateLangs($value, 'my_affiliates', 'Mes affiliés');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_affiliate_link_is', 'Votre lien d&#39;affiliation est');
    $lang_update_queries[] = PT_UpdateLangs($value, 'my_balance', 'Mon solde');
    $lang_update_queries[] = PT_UpdateLangs($value, 'earn_up_to', 'Gagnez jusqu&#39;à');
    $lang_update_queries[] = PT_UpdateLangs($value, 'for_each_user_your_refer_to_us__', 'pour chaque utilisateur vous nous référez!');
    $lang_update_queries[] = PT_UpdateLangs($value, 'joined', 'rejoint');
    $lang_update_queries[] = PT_UpdateLangs($value, 'payments', 'Paiements');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_balance_is', 'Votre équilibre est');
    $lang_update_queries[] = PT_UpdateLangs($value, '__minimum_withdrawal_request_is', ', la demande de retrait minimum est');
    $lang_update_queries[] = PT_UpdateLangs($value, 'paypal_email', 'PayPal email');
    $lang_update_queries[] = PT_UpdateLangs($value, 'request_withdrawal', 'Demande de retrait');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_request_has_been_sent__you__039_ll_receive_an_email_regarding_the_payment_details_soon.', 'Votre demande a été envoyée, vous');
    $lang_update_queries[] = PT_UpdateLangs($value, 'requested', 'demandé');
    $lang_update_queries[] = PT_UpdateLangs($value, 'payment_history', 'Historique de paiement');
    $lang_update_queries[] = PT_UpdateLangs($value, 'approved', 'approuvé');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_amount_value__your_amount_is_', 'Valeur de montant non valide, votre montant est:');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_friend', 'Ajouter un ami');
    $lang_update_queries[] = PT_UpdateLangs($value, 'unfriend', 'UnFriend');
    $lang_update_queries[] = PT_UpdateLangs($value, 'friend_request_sent', 'Demande d&#39;ami envoyée');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_already_sent_friend_request.', 'Vous avez déjà envoyé une demande.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'success', 'Succès');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirm_request_when_someone_follows_you__', 'Confirmer la demande lorsque quelqu&#39;un vous suit?');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirm_request_when_someone_request_friend_you__', 'Confirmer la demande lorsque quelqu&#39;un demande à devenir ami avec vous?');
    $lang_update_queries[] = PT_UpdateLangs($value, 'created_a_story_with_you.', 'créé une histoire avec vous.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'accepted_your_friend_request.', 'accepté votre demande d&#39;ami.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'declined_your_friend_request.', 'a refusé votre demande d&#39;ami.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'send_friend_request_to_you.', 'a demandé à être un ami avec vous.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'friend_requests', 'Demandes d&#39;ami');
    $lang_update_queries[] = PT_UpdateLangs($value, 'is_now_in_friend_list.', 'est maintenant dans votre liste d&#39;amis.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'decline_request', 'Demande de refus');
    $lang_update_queries[] = PT_UpdateLangs($value, 'accept_request', 'Accepter la requête');
    $lang_update_queries[] = PT_UpdateLangs($value, 'request_your_friendship.', 'demandez votre amitié.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'can_not_create_notification', 'ne peut pas créer de notification');
    $lang_update_queries[] = PT_UpdateLangs($value, 'pending_review', 'en attendant l&#39;examen');
    $lang_update_queries[] = PT_UpdateLangs($value, 'the_username_is_blacklisted_and_not_allowed__please_choose_another_username.', 'Le nom d&#39;utilisateur est sur liste noire et n&#39;est pas autorisé, veuillez choisir un autre nom d&#39;utilisateur.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'the_email_provider_is_blacklisted_and_not_allowed__please_choose_another_email_provider.', 'Le fournisseur de messagerie est sur liste noire et n&#39;est pas autorisé, veuillez choisir un autre fournisseur de messagerie.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'latest__0__users.', 'Derniers {0} utilisateurs.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_reach_to_limit_of_media_uploads.', 'Vous avez atteint la limite des téléchargements multimédias.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'email_sent_to', 'Email envoyé à');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_sending_emails', 'Erreur lors de l&#39;envoi d&#39;e-mails');
    $lang_update_queries[] = PT_UpdateLangs($value, 'under_review', 'À l&#39;étude');
        } else if ($value == 'german') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'manage_sessions', 'Sitzungen verwalten');
    $lang_update_queries[] = PT_UpdateLangs($value, 'platform', 'Plattform');
    $lang_update_queries[] = PT_UpdateLangs($value, 'last_seen', 'Zuletzt gesehen');
    $lang_update_queries[] = PT_UpdateLangs($value, 'os', 'Betriebssystem');
    $lang_update_queries[] = PT_UpdateLangs($value, 'browser', 'Browser');
    $lang_update_queries[] = PT_UpdateLangs($value, 'action', 'Aktion');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_deleting_session__please_try_again_later.', 'Fehler beim Löschen der Sitzung, versuchen Sie es später erneut.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'session_deleted_successfully.', 'Sitzung wurde erfolgreich gelöscht.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'two-factor_authentication', 'Zwei-Faktor-Authentifizierung');
    $lang_update_queries[] = PT_UpdateLangs($value, 'turn_on_2-step_login_to_level-up_your_account_s_security__once_turned_on__you_ll_use_both_your_password_and_a_6-digit_security_code_sent_to_your_phone_or_email_to_log_in.', 'Aktivieren Sie die zweistufige Anmeldung, um Ihr Konto zu verbessern');
    $lang_update_queries[] = PT_UpdateLangs($value, 'two-factor_authentication_data_saved_successfully.', 'Zwei-Faktor-Authentifizierungsdaten wurden erfolgreich gespeichert.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'a_confirmation_email_has_been_sent.', 'Eine Bestätigungs-E-Mail wurde gesendet.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_an_email_that_contains_the_confirmation_code_to_enable_two-factor_authentication.', 'Wir haben eine E-Mail gesendet, die den Bestätigungscode enthält, um die Zwei-Faktor-Authentifizierung zu aktivieren.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirmation_code', 'Bestätigungscode');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_check_your_details.', 'Bitte überprüfe deine Details.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_e-mail_has_been_successfully_verified.', 'Ihre E-Mail wurde erfolgreich überprüft.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number_should_be_as_this_format___90..', 'Die Telefonnummer sollte das folgende Format haben: 90 ..');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_phone_number_and_e-mail_have_been_successfully_verified.', 'Ihre Telefonnummer und E-Mail-Adresse wurden erfolgreich überprüft.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'unusual_login', 'Ungewöhnliche Anmeldung');
    $lang_update_queries[] = PT_UpdateLangs($value, 'to_log_in__you_need_to_verify_your_identity.', 'Um sich anzumelden, müssen Sie Ihre Identität überprüfen.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_you_the_confirmation_code_to_your_phone_and_to_your_email_address.', 'Wir haben Ihnen den Bestätigungscode an Ihr Telefon und an Ihre E-Mail-Adresse gesendet.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_you_the_confirmation_code_to_your_email_address.', 'Wir haben Ihnen den Bestätigungscode an Ihre E-Mail-Adresse gesendet.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_confirmation_code.', 'Bitte geben Sie den Bestätigungscode ein.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'something_went_wrong__please_try_again_later.', 'Es ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'wrong_confirmation_code.', 'Falscher Bestätigungscode.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_login__please_try_again_later.', 'Fehler beim Anmelden, versuchen Sie es später erneut.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_user_id', 'Ungültige Benutzer-Id');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_confirmation_code', 'ungültiger Bestätigungscode');
    $lang_update_queries[] = PT_UpdateLangs($value, 'find_potential_matches_by_country', 'Finden Sie mögliche Übereinstimmungen nach Land');
    $lang_update_queries[] = PT_UpdateLangs($value, 'manage_notifications', 'Benachrichtigungen verwalten');
    $lang_update_queries[] = PT_UpdateLangs($value, 'custom_field', 'Benutzerdefinierte Feld');
    $lang_update_queries[] = PT_UpdateLangs($value, 'food', 'Essen');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_media', 'Medien hinzufügen');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_video', 'Video hinzufügen');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_photo', 'Foto hinzufügen');
    $lang_update_queries[] = PT_UpdateLangs($value, 'upload', 'Hochladen');
    $lang_update_queries[] = PT_UpdateLangs($value, 'video_title', 'Videotitel');
    $lang_update_queries[] = PT_UpdateLangs($value, 'public', 'Öffentlichkeit');
    $lang_update_queries[] = PT_UpdateLangs($value, 'private', 'Privat');
    $lang_update_queries[] = PT_UpdateLangs($value, 'thumbnail', 'Miniaturansicht');
    $lang_update_queries[] = PT_UpdateLangs($value, 'my_affiliates', 'Meine Partner');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_affiliate_link_is', 'Ihr Affiliate-Link ist');
    $lang_update_queries[] = PT_UpdateLangs($value, 'my_balance', 'Mein Gleichgewicht');
    $lang_update_queries[] = PT_UpdateLangs($value, 'earn_up_to', 'Verdienen bis zu');
    $lang_update_queries[] = PT_UpdateLangs($value, 'for_each_user_your_refer_to_us__', 'Für jeden Benutzer verweisen Sie auf uns!');
    $lang_update_queries[] = PT_UpdateLangs($value, 'joined', 'beigetreten');
    $lang_update_queries[] = PT_UpdateLangs($value, 'payments', 'Zahlungen');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_balance_is', 'Ihr Gleichgewicht ist');
    $lang_update_queries[] = PT_UpdateLangs($value, '__minimum_withdrawal_request_is', ', Mindestauszahlungsanforderung ist');
    $lang_update_queries[] = PT_UpdateLangs($value, 'paypal_email', 'Paypal Email');
    $lang_update_queries[] = PT_UpdateLangs($value, 'request_withdrawal', 'Rücknahme beantragen');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_request_has_been_sent__you__039_ll_receive_an_email_regarding_the_payment_details_soon.', 'Ihre Anfrage wurde gesendet, Sie');
    $lang_update_queries[] = PT_UpdateLangs($value, 'requested', 'angefordert');
    $lang_update_queries[] = PT_UpdateLangs($value, 'payment_history', 'Zahlungshistorie');
    $lang_update_queries[] = PT_UpdateLangs($value, 'approved', 'genehmigt');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_amount_value__your_amount_is_', 'Ungültiger Betragswert, Ihr Betrag ist:');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_friend', 'Freund hinzufügen');
    $lang_update_queries[] = PT_UpdateLangs($value, 'unfriend', 'Unfreundlich');
    $lang_update_queries[] = PT_UpdateLangs($value, 'friend_request_sent', 'Freundschaftsanfrage gesendet');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_already_sent_friend_request.', 'Sie haben bereits eine Anfrage gesendet.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'success', 'Erfolg');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirm_request_when_someone_follows_you__', 'Anfrage bestätigen, wenn Ihnen jemand folgt?');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirm_request_when_someone_request_friend_you__', 'Anfrage bestätigen, wenn jemand darum bittet, mit Ihnen befreundet zu sein?');
    $lang_update_queries[] = PT_UpdateLangs($value, 'created_a_story_with_you.', 'hat mit dir eine Geschichte erstellt.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'accepted_your_friend_request.', 'hat deine Freundschaftsanfrage akzeptiert.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'declined_your_friend_request.', 'lehnte Ihre Freundschaftsanfrage ab.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'send_friend_request_to_you.', 'gebeten, mit dir befreundet zu sein.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'friend_requests', 'Freundschaftsanfragen');
    $lang_update_queries[] = PT_UpdateLangs($value, 'is_now_in_friend_list.', 'ist jetzt in deiner Freundesliste.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'decline_request', 'Anfrage ablehnen');
    $lang_update_queries[] = PT_UpdateLangs($value, 'accept_request', 'Anfrage annehmen');
    $lang_update_queries[] = PT_UpdateLangs($value, 'request_your_friendship.', 'Bitte um deine Freundschaft.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'can_not_create_notification', 'kann keine Benachrichtigung erstellen');
    $lang_update_queries[] = PT_UpdateLangs($value, 'pending_review', 'ausstehende Bewertung');
    $lang_update_queries[] = PT_UpdateLangs($value, 'the_username_is_blacklisted_and_not_allowed__please_choose_another_username.', 'Der Benutzername ist auf der schwarzen Liste und nicht erlaubt. Bitte wählen Sie einen anderen Benutzernamen.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'the_email_provider_is_blacklisted_and_not_allowed__please_choose_another_email_provider.', 'Der E-Mail-Anbieter ist auf der schwarzen Liste und nicht zulässig. Bitte wählen Sie einen anderen E-Mail-Anbieter.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'latest__0__users.', 'Neueste {0} Benutzer.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_reach_to_limit_of_media_uploads.', 'Sie haben das Limit für das Hochladen von Medien erreicht.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'email_sent_to', 'Email an gesendet');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_sending_emails', 'Fehler beim Senden von E-Mails');
    $lang_update_queries[] = PT_UpdateLangs($value, 'under_review', 'Wird überprüft');
        } else if ($value == 'italian') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'manage_sessions', 'Gestisci sessioni');
    $lang_update_queries[] = PT_UpdateLangs($value, 'platform', 'piattaforma');
    $lang_update_queries[] = PT_UpdateLangs($value, 'last_seen', 'Ultima visualizzazione');
    $lang_update_queries[] = PT_UpdateLangs($value, 'os', 'IL');
    $lang_update_queries[] = PT_UpdateLangs($value, 'browser', '');
    $lang_update_queries[] = PT_UpdateLangs($value, 'action', 'Azione');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_deleting_session__please_try_again_later.', 'Errore durante l&#39;eliminazione della sessione, riprovare più tardi.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'session_deleted_successfully.', 'La sessione è stata eliminata correttamente.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'two-factor_authentication', 'Autenticazione a due fattori');
    $lang_update_queries[] = PT_UpdateLangs($value, 'turn_on_2-step_login_to_level-up_your_account_s_security__once_turned_on__you_ll_use_both_your_password_and_a_6-digit_security_code_sent_to_your_phone_or_email_to_log_in.', 'Attiva l&#39;accesso in 2 passaggi per salire di livello nel tuo account');
    $lang_update_queries[] = PT_UpdateLangs($value, 'two-factor_authentication_data_saved_successfully.', 'Dati di autenticazione a due fattori salvati correttamente.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'a_confirmation_email_has_been_sent.', 'È stata inviata una email di conferma.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_an_email_that_contains_the_confirmation_code_to_enable_two-factor_authentication.', 'Abbiamo inviato un&#39;email contenente il codice di conferma per abilitare l&#39;autenticazione a due fattori.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirmation_code', 'Codice di conferma');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_check_your_details.', 'Si prega di controllare i dettagli.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_e-mail_has_been_successfully_verified.', 'La tua e-mail è stata verificata correttamente.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number_should_be_as_this_format___90..', 'Il numero di telefono dovrebbe essere come questo formato: 90 ..');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_phone_number_and_e-mail_have_been_successfully_verified.', 'Il tuo numero di telefono ed e-mail sono stati verificati correttamente.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'unusual_login', 'Login insolito');
    $lang_update_queries[] = PT_UpdateLangs($value, 'to_log_in__you_need_to_verify_your_identity.', 'Per accedere, è necessario verificare la tua identità.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_you_the_confirmation_code_to_your_phone_and_to_your_email_address.', 'Ti abbiamo inviato il codice di conferma sul tuo telefono e sul tuo indirizzo email.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_you_the_confirmation_code_to_your_email_address.', 'Ti abbiamo inviato il codice di conferma al tuo indirizzo email.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_confirmation_code.', 'Inserisci il codice di conferma');
    $lang_update_queries[] = PT_UpdateLangs($value, 'something_went_wrong__please_try_again_later.', 'Qualcosa è andato storto, per favore riprova più tardi.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'wrong_confirmation_code.', 'Codice di conferma errato.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_login__please_try_again_later.', 'Errore durante l&#39;accesso, riprovare più tardi.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_user_id', 'ID utente non valido');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_confirmation_code', 'Codice di conferma non valido');
    $lang_update_queries[] = PT_UpdateLangs($value, 'find_potential_matches_by_country', 'Trova potenziali corrispondenze per paese');
    $lang_update_queries[] = PT_UpdateLangs($value, 'manage_notifications', 'Gestisci notifiche');
    $lang_update_queries[] = PT_UpdateLangs($value, 'custom_field', 'Campo personalizzato');
    $lang_update_queries[] = PT_UpdateLangs($value, 'food', '');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_media', 'Add Media');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_video', 'Aggiungi video');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_photo', 'Aggiungi foto');
    $lang_update_queries[] = PT_UpdateLangs($value, 'upload', 'Caricare');
    $lang_update_queries[] = PT_UpdateLangs($value, 'video_title', 'Titolo del video');
    $lang_update_queries[] = PT_UpdateLangs($value, 'public', 'Pubblico');
    $lang_update_queries[] = PT_UpdateLangs($value, 'private', 'Privato');
    $lang_update_queries[] = PT_UpdateLangs($value, 'thumbnail', '');
    $lang_update_queries[] = PT_UpdateLangs($value, 'my_affiliates', 'I miei affiliati');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_affiliate_link_is', 'Il tuo link di affiliazione è');
    $lang_update_queries[] = PT_UpdateLangs($value, 'my_balance', 'Il mio saldo');
    $lang_update_queries[] = PT_UpdateLangs($value, 'earn_up_to', 'Guadagna fino a');
    $lang_update_queries[] = PT_UpdateLangs($value, 'for_each_user_your_refer_to_us__', 'per ogni utente fai riferimento a noi!');
    $lang_update_queries[] = PT_UpdateLangs($value, 'joined', 'Iscritto');
    $lang_update_queries[] = PT_UpdateLangs($value, 'payments', 'pagamenti');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_balance_is', 'Il tuo saldo è');
    $lang_update_queries[] = PT_UpdateLangs($value, '__minimum_withdrawal_request_is', ', la richiesta di prelievo minima è');
    $lang_update_queries[] = PT_UpdateLangs($value, 'paypal_email', 'Email PayPal');
    $lang_update_queries[] = PT_UpdateLangs($value, 'request_withdrawal', 'Richiedi il ritiro');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_request_has_been_sent__you__039_ll_receive_an_email_regarding_the_payment_details_soon.', 'La tua richiesta è stata inviata, tu');
    $lang_update_queries[] = PT_UpdateLangs($value, 'requested', 'richiesto');
    $lang_update_queries[] = PT_UpdateLangs($value, 'payment_history', 'Storico dei pagamenti');
    $lang_update_queries[] = PT_UpdateLangs($value, 'approved', 'approvato');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_amount_value__your_amount_is_', 'Valore dell&#39;importo non valido, l&#39;importo è:');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_friend', 'Aggiungi amico');
    $lang_update_queries[] = PT_UpdateLangs($value, 'unfriend', 'unfriend');
    $lang_update_queries[] = PT_UpdateLangs($value, 'friend_request_sent', 'Richiesta di amicizia inviata');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_already_sent_friend_request.', 'Hai già inviato una richiesta.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'success', 'Successo');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirm_request_when_someone_follows_you__', 'Conferma la richiesta quando qualcuno ti segue?');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirm_request_when_someone_request_friend_you__', 'Conferma la richiesta quando qualcuno richiede di essere amico di te?');
    $lang_update_queries[] = PT_UpdateLangs($value, 'created_a_story_with_you.', 'creato una storia con te.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'accepted_your_friend_request.', 'ha accettato la tua richiesta di amicizia.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'declined_your_friend_request.', 'ha rifiutato la tua richiesta di amicizia.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'send_friend_request_to_you.', 'richiesto di essere un amico con te.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'friend_requests', 'Richieste di amicizia');
    $lang_update_queries[] = PT_UpdateLangs($value, 'is_now_in_friend_list.', 'è ora nella tua lista amici.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'decline_request', 'Rifiuta richiesta');
    $lang_update_queries[] = PT_UpdateLangs($value, 'accept_request', 'Richiesta accettata');
    $lang_update_queries[] = PT_UpdateLangs($value, 'request_your_friendship.', 'richiedi la tua amicizia.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'can_not_create_notification', 'impossibile creare la notifica');
    $lang_update_queries[] = PT_UpdateLangs($value, 'pending_review', 'Revisione in atto');
    $lang_update_queries[] = PT_UpdateLangs($value, 'the_username_is_blacklisted_and_not_allowed__please_choose_another_username.', 'Il nome utente è nella lista nera e non è consentito, scegli un altro nome utente.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'the_email_provider_is_blacklisted_and_not_allowed__please_choose_another_email_provider.', 'Il provider di posta elettronica è nella lista nera e non è consentito, scegli un altro provider di posta elettronica.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'latest__0__users.', 'Ultimi {0} utenti.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_reach_to_limit_of_media_uploads.', 'Hai raggiunto il limite di caricamenti multimediali.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'email_sent_to', 'Email inviata a');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_sending_emails', 'Errore durante l&#39;invio di e-mail');
    $lang_update_queries[] = PT_UpdateLangs($value, 'under_review', 'In corso di revisione');
        } else if ($value == 'portuguese') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'manage_sessions', 'Gerenciar sessões');
    $lang_update_queries[] = PT_UpdateLangs($value, 'platform', 'Plataforma');
    $lang_update_queries[] = PT_UpdateLangs($value, 'last_seen', 'Visto pela última vez');
    $lang_update_queries[] = PT_UpdateLangs($value, 'os', 'OS');
    $lang_update_queries[] = PT_UpdateLangs($value, 'browser', '');
    $lang_update_queries[] = PT_UpdateLangs($value, 'action', 'Açao');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_deleting_session__please_try_again_later.', 'Erro ao excluir a sessão, tente novamente mais tarde.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'session_deleted_successfully.', 'A sessão foi excluída com sucesso.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'two-factor_authentication', 'Autenticação de dois fatores');
    $lang_update_queries[] = PT_UpdateLangs($value, 'turn_on_2-step_login_to_level-up_your_account_s_security__once_turned_on__you_ll_use_both_your_password_and_a_6-digit_security_code_sent_to_your_phone_or_email_to_log_in.', 'Ative o login em duas etapas para aumentar o nível da sua conta');
    $lang_update_queries[] = PT_UpdateLangs($value, 'two-factor_authentication_data_saved_successfully.', 'Dados de autenticação de dois fatores salvos com sucesso.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'a_confirmation_email_has_been_sent.', 'Um email de confirmação foi enviado.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_an_email_that_contains_the_confirmation_code_to_enable_two-factor_authentication.', 'Enviamos um email que contém o código de confirmação para ativar a autenticação de dois fatores.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirmation_code', 'Código de confirmação');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_check_your_details.', 'Por favor, verifique seus detalhes.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_e-mail_has_been_successfully_verified.', 'Seu email foi verificado com sucesso.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number_should_be_as_this_format___90..', 'O número de telefone deve estar no seguinte formato: 90 ..');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_phone_number_and_e-mail_have_been_successfully_verified.', 'Seu número de telefone e email foram verificados com sucesso.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'unusual_login', 'Login incomum');
    $lang_update_queries[] = PT_UpdateLangs($value, 'to_log_in__you_need_to_verify_your_identity.', 'Para fazer login, você precisa verificar sua identidade.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_you_the_confirmation_code_to_your_phone_and_to_your_email_address.', 'Nós enviamos o código de confirmação para o seu telefone e seu endereço de e-mail.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_you_the_confirmation_code_to_your_email_address.', 'Enviámos o código de confirmação para o seu endereço de email.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_confirmation_code.', 'Digite o código de confirmação.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'something_went_wrong__please_try_again_later.', 'Ocorreu um erro. Tente novamente mais tarde.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'wrong_confirmation_code.', 'Código de confirmação incorreto.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_login__please_try_again_later.', 'Erro durante o login, tente novamente mais tarde.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_user_id', 'ID de usuário inválido');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_confirmation_code', 'Código de confirmação inválido');
    $lang_update_queries[] = PT_UpdateLangs($value, 'find_potential_matches_by_country', 'Encontre possíveis correspondências por país');
    $lang_update_queries[] = PT_UpdateLangs($value, 'manage_notifications', 'Gerenciar notificações');
    $lang_update_queries[] = PT_UpdateLangs($value, 'custom_field', 'Campo customizado');
    $lang_update_queries[] = PT_UpdateLangs($value, 'food', '');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_media', 'Adicionar mídia');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_video', 'Adicionar vídeo');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_photo', 'Adicionar foto');
    $lang_update_queries[] = PT_UpdateLangs($value, 'upload', 'Envio');
    $lang_update_queries[] = PT_UpdateLangs($value, 'video_title', 'Título do vídeo');
    $lang_update_queries[] = PT_UpdateLangs($value, 'public', 'Público');
    $lang_update_queries[] = PT_UpdateLangs($value, 'private', 'Privado');
    $lang_update_queries[] = PT_UpdateLangs($value, 'thumbnail', '');
    $lang_update_queries[] = PT_UpdateLangs($value, 'my_affiliates', 'Meus afiliados');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_affiliate_link_is', 'O seu link de afiliado é');
    $lang_update_queries[] = PT_UpdateLangs($value, 'my_balance', 'Meu saldo');
    $lang_update_queries[] = PT_UpdateLangs($value, 'earn_up_to', 'Ganhe até');
    $lang_update_queries[] = PT_UpdateLangs($value, 'for_each_user_your_refer_to_us__', 'para cada usuário, consulte-nos!');
    $lang_update_queries[] = PT_UpdateLangs($value, 'joined', 'juntou');
    $lang_update_queries[] = PT_UpdateLangs($value, 'payments', 'Pagamentos');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_balance_is', 'Seu saldo é');
    $lang_update_queries[] = PT_UpdateLangs($value, '__minimum_withdrawal_request_is', ', o pedido mínimo de retirada é');
    $lang_update_queries[] = PT_UpdateLangs($value, 'paypal_email', 'Email do Paypal');
    $lang_update_queries[] = PT_UpdateLangs($value, 'request_withdrawal', 'Solicitar retirada');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_request_has_been_sent__you__039_ll_receive_an_email_regarding_the_payment_details_soon.', 'Sua solicitação foi enviada, você');
    $lang_update_queries[] = PT_UpdateLangs($value, 'requested', 'Requeridos');
    $lang_update_queries[] = PT_UpdateLangs($value, 'payment_history', 'Histórico de pagamento');
    $lang_update_queries[] = PT_UpdateLangs($value, 'approved', 'aprovado');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_amount_value__your_amount_is_', 'Valor de valor inválido, seu valor é:');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_friend', 'Adicionar amigo');
    $lang_update_queries[] = PT_UpdateLangs($value, 'unfriend', 'UnFriend');
    $lang_update_queries[] = PT_UpdateLangs($value, 'friend_request_sent', 'Pedido de amizade enviado');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_already_sent_friend_request.', 'Você já enviou uma solicitação.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'success', 'Sucesso');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirm_request_when_someone_follows_you__', 'Confirmar solicitação quando alguém segue você?');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirm_request_when_someone_request_friend_you__', 'Confirmar solicitação quando alguém solicitar para ser seu amigo?');
    $lang_update_queries[] = PT_UpdateLangs($value, 'created_a_story_with_you.', 'criou uma história com você.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'accepted_your_friend_request.', 'aceitou seu pedido de amizade.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'declined_your_friend_request.', 'recusou o seu pedido de amizade.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'send_friend_request_to_you.', 'solicitado para ser um amigo com você.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'friend_requests', 'Pedidos de amizade');
    $lang_update_queries[] = PT_UpdateLangs($value, 'is_now_in_friend_list.', 'está agora na sua lista de amigos.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'decline_request', 'Recusar solicitação');
    $lang_update_queries[] = PT_UpdateLangs($value, 'accept_request', 'Aceitar pedido');
    $lang_update_queries[] = PT_UpdateLangs($value, 'request_your_friendship.', 'solicite sua amizade.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'can_not_create_notification', 'não pode criar notificação');
    $lang_update_queries[] = PT_UpdateLangs($value, 'pending_review', 'revisão pendente');
    $lang_update_queries[] = PT_UpdateLangs($value, 'the_username_is_blacklisted_and_not_allowed__please_choose_another_username.', 'O nome de usuário está na lista negra e não é permitido. Escolha outro nome de usuário.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'the_email_provider_is_blacklisted_and_not_allowed__please_choose_another_email_provider.', 'O provedor de email está na lista negra e não é permitido. Escolha outro provedor de email.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'latest__0__users.', 'Últimos {0} usuários.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_reach_to_limit_of_media_uploads.', 'Você atingiu o limite de uploads de mídia.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'email_sent_to', 'Email enviado para');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_sending_emails', 'Erro ao enviar e-mails');
    $lang_update_queries[] = PT_UpdateLangs($value, 'under_review', 'Sob revisão');
        } else if ($value == 'russian') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'manage_sessions', 'Управление сессиями');
    $lang_update_queries[] = PT_UpdateLangs($value, 'platform', 'Платформа');
    $lang_update_queries[] = PT_UpdateLangs($value, 'last_seen', 'В последний раз видел');
    $lang_update_queries[] = PT_UpdateLangs($value, 'os', '');
    $lang_update_queries[] = PT_UpdateLangs($value, 'browser', 'браузер');
    $lang_update_queries[] = PT_UpdateLangs($value, 'action', 'действие');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_deleting_session__please_try_again_later.', 'Ошибка при удалении сеанса, повторите попытку позже.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'session_deleted_successfully.', 'Сессия была успешно удалена.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'two-factor_authentication', 'Двухфакторная аутентификация');
    $lang_update_queries[] = PT_UpdateLangs($value, 'turn_on_2-step_login_to_level-up_your_account_s_security__once_turned_on__you_ll_use_both_your_password_and_a_6-digit_security_code_sent_to_your_phone_or_email_to_log_in.', 'Включите двухэтапный вход, чтобы повысить уровень своей учетной записи');
    $lang_update_queries[] = PT_UpdateLangs($value, 'two-factor_authentication_data_saved_successfully.', 'Данные двухфакторной аутентификации успешно сохранены.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'a_confirmation_email_has_been_sent.', 'Письмо с подтверждением было отправлено.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_an_email_that_contains_the_confirmation_code_to_enable_two-factor_authentication.', 'Мы отправили электронное письмо с кодом подтверждения для включения двухфакторной аутентификации.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirmation_code', 'Код подтверждения');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_check_your_details.', 'Пожалуйста, проверьте ваши данные.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_e-mail_has_been_successfully_verified.', 'Ваш адрес электронной почты был успешно подтвержден.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number_should_be_as_this_format___90..', 'Номер телефона должен быть в таком формате: 90 ..');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_phone_number_and_e-mail_have_been_successfully_verified.', 'Ваш номер телефона и адрес электронной почты были успешно проверены.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'unusual_login', 'Необычный логин');
    $lang_update_queries[] = PT_UpdateLangs($value, 'to_log_in__you_need_to_verify_your_identity.', 'Для входа необходимо подтвердить свою личность.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_you_the_confirmation_code_to_your_phone_and_to_your_email_address.', 'Мы отправили вам код подтверждения на ваш телефон и на ваш адрес электронной почты.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_you_the_confirmation_code_to_your_email_address.', 'Мы отправили вам код подтверждения на ваш адрес электронной почты.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_confirmation_code.', 'Пожалуйста, введите код подтверждения.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'something_went_wrong__please_try_again_later.', 'Что-то пошло не так. Пожалуйста, повторите попытку позже.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'wrong_confirmation_code.', 'Неверный код подтверждения.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_login__please_try_again_later.', 'Ошибка при входе, повторите попытку позже.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_user_id', 'Неверный идентификатор пользователя');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_confirmation_code', 'неверный код подтверждения');
    $lang_update_queries[] = PT_UpdateLangs($value, 'find_potential_matches_by_country', 'Найти потенциальные совпадения по стране');
    $lang_update_queries[] = PT_UpdateLangs($value, 'manage_notifications', 'Управление уведомлениями');
    $lang_update_queries[] = PT_UpdateLangs($value, 'custom_field', 'Пользовательское поле');
    $lang_update_queries[] = PT_UpdateLangs($value, 'food', 'питание');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_media', 'Добавить медиа');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_video', 'Добавить видео');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_photo', 'Добавить фото');
    $lang_update_queries[] = PT_UpdateLangs($value, 'upload', 'Загрузить');
    $lang_update_queries[] = PT_UpdateLangs($value, 'video_title', 'Название видео');
    $lang_update_queries[] = PT_UpdateLangs($value, 'public', 'общественного');
    $lang_update_queries[] = PT_UpdateLangs($value, 'private', 'Частный');
    $lang_update_queries[] = PT_UpdateLangs($value, 'thumbnail', 'Thumbnail');
    $lang_update_queries[] = PT_UpdateLangs($value, 'my_affiliates', 'Мои филиалы');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_affiliate_link_is', 'Ваша партнерская ссылка');
    $lang_update_queries[] = PT_UpdateLangs($value, 'my_balance', 'Мой баланс');
    $lang_update_queries[] = PT_UpdateLangs($value, 'earn_up_to', 'Заработай до');
    $lang_update_queries[] = PT_UpdateLangs($value, 'for_each_user_your_refer_to_us__', 'для каждого пользователя вы обращаетесь к нам!');
    $lang_update_queries[] = PT_UpdateLangs($value, 'joined', 'присоединился');
    $lang_update_queries[] = PT_UpdateLangs($value, 'payments', 'платежи');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_balance_is', 'Ваш баланс');
    $lang_update_queries[] = PT_UpdateLangs($value, '__minimum_withdrawal_request_is', 'минимальный запрос на снятие средств');
    $lang_update_queries[] = PT_UpdateLangs($value, 'paypal_email', 'Электронная почта PayPal');
    $lang_update_queries[] = PT_UpdateLangs($value, 'request_withdrawal', 'Запрос на снятие');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_request_has_been_sent__you__039_ll_receive_an_email_regarding_the_payment_details_soon.', 'Ваш запрос был отправлен, вы');
    $lang_update_queries[] = PT_UpdateLangs($value, 'requested', 'запрошенный');
    $lang_update_queries[] = PT_UpdateLangs($value, 'payment_history', 'История платежей');
    $lang_update_queries[] = PT_UpdateLangs($value, 'approved', 'одобренный');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_amount_value__your_amount_is_', 'Неверное значение суммы, ваша сумма:');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_friend', 'Добавить друга');
    $lang_update_queries[] = PT_UpdateLangs($value, 'unfriend', 'Unfriend');
    $lang_update_queries[] = PT_UpdateLangs($value, 'friend_request_sent', 'Запрос на добавление в друзья');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_already_sent_friend_request.', 'Вы уже отправили запрос.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'success', 'успех');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirm_request_when_someone_follows_you__', 'Подтвердить запрос, когда кто-то следует за вами?');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirm_request_when_someone_request_friend_you__', 'Подтвердите запрос, когда кто-то запросит дружить с вами?');
    $lang_update_queries[] = PT_UpdateLangs($value, 'created_a_story_with_you.', 'создал историю с вами.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'accepted_your_friend_request.', 'принял ваш запрос на добавление в друзья.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'declined_your_friend_request.', 'отклонил ваш запрос на добавление в друзья.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'send_friend_request_to_you.', 'просил дружить с тобой.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'friend_requests', 'Запросы в друзья');
    $lang_update_queries[] = PT_UpdateLangs($value, 'is_now_in_friend_list.', 'сейчас в вашем списке друзей.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'decline_request', 'Отклонить запрос');
    $lang_update_queries[] = PT_UpdateLangs($value, 'accept_request', 'Принять запрос');
    $lang_update_queries[] = PT_UpdateLangs($value, 'request_your_friendship.', 'просить вашей дружбы.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'can_not_create_notification', 'не может создать уведомление');
    $lang_update_queries[] = PT_UpdateLangs($value, 'pending_review', 'ожидает оценки');
    $lang_update_queries[] = PT_UpdateLangs($value, 'the_username_is_blacklisted_and_not_allowed__please_choose_another_username.', 'Имя пользователя занесено в черный список и не допускается, выберите другое имя пользователя.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'the_email_provider_is_blacklisted_and_not_allowed__please_choose_another_email_provider.', 'Поставщик электронной почты находится в черном списке и не допускается, выберите другого поставщика электронной почты.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'latest__0__users.', 'Последние {0} пользователей.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_reach_to_limit_of_media_uploads.', 'Вы достигли лимита загрузки медиафайлов.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'email_sent_to', 'Е-мейл отправлен');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_sending_emails', 'Ошибка при отправке писем');
    $lang_update_queries[] = PT_UpdateLangs($value, 'under_review', 'На рассмотрении');
        } else if ($value == 'spanish') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'manage_sessions', 'Administrar sesiones');
    $lang_update_queries[] = PT_UpdateLangs($value, 'platform', 'Plataforma');
    $lang_update_queries[] = PT_UpdateLangs($value, 'last_seen', 'Ultima vez visto');
    $lang_update_queries[] = PT_UpdateLangs($value, 'os', 'OS');
    $lang_update_queries[] = PT_UpdateLangs($value, 'browser', '');
    $lang_update_queries[] = PT_UpdateLangs($value, 'action', 'Acción');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_deleting_session__please_try_again_later.', 'Error al eliminar sesión, intente nuevamente más tarde.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'session_deleted_successfully.', 'La sesión se ha eliminado con éxito.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'two-factor_authentication', 'Autenticación de dos factores');
    $lang_update_queries[] = PT_UpdateLangs($value, 'turn_on_2-step_login_to_level-up_your_account_s_security__once_turned_on__you_ll_use_both_your_password_and_a_6-digit_security_code_sent_to_your_phone_or_email_to_log_in.', 'Active el inicio de sesión en 2 pasos para subir de nivel su cuenta');
    $lang_update_queries[] = PT_UpdateLangs($value, 'two-factor_authentication_data_saved_successfully.', 'Los datos de autenticación de dos factores se guardaron correctamente.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'a_confirmation_email_has_been_sent.', 'Un correo electrónico de confirmación ha sido enviado.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_an_email_that_contains_the_confirmation_code_to_enable_two-factor_authentication.', 'Hemos enviado un correo electrónico que contiene el código de confirmación para habilitar la autenticación de dos factores.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirmation_code', 'Código de confirmación');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_check_your_details.', 'Por favor comprueba tus detalles.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_e-mail_has_been_successfully_verified.', 'Su correo electrónico ha sido verificado con éxito.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number_should_be_as_this_format___90..', 'El número de teléfono debe tener este formato: 90 ..');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_phone_number_and_e-mail_have_been_successfully_verified.', 'Su número de teléfono y correo electrónico han sido verificados con éxito.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'unusual_login', 'Inicio de sesión inusual');
    $lang_update_queries[] = PT_UpdateLangs($value, 'to_log_in__you_need_to_verify_your_identity.', 'Para iniciar sesión, debe verificar su identidad.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_you_the_confirmation_code_to_your_phone_and_to_your_email_address.', 'Le hemos enviado el código de confirmación a su teléfono y a su dirección de correo electrónico.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_you_the_confirmation_code_to_your_email_address.', 'Le hemos enviado el código de confirmación a su dirección de correo electrónico.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_confirmation_code.', 'Por favor, introduzca el código de confirmación.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'something_went_wrong__please_try_again_later.', 'Algo salió mal, por favor intente nuevamente más tarde.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'wrong_confirmation_code.', 'Código de confirmación incorrecto.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_login__please_try_again_later.', 'Error al iniciar sesión, intente nuevamente más tarde.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_user_id', 'ID de usuario invalido');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_confirmation_code', 'Código de confirmación inválido');
    $lang_update_queries[] = PT_UpdateLangs($value, 'find_potential_matches_by_country', 'Encuentra posibles coincidencias por país');
    $lang_update_queries[] = PT_UpdateLangs($value, 'manage_notifications', 'Administrar notificaciones');
    $lang_update_queries[] = PT_UpdateLangs($value, 'custom_field', 'Campo personalizado');
    $lang_update_queries[] = PT_UpdateLangs($value, 'food', '');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_media', 'Agregar medios');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_video', 'Añadir video');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_photo', 'Añadir foto');
    $lang_update_queries[] = PT_UpdateLangs($value, 'upload', 'Subir');
    $lang_update_queries[] = PT_UpdateLangs($value, 'video_title', 'Titulo del Video');
    $lang_update_queries[] = PT_UpdateLangs($value, 'public', 'Público');
    $lang_update_queries[] = PT_UpdateLangs($value, 'private', 'Privado');
    $lang_update_queries[] = PT_UpdateLangs($value, 'thumbnail', '');
    $lang_update_queries[] = PT_UpdateLangs($value, 'my_affiliates', 'Mis afiliados');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_affiliate_link_is', 'Su enlace de afiliado es');
    $lang_update_queries[] = PT_UpdateLangs($value, 'my_balance', 'Mi balance');
    $lang_update_queries[] = PT_UpdateLangs($value, 'earn_up_to', 'Ganar hasta');
    $lang_update_queries[] = PT_UpdateLangs($value, 'for_each_user_your_refer_to_us__', '¡para cada usuario, refiérase a nosotros!');
    $lang_update_queries[] = PT_UpdateLangs($value, 'joined', 'unido');
    $lang_update_queries[] = PT_UpdateLangs($value, 'payments', 'Pagos');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_balance_is', 'Su balance es');
    $lang_update_queries[] = PT_UpdateLangs($value, '__minimum_withdrawal_request_is', ', la solicitud de retiro mínimo es');
    $lang_update_queries[] = PT_UpdateLangs($value, 'paypal_email', 'E-mail de Paypal');
    $lang_update_queries[] = PT_UpdateLangs($value, 'request_withdrawal', 'Solicitud de retirada');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_request_has_been_sent__you__039_ll_receive_an_email_regarding_the_payment_details_soon.', 'Su solicitud ha sido enviada, usted');
    $lang_update_queries[] = PT_UpdateLangs($value, 'requested', 'pedido');
    $lang_update_queries[] = PT_UpdateLangs($value, 'payment_history', 'Historial de pagos');
    $lang_update_queries[] = PT_UpdateLangs($value, 'approved', 'aprobado');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_amount_value__your_amount_is_', 'Valor de importe no válido, su importe es:');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_friend', 'Añadir amigo');
    $lang_update_queries[] = PT_UpdateLangs($value, 'unfriend', 'No amigo');
    $lang_update_queries[] = PT_UpdateLangs($value, 'friend_request_sent', 'Solicitud de amistad enviada');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_already_sent_friend_request.', 'Ya has enviado una solicitud.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'success', 'Éxito');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirm_request_when_someone_follows_you__', '¿Confirmar solicitud cuando alguien te sigue?');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirm_request_when_someone_request_friend_you__', '¿Confirmar solicitud cuando alguien solicita ser un amigo con usted?');
    $lang_update_queries[] = PT_UpdateLangs($value, 'created_a_story_with_you.', 'Creé una historia contigo.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'accepted_your_friend_request.', 'acepté tu solicitud de amistad.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'declined_your_friend_request.', 'rechazó su solicitud de amistad.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'send_friend_request_to_you.', 'solicitó ser un amigo contigo.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'friend_requests', 'Peticiones de amistad');
    $lang_update_queries[] = PT_UpdateLangs($value, 'is_now_in_friend_list.', 'ahora está en tu lista de amigos.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'decline_request', 'Rechazar solicitud');
    $lang_update_queries[] = PT_UpdateLangs($value, 'accept_request', 'Aceptar petición');
    $lang_update_queries[] = PT_UpdateLangs($value, 'request_your_friendship.', 'solicita tu amistad');
    $lang_update_queries[] = PT_UpdateLangs($value, 'can_not_create_notification', 'no se puede crear una notificación');
    $lang_update_queries[] = PT_UpdateLangs($value, 'pending_review', 'revisión pendiente');
    $lang_update_queries[] = PT_UpdateLangs($value, 'the_username_is_blacklisted_and_not_allowed__please_choose_another_username.', 'El nombre de usuario está en la lista negra y no está permitido, elija otro nombre de usuario.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'the_email_provider_is_blacklisted_and_not_allowed__please_choose_another_email_provider.', 'El proveedor de correo electrónico está en la lista negra y no está permitido, elija otro proveedor de correo electrónico.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'latest__0__users.', 'Últimos {0} usuarios.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_reach_to_limit_of_media_uploads.', 'Has alcanzado el límite de carga de medios.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'email_sent_to', 'Correo electrónico enviado a');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_sending_emails', 'Error al enviar correos electrónicos');
    $lang_update_queries[] = PT_UpdateLangs($value, 'under_review', 'Bajo revisión');
        } else if ($value == 'turkish') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'manage_sessions', 'Oturumları Yönet');
    $lang_update_queries[] = PT_UpdateLangs($value, 'platform', 'platform');
    $lang_update_queries[] = PT_UpdateLangs($value, 'last_seen', 'Son görülen');
    $lang_update_queries[] = PT_UpdateLangs($value, 'os', '');
    $lang_update_queries[] = PT_UpdateLangs($value, 'browser', '');
    $lang_update_queries[] = PT_UpdateLangs($value, 'action', 'Aksiyon');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_deleting_session__please_try_again_later.', 'Oturum silinirken hata oluştu, lütfen daha sonra tekrar deneyin.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'session_deleted_successfully.', 'Oturum başarıyla silindi.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'two-factor_authentication', 'İki faktörlü kimlik doğrulama');
    $lang_update_queries[] = PT_UpdateLangs($value, 'turn_on_2-step_login_to_level-up_your_account_s_security__once_turned_on__you_ll_use_both_your_password_and_a_6-digit_security_code_sent_to_your_phone_or_email_to_log_in.', 'Hesabınızı yükseltmek için 2 adımlı giriş özelliğini açın');
    $lang_update_queries[] = PT_UpdateLangs($value, 'two-factor_authentication_data_saved_successfully.', 'İki faktörlü kimlik doğrulama verileri başarıyla kaydedildi.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'a_confirmation_email_has_been_sent.', 'Bir onay e-postası gönderildi.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_an_email_that_contains_the_confirmation_code_to_enable_two-factor_authentication.', 'İki faktörlü kimlik doğrulamayı etkinleştirmek için onay kodunu içeren bir e-posta gönderdik.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirmation_code', 'Onay kodu');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_check_your_details.', 'Lütfen bilgilerinizi kontrol edin.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_e-mail_has_been_successfully_verified.', 'E-postanız başarıyla doğrulandı.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number_should_be_as_this_format___90..', 'Telefon numarası şu formatta olmalıdır: 90 ..');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_phone_number_and_e-mail_have_been_successfully_verified.', 'Telefon numaranız ve e-postanız başarıyla doğrulandı.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'unusual_login', 'Olağandışı Giriş');
    $lang_update_queries[] = PT_UpdateLangs($value, 'to_log_in__you_need_to_verify_your_identity.', 'Giriş yapmak için kimliğinizi doğrulamanız gerekiyor.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_you_the_confirmation_code_to_your_phone_and_to_your_email_address.', 'Onay kodunu telefonunuza ve e-posta adresinize gönderdik.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_you_the_confirmation_code_to_your_email_address.', 'Size onay kodunu e-posta adresinize gönderdik.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_confirmation_code.', 'Lütfen onay kodunu girin.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'something_went_wrong__please_try_again_later.', 'Bir şeyler yanlış oldu. Lütfen sonra tekrar deneyiniz.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'wrong_confirmation_code.', 'Yanlış onay kodu.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_login__please_try_again_later.', 'Giriş yapılırken hata oluştu, lütfen daha sonra tekrar deneyin.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_user_id', 'Geçersiz kullanıcı kimliği');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_confirmation_code', 'Geçersiz onay kodu');
    $lang_update_queries[] = PT_UpdateLangs($value, 'find_potential_matches_by_country', 'Ülkelere göre potansiyel eşleşmeleri bulun');
    $lang_update_queries[] = PT_UpdateLangs($value, 'manage_notifications', 'Bildirimleri Yönet');
    $lang_update_queries[] = PT_UpdateLangs($value, 'custom_field', 'Özel alan');
    $lang_update_queries[] = PT_UpdateLangs($value, 'food', '');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_media', 'Medya ekle');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_video', 'Video ekle');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_photo', 'Fotoğraf ekle');
    $lang_update_queries[] = PT_UpdateLangs($value, 'upload', 'Yükleme');
    $lang_update_queries[] = PT_UpdateLangs($value, 'video_title', 'video başlığı');
    $lang_update_queries[] = PT_UpdateLangs($value, 'public', 'halka açık');
    $lang_update_queries[] = PT_UpdateLangs($value, 'private', 'Özel');
    $lang_update_queries[] = PT_UpdateLangs($value, 'thumbnail', '');
    $lang_update_queries[] = PT_UpdateLangs($value, 'my_affiliates', 'İştiraklerim');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_affiliate_link_is', 'Satış ortağı bağlantınız');
    $lang_update_queries[] = PT_UpdateLangs($value, 'my_balance', 'Benim dengem');
    $lang_update_queries[] = PT_UpdateLangs($value, 'earn_up_to', 'Kadar kazanın');
    $lang_update_queries[] = PT_UpdateLangs($value, 'for_each_user_your_refer_to_us__', 'her kullanıcı için bize bakın!');
    $lang_update_queries[] = PT_UpdateLangs($value, 'joined', 'katıldı');
    $lang_update_queries[] = PT_UpdateLangs($value, 'payments', 'Ödemeler');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_balance_is', 'Bakiyeniz');
    $lang_update_queries[] = PT_UpdateLangs($value, '__minimum_withdrawal_request_is', ', minimum para çekme talebi');
    $lang_update_queries[] = PT_UpdateLangs($value, 'paypal_email', 'PayPal e-postası');
    $lang_update_queries[] = PT_UpdateLangs($value, 'request_withdrawal', 'Para çekme isteği');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_request_has_been_sent__you__039_ll_receive_an_email_regarding_the_payment_details_soon.', 'Talebiniz gönderildi, siz');
    $lang_update_queries[] = PT_UpdateLangs($value, 'requested', 'talep edilen');
    $lang_update_queries[] = PT_UpdateLangs($value, 'payment_history', 'Ödeme geçmişi');
    $lang_update_queries[] = PT_UpdateLangs($value, 'approved', 'onaylandı');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_amount_value__your_amount_is_', 'Geçersiz tutar değeri, tutarınız:');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_friend', 'Arkadaş Ekle');
    $lang_update_queries[] = PT_UpdateLangs($value, 'unfriend', 'unfriend');
    $lang_update_queries[] = PT_UpdateLangs($value, 'friend_request_sent', 'Arkadaşlık isteği gönderildi');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_already_sent_friend_request.', 'Zaten bir istek gönderdiniz.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'success', 'başarı');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirm_request_when_someone_follows_you__', 'Biri sizi takip ettiğinde talebi onaylamak istiyor musunuz?');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirm_request_when_someone_request_friend_you__', 'Birisi sizinle arkadaş olmak istediğinde isteği onaylamak istiyor musunuz?');
    $lang_update_queries[] = PT_UpdateLangs($value, 'created_a_story_with_you.', 'seninle bir hikaye yarattı.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'accepted_your_friend_request.', 'arkadaşlık isteğin kabul edildi.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'declined_your_friend_request.', 'arkadaşlık isteğini reddetti.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'send_friend_request_to_you.', 'seninle arkadaş olmak istedi.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'friend_requests', 'Arkadaş istekleri');
    $lang_update_queries[] = PT_UpdateLangs($value, 'is_now_in_friend_list.', 'şimdi arkadaş listenizde.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'decline_request', 'İsteği reddet');
    $lang_update_queries[] = PT_UpdateLangs($value, 'accept_request', 'kabul et isteği');
    $lang_update_queries[] = PT_UpdateLangs($value, 'request_your_friendship.', 'arkadaşlık iste.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'can_not_create_notification', 'bildirim oluşturulamıyor');
    $lang_update_queries[] = PT_UpdateLangs($value, 'pending_review', 'bekleyen yorum');
    $lang_update_queries[] = PT_UpdateLangs($value, 'the_username_is_blacklisted_and_not_allowed__please_choose_another_username.', 'Kullanıcı adı kara listede ve izin verilmiyor, lütfen başka bir kullanıcı adı seçin.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'the_email_provider_is_blacklisted_and_not_allowed__please_choose_another_email_provider.', 'E-posta sağlayıcısı kara listeye alındı ​​ve izin verilmiyor, lütfen başka bir e-posta sağlayıcısı seçin.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'latest__0__users.', 'En son {0} kullanıcı.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_reach_to_limit_of_media_uploads.', 'Medya yükleme sınırına ulaştınız.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'email_sent_to', 'Adresine e-posta gönderildi');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_sending_emails', 'E-posta gönderilirken hata oluştu');
    $lang_update_queries[] = PT_UpdateLangs($value, 'under_review', 'İnceleme altında');
        } else if ($value == 'english') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'manage_sessions', 'Manage Sessions');
    $lang_update_queries[] = PT_UpdateLangs($value, 'platform', 'Platform');
    $lang_update_queries[] = PT_UpdateLangs($value, 'last_seen', 'Last seen');
    $lang_update_queries[] = PT_UpdateLangs($value, 'os', 'OS');
    $lang_update_queries[] = PT_UpdateLangs($value, 'browser', 'Browser');
    $lang_update_queries[] = PT_UpdateLangs($value, 'action', 'Action');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_deleting_session__please_try_again_later.', 'Error while deleting session, please try again later.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'session_deleted_successfully.', 'Session has been deleted successfully.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'two-factor_authentication', 'Two-factor authentication');
    $lang_update_queries[] = PT_UpdateLangs($value, 'turn_on_2-step_login_to_level-up_your_account_s_security__once_turned_on__you_ll_use_both_your_password_and_a_6-digit_security_code_sent_to_your_phone_or_email_to_log_in.', 'Turn on 2-step login to level-up your account&#039;s security, Once turned on, you&#039;ll use both your password and a 6-digit security code sent to your phone or email to log in.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'two-factor_authentication_data_saved_successfully.', 'Two-factor authentication data saved successfully.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'a_confirmation_email_has_been_sent.', 'A confirmation email has been sent.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_an_email_that_contains_the_confirmation_code_to_enable_two-factor_authentication.', 'We have sent an email that contains the confirmation code to enable Two-factor authentication.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirmation_code', 'Confirmation code');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_check_your_details.', 'Please check your details.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_e-mail_has_been_successfully_verified.', 'Your e-mail has been successfully verified.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number_should_be_as_this_format___90..', 'Phone number should be as this format: +90..');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_phone_number_and_e-mail_have_been_successfully_verified.', 'Your phone number and e-mail have been successfully verified.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'unusual_login', 'Unusual Login');
    $lang_update_queries[] = PT_UpdateLangs($value, 'to_log_in__you_need_to_verify_your_identity.', 'To log in, you need to verify your identity.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_you_the_confirmation_code_to_your_phone_and_to_your_email_address.', 'We have sent you the confirmation code to your phone and to your email address.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_you_the_confirmation_code_to_your_email_address.', 'We have sent you the confirmation code to your email address.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_confirmation_code.', 'Please enter confirmation code.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'something_went_wrong__please_try_again_later.', 'Something went wrong, please try again later.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'wrong_confirmation_code.', 'Wrong confirmation code.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_login__please_try_again_later.', 'Error while login, please try again later.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_user_id', 'Invalid User ID');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_confirmation_code', 'Invalid confirmation code');
    $lang_update_queries[] = PT_UpdateLangs($value, 'find_potential_matches_by_country', 'Find potential matches by country');
    $lang_update_queries[] = PT_UpdateLangs($value, 'manage_notifications', 'Manage Notifications');
    $lang_update_queries[] = PT_UpdateLangs($value, 'custom_field', 'Custom field');
    $lang_update_queries[] = PT_UpdateLangs($value, 'food', 'food');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_media', 'Add Media');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_video', 'Add Video');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_photo', 'Add Photo');
    $lang_update_queries[] = PT_UpdateLangs($value, 'upload', 'Upload');
    $lang_update_queries[] = PT_UpdateLangs($value, 'video_title', 'Video Title');
    $lang_update_queries[] = PT_UpdateLangs($value, 'public', 'Public');
    $lang_update_queries[] = PT_UpdateLangs($value, 'private', 'Private');
    $lang_update_queries[] = PT_UpdateLangs($value, 'thumbnail', 'Thumbnail');
    $lang_update_queries[] = PT_UpdateLangs($value, 'my_affiliates', 'My affiliates');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_affiliate_link_is', 'Your affiliate link is');
    $lang_update_queries[] = PT_UpdateLangs($value, 'my_balance', 'My balance');
    $lang_update_queries[] = PT_UpdateLangs($value, 'earn_up_to', 'Earn up to');
    $lang_update_queries[] = PT_UpdateLangs($value, 'for_each_user_your_refer_to_us__', 'for each user your refer to us !');
    $lang_update_queries[] = PT_UpdateLangs($value, 'joined', 'joined');
    $lang_update_queries[] = PT_UpdateLangs($value, 'payments', 'Payments');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_balance_is', 'Your balance is');
    $lang_update_queries[] = PT_UpdateLangs($value, '__minimum_withdrawal_request_is', ', minimum withdrawal request is');
    $lang_update_queries[] = PT_UpdateLangs($value, 'paypal_email', 'PayPal email');
    $lang_update_queries[] = PT_UpdateLangs($value, 'request_withdrawal', 'Request withdrawal');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_request_has_been_sent__you__039_ll_receive_an_email_regarding_the_payment_details_soon.', 'Your request has been sent, you&#039;ll receive an email regarding the payment details soon.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'requested', 'requested');
    $lang_update_queries[] = PT_UpdateLangs($value, 'payment_history', 'Payment history');
    $lang_update_queries[] = PT_UpdateLangs($value, 'approved', 'approved');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_amount_value__your_amount_is_', 'Invalid amount value, your amount is:');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_friend', 'Add Friend');
    $lang_update_queries[] = PT_UpdateLangs($value, 'unfriend', 'UnFriend');
    $lang_update_queries[] = PT_UpdateLangs($value, 'friend_request_sent', 'Friend request sent');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_already_sent_friend_request.', 'You have already sent a request.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'success', 'Success');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirm_request_when_someone_follows_you__', 'Confirm request when someone follows you?');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirm_request_when_someone_request_friend_you__', 'Confirm request when someone request to be a friend with you?');
    $lang_update_queries[] = PT_UpdateLangs($value, 'created_a_story_with_you.', 'created a story with you.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'accepted_your_friend_request.', 'accepted your friend request.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'declined_your_friend_request.', 'declined your friend request.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'send_friend_request_to_you.', 'requested to be a friend with you.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'friend_requests', 'Friend requests');
    $lang_update_queries[] = PT_UpdateLangs($value, 'is_now_in_friend_list.', 'is now in your friend list.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'decline_request', 'Decline request');
    $lang_update_queries[] = PT_UpdateLangs($value, 'accept_request', 'Accept request');
    $lang_update_queries[] = PT_UpdateLangs($value, 'request_your_friendship.', 'requested to be your friend.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'can_not_create_notification', 'can not create notification');
    $lang_update_queries[] = PT_UpdateLangs($value, 'pending_review', 'pending review');
    $lang_update_queries[] = PT_UpdateLangs($value, 'the_username_is_blacklisted_and_not_allowed__please_choose_another_username.', 'The username is blacklisted and not allowed, please choose another username.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'the_email_provider_is_blacklisted_and_not_allowed__please_choose_another_email_provider.', 'The email provider is blacklisted and not allowed, please choose another email provider.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'latest__0__users.', 'Latest {0} users.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_reach_to_limit_of_media_uploads.', 'You have reached the limit of media uploads.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'email_sent_to', 'Email sent to');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_sending_emails', 'Error while sending emails');
    $lang_update_queries[] = PT_UpdateLangs($value, 'under_review', 'Under Review');
        } else if ($value != 'english') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'manage_sessions', 'Manage Sessions');
    $lang_update_queries[] = PT_UpdateLangs($value, 'platform', 'Platform');
    $lang_update_queries[] = PT_UpdateLangs($value, 'last_seen', 'Last seen');
    $lang_update_queries[] = PT_UpdateLangs($value, 'os', 'OS');
    $lang_update_queries[] = PT_UpdateLangs($value, 'browser', 'Browser');
    $lang_update_queries[] = PT_UpdateLangs($value, 'action', 'Action');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_deleting_session__please_try_again_later.', 'Error while deleting session, please try again later.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'session_deleted_successfully.', 'Session has been deleted successfully.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'two-factor_authentication', 'Two-factor authentication');
    $lang_update_queries[] = PT_UpdateLangs($value, 'turn_on_2-step_login_to_level-up_your_account_s_security__once_turned_on__you_ll_use_both_your_password_and_a_6-digit_security_code_sent_to_your_phone_or_email_to_log_in.', 'Turn on 2-step login to level-up your account&#039;s security, Once turned on, you&#039;ll use both your password and a 6-digit security code sent to your phone or email to log in.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'two-factor_authentication_data_saved_successfully.', 'Two-factor authentication data saved successfully.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'a_confirmation_email_has_been_sent.', 'A confirmation email has been sent.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_an_email_that_contains_the_confirmation_code_to_enable_two-factor_authentication.', 'We have sent an email that contains the confirmation code to enable Two-factor authentication.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirmation_code', 'Confirmation code');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_check_your_details.', 'Please check your details.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_e-mail_has_been_successfully_verified.', 'Your e-mail has been successfully verified.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number_should_be_as_this_format___90..', 'Phone number should be as this format: +90..');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_phone_number_and_e-mail_have_been_successfully_verified.', 'Your phone number and e-mail have been successfully verified.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'unusual_login', 'Unusual Login');
    $lang_update_queries[] = PT_UpdateLangs($value, 'to_log_in__you_need_to_verify_your_identity.', 'To log in, you need to verify your identity.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_you_the_confirmation_code_to_your_phone_and_to_your_email_address.', 'We have sent you the confirmation code to your phone and to your email address.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_sent_you_the_confirmation_code_to_your_email_address.', 'We have sent you the confirmation code to your email address.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_confirmation_code.', 'Please enter confirmation code.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'something_went_wrong__please_try_again_later.', 'Something went wrong, please try again later.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'wrong_confirmation_code.', 'Wrong confirmation code.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_login__please_try_again_later.', 'Error while login, please try again later.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_user_id', 'Invalid User ID');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_confirmation_code', 'Invalid confirmation code');
    $lang_update_queries[] = PT_UpdateLangs($value, 'find_potential_matches_by_country', 'Find potential matches by country');
    $lang_update_queries[] = PT_UpdateLangs($value, 'manage_notifications', 'Manage Notifications');
    $lang_update_queries[] = PT_UpdateLangs($value, 'custom_field', 'Custom field');
    $lang_update_queries[] = PT_UpdateLangs($value, 'food', 'food');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_media', 'Add Media');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_video', 'Add Video');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_photo', 'Add Photo');
    $lang_update_queries[] = PT_UpdateLangs($value, 'upload', 'Upload');
    $lang_update_queries[] = PT_UpdateLangs($value, 'video_title', 'Video Title');
    $lang_update_queries[] = PT_UpdateLangs($value, 'public', 'Public');
    $lang_update_queries[] = PT_UpdateLangs($value, 'private', 'Private');
    $lang_update_queries[] = PT_UpdateLangs($value, 'thumbnail', 'Thumbnail');
    $lang_update_queries[] = PT_UpdateLangs($value, 'my_affiliates', 'My affiliates');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_affiliate_link_is', 'Your affiliate link is');
    $lang_update_queries[] = PT_UpdateLangs($value, 'my_balance', 'My balance');
    $lang_update_queries[] = PT_UpdateLangs($value, 'earn_up_to', 'Earn up to');
    $lang_update_queries[] = PT_UpdateLangs($value, 'for_each_user_your_refer_to_us__', 'for each user your refer to us !');
    $lang_update_queries[] = PT_UpdateLangs($value, 'joined', 'joined');
    $lang_update_queries[] = PT_UpdateLangs($value, 'payments', 'Payments');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_balance_is', 'Your balance is');
    $lang_update_queries[] = PT_UpdateLangs($value, '__minimum_withdrawal_request_is', ', minimum withdrawal request is');
    $lang_update_queries[] = PT_UpdateLangs($value, 'paypal_email', 'PayPal email');
    $lang_update_queries[] = PT_UpdateLangs($value, 'request_withdrawal', 'Request withdrawal');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_request_has_been_sent__you__039_ll_receive_an_email_regarding_the_payment_details_soon.', 'Your request has been sent, you&#039;ll receive an email regarding the payment details soon.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'requested', 'requested');
    $lang_update_queries[] = PT_UpdateLangs($value, 'payment_history', 'Payment history');
    $lang_update_queries[] = PT_UpdateLangs($value, 'approved', 'approved');
    $lang_update_queries[] = PT_UpdateLangs($value, 'invalid_amount_value__your_amount_is_', 'Invalid amount value, your amount is:');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_friend', 'Add Friend');
    $lang_update_queries[] = PT_UpdateLangs($value, 'unfriend', 'UnFriend');
    $lang_update_queries[] = PT_UpdateLangs($value, 'friend_request_sent', 'Friend request sent');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_already_sent_friend_request.', 'You have already sent a request.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'success', 'Success');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirm_request_when_someone_follows_you__', 'Confirm request when someone follows you?');
    $lang_update_queries[] = PT_UpdateLangs($value, 'confirm_request_when_someone_request_friend_you__', 'Confirm request when someone request to be a friend with you?');
    $lang_update_queries[] = PT_UpdateLangs($value, 'created_a_story_with_you.', 'created a story with you.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'accepted_your_friend_request.', 'accepted your friend request.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'declined_your_friend_request.', 'declined your friend request.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'send_friend_request_to_you.', 'requested to be a friend with you.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'friend_requests', 'Friend requests');
    $lang_update_queries[] = PT_UpdateLangs($value, 'is_now_in_friend_list.', 'is now in your friend list.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'decline_request', 'Decline request');
    $lang_update_queries[] = PT_UpdateLangs($value, 'accept_request', 'Accept request');
    $lang_update_queries[] = PT_UpdateLangs($value, 'request_your_friendship.', 'requested to be your friend.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'can_not_create_notification', 'can not create notification');
    $lang_update_queries[] = PT_UpdateLangs($value, 'pending_review', 'pending review');
    $lang_update_queries[] = PT_UpdateLangs($value, 'the_username_is_blacklisted_and_not_allowed__please_choose_another_username.', 'The username is blacklisted and not allowed, please choose another username.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'the_email_provider_is_blacklisted_and_not_allowed__please_choose_another_email_provider.', 'The email provider is blacklisted and not allowed, please choose another email provider.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'latest__0__users.', 'Latest {0} users.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_reach_to_limit_of_media_uploads.', 'You have reached the limit of media uploads.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'email_sent_to', 'Email sent to');
    $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_sending_emails', 'Error while sending emails');
    $lang_update_queries[] = PT_UpdateLangs($value, 'under_review', 'Under Review');
        }
    }
    if (!empty($lang_update_queries)) {
        foreach ($lang_update_queries as $key => $query) {
            $sql = mysqli_query($conn, $query);
        }
    }
    $name = md5(microtime()) . '_updated.php';
    rename('update.php', $name);
}
?>
<html>
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
      <meta name="viewport" content="width=device-width, initial-scale=1"/>
      <title>Updating Quickdate</title>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <style>
         @import url('https://fonts.googleapis.com/css?family=Roboto:400,500');
         @media print {
            .wo_update_changelog {max-height: none !important; min-height: !important}
            .btn, .hide_print, .setting-well h4 {display:none;}
         }
         * {outline: none !important;}
         body {background: #f3f3f3;font-family: 'Roboto', sans-serif;}
         .light {font-weight: 400;}
         .bold {font-weight: 500;}
         .btn {height: 52px;line-height: 1;font-size: 16px;transition: all 0.3s;border-radius: 2em;font-weight: 500;padding: 0 28px;letter-spacing: .5px;}
         .btn svg {margin-left: 10px;margin-top: -2px;transition: all 0.3s;vertical-align: middle;}
         .btn:hover svg {-webkit-transform: translateX(3px);-moz-transform: translateX(3px);-ms-transform: translateX(3px);-o-transform: translateX(3px);transform: translateX(3px);}
         .btn-main {color: #ffffff;background-color: #b0088d;border-color: #b0088d;}
         .btn-main:disabled, .btn-main:focus {color: #fff;}
         .btn-main:hover {color: #ffffff;background-color: #0dcde2;border-color: #0dcde2;box-shadow: -2px 2px 14px rgba(168, 72, 73, 0.35);}
         svg {vertical-align: middle;}
         .main {color: #b0088d;}
         .wo_update_changelog {
          border: 1px solid #eee;
          padding: 10px !important;
         }
         .content-container {display: -webkit-box; width: 100%;display: -moz-box;display: -ms-flexbox;display: -webkit-flex;display: flex;-webkit-flex-direction: column;flex-direction: column;min-height: 100vh;position: relative;}
         .content-container:before, .content-container:after {-webkit-box-flex: 1;box-flex: 1;-webkit-flex-grow: 1;flex-grow: 1;content: '';display: block;height: 50px;}
         .wo_install_wiz {position: relative;background-color: white;box-shadow: 0 1px 15px 2px rgba(0, 0, 0, 0.1);border-radius: 10px;padding: 20px 30px;border-top: 1px solid rgba(0, 0, 0, 0.04);}
         .wo_install_wiz h2 {margin-top: 10px;margin-bottom: 30px;display: flex;align-items: center;}
         .wo_install_wiz h2 span {margin-left: auto;font-size: 15px;}
         .wo_update_changelog {padding:0;list-style-type: none;margin-bottom: 15px;max-height: 440px;overflow-y: auto; min-height: 440px;}
         .wo_update_changelog li {margin-bottom:7px; max-height: 20px; overflow: hidden;}
         .wo_update_changelog li span {padding: 2px 7px;font-size: 12px;margin-right: 4px;border-radius: 2px;}
         .wo_update_changelog li span.added {background-color: #4CAF50;color: white;}
         .wo_update_changelog li span.changed {background-color: #e62117;color: white;}
         .wo_update_changelog li span.improved {background-color: #9C27B0;color: white;}
         .wo_update_changelog li span.compressed {background-color: #795548;color: white;}
         .wo_update_changelog li span.fixed {background-color: #2196F3;color: white;}
         input.form-control {background-color: #f4f4f4;border: 0;border-radius: 2em;height: 40px;padding: 3px 14px;color: #383838;transition: all 0.2s;}
input.form-control:hover {background-color: #e9e9e9;}
input.form-control:focus {background: #fff;box-shadow: 0 0 0 1.5px #a84849;}
         .empty_state {margin-top: 80px;margin-bottom: 80px;font-weight: 500;color: #6d6d6d;display: block;text-align: center;}
         .checkmark__circle {stroke-dasharray: 166;stroke-dashoffset: 166;stroke-width: 2;stroke-miterlimit: 10;stroke: #7ac142;fill: none;animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;}
         .checkmark {width: 80px;height: 80px; border-radius: 50%;display: block;stroke-width: 3;stroke: #fff;stroke-miterlimit: 10;margin: 100px auto 50px;box-shadow: inset 0px 0px 0px #7ac142;animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;}
         .checkmark__check {transform-origin: 50% 50%;stroke-dasharray: 48;stroke-dashoffset: 48;animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;}
         @keyframes stroke { 100% {stroke-dashoffset: 0;}}
         @keyframes scale {0%, 100% {transform: none;}  50% {transform: scale3d(1.1, 1.1, 1); }}
         @keyframes fill { 100% {box-shadow: inset 0px 0px 0px 54px #7ac142; }}
      </style>
   </head>
   <body>
      <div class="content-container container">
         <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
               <div class="wo_install_wiz">
                 <?php if ($updated == false) { ?>
                  <div>
                     <h2 class="light">Update to v1.4 </span></h2>
                     <div class="setting-well">
                        <h4>Changelog</h4>
                        <ul class="wo_update_changelog">
                            <li>[Added] ability to upload videos + FFMPEG.</li>
                            <li>[Added] moderator user (admin could set what pages a specific moderator could access in the admin).</li>
                            <li>[Added] ability to lock gender after someone selects during sign up.</li>
                            <li>[Added] friend system, user can add friends / accept decline (enable / disable.)</li>
                            <li>[Added] blacklist system, block user by email, username and IP. </li>
                            <li>[Added] affiliate system, users can earn money by refrerring users. </li>
                            <li>[Added] users on home page (enable/disable). </li>
                            <li>[Added] ability to send mock emails to user who didn't login for week/month/year/ from admin panel.</li>
                            <li>[Added] ability to limit number of max photo a user can upload from admin panel (unlimited for pro user)</li>
                            <li>[Added] ability to review photos and videos by admin with (enable/disable)</li>
                            <li>[Fixed] 20+ reported bugs.</li>
                            <li>[Improved] stability & speed. </li>
                        </ul>
                        <p class="hide_print">Note: The update process might take few minutes.</p>
                        <p class="hide_print">Important: If you got any fail queries, please copy them, open a support ticket and send us the details.</p>
                        <br>
                             <button class="pull-right btn btn-default" onclick="window.print();">Share Log</button>
                             <button type="button" class="btn btn-main" id="button-update">
                             Update 
                             <svg viewBox="0 0 19 14" xmlns="http://www.w3.org/2000/svg" width="18" height="18">
                                <path fill="currentColor" d="M18.6 6.9v-.5l-6-6c-.3-.3-.9-.3-1.2 0-.3.3-.3.9 0 1.2l5 5H1c-.5 0-.9.4-.9.9s.4.8.9.8h14.4l-4 4.1c-.3.3-.3.9 0 1.2.2.2.4.2.6.2.2 0 .4-.1.6-.2l5.2-5.2h.2c.5 0 .8-.4.8-.8 0-.3 0-.5-.2-.7z"></path>
                             </svg>
                          </button>
                     </div>
                     <?php }?>
                     <?php if ($updated == true) { ?>
                      <div>
                        <div class="empty_state">
                           <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                              <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                              <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                           </svg>
                           <p>Congratulations, you have successfully updated your site. Thanks for choosing WoWonder.</p>
                           <br>
                           <a href="<?php echo $wo['config']['site_url'] ?>" class="btn btn-main" style="line-height:50px;">Home</a>
                        </div>
                     </div>
                     <?php }?>
                  </div>
               </div>
            </div>
            <div class="col-md-1"></div>
         </div>
      </div>
   </body>
</html>
<script>  
var queries = [
    "UPDATE `options` SET `option_value`= '1.4' WHERE option_name = 'version';",
    "ALTER TABLE `mediafiles` ADD `is_video` INT(11) UNSIGNED NULL DEFAULT '0' AFTER `created_at`, ADD `video_file` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' AFTER `is_video`, ADD `is_confirmed` INT(11) UNSIGNED NULL DEFAULT '0' AFTER `video_file`;",
    "INSERT INTO `options` (`id`, `option_name`, `option_value`, `created_at`) VALUES (NULL, 'two_factor', '1', CURRENT_TIMESTAMP), (NULL, 'two_factor_type', 'email', CURRENT_TIMESTAMP);",
    "ALTER TABLE `users` CHANGE `email_code` `email_code` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;",
    "ALTER TABLE `users`ADD `two_factor` INT(11) UNSIGNED NULL DEFAULT '0' AFTER `last_activation_request`,ADD `two_factor_verified` INT(11) UNSIGNED NULL DEFAULT '0' AFTER `two_factor`,ADD `two_factor_email_code` VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' AFTER `two_factor_verified`,ADD `new_email` VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' AFTER `two_factor_email_code`,ADD `new_phone` VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' AFTER `new_email`,ADD `permission` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL AFTER `new_phone`;",
    "CREATE TABLE IF NOT EXISTS `affiliates_requests` (`id` int(11) NOT NULL,`user_id` int(11) NOT NULL DEFAULT '0',`amount` varchar(100) NOT NULL DEFAULT '0',`full_amount` varchar(100) NOT NULL DEFAULT '',`status` int(11) NOT NULL DEFAULT '0',`time` int(11) NOT NULL DEFAULT '0') ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "ALTER TABLE `affiliates_requests` ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`), ADD KEY `time` (`time`), ADD KEY `status` (`status`);",
    "ALTER TABLE `affiliates_requests` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;",
    "INSERT INTO `options` (`id`, `option_name`, `option_value`, `created_at`) VALUES (NULL, 'affiliate_system', '1', CURRENT_TIMESTAMP), (NULL, 'affiliate_type', NULL, CURRENT_TIMESTAMP), (NULL, 'm_withdrawal', NULL, CURRENT_TIMESTAMP), (NULL, 'amount_ref', NULL, CURRENT_TIMESTAMP), (NULL, 'amount_percent_ref', NULL, CURRENT_TIMESTAMP), (NULL, 'connectivitySystem', '0', CURRENT_TIMESTAMP), (NULL, 'connectivitySystemLimit', '5000', CURRENT_TIMESTAMP), (NULL, 'show_user_on_homepage', '1', CURRENT_TIMESTAMP), (NULL, 'showed_user', '25', CURRENT_TIMESTAMP), (NULL, 'max_photo_per_user', '12', CURRENT_TIMESTAMP), (NULL, 'review_media_files', '0', CURRENT_TIMESTAMP), (NULL, 'ffmpeg_sys', '0', CURRENT_TIMESTAMP), (NULL, 'max_video_duration', '30', CURRENT_TIMESTAMP), (NULL, 'ffmpeg_binary', './ffmpeg/ffmpeg', CURRENT_TIMESTAMP);",
    "ALTER TABLE `users` ADD `referrer` INT(11) UNSIGNED NOT NULL DEFAULT '0' AFTER `permission`, ADD `aff_balance` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0' AFTER `referrer`, ADD `paypal_email` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' AFTER `aff_balance`, ADD `confirm_followers` ENUM('0','1') NOT NULL DEFAULT '1' AFTER `paypal_email`;",
    "CREATE TABLE `banned_ip` ( `id` int(11) NOT NULL, `ip_address` varchar(32) NOT NULL DEFAULT '', `time` int(11) NOT NULL DEFAULT '0') ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "ALTER TABLE `banned_ip` ADD PRIMARY KEY (`id`), ADD KEY `ip_address` (`ip_address`);",
    "ALTER TABLE `banned_ip` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;",
    "CREATE TABLE `followers` ( `id` int(11) NOT NULL, `following_id` int(11) NOT NULL DEFAULT '0', `follower_id` int(11) NOT NULL DEFAULT '0', `active` int(255) NOT NULL DEFAULT '1', `created_at` INT(11) UNSIGNED NULL DEFAULT '0') ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "ALTER TABLE `followers` ADD PRIMARY KEY (`id`), ADD KEY `following_id` (`following_id`), ADD KEY `follower_id` (`follower_id`), ADD KEY `active` (`active`), ADD KEY `order1` (`following_id`,`id`), ADD KEY `order2` (`follower_id`,`id`);",
    "ALTER TABLE `followers` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;",
    "ALTER TABLE `mediafiles` ADD `is_approved` INT(11) UNSIGNED NULL DEFAULT '1' AFTER `is_confirmed`;",
    "ALTER TABLE `emails` ADD `src` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'site' AFTER `message`;",
    "UPDATE mediafiles SET is_confirmed = '0', is_approved = '1'",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'manage_sessions');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'platform');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'last_seen');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'os');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'browser');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'action');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'error_while_deleting_session__please_try_again_later.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'session_deleted_successfully.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'two-factor_authentication');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'turn_on_2-step_login_to_level-up_your_account_s_security__once_turned_on__you_ll_use_both_your_password_and_a_6-digit_security_code_sent_to_your_phone_or_email_to_log_in.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'two-factor_authentication_data_saved_successfully.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'a_confirmation_email_has_been_sent.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'we_have_sent_an_email_that_contains_the_confirmation_code_to_enable_two-factor_authentication.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'confirmation_code');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'please_check_your_details.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'your_e-mail_has_been_successfully_verified.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'phone_number_should_be_as_this_format___90..');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'your_phone_number_and_e-mail_have_been_successfully_verified.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'unusual_login');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'to_log_in__you_need_to_verify_your_identity.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'we_have_sent_you_the_confirmation_code_to_your_phone_and_to_your_email_address.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'we_have_sent_you_the_confirmation_code_to_your_email_address.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'please_enter_confirmation_code.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'something_went_wrong__please_try_again_later.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'wrong_confirmation_code.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'error_while_login__please_try_again_later.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'invalid_user_id');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'invalid_confirmation_code');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'find_potential_matches_by_country');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'manage_notifications');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'custom_field');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'food');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'add_media');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'add_video');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'add_photo');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'upload');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'video_title');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'public');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'private');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'thumbnail');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'my_affiliates');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'your_affiliate_link_is');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'my_balance');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'earn_up_to');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'for_each_user_your_refer_to_us__');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'joined');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'payments');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'your_balance_is');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, '__minimum_withdrawal_request_is');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'paypal_email');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'request_withdrawal');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'your_request_has_been_sent__you__039_ll_receive_an_email_regarding_the_payment_details_soon.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'requested');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'payment_history');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'approved');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'invalid_amount_value__your_amount_is_');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'add_friend');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'unfriend');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'friend_request_sent');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'you_already_sent_friend_request.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'success');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'confirm_request_when_someone_follows_you__');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'confirm_request_when_someone_request_friend_you__');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'created_a_story_with_you.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'accepted_your_friend_request.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'declined_your_friend_request.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'send_friend_request_to_you.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'friend_requests');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'is_now_in_friend_list.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'decline_request');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'accept_request');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'request_your_friendship.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'can_not_create_notification');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'pending_review');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'the_username_is_blacklisted_and_not_allowed__please_choose_another_username.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'the_email_provider_is_blacklisted_and_not_allowed__please_choose_another_email_provider.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'latest__0__users.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'you_reach_to_limit_of_media_uploads.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'email_sent_to');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'error_while_sending_emails');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'under_review');",

];

$('#input_code').bind("paste keyup input propertychange", function(e) {
    if (isPurchaseCode($(this).val())) {
        $('#button-update').removeAttr('disabled');
    } else {
        $('#button-update').attr('disabled', 'true');
    }
});

function isPurchaseCode(str) {
    var patt = new RegExp("(.*)-(.*)-(.*)-(.*)-(.*)");
    var res = patt.test(str);
    if (res) {
        return true;
    }
    return false;
}

$(document).on('click', '#button-update', function(event) {
    if ($('body').attr('data-update') == 'true') {
        window.location.href = '<?php echo $site_url?>';
        return false;
    }
    $(this).attr('disabled', true);
    $('.wo_update_changelog').html('');
    $('.wo_update_changelog').css({
        background: '#1e2321',
        color: '#fff'
    });
    $('.setting-well h4').text('Updating..');
    $(this).attr('disabled', true);
    RunQuery();
});

var queriesLength = queries.length;
var query = queries[0];
var count = 0;
function b64EncodeUnicode(str) {
    // first we use encodeURIComponent to get percent-encoded UTF-8,
    // then we convert the percent encodings into raw bytes which
    // can be fed into btoa.
    return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g,
        function toSolidBytes(match, p1) {
            return String.fromCharCode('0x' + p1);
    }));
}
function RunQuery() {
    var query = queries[count];
    $.post('?update', {
        query: b64EncodeUnicode(query)
    }, function(data, textStatus, xhr) {
        if (data.status == 200) {
            $('.wo_update_changelog').append('<li><span class="added">SUCCESS</span> ~$ mysql > ' + query + '</li>');
        } else {
            $('.wo_update_changelog').append('<li><span class="changed">FAILED</span> ~$ mysql > ' + query + '</li>');
        }
        count = count + 1;
        if (queriesLength > count) {
            setTimeout(function() {
                RunQuery();
            }, 1500);
        } else {
            $('.wo_update_changelog').append('<li><span class="added">Updating Langauges & Categories</span> ~$ languages.sh, Please wait, this might take some time..</li>');
            $.post('?run_lang', {
                update_langs: 'true'
            }, function(data, textStatus, xhr) {
              $('.wo_update_changelog').append('<li><span class="fixed">Finished!</span> ~$ Congratulations! you have successfully updated your site. Thanks for choosing QuickDate.</li>');
              $('.setting-well h4').text('Update Log');
              $('#button-update').html('Home <svg viewBox="0 0 19 14" xmlns="http://www.w3.org/2000/svg" width="18" height="18"> <path fill="currentColor" d="M18.6 6.9v-.5l-6-6c-.3-.3-.9-.3-1.2 0-.3.3-.3.9 0 1.2l5 5H1c-.5 0-.9.4-.9.9s.4.8.9.8h14.4l-4 4.1c-.3.3-.3.9 0 1.2.2.2.4.2.6.2.2 0 .4-.1.6-.2l5.2-5.2h.2c.5 0 .8-.4.8-.8 0-.3 0-.5-.2-.7z"></path> </svg>');
              $('#button-update').attr('disabled', false);
              $(".wo_update_changelog").scrollTop($(".wo_update_changelog")[0].scrollHeight);
              $('body').attr('data-update', 'true');
            });
        }
        $(".wo_update_changelog").scrollTop($(".wo_update_changelog")[0].scrollHeight);
    });
}
</script>