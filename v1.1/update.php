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
    $lang_update_queries = array();
    foreach ($data as $key => $value) {
        $value = ($value);
        if ($value == 'arabic') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'bank_transfer', 'التحويل المصرفي');
            $lang_update_queries[] = PT_UpdateLangs($value, 'close', 'قريب');
            $lang_update_queries[] = PT_UpdateLangs($value, 'bank_information', 'المعلومات المصرفية');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_transfer_the_amount_of', 'يرجى نقل كمية');
            $lang_update_queries[] = PT_UpdateLangs($value, 'to_this_bank_account_to_buy', 'لهذا الحساب المصرفي للشراء');
            $lang_update_queries[] = PT_UpdateLangs($value, 'upload_receipt', 'تحميل الإيصال');
            $lang_update_queries[] = PT_UpdateLangs($value, 'confirm', 'تؤكد');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_receipt_uploaded_successfully.', 'تم استلام الإيصال بنجاح.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'date', 'تاريخ');
            $lang_update_queries[] = PT_UpdateLangs($value, 'processed_by', 'معالجتها بواسطة');
            $lang_update_queries[] = PT_UpdateLangs($value, 'amount', 'كمية');
            $lang_update_queries[] = PT_UpdateLangs($value, 'type', 'نوع');
            $lang_update_queries[] = PT_UpdateLangs($value, 'notes', 'ملاحظات');
            $lang_update_queries[] = PT_UpdateLangs($value, 'plan_premium_membership', 'خطة عضوية مميزة');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_boost_will_expire_in', 'ستنتهي صلاحيتك في');
            $lang_update_queries[] = PT_UpdateLangs($value, 'hide', 'إخفاء');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_reach_the_max_of_swipes_per_day._you_have_to_wait__0__hours_before_you_can_redo_likes_or_upgrade_to_pro_to_for_unlimited.', 'لقد وصلت إلى الحد الأقصى من الضربات الشديدة في اليوم ، يجب عليك الانتظار {0} ساعات قبل أن تتمكن من إعادة الضربات الشديدة ، أو الترقية الآن إلى عضوية Pro للحصول على الضربات الشديدة والإعجابات غير المحدودة.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_payment_was_processed_successfully.', 'تمت معالجة دفعتك بنجاح.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sms', 'رسالة قصيرة');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_message_', 'ارسلت لك رساله!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accept', 'قبول');
            $lang_update_queries[] = PT_UpdateLangs($value, 'decline', 'انخفاض');
            $lang_update_queries[] = PT_UpdateLangs($value, 'calling', 'دعوة');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_wait_for_your_friend_answer.', 'يرجى الانتظار للحصول على إجابة صديقك.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_answer', 'لا اجابة');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_try_again_later.', 'الرجاء معاودة المحاولة في وقت لاحق.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'new_video_call', 'مكالمة فيديو واردة جديدة');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wants_to_video_chat_with_you.', 'يريد دردشة الفيديو معك.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'call_declined', 'تم رفض الاتصال');
            $lang_update_queries[] = PT_UpdateLangs($value, 'the_recipient_has_declined_the_call__please_try_again_later.', 'رفض المستلم الاتصال ، يرجى المحاولة مرة أخرى لاحقًا.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accept___start', 'اقبل وابدأ');
            $lang_update_queries[] = PT_UpdateLangs($value, 'answered__', 'أجاب!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_wait..', 'ارجوك انتظر..');
            $lang_update_queries[] = PT_UpdateLangs($value, 'video_call', 'مكالمة فيديو');
            $lang_update_queries[] = PT_UpdateLangs($value, 'new_audio_call', 'مكالمة صوتية واردة جديدة');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wants_to_talk_with_you.', 'يريد التحدث معك');
            $lang_update_queries[] = PT_UpdateLangs($value, 'audio_call', 'مكالمة صوتية');
            $lang_update_queries[] = PT_UpdateLangs($value, 'talking_with', 'يتحدث مع');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_website_uses_cookies_to_ensure_you_get_the_best_experience_on_our_website.', 'يستخدم هذا الموقع ملفات تعريف الارتباط لضمان حصولك على أفضل تجربة على موقعنا.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'got_it_', 'فهمتك!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'learn_more', 'أعرف أكثر');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_result_found', 'لم يتم العثور على نتائج');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_gif', 'إرسال GIF');
            $lang_update_queries[] = PT_UpdateLangs($value, 'search_gifs', 'بحث صور متحركة');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sticker_added', 'وأضاف ملصقا');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_phone_number_is_required.', 'رقم هاتفك مطلوب.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'select_your_country.', 'يرجى اختيار بلدك.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'select_your_birth_date.', 'يرجى اختيار عيد ميلادك.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'my_location', 'موقعي');
            $lang_update_queries[] = PT_UpdateLangs($value, 'or', 'أو');
            $lang_update_queries[] = PT_UpdateLangs($value, 'instagram', 'إينستاجرام');
            $lang_update_queries[] = PT_UpdateLangs($value, 'disable', 'تعطيل');
            $lang_update_queries[] = PT_UpdateLangs($value, 'enable', 'مكن');
            $lang_update_queries[] = PT_UpdateLangs($value, 'travel_to_another_country__and_relocate_', 'السفر إلى بلد آخر ، والانتقال!');
            $lang_update_queries[] = PT_UpdateLangs($value, '4525', 'الذكر');
            $lang_update_queries[] = PT_UpdateLangs($value, '4526', 'إناثا');
            $lang_update_queries[] = PT_UpdateLangs($value, 'about_you', 'حولك');
            $lang_update_queries[] = PT_UpdateLangs($value, 'km', 'كم');
            $lang_update_queries[] = PT_UpdateLangs($value, 'message_requests', 'طلبات الرسائل');
            $lang_update_queries[] = PT_UpdateLangs($value, 'all_conversations', 'كل المحادثات');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_can_chat_with_this_user_after', 'يمكنك الدردشة مع هذا الملف الشخصي بعد');
            $lang_update_queries[] = PT_UpdateLangs($value, 'hours.', 'ساعات.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_user_decline_your_chat_before_so_you_can_chat_with_this_user_after', 'رفض هذا المستخدم الدردشة من قبل ، وستتمكن من الدردشة مع هذا المستخدم بعد');
            $lang_update_queries[] = PT_UpdateLangs($value, 'active', 'نشيط');
            $lang_update_queries[] = PT_UpdateLangs($value, 'declined', 'رفض');
            $lang_update_queries[] = PT_UpdateLangs($value, 'pending', 'قيد الانتظار');
            $lang_update_queries[] = PT_UpdateLangs($value, 'night_mode', 'وضع الليل');
            $lang_update_queries[] = PT_UpdateLangs($value, 'day_mode', 'وضع اليوم');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we___ll_be_back_soon_', 'سنعود قريبا!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sorry_for_the_inconvenience_but_we_rsquo_re_performing_some_maintenance_at_the_moment._if_you_need_help_you_can_always', 'نأسف للإزعاج لكننا نقوم ببعض الصيانة في الوقت الحالي. إذا كنت بحاجة إلى مساعدة يمكنك دائما');
            $lang_update_queries[] = PT_UpdateLangs($value, 'otherwise_we_rsquo_ll_be_back_online_shortly_', 'وإلا سنعود عبر الإنترنت قريبًا!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details', 'لقد رفضنا تحويلك المصرفي ، يرجى الاتصال بنا للحصول على مزيد من التفاصيل.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accepted_your_message_request_', 'قبلت طلب رسالتك!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'declined_your_message_request_', 'رفض طلب رسالتك!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_approved_your_bank_transfer_of__d_', 'لقد وافقنا على تحويلك المصرفي لـ ٪d!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'note', 'ملحوظة');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_chat', 'حذف الدردشة');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x3_matches_will_expire_in', 'ستنتهي صلاحية مباريات x3 الخاصة بك');
            $lang_update_queries[] = PT_UpdateLangs($value, 'to_get_your_profile_verified_you_have_to_verify_these.', 'للتحقق من ملف التعريف الخاص بك ، يجب عليك التحقق من ذلك.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'upload_at_least_5_image.', 'قم بتحميل 5 صورة على الأقل.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'unblock', 'رفع الحظر');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number__e.g__90..', 'رقم الهاتف ، على سبيل المثال +90 ..');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_verification_needed', 'التحقق من الهاتف مطلوب');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone', 'هاتف');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_otp', 'إرسال OTP');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_activiation_', 'تفعيل الهاتف ،');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_the_verification_code_sent_to_your_phone', 'الرجاء إدخال رمز التحقق الذي تم إرساله إلى هاتفك.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'resend', 'إعادة إرسال');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_verify_your_email_address', 'يرجى التحقق من عنوان البريد الإلكتروني الخاص بك.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_sending_an_sms__please_try_again_later.', 'خطأ أثناء إرسال الرسائل القصيرة ، يرجى المحاولة مرة أخرى لاحقًا.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_submitting_form.', 'خطأ أثناء تقديم النموذج.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_verification_needed', 'التحقق من البريد الإلكتروني مطلوب');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_activiation_', 'تفعيل البريد الإلكتروني ،');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_the_verification_code_sent_to_your_email', 'الرجاء إدخال رمز التحقق الذي تم إرساله إلى بريدك الإلكتروني.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'social_accounts', 'الحسابات الاجتماعية');
            $lang_update_queries[] = PT_UpdateLangs($value, 'current_password', 'كلمة المرور الحالي');
            $lang_update_queries[] = PT_UpdateLangs($value, 'chat_conversations_fetch_successfully', 'تم جلب محادثات الدردشة بنجاح');
            $lang_update_queries[] = PT_UpdateLangs($value, 'messages_deleted_successfully.', 'تم حذف الرسائل بنجاح.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_chat.', 'حذف الدردشة.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'by_creating_your_account__you_agree_to_our', 'بإنشاء حسابك ، أنت توافق على');
            $lang_update_queries[] = PT_UpdateLangs($value, 'choose_your_gender', 'اختيار جنسك');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_phone_number_is_already_exist.', 'رقم الهاتف هذا موجود بالفعل.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_account_', 'حذف الحساب؟');
            $lang_update_queries[] = PT_UpdateLangs($value, 'are_you_sure_you_want_to_delete_your_account__all_content_including_published_photos_and_other_data_will_be_permanetly_removed_', 'هل انت متأكد انك تريد حذف حسابك؟ ستتم إزالة جميع المحتويات بما في ذلك الصور المنشورة والبيانات الأخرى نهائيًا!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete', 'حذف');
            $lang_update_queries[] = PT_UpdateLangs($value, 'enter_a_location', 'أدخل الموقع');
            $lang_update_queries[] = PT_UpdateLangs($value, 'media_message_sent_successfully.', 'تم الارسال.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_are_a_pro_now.', 'أنت محترف الآن!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x10_visits_will_expire_in', 'ستنتهي زياراتك في العاشر');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x4_likes_will_expire_in', 'ستنتهي صلاحية x4 إعجابك');
            $lang_update_queries[] = PT_UpdateLangs($value, 'verification', 'التحقق');
            $lang_update_queries[] = PT_UpdateLangs($value, 'gift_added', 'تم إضافة الهدية بنجاح.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_a_gift_to_you.', 'أرسل هدية لك.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number_cannot_be_empty', 'رقم الهاتف مطلوب');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_add_gift', 'خطأ أثناء إضافة هدية');
            $lang_update_queries[] = PT_UpdateLangs($value, 'profile_avatar_uploaded_successfully.', 'تم تحميل ملف التعريف الشخصي بنجاح.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_add_sticker', 'خطأ أثناء إضافة الملصق');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_saving_notification', 'خطأ أثناء حفظ الإشعار');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sticker_sent_successfully.', 'أرسلت ملصقا بنجاح.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'like_add_successfully.', 'تمت الإضافة بنجاح.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_a_message_', 'ارسلت لك رساله!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details.', 'لقد رفضنا تحويلك المصرفي ، يرجى الاتصال بنا للحصول على مزيد من التفاصيل.');
        } else if ($value == 'dutch') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'social_accounts', 'Sociale accounts');
            $lang_update_queries[] = PT_UpdateLangs($value, 'current_password', 'huidig ​​wachtwoord');
            $lang_update_queries[] = PT_UpdateLangs($value, 'chat_conversations_fetch_successfully', 'Chatgesprekken met succes opgehaald');
            $lang_update_queries[] = PT_UpdateLangs($value, 'messages_deleted_successfully.', 'Berichten zijn succesvol verwijderd.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_chat.', 'Verwijder chat.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'by_creating_your_account__you_agree_to_our', 'Door uw account aan te maken, gaat u akkoord met onze');
            $lang_update_queries[] = PT_UpdateLangs($value, 'choose_your_gender', 'Kies je geslacht');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_phone_number_is_already_exist.', 'Dit telefoonnummer bestaat al.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_account_', 'Account verwijderen?');
            $lang_update_queries[] = PT_UpdateLangs($value, 'are_you_sure_you_want_to_delete_your_account__all_content_including_published_photos_and_other_data_will_be_permanetly_removed_', 'Weet je zeker dat je je account wilt verwijderen? Alle inhoud inclusief gepubliceerde foto\'s en andere gegevens worden permanent verwijderd!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete', 'Verwijder');
            $lang_update_queries[] = PT_UpdateLangs($value, 'enter_a_location', 'Voer een locatie in');
            $lang_update_queries[] = PT_UpdateLangs($value, 'media_message_sent_successfully.', 'Bericht verzonden.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_are_a_pro_now.', 'Je bent nu een pro!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x10_visits_will_expire_in', 'Uw X10-bezoeken verlopen over');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x4_likes_will_expire_in', 'Je x4-likes vervallen binnen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'verification', 'Verificatie');
            $lang_update_queries[] = PT_UpdateLangs($value, 'gift_added', 'Gift succesvol toegevoegd.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_a_gift_to_you.', 'stuurde een geschenk naar jou.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number_cannot_be_empty', 'Telefoonnummer is verplicht');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_add_gift', 'Fout bij het toevoegen van een cadeau');
            $lang_update_queries[] = PT_UpdateLangs($value, 'profile_avatar_uploaded_successfully.', 'Profiel avatar succesvol geüpload.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_add_sticker', 'Fout tijdens het toevoegen van de sticker');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_saving_notification', 'Fout tijdens het opslaan van de melding');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sticker_sent_successfully.', 'Sticker succesvol verzonden.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'like_add_successfully.', 'Zoals succesvol toegevoegd.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_a_message_', 'heeft je een bericht gestuurd!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details.', 'We hebben uw overboeking geweigerd. Neem contact met ons op voor meer informatie.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number__e.g__90..', 'Telefoonnummer, bijvoorbeeld +90 ..');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_verification_needed', 'Telefonische verificatie vereist');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone', 'Telefoon');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_otp', 'Stuur OTP');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_activiation_', 'Telefoonactivatie,');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_the_verification_code_sent_to_your_phone', 'Voer de verificatiecode in die naar uw telefoon is verzonden.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'resend', 'Opnieuw versturen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_verify_your_email_address', 'Verifieer uw email adres alstublieft.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_sending_an_sms__please_try_again_later.', 'Fout tijdens het verzenden van de sms, probeer het later opnieuw.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_submitting_form.', 'Fout bij het verzenden van het formulier.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_verification_needed', 'E-mailverificatie vereist');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_activiation_', 'E-mailactivatie,');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_the_verification_code_sent_to_your_email', 'Voer de verificatiecode in die naar uw e-mailadres is verzonden.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'bank_transfer', 'Overschrijving');
            $lang_update_queries[] = PT_UpdateLangs($value, 'close', 'Dichtbij');
            $lang_update_queries[] = PT_UpdateLangs($value, 'bank_information', 'Bank informatie');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_transfer_the_amount_of', 'Gelieve het bedrag over te maken');
            $lang_update_queries[] = PT_UpdateLangs($value, 'to_this_bank_account_to_buy', 'om deze bankrekening te kopen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'upload_receipt', 'Upload ontvangst');
            $lang_update_queries[] = PT_UpdateLangs($value, 'confirm', 'Bevestigen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_receipt_uploaded_successfully.', 'Uw kwitantie is succesvol geüpload.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'date', 'Datum');
            $lang_update_queries[] = PT_UpdateLangs($value, 'processed_by', 'Verwerkt door');
            $lang_update_queries[] = PT_UpdateLangs($value, 'amount', 'Bedrag');
            $lang_update_queries[] = PT_UpdateLangs($value, 'type', 'Type');
            $lang_update_queries[] = PT_UpdateLangs($value, 'notes', 'Notes');
            $lang_update_queries[] = PT_UpdateLangs($value, 'plan_premium_membership', 'Plan Premium-lidmaatschap');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_boost_will_expire_in', 'Je boost verloopt over');
            $lang_update_queries[] = PT_UpdateLangs($value, 'hide', 'Verbergen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_reach_the_max_of_swipes_per_day._you_have_to_wait__0__hours_before_you_can_redo_likes_or_upgrade_to_pro_to_for_unlimited.', 'Je hebt de maximale hoeveelheid swipes per dag bereikt, je moet {0} uur wachten voordat je veegbewegingen opnieuw kunt uitvoeren OF OF nu upgraden naar Pro-lidmaatschap voor onbeperkte swipes en vind-ik-leuks.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_payment_was_processed_successfully.', 'Uw betaling is verwerkt.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sms', 'sms');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_message_', 'heeft je een bericht gestuurd!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_a_message_', 'heeft je een bericht gestuurd!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accept', 'Aanvaarden');
            $lang_update_queries[] = PT_UpdateLangs($value, 'decline', 'Afwijzen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'calling', 'Roeping');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_wait_for_your_friend_answer.', 'Wacht alstublieft op het antwoord van uw vriend.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_answer', 'Geen antwoord');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_try_again_later.', 'Probeer het later opnieuw.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'new_video_call', 'Nieuw binnenkomend videogesprek');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wants_to_video_chat_with_you.', 'wil videochatten met jou.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'call_declined', 'Oproep geweigerd');
            $lang_update_queries[] = PT_UpdateLangs($value, 'the_recipient_has_declined_the_call__please_try_again_later.', 'De ontvanger heeft de oproep geweigerd. Probeer het later opnieuw.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accept___start', 'Accepteren en starten');
            $lang_update_queries[] = PT_UpdateLangs($value, 'answered__', 'Beantwoord!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_wait..', 'Even geduld aub..');
            $lang_update_queries[] = PT_UpdateLangs($value, 'video_call', 'Video-oproep');
            $lang_update_queries[] = PT_UpdateLangs($value, 'new_audio_call', 'Nieuwe inkomende audio-oproep');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wants_to_talk_with_you.', 'wil met je praten.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'audio_call', 'Audio-oproep');
            $lang_update_queries[] = PT_UpdateLangs($value, 'talking_with', 'praten met');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_website_uses_cookies_to_ensure_you_get_the_best_experience_on_our_website.', 'Deze website maakt gebruik van cookies om ervoor te zorgen dat u de beste ervaring op onze website krijgt.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'got_it_', 'Begrepen!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'learn_more', 'Kom meer te weten');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_result_found', 'geen resultaat gevonden');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_gif', 'Verzend GIF');
            $lang_update_queries[] = PT_UpdateLangs($value, 'search_gifs', 'Zoek GIF\'s');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sticker_added', 'Sticker toegevoegd');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_phone_number_is_required.', 'Uw telefoonnummer is verplicht');
            $lang_update_queries[] = PT_UpdateLangs($value, 'select_your_country.', 'Selecteer uw land alstublieft.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'select_your_birth_date.', 'Selecteer alstublieft uw geboortedatum');
            $lang_update_queries[] = PT_UpdateLangs($value, 'my_location', 'Mijn locatie');
            $lang_update_queries[] = PT_UpdateLangs($value, 'or', 'OF');
            $lang_update_queries[] = PT_UpdateLangs($value, 'instagram', 'Instagram');
            $lang_update_queries[] = PT_UpdateLangs($value, 'disable', 'onbruikbaar maken');
            $lang_update_queries[] = PT_UpdateLangs($value, 'enable', 'in staat stellen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'travel_to_another_country__and_relocate_', 'Reis naar een ander land en verplaats je!');
            $lang_update_queries[] = PT_UpdateLangs($value, '4525', 'Mannetje');
            $lang_update_queries[] = PT_UpdateLangs($value, '4526', 'Vrouw');
            $lang_update_queries[] = PT_UpdateLangs($value, 'about_you', 'Over jou');
            $lang_update_queries[] = PT_UpdateLangs($value, 'km', 'km');
            $lang_update_queries[] = PT_UpdateLangs($value, 'message_requests', 'Berichtverzoeken');
            $lang_update_queries[] = PT_UpdateLangs($value, 'all_conversations', 'Alle gesprekken');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_can_chat_with_this_user_after', 'Je kunt na dit chatten met dit profiel');
            $lang_update_queries[] = PT_UpdateLangs($value, 'hours.', 'uur.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_user_decline_your_chat_before_so_you_can_chat_with_this_user_after', 'Deze gebruiker heeft je chat eerder afgewezen, je kunt hierna met deze gebruiker chatten');
            $lang_update_queries[] = PT_UpdateLangs($value, 'active', 'actief');
            $lang_update_queries[] = PT_UpdateLangs($value, 'declined', 'Afgewezen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'pending', 'In afwachting');
            $lang_update_queries[] = PT_UpdateLangs($value, 'night_mode', 'Nachtstand');
            $lang_update_queries[] = PT_UpdateLangs($value, 'day_mode', 'Dagmodus');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we___ll_be_back_soon_', 'We zullen snel terug zijn!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sorry_for_the_inconvenience_but_we_rsquo_re_performing_some_maintenance_at_the_moment._if_you_need_help_you_can_always', 'Sorry voor het ongemak maar we voeren momenteel wat onderhoud uit. Als je hulp nodig hebt, kan dat altijd');
            $lang_update_queries[] = PT_UpdateLangs($value, 'otherwise_we_rsquo_ll_be_back_online_shortly_', 'anders zijn we binnenkort weer online!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details', 'We hebben uw overboeking geweigerd. Neem contact met ons op voor meer informatie.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accepted_your_message_request_', 'accepteerde uw berichtaanvraag!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'declined_your_message_request_', 'heeft uw berichtverzoek geweigerd!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_approved_your_bank_transfer_of__d_', 'We hebben uw overboeking van %d goedgekeurd!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'note', 'Notitie');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_chat', 'Verwijder chat');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x3_matches_will_expire_in', 'Je x3-wedstrijden verlopen in');
            $lang_update_queries[] = PT_UpdateLangs($value, 'to_get_your_profile_verified_you_have_to_verify_these.', 'Om je profiel geverifieerd te krijgen, moet je deze verifiëren.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'upload_at_least_5_image.', 'Upload minimaal 5 afbeeldingen.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'unblock', 'deblokkeren');
        } else if ($value == 'french') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'social_accounts', 'Comptes sociaux');
            $lang_update_queries[] = PT_UpdateLangs($value, 'current_password', 'Mot de passe actuel');
            $lang_update_queries[] = PT_UpdateLangs($value, 'chat_conversations_fetch_successfully', 'Conversations de conversation récupérées avec succès');
            $lang_update_queries[] = PT_UpdateLangs($value, 'messages_deleted_successfully.', 'Les messages ont été supprimés avec succès.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_chat.', 'Supprimer le chat.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'by_creating_your_account__you_agree_to_our', 'En créant votre compte, vous acceptez notre');
            $lang_update_queries[] = PT_UpdateLangs($value, 'choose_your_gender', 'Choisissez votre genre');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_phone_number_is_already_exist.', 'Ce numéro de téléphone existe déjà.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_account_', 'Supprimer le compte?');
            $lang_update_queries[] = PT_UpdateLangs($value, 'are_you_sure_you_want_to_delete_your_account__all_content_including_published_photos_and_other_data_will_be_permanetly_removed_', 'Êtes-vous sûr de vouloir supprimer votre compte? Tout le contenu, y compris les photos publiées et autres données, sera définitivement supprimé!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete', 'Effacer');
            $lang_update_queries[] = PT_UpdateLangs($value, 'enter_a_location', 'Entrez un lieu');
            $lang_update_queries[] = PT_UpdateLangs($value, 'media_message_sent_successfully.', 'Message envoyé.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_are_a_pro_now.', 'Vous êtes un pro maintenant!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x10_visits_will_expire_in', 'Vos visites x10 expireront dans');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x4_likes_will_expire_in', 'Vos goûts x4 expireront dans');
            $lang_update_queries[] = PT_UpdateLangs($value, 'verification', 'Vérification');
            $lang_update_queries[] = PT_UpdateLangs($value, 'gift_added', 'Cadeau ajouté avec succès.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_a_gift_to_you.', 'envoyé un cadeau pour vous.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number_cannot_be_empty', 'Le numéro de téléphone est obligatoire');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_add_gift', 'Erreur lors de l\'ajout d\'un cadeau');
            $lang_update_queries[] = PT_UpdateLangs($value, 'profile_avatar_uploaded_successfully.', 'Avatar du profil chargé avec succès.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_add_sticker', 'Erreur lors de l\'ajout de la vignette');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_saving_notification', 'Erreur lors de l\'enregistrement de la notification');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sticker_sent_successfully.', 'Autocollant envoyé avec succès.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'like_add_successfully.', 'Comme ajouté avec succès.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_a_message_', 'Vous a envoyé un message!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details.', 'Nous avons refusé votre virement bancaire, veuillez nous contacter pour plus de détails.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number__e.g__90..', 'Numéro de téléphone, par exemple +90 ..');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_verification_needed', 'Vérification téléphonique requise');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone', 'Téléphone');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_otp', 'Envoyer OTP');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_activiation_', 'Activation du téléphone,');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_the_verification_code_sent_to_your_phone', 'Veuillez entrer le code de vérification qui a été envoyé sur votre téléphone.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'resend', 'Renvoyer');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_verify_your_email_address', 'Veuillez vérifier votre adresse e-mail.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_sending_an_sms__please_try_again_later.', 'Erreur lors de l\'envoi du SMS, veuillez réessayer ultérieurement.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_submitting_form.', 'Erreur lors de la soumission du formulaire.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_verification_needed', 'Vérification d\'email requise');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_activiation_', 'Activation du courrier électronique,');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_the_verification_code_sent_to_your_email', 'Veuillez entrer le code de vérification qui a été envoyé à votre adresse e-mail.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'bank_transfer', 'Virement');
            $lang_update_queries[] = PT_UpdateLangs($value, 'close', 'Fermer');
            $lang_update_queries[] = PT_UpdateLangs($value, 'bank_information', 'Information bancaire');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_transfer_the_amount_of', 'S\'il vous plaît transférer le montant de');
            $lang_update_queries[] = PT_UpdateLangs($value, 'to_this_bank_account_to_buy', 'à ce compte bancaire pour acheter');
            $lang_update_queries[] = PT_UpdateLangs($value, 'upload_receipt', 'Télécharger le reçu');
            $lang_update_queries[] = PT_UpdateLangs($value, 'confirm', 'Confirmer');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_receipt_uploaded_successfully.', 'Votre reçu a été téléchargé avec succès.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'date', 'Rendez-vous amoureux');
            $lang_update_queries[] = PT_UpdateLangs($value, 'processed_by', 'Traité par');
            $lang_update_queries[] = PT_UpdateLangs($value, 'amount', 'Montant');
            $lang_update_queries[] = PT_UpdateLangs($value, 'type', 'Type');
            $lang_update_queries[] = PT_UpdateLangs($value, 'notes', 'Remarques');
            $lang_update_queries[] = PT_UpdateLangs($value, 'plan_premium_membership', 'Planifier l\'abonnement Premium');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_boost_will_expire_in', 'Votre boost expirera dans');
            $lang_update_queries[] = PT_UpdateLangs($value, 'hide', 'Cacher');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_reach_the_max_of_swipes_per_day._you_have_to_wait__0__hours_before_you_can_redo_likes_or_upgrade_to_pro_to_for_unlimited.', 'Vous avez atteint le maximum de balayages par jour, vous devez patienter {0} heures avant de pouvoir refaire les balayages, OU passer maintenant à l’abonnement Pro pour des balayages et des goûts illimités.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_payment_was_processed_successfully.', 'Votre paiement a été traité avec succès.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sms', 'SMS');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_message_', 'Vous a envoyé un message!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_a_message_', 'Vous a envoyé un message!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accept', 'Acceptez');
            $lang_update_queries[] = PT_UpdateLangs($value, 'decline', 'Déclin');
            $lang_update_queries[] = PT_UpdateLangs($value, 'calling', 'Appel');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_wait_for_your_friend_answer.', 'S\'il vous plaît attendre la réponse de votre ami.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_answer', 'Pas de réponse');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_try_again_later.', 'Veuillez réessayer plus tard.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'new_video_call', 'Nouvel appel vidéo entrant');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wants_to_video_chat_with_you.', 'veut discuter par vidéo avec vous.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'call_declined', 'Appel refusé');
            $lang_update_queries[] = PT_UpdateLangs($value, 'the_recipient_has_declined_the_call__please_try_again_later.', 'Le destinataire a refusé l\'appel. Veuillez réessayer ultérieurement.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accept___start', 'Accepter et démarrer');
            $lang_update_queries[] = PT_UpdateLangs($value, 'answered__', 'Répondu!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_wait..', 'S\'il vous plaît, attendez..');
            $lang_update_queries[] = PT_UpdateLangs($value, 'video_call', 'Appel vidéo');
            $lang_update_queries[] = PT_UpdateLangs($value, 'new_audio_call', 'Nouvel appel audio entrant');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wants_to_talk_with_you.', 'veut parler avec toi.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'audio_call', 'Appel audio');
            $lang_update_queries[] = PT_UpdateLangs($value, 'talking_with', 'parler avec');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_website_uses_cookies_to_ensure_you_get_the_best_experience_on_our_website.', 'Ce site utilise des cookies pour vous garantir la meilleure expérience sur notre site.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'got_it_', 'Je l\'ai!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'learn_more', 'Apprendre encore plus');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_result_found', 'Aucun résultat trouvé');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_gif', 'Envoyer un GIF');
            $lang_update_queries[] = PT_UpdateLangs($value, 'search_gifs', 'Rechercher des GIF');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sticker_added', 'Sticker ajouté');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_phone_number_is_required.', 'Votre numéro de téléphone est requis.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'select_your_country.', 'S\'il vous plaît sélectionnez votre pays.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'select_your_birth_date.', 'S\'il vous plaît sélectionnez votre anniversaire.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'my_location', 'Ma position');
            $lang_update_queries[] = PT_UpdateLangs($value, 'or', 'OU');
            $lang_update_queries[] = PT_UpdateLangs($value, 'instagram', 'Instagram');
            $lang_update_queries[] = PT_UpdateLangs($value, 'disable', 'désactiver');
            $lang_update_queries[] = PT_UpdateLangs($value, 'enable', 'activer');
            $lang_update_queries[] = PT_UpdateLangs($value, 'travel_to_another_country__and_relocate_', 'Voyagez dans un autre pays et déplacez-vous!');
            $lang_update_queries[] = PT_UpdateLangs($value, '4525', 'Mâle');
            $lang_update_queries[] = PT_UpdateLangs($value, '4526', 'Femelle');
            $lang_update_queries[] = PT_UpdateLangs($value, 'about_you', 'Au propos de vous');
            $lang_update_queries[] = PT_UpdateLangs($value, 'km', 'km');
            $lang_update_queries[] = PT_UpdateLangs($value, 'message_requests', 'Demandes de message');
            $lang_update_queries[] = PT_UpdateLangs($value, 'all_conversations', 'Toutes les conversations');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_can_chat_with_this_user_after', 'Vous pouvez discuter avec ce profil après');
            $lang_update_queries[] = PT_UpdateLangs($value, 'hours.', 'heures.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_user_decline_your_chat_before_so_you_can_chat_with_this_user_after', 'Cet utilisateur a déjà refusé votre chat, vous pourrez discuter avec lui après');
            $lang_update_queries[] = PT_UpdateLangs($value, 'active', 'actif');
            $lang_update_queries[] = PT_UpdateLangs($value, 'declined', 'Diminué');
            $lang_update_queries[] = PT_UpdateLangs($value, 'pending', 'en attendant');
            $lang_update_queries[] = PT_UpdateLangs($value, 'night_mode', 'Mode nuit');
            $lang_update_queries[] = PT_UpdateLangs($value, 'day_mode', 'Mode jour');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we___ll_be_back_soon_', 'Nous reviendrons bientôt!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sorry_for_the_inconvenience_but_we_rsquo_re_performing_some_maintenance_at_the_moment._if_you_need_help_you_can_always', 'Désolé pour le désagrément, mais nous effectuons actuellement des travaux de maintenance. Si vous avez besoin d\'aide, vous pouvez toujours');
            $lang_update_queries[] = PT_UpdateLangs($value, 'otherwise_we_rsquo_ll_be_back_online_shortly_', 'sinon nous serons de retour en ligne sous peu!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details', 'Nous avons refusé votre virement bancaire, veuillez nous contacter pour plus de détails.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accepted_your_message_request_', 'accepté votre demande de message!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'declined_your_message_request_', 'a refusé votre demande de message!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_approved_your_bank_transfer_of__d_', 'Nous avons approuvé votre virement bancaire de %d!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'note', 'Remarque');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_chat', 'Supprimer le chat');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x3_matches_will_expire_in', 'Vos matchs x3 expireront dans');
            $lang_update_queries[] = PT_UpdateLangs($value, 'to_get_your_profile_verified_you_have_to_verify_these.', 'Pour que votre profil soit vérifié, vous devez les vérifier.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'upload_at_least_5_image.', 'Téléchargez au moins 5 images.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'unblock', 'Débloquer');
        } else if ($value == 'german') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'social_accounts', 'Soziale Konten');
            $lang_update_queries[] = PT_UpdateLangs($value, 'current_password', 'derzeitiges Passwort');
            $lang_update_queries[] = PT_UpdateLangs($value, 'chat_conversations_fetch_successfully', 'Chat-Unterhaltungen wurden erfolgreich abgerufen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'messages_deleted_successfully.', 'Nachrichten wurden erfolgreich gelöscht.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_chat.', 'Chat löschen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'by_creating_your_account__you_agree_to_our', 'Durch die Erstellung Ihres Kontos stimmen Sie unserem zu');
            $lang_update_queries[] = PT_UpdateLangs($value, 'choose_your_gender', 'Wählen Sie ihr Geschlecht');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_phone_number_is_already_exist.', 'Diese Telefonnummer ist bereits vorhanden.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_account_', 'Konto löschen?');
            $lang_update_queries[] = PT_UpdateLangs($value, 'are_you_sure_you_want_to_delete_your_account__all_content_including_published_photos_and_other_data_will_be_permanetly_removed_', 'Möchten Sie Ihr Konto wirklich löschen? Alle Inhalte einschließlich veröffentlichter Fotos und anderer Daten werden dauerhaft entfernt!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete', 'Löschen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'enter_a_location', 'Ort eingeben');
            $lang_update_queries[] = PT_UpdateLangs($value, 'media_message_sent_successfully.', 'Nachricht gesendet.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_are_a_pro_now.', 'Du bist jetzt ein Profi!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x10_visits_will_expire_in', 'Ihre x10 Besuche laufen in ab');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x4_likes_will_expire_in', 'Ihre x4 Likes verfallen in');
            $lang_update_queries[] = PT_UpdateLangs($value, 'verification', 'Nachprüfung');
            $lang_update_queries[] = PT_UpdateLangs($value, 'gift_added', 'Geschenk erfolgreich hinzugefügt');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_a_gift_to_you.', 'Ich habe Ihnen ein Geschenk geschickt.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number_cannot_be_empty', 'Telefonnummer ist erforderlich');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_add_gift', 'Fehler beim Hinzufügen eines Geschenks');
            $lang_update_queries[] = PT_UpdateLangs($value, 'profile_avatar_uploaded_successfully.', 'Profil-Avatar wurde erfolgreich hochgeladen.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_add_sticker', 'Fehler beim Hinzufügen des Aufklebers');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_saving_notification', 'Fehler beim Speichern der Benachrichtigung');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sticker_sent_successfully.', 'Aufkleber erfolgreich gesendet');
            $lang_update_queries[] = PT_UpdateLangs($value, 'like_add_successfully.', 'Wie erfolgreich hinzugefügt.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_a_message_', 'Schickte dir eine Nachricht!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details.', 'Wir haben Ihre Banküberweisung abgelehnt. Bitte kontaktieren Sie uns für weitere Informationen.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number__e.g__90..', 'Telefonnummer, z. B. +90 ..');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_verification_needed', 'Telefonische Bestätigung erforderlich');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone', 'Telefon');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_otp', 'OTP senden');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_activiation_', 'Telefonaktivierung,');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_the_verification_code_sent_to_your_phone', 'Bitte geben Sie den Bestätigungscode ein, der an Ihr Telefon gesendet wurde.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'resend', 'Erneut senden');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_verify_your_email_address', 'Bitte bestätige deine Email Adresse.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_sending_an_sms__please_try_again_later.', 'Fehler beim Senden der SMS. Bitte versuchen Sie es später erneut.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_submitting_form.', 'Fehler beim Senden des Formulars');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_verification_needed', 'E-Mail-Bestätigung erforderlich');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_activiation_', 'E-Mail-Aktivierung');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_the_verification_code_sent_to_your_email', 'Bitte geben Sie den Bestätigungscode ein, der an Ihre E-Mail gesendet wurde.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'bank_transfer', 'Banküberweisung');
            $lang_update_queries[] = PT_UpdateLangs($value, 'close', 'Schließen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'bank_information', 'Bank Informationen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_transfer_the_amount_of', 'Bitte überweisen Sie den Betrag von');
            $lang_update_queries[] = PT_UpdateLangs($value, 'to_this_bank_account_to_buy', 'zu diesem Bankkonto kaufen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'upload_receipt', 'Quittung hochladen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'confirm', 'Bestätigen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_receipt_uploaded_successfully.', 'Ihre Quittung wurde erfolgreich hochgeladen.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'date', 'Datum');
            $lang_update_queries[] = PT_UpdateLangs($value, 'processed_by', 'Verarbeitet von');
            $lang_update_queries[] = PT_UpdateLangs($value, 'amount', 'Menge');
            $lang_update_queries[] = PT_UpdateLangs($value, 'type', 'Art');
            $lang_update_queries[] = PT_UpdateLangs($value, 'notes', 'Anmerkungen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'plan_premium_membership', 'Planen Sie die Premium-Mitgliedschaft');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_boost_will_expire_in', 'Ihr Boost läuft in ab');
            $lang_update_queries[] = PT_UpdateLangs($value, 'hide', 'verbergen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_reach_the_max_of_swipes_per_day._you_have_to_wait__0__hours_before_you_can_redo_likes_or_upgrade_to_pro_to_for_unlimited.', 'Sie haben die maximale Anzahl an Wischvorgängen pro Tag erreicht. Sie müssen {0} Stunden warten, bis Sie die Wischvorgänge wiederholen können, oder ein Upgrade auf Pro-Mitgliedschaft durchführen, um unbegrenzt Wischvorgänge und Likes durchzuführen.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_payment_was_processed_successfully.', 'Ihre Zahlung wurde erfolgreich verarbeitet.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sms', 'SMS');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_message_', 'Schickte dir eine Nachricht!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_a_message_', 'Schickte dir eine Nachricht!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accept', 'Akzeptieren');
            $lang_update_queries[] = PT_UpdateLangs($value, 'decline', 'Ablehnen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'calling', 'Berufung');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_wait_for_your_friend_answer.', 'Bitte warten Sie auf die Antwort Ihres Freundes.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_answer', 'Keine Antwort');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_try_again_later.', 'Bitte versuchen Sie es später erneut.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'new_video_call', 'Neu eingehender Videoanruf');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wants_to_video_chat_with_you.', 'möchte mit dir video chatten.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'call_declined', 'Anruf abgelehnt');
            $lang_update_queries[] = PT_UpdateLangs($value, 'the_recipient_has_declined_the_call__please_try_again_later.', 'Der Empfänger hat den Anruf abgelehnt. Bitte versuchen Sie es später erneut.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accept___start', 'Akzeptieren und starten');
            $lang_update_queries[] = PT_UpdateLangs($value, 'answered__', 'Antwortete!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_wait..', 'Warten Sie mal..');
            $lang_update_queries[] = PT_UpdateLangs($value, 'video_call', 'Videoanruf');
            $lang_update_queries[] = PT_UpdateLangs($value, 'new_audio_call', 'Neuer eingehender Audioanruf');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wants_to_talk_with_you.', 'will mit dir reden');
            $lang_update_queries[] = PT_UpdateLangs($value, 'audio_call', 'Audioanruf');
            $lang_update_queries[] = PT_UpdateLangs($value, 'talking_with', 'sprechen mit');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_website_uses_cookies_to_ensure_you_get_the_best_experience_on_our_website.', 'Diese Website verwendet Cookies, um sicherzustellen, dass Sie das beste Erlebnis auf unserer Website erhalten.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'got_it_', 'Ich habs!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'learn_more', 'Lern mehr');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_result_found', 'Keine Einträge gefunden');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_gif', 'Senden Sie GIF');
            $lang_update_queries[] = PT_UpdateLangs($value, 'search_gifs', 'GIFs durchsuchen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sticker_added', 'Aufkleber hinzugefügt');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_phone_number_is_required.', 'Ihre Telefonnummer ist erforderlich.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'select_your_country.', 'Bitte wählen Sie Ihr Land.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'select_your_birth_date.', 'Bitte wählen Sie Ihren Geburtstag aus.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'my_location', 'Mein Standort');
            $lang_update_queries[] = PT_UpdateLangs($value, 'or', 'ODER');
            $lang_update_queries[] = PT_UpdateLangs($value, 'instagram', 'Instagram');
            $lang_update_queries[] = PT_UpdateLangs($value, 'disable', 'deaktivieren');
            $lang_update_queries[] = PT_UpdateLangs($value, 'enable', 'aktivieren');
            $lang_update_queries[] = PT_UpdateLangs($value, 'travel_to_another_country__and_relocate_', 'Reisen Sie in ein anderes Land und ziehen Sie um!');
            $lang_update_queries[] = PT_UpdateLangs($value, '4525', 'Männlich');
            $lang_update_queries[] = PT_UpdateLangs($value, '4526', 'Weiblich');
            $lang_update_queries[] = PT_UpdateLangs($value, 'about_you', 'Über dich');
            $lang_update_queries[] = PT_UpdateLangs($value, 'km', 'km');
            $lang_update_queries[] = PT_UpdateLangs($value, 'message_requests', 'Nachrichtenanfragen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'all_conversations', 'Alle Gespräche');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_can_chat_with_this_user_after', 'Sie können danach mit diesem Profil chatten');
            $lang_update_queries[] = PT_UpdateLangs($value, 'hours.', 'Std.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_user_decline_your_chat_before_so_you_can_chat_with_this_user_after', 'Dieser Benutzer hat Ihren Chat zuvor abgelehnt. Danach können Sie mit diesem Benutzer chatten');
            $lang_update_queries[] = PT_UpdateLangs($value, 'active', 'aktiv');
            $lang_update_queries[] = PT_UpdateLangs($value, 'declined', 'Abgelehnt');
            $lang_update_queries[] = PT_UpdateLangs($value, 'pending', 'steht aus');
            $lang_update_queries[] = PT_UpdateLangs($value, 'night_mode', 'Nacht-Modus');
            $lang_update_queries[] = PT_UpdateLangs($value, 'day_mode', 'Tagesmodus');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we___ll_be_back_soon_', 'Wir werden bald zurück sein!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sorry_for_the_inconvenience_but_we_rsquo_re_performing_some_maintenance_at_the_moment._if_you_need_help_you_can_always', 'Wir entschuldigen uns für die Unannehmlichkeiten, aber wir führen zurzeit einige Wartungsarbeiten durch. Wenn Sie Hilfe brauchen, können Sie das immer tun');
            $lang_update_queries[] = PT_UpdateLangs($value, 'otherwise_we_rsquo_ll_be_back_online_shortly_', 'Ansonsten sind wir in Kürze wieder online!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details', 'Wir haben Ihre Banküberweisung abgelehnt. Bitte kontaktieren Sie uns für weitere Informationen.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accepted_your_message_request_', 'Ihre Anfrage angenommen!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'declined_your_message_request_', 'Ihre Nachricht wurde abgelehnt!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_approved_your_bank_transfer_of__d_', 'Wir haben Ihrer Überweisung von %d zugestimmt!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'note', 'Hinweis');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_chat', 'Chat löschen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x3_matches_will_expire_in', 'Ihre x3-Matches verfallen in');
            $lang_update_queries[] = PT_UpdateLangs($value, 'to_get_your_profile_verified_you_have_to_verify_these.', 'Um Ihr Profil bestätigen zu lassen, müssen Sie diese überprüfen.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'upload_at_least_5_image.', 'Laden Sie mindestens 5 Bilder hoch.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'unblock', 'Blockierung aufheben');
        } else if ($value == 'italian') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'social_accounts', 'Account sociali');
            $lang_update_queries[] = PT_UpdateLangs($value, 'current_password', 'Password attuale');
            $lang_update_queries[] = PT_UpdateLangs($value, 'chat_conversations_fetch_successfully', 'Le conversazioni di chat sono state recuperate con successo');
            $lang_update_queries[] = PT_UpdateLangs($value, 'messages_deleted_successfully.', 'I messaggi sono stati cancellati con successo.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_chat.', 'Elimina chat.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'by_creating_your_account__you_agree_to_our', 'Creando il tuo account, accetti i nostri');
            $lang_update_queries[] = PT_UpdateLangs($value, 'choose_your_gender', 'Scegli il tuo genere');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_phone_number_is_already_exist.', 'Questo numero di telefono è già esistente.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_account_', 'Eliminare l\'account?');
            $lang_update_queries[] = PT_UpdateLangs($value, 'are_you_sure_you_want_to_delete_your_account__all_content_including_published_photos_and_other_data_will_be_permanetly_removed_', 'Sei sicuro di voler cancellare il tuo account? Tutti i contenuti, incluse le foto pubblicate e altri dati, verranno rimossi definitivamente!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete', 'Elimina');
            $lang_update_queries[] = PT_UpdateLangs($value, 'enter_a_location', 'Inserisci una posizione');
            $lang_update_queries[] = PT_UpdateLangs($value, 'media_message_sent_successfully.', 'Messaggio inviato.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_are_a_pro_now.', 'Sei un professionista ora!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x10_visits_will_expire_in', 'Le tue visite x10 scadranno');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x4_likes_will_expire_in', 'I tuoi Mi piace x4 scadranno');
            $lang_update_queries[] = PT_UpdateLangs($value, 'verification', 'Verifica');
            $lang_update_queries[] = PT_UpdateLangs($value, 'gift_added', 'Regalo aggiunto con successo.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_a_gift_to_you.', 'ti ho inviato un regalo.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number_cannot_be_empty', 'Il numero di telefono è richiesto');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_add_gift', 'Errore durante l\'aggiunta di un regalo');
            $lang_update_queries[] = PT_UpdateLangs($value, 'profile_avatar_uploaded_successfully.', 'Avatar profilo caricato correttamente.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_add_sticker', 'Errore durante l\'aggiunta dell\'adesivo');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_saving_notification', 'Errore durante il salvataggio della notifica');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sticker_sent_successfully.', 'Adesivo inviato con successo.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'like_add_successfully.', 'Come aggiunto con successo.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_a_message_', 'ti ho mandato un messaggio!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details.', 'Abbiamo rifiutato il tuo bonifico bancario, ti preghiamo di contattarci per ulteriori dettagli.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number__e.g__90..', 'Numero di telefono, ad esempio +90 ..');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_verification_needed', 'È richiesta la verifica del telefono');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone', 'Telefono');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_otp', 'Invia OTP');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_activiation_', 'Attivazione del telefono,');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_the_verification_code_sent_to_your_phone', 'Inserisci il codice di verifica che è stato inviato al tuo telefono.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'resend', 'Re-send');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_verify_your_email_address', 'Per cortesia verifichi il suo indirizzo email.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_sending_an_sms__please_try_again_later.', 'Errore durante l\'invio dell\'SMS, riprova più tardi.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_submitting_form.', 'Errore durante l\'invio del modulo.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_verification_needed', 'Richiesta la verifica tramite email');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_activiation_', 'Attivazione dell\'email,');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_the_verification_code_sent_to_your_email', 'Inserisci il codice di verifica che è stato inviato alla tua e-mail.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'bank_transfer', 'Trasferimento bancario');
            $lang_update_queries[] = PT_UpdateLangs($value, 'close', 'Vicino');
            $lang_update_queries[] = PT_UpdateLangs($value, 'bank_information', 'Informazioni bancarie');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_transfer_the_amount_of', 'Si prega di trasferire l\'importo di');
            $lang_update_queries[] = PT_UpdateLangs($value, 'to_this_bank_account_to_buy', 'a questo conto bancario per l\'acquisto');
            $lang_update_queries[] = PT_UpdateLangs($value, 'upload_receipt', 'Carica ricevuta');
            $lang_update_queries[] = PT_UpdateLangs($value, 'confirm', 'Confermare');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_receipt_uploaded_successfully.', 'La tua ricevuta è stata caricata correttamente.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'date', 'Data');
            $lang_update_queries[] = PT_UpdateLangs($value, 'processed_by', 'Elaborato da');
            $lang_update_queries[] = PT_UpdateLangs($value, 'amount', 'Quantità');
            $lang_update_queries[] = PT_UpdateLangs($value, 'type', 'genere');
            $lang_update_queries[] = PT_UpdateLangs($value, 'notes', 'Gli appunti');
            $lang_update_queries[] = PT_UpdateLangs($value, 'plan_premium_membership', 'Pianifica l\'abbonamento Premium');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_boost_will_expire_in', 'La tua spinta scadrà nel');
            $lang_update_queries[] = PT_UpdateLangs($value, 'hide', 'Nascondere');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_reach_the_max_of_swipes_per_day._you_have_to_wait__0__hours_before_you_can_redo_likes_or_upgrade_to_pro_to_for_unlimited.', 'Hai raggiunto il limite massimo di swipes al giorno, devi aspettare {0} ore prima di poter ripetere i passaggi, OPPURE effettuare l\'upgrade ora a Pro Membership per swipes e likes illimitati.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_payment_was_processed_successfully.', 'Il tuo pagamento è stato elaborato correttamente.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sms', 'sms');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_message_', 'ti ho mandato un messaggio!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_a_message_', 'ti ho mandato un messaggio!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accept', 'Accettare');
            $lang_update_queries[] = PT_UpdateLangs($value, 'decline', 'Declino');
            $lang_update_queries[] = PT_UpdateLangs($value, 'calling', 'chiamata');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_wait_for_your_friend_answer.', 'Per favore aspetta la risposta del tuo amico.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_answer', 'Nessuna risposta');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_try_again_later.', 'Per favore riprova più tardi.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'new_video_call', 'Nuova videochiamata in arrivo');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wants_to_video_chat_with_you.', 'vuole chat video con te.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'call_declined', 'Chiamata rifiutata');
            $lang_update_queries[] = PT_UpdateLangs($value, 'the_recipient_has_declined_the_call__please_try_again_later.', 'Il destinatario ha rifiutato la chiamata, riprova più tardi.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accept___start', 'Accetta e inizia');
            $lang_update_queries[] = PT_UpdateLangs($value, 'answered__', 'Risposto!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_wait..', 'Attendere prego..');
            $lang_update_queries[] = PT_UpdateLangs($value, 'video_call', 'Video chiamata');
            $lang_update_queries[] = PT_UpdateLangs($value, 'new_audio_call', 'Nuova chiamata audio in arrivo');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wants_to_talk_with_you.', 'vuole parlare con te');
            $lang_update_queries[] = PT_UpdateLangs($value, 'audio_call', 'Chiamata audio');
            $lang_update_queries[] = PT_UpdateLangs($value, 'talking_with', 'parlando con');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_website_uses_cookies_to_ensure_you_get_the_best_experience_on_our_website.', 'Questo sito Web utilizza i cookie per assicurarti di ottenere la migliore esperienza sul nostro sito web.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'got_it_', 'Fatto!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'learn_more', 'Per saperne di più');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_result_found', 'nessun risultato trovato');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_gif', 'Invia GIF');
            $lang_update_queries[] = PT_UpdateLangs($value, 'search_gifs', 'Cerca GIF');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sticker_added', 'Sticker aggiunto');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_phone_number_is_required.', 'Il tuo numero di telefono è richiesto.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'select_your_country.', 'Selezionare il proprio paese.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'select_your_birth_date.', 'Per favore seleziona il tuo compleanno.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'my_location', 'La mia posizione');
            $lang_update_queries[] = PT_UpdateLangs($value, 'or', 'O');
            $lang_update_queries[] = PT_UpdateLangs($value, 'instagram', 'Instagram');
            $lang_update_queries[] = PT_UpdateLangs($value, 'disable', 'disattivare');
            $lang_update_queries[] = PT_UpdateLangs($value, 'enable', 'abilitare');
            $lang_update_queries[] = PT_UpdateLangs($value, 'travel_to_another_country__and_relocate_', 'Viaggia in un altro paese e trasferisci!');
            $lang_update_queries[] = PT_UpdateLangs($value, '4525', 'Maschio');
            $lang_update_queries[] = PT_UpdateLangs($value, '4526', 'Femmina');
            $lang_update_queries[] = PT_UpdateLangs($value, 'about_you', 'A proposito di te');
            $lang_update_queries[] = PT_UpdateLangs($value, 'km', 'km');
            $lang_update_queries[] = PT_UpdateLangs($value, 'message_requests', 'Richieste di messaggi');
            $lang_update_queries[] = PT_UpdateLangs($value, 'all_conversations', 'Tutte le conversazioni');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_can_chat_with_this_user_after', 'Puoi chattare con questo profilo dopo');
            $lang_update_queries[] = PT_UpdateLangs($value, 'hours.', 'ore.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_user_decline_your_chat_before_so_you_can_chat_with_this_user_after', 'Questo utente ha rifiutato la chat in precedenza, dopo potrai chattare con questo utente');
            $lang_update_queries[] = PT_UpdateLangs($value, 'active', 'attivo');
            $lang_update_queries[] = PT_UpdateLangs($value, 'declined', 'rifiutato');
            $lang_update_queries[] = PT_UpdateLangs($value, 'pending', 'in attesa di');
            $lang_update_queries[] = PT_UpdateLangs($value, 'night_mode', 'Modalità notturna');
            $lang_update_queries[] = PT_UpdateLangs($value, 'day_mode', 'Modalità giorno');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we___ll_be_back_soon_', 'Torneremo presto!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sorry_for_the_inconvenience_but_we_rsquo_re_performing_some_maintenance_at_the_moment._if_you_need_help_you_can_always', 'Ci scusiamo per l\'inconveniente, ma al momento stiamo eseguendo degli interventi di manutenzione. Se hai bisogno di aiuto puoi sempre');
            $lang_update_queries[] = PT_UpdateLangs($value, 'otherwise_we_rsquo_ll_be_back_online_shortly_', 'altrimenti torneremo online presto!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details', 'Abbiamo rifiutato il tuo bonifico bancario, ti preghiamo di contattarci per ulteriori dettagli.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accepted_your_message_request_', 'accettato la tua richiesta di messaggio!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'declined_your_message_request_', 'rifiutato la tua richiesta di messaggio!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_approved_your_bank_transfer_of__d_', 'Abbiamo approvato il tuo bonifico bancario di %d!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'note', 'Nota');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_chat', 'Elimina chat');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x3_matches_will_expire_in', 'Le tue partite x3 scadranno');
            $lang_update_queries[] = PT_UpdateLangs($value, 'to_get_your_profile_verified_you_have_to_verify_these.', 'Per verificare il tuo profilo devi verificarlo.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'upload_at_least_5_image.', 'Carica almeno 5 immagini.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'unblock', 'Sbloccare');
        } else if ($value == 'portuguese') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'social_accounts', 'Contas sociais');
            $lang_update_queries[] = PT_UpdateLangs($value, 'current_password', 'senha atual');
            $lang_update_queries[] = PT_UpdateLangs($value, 'chat_conversations_fetch_successfully', 'Conversas de bate-papo recebidas com sucesso');
            $lang_update_queries[] = PT_UpdateLangs($value, 'messages_deleted_successfully.', 'Mensagens foram apagadas com sucesso.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_chat.', 'Excluir bate-papo.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'by_creating_your_account__you_agree_to_our', 'Ao criar sua conta, você concorda com nossos');
            $lang_update_queries[] = PT_UpdateLangs($value, 'choose_your_gender', 'Escolha seu gênero');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_phone_number_is_already_exist.', 'Este número de telefone já existe.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_account_', 'Deletar conta?');
            $lang_update_queries[] = PT_UpdateLangs($value, 'are_you_sure_you_want_to_delete_your_account__all_content_including_published_photos_and_other_data_will_be_permanetly_removed_', 'Tem certeza de que deseja excluir sua conta? Todo o conteúdo, incluindo fotos publicadas e outros dados, será permanentemente removido.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete', 'Excluir');
            $lang_update_queries[] = PT_UpdateLangs($value, 'enter_a_location', 'Digite um local');
            $lang_update_queries[] = PT_UpdateLangs($value, 'media_message_sent_successfully.', 'Mensagem enviada.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_are_a_pro_now.', 'Você é um profissional agora!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x10_visits_will_expire_in', 'Suas visitas x10 expiram em');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x4_likes_will_expire_in', 'Seus gostos de x4 expiram em');
            $lang_update_queries[] = PT_UpdateLangs($value, 'verification', 'Verificação');
            $lang_update_queries[] = PT_UpdateLangs($value, 'gift_added', 'Presente adicionado com sucesso.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_a_gift_to_you.', 'enviou um presente para você.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number_cannot_be_empty', 'O número de telefone é obrigatório');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_add_gift', 'Erro ao adicionar um presente');
            $lang_update_queries[] = PT_UpdateLangs($value, 'profile_avatar_uploaded_successfully.', 'Avatar do perfil carregado com sucesso.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_add_sticker', 'Erro ao adicionar o adesivo');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_saving_notification', 'Erro ao salvar a notificação');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sticker_sent_successfully.', 'Etiqueta enviada com sucesso.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'like_add_successfully.', 'Como adicionado com sucesso.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_a_message_', 'lhe enviou uma mensagem!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details.', 'Rejeitamos sua transferência bancária. Entre em contato para mais detalhes.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number__e.g__90..', 'Número de telefone, por exemplo, +90.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_verification_needed', 'Confirmação de telefone necessária');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone', 'telefone');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_otp', 'Enviar OTP');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_activiation_', 'Ativação por telefone,');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_the_verification_code_sent_to_your_phone', 'Digite o código de verificação que foi enviado para o seu telefone.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'resend', 'Reenviar');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_verify_your_email_address', 'Por favor verifique seu endereço de email.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_sending_an_sms__please_try_again_later.', 'Erro ao enviar o SMS, tente novamente mais tarde.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_submitting_form.', 'Erro ao enviar o formulário.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_verification_needed', 'Confirmação de email obrigatória');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_activiation_', 'Ativação de email,');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_the_verification_code_sent_to_your_email', 'Por favor insira o código de verificação que foi enviado para o seu E-mail.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'bank_transfer', 'Transferência bancária');
            $lang_update_queries[] = PT_UpdateLangs($value, 'close', 'Perto');
            $lang_update_queries[] = PT_UpdateLangs($value, 'bank_information', 'Informação bancária');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_transfer_the_amount_of', 'Por favor, transfira a quantidade de');
            $lang_update_queries[] = PT_UpdateLangs($value, 'to_this_bank_account_to_buy', 'para esta conta bancária para comprar');
            $lang_update_queries[] = PT_UpdateLangs($value, 'upload_receipt', 'Carregar Recibo');
            $lang_update_queries[] = PT_UpdateLangs($value, 'confirm', 'confirme');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_receipt_uploaded_successfully.', 'Seu recibo foi enviado com sucesso.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'date', 'Encontro');
            $lang_update_queries[] = PT_UpdateLangs($value, 'processed_by', 'Processado por');
            $lang_update_queries[] = PT_UpdateLangs($value, 'amount', 'Montante');
            $lang_update_queries[] = PT_UpdateLangs($value, 'type', 'Tipo');
            $lang_update_queries[] = PT_UpdateLangs($value, 'notes', 'Notas');
            $lang_update_queries[] = PT_UpdateLangs($value, 'plan_premium_membership', 'Planeje a filiação Premium');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_boost_will_expire_in', 'Seu impulso expirará em');
            $lang_update_queries[] = PT_UpdateLangs($value, 'hide', 'ocultar');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_reach_the_max_of_swipes_per_day._you_have_to_wait__0__hours_before_you_can_redo_likes_or_upgrade_to_pro_to_for_unlimited.', 'Você atingiu a quantidade máxima de furtos por dia, precisa esperar {0} horas antes de refazer furtos ou fazer upgrade agora para a Associação Pro para furtos e curtidas ilimitados.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_payment_was_processed_successfully.', 'Seu pagamento foi processado com sucesso.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sms', 'SMS');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_message_', 'lhe enviou uma mensagem!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_a_message_', 'lhe enviou uma mensagem!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accept', 'Aceitar');
            $lang_update_queries[] = PT_UpdateLangs($value, 'decline', 'Declínio');
            $lang_update_queries[] = PT_UpdateLangs($value, 'calling', 'Chamando');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_wait_for_your_friend_answer.', 'Por favor, aguarde a resposta do seu amigo.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_answer', 'Sem resposta');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_try_again_later.', 'Por favor, tente novamente mais tarde.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'new_video_call', 'Nova chamada de vídeo recebida');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wants_to_video_chat_with_you.', 'quer bater papo por vídeo com você.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'call_declined', 'Chamada recusada');
            $lang_update_queries[] = PT_UpdateLangs($value, 'the_recipient_has_declined_the_call__please_try_again_later.', 'O destinatário recusou a chamada, tente novamente mais tarde.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accept___start', 'Aceitar e começar');
            $lang_update_queries[] = PT_UpdateLangs($value, 'answered__', 'Respondidas!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_wait..', 'Por favor, espere..');
            $lang_update_queries[] = PT_UpdateLangs($value, 'video_call', 'Video chamada');
            $lang_update_queries[] = PT_UpdateLangs($value, 'new_audio_call', 'Nova chamada de áudio recebida');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wants_to_talk_with_you.', 'quer falar com você.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'audio_call', 'Chamada de áudio');
            $lang_update_queries[] = PT_UpdateLangs($value, 'talking_with', 'conversando com');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_website_uses_cookies_to_ensure_you_get_the_best_experience_on_our_website.', 'Este site usa cookies para garantir que você obtenha a melhor experiência em nosso site.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'got_it_', 'Consegui!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'learn_more', 'Saber mais');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_result_found', 'nenhum resultado encontrado');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_gif', 'Enviar GIF');
            $lang_update_queries[] = PT_UpdateLangs($value, 'search_gifs', 'Pesquisar GIFs');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sticker_added', 'Etiqueta adicionada');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_phone_number_is_required.', 'Seu número de telefone é obrigatório.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'select_your_country.', 'Por favor selecione seu país.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'select_your_birth_date.', 'Por favor, selecione seu aniversário.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'my_location', 'Minha localização');
            $lang_update_queries[] = PT_UpdateLangs($value, 'or', 'OU');
            $lang_update_queries[] = PT_UpdateLangs($value, 'instagram', 'Instagram');
            $lang_update_queries[] = PT_UpdateLangs($value, 'disable', 'desativar');
            $lang_update_queries[] = PT_UpdateLangs($value, 'enable', 'habilitar');
            $lang_update_queries[] = PT_UpdateLangs($value, 'travel_to_another_country__and_relocate_', 'Viaje para outro país e mude de lugar!');
            $lang_update_queries[] = PT_UpdateLangs($value, '4525', 'Masculino');
            $lang_update_queries[] = PT_UpdateLangs($value, '4526', 'Fêmea');
            $lang_update_queries[] = PT_UpdateLangs($value, 'about_you', 'Sobre você');
            $lang_update_queries[] = PT_UpdateLangs($value, 'km', 'km');
            $lang_update_queries[] = PT_UpdateLangs($value, 'message_requests', 'Pedidos de mensagem');
            $lang_update_queries[] = PT_UpdateLangs($value, 'all_conversations', 'Todas as conversas');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_can_chat_with_this_user_after', 'Você pode conversar com este perfil depois');
            $lang_update_queries[] = PT_UpdateLangs($value, 'hours.', 'horas.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_user_decline_your_chat_before_so_you_can_chat_with_this_user_after', 'Este utilizador recusou o seu chat antes, poderá conversar com este utilizador depois');
            $lang_update_queries[] = PT_UpdateLangs($value, 'active', 'ativo');
            $lang_update_queries[] = PT_UpdateLangs($value, 'declined', 'Recusado');
            $lang_update_queries[] = PT_UpdateLangs($value, 'pending', 'Pendente');
            $lang_update_queries[] = PT_UpdateLangs($value, 'night_mode', 'Modo noturno');
            $lang_update_queries[] = PT_UpdateLangs($value, 'day_mode', 'Modo dia');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we___ll_be_back_soon_', 'Voltaremos em breve!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sorry_for_the_inconvenience_but_we_rsquo_re_performing_some_maintenance_at_the_moment._if_you_need_help_you_can_always', 'Desculpe pela inconveniência, mas estamos realizando alguma manutenção no momento. Se você precisar de ajuda, você sempre pode');
            $lang_update_queries[] = PT_UpdateLangs($value, 'otherwise_we_rsquo_ll_be_back_online_shortly_', 'caso contrário, estaremos de volta online em breve!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details', 'Rejeitamos sua transferência bancária. Entre em contato para mais detalhes.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accepted_your_message_request_', 'aceitou o seu pedido de mensagem!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'declined_your_message_request_', 'recusou o seu pedido de mensagem!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_approved_your_bank_transfer_of__d_', 'Nós aprovamos sua transferência bancária de %d!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'note', 'Nota');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_chat', 'Excluir bate-papo');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x3_matches_will_expire_in', 'Suas partidas x3 expiram em');
            $lang_update_queries[] = PT_UpdateLangs($value, 'to_get_your_profile_verified_you_have_to_verify_these.', 'Para ter seu perfil verificado, você precisa verificar isso.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'upload_at_least_5_image.', 'Carregue pelo menos 5 imagens.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'unblock', 'Desbloquear');
        } else if ($value == 'russian') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'social_accounts', 'Социальные аккаунты');
            $lang_update_queries[] = PT_UpdateLangs($value, 'current_password', 'Текущий пароль');
            $lang_update_queries[] = PT_UpdateLangs($value, 'chat_conversations_fetch_successfully', 'Разговоры в чате успешно получены');
            $lang_update_queries[] = PT_UpdateLangs($value, 'messages_deleted_successfully.', 'Сообщения были успешно удалены.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_chat.', 'Удалить чат.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'by_creating_your_account__you_agree_to_our', 'Создавая свой аккаунт, вы соглашаетесь с нашими');
            $lang_update_queries[] = PT_UpdateLangs($value, 'choose_your_gender', 'Выберите свой пол');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_phone_number_is_already_exist.', 'Этот номер телефона уже существует.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_account_', 'Удалить аккаунт?');
            $lang_update_queries[] = PT_UpdateLangs($value, 'are_you_sure_you_want_to_delete_your_account__all_content_including_published_photos_and_other_data_will_be_permanetly_removed_', 'Вы уверены, что хотите удалить свой аккаунт? Весь контент, включая опубликованные фотографии и другие данные, будет окончательно удален!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete', 'удалять');
            $lang_update_queries[] = PT_UpdateLangs($value, 'enter_a_location', 'Введите местоположение');
            $lang_update_queries[] = PT_UpdateLangs($value, 'media_message_sent_successfully.', 'Сообщение отправлено.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_are_a_pro_now.', 'Вы профессионал сейчас!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x10_visits_will_expire_in', 'Ваши посещения x10 истекают через');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x4_likes_will_expire_in', 'Ваши x4 лайки истекают через');
            $lang_update_queries[] = PT_UpdateLangs($value, 'verification', 'верификация');
            $lang_update_queries[] = PT_UpdateLangs($value, 'gift_added', 'Подарок успешно добавлен.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_a_gift_to_you.', 'послал тебе подарок.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number_cannot_be_empty', 'Требуется номер телефона');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_add_gift', 'Ошибка при добавлении подарка');
            $lang_update_queries[] = PT_UpdateLangs($value, 'profile_avatar_uploaded_successfully.', 'Профиль аватара успешно загружен.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_add_sticker', 'Ошибка при добавлении стикера');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_saving_notification', 'Ошибка при сохранении уведомления');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sticker_sent_successfully.', 'Стикер успешно отправлен.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'like_add_successfully.', 'Вроде успешно добавлено.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_a_message_', 'отправил вам сообщение!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details.', 'Мы отклонили ваш банковский перевод, пожалуйста, свяжитесь с нами для получения более подробной информации.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number__e.g__90..', 'Номер телефона, например, +90 ..');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_verification_needed', 'Требуется проверка телефона');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone', 'Телефон');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_otp', 'Отправить OTP');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_activiation_', 'Активация телефона,');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_the_verification_code_sent_to_your_phone', 'Пожалуйста, введите проверочный код, который был отправлен на ваш телефон.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'resend', 'Отправить');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_verify_your_email_address', 'Пожалуйста, подтвердите ваш адрес электронной почты.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_sending_an_sms__please_try_again_later.', 'Ошибка при отправке SMS, повторите попытку позже.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_submitting_form.', 'Ошибка при отправке формы.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_verification_needed', 'Требуется подтверждение по электронной почте');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_activiation_', 'Активация электронной почты,');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_the_verification_code_sent_to_your_email', 'Пожалуйста, введите проверочный код, который был отправлен на ваш E-mail.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'bank_transfer', 'Банковский перевод');
            $lang_update_queries[] = PT_UpdateLangs($value, 'close', 'близко');
            $lang_update_queries[] = PT_UpdateLangs($value, 'bank_information', 'Банковская информация');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_transfer_the_amount_of', 'Пожалуйста, перечислите сумму');
            $lang_update_queries[] = PT_UpdateLangs($value, 'to_this_bank_account_to_buy', 'на этот банковский счет для покупки');
            $lang_update_queries[] = PT_UpdateLangs($value, 'upload_receipt', 'Загрузить квитанцию');
            $lang_update_queries[] = PT_UpdateLangs($value, 'confirm', 'подтвердить');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_receipt_uploaded_successfully.', 'Ваша квитанция была успешно загружена.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'date', 'Дата');
            $lang_update_queries[] = PT_UpdateLangs($value, 'processed_by', 'Обработано');
            $lang_update_queries[] = PT_UpdateLangs($value, 'amount', 'Количество');
            $lang_update_queries[] = PT_UpdateLangs($value, 'type', 'Тип');
            $lang_update_queries[] = PT_UpdateLangs($value, 'notes', 'Заметки');
            $lang_update_queries[] = PT_UpdateLangs($value, 'plan_premium_membership', 'План Премиум членства');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_boost_will_expire_in', 'Ваш буст истекает через');
            $lang_update_queries[] = PT_UpdateLangs($value, 'hide', 'Спрятать');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_reach_the_max_of_swipes_per_day._you_have_to_wait__0__hours_before_you_can_redo_likes_or_upgrade_to_pro_to_for_unlimited.', 'Вы достигли максимального количества свипов в день, вам нужно подождать {0} часов, прежде чем вы сможете повторить свипы, ИЛИ перейти на Про членство Pro для неограниченного количества свайпов и лайков.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_payment_was_processed_successfully.', 'Ваш платеж был успешно обработан.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sms', 'смс');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_message_', 'отправил вам сообщение!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_a_message_', 'отправил вам сообщение!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accept', 'принимать');
            $lang_update_queries[] = PT_UpdateLangs($value, 'decline', 'снижение');
            $lang_update_queries[] = PT_UpdateLangs($value, 'calling', 'призвание');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_wait_for_your_friend_answer.', 'Пожалуйста, подождите ответа вашего друга.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_answer', 'Нет ответа');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_try_again_later.', 'Пожалуйста, попробуйте позже.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'new_video_call', 'Новый входящий видеозвонок');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wants_to_video_chat_with_you.', 'хочет с тобой видеочат');
            $lang_update_queries[] = PT_UpdateLangs($value, 'call_declined', 'Звонок отклонен');
            $lang_update_queries[] = PT_UpdateLangs($value, 'the_recipient_has_declined_the_call__please_try_again_later.', 'Получатель отклонил вызов, повторите попытку позже.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accept___start', 'Принять и начать');
            $lang_update_queries[] = PT_UpdateLangs($value, 'answered__', 'Ответил!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_wait..', 'Пожалуйста, подождите..');
            $lang_update_queries[] = PT_UpdateLangs($value, 'video_call', 'Видеозвонок');
            $lang_update_queries[] = PT_UpdateLangs($value, 'new_audio_call', 'Новый входящий аудиозвонок');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wants_to_talk_with_you.', 'хочет поговорить с тобой.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'audio_call', 'Аудио звонок');
            $lang_update_queries[] = PT_UpdateLangs($value, 'talking_with', 'говорить с');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_website_uses_cookies_to_ensure_you_get_the_best_experience_on_our_website.', 'Этот веб-сайт использует куки-файлы, чтобы обеспечить вам максимальную отдачу от нашего веб-сайта.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'got_it_', 'Понял!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'learn_more', 'Учить больше');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_result_found', 'Результатов не найдено');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_gif', 'Отправить GIF');
            $lang_update_queries[] = PT_UpdateLangs($value, 'search_gifs', 'Поиск GIF-файлов');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sticker_added', 'Стикер добавлен');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_phone_number_is_required.', 'Ваш номер телефона требуется.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'select_your_country.', 'Пожалуйста, выберите вашу страну.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'select_your_birth_date.', 'Пожалуйста, выберите свой день рождения.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'my_location', 'Мое местонахождение');
            $lang_update_queries[] = PT_UpdateLangs($value, 'or', 'ИЛИ ЖЕ');
            $lang_update_queries[] = PT_UpdateLangs($value, 'instagram', 'Instagram');
            $lang_update_queries[] = PT_UpdateLangs($value, 'disable', 'запрещать');
            $lang_update_queries[] = PT_UpdateLangs($value, 'enable', 'включить');
            $lang_update_queries[] = PT_UpdateLangs($value, 'travel_to_another_country__and_relocate_', 'Отправляйся в другую страну и переезжай!');
            $lang_update_queries[] = PT_UpdateLangs($value, '4525', 'мужчина');
            $lang_update_queries[] = PT_UpdateLangs($value, '4526', 'женский');
            $lang_update_queries[] = PT_UpdateLangs($value, 'about_you', 'О вас');
            $lang_update_queries[] = PT_UpdateLangs($value, 'km', 'км');
            $lang_update_queries[] = PT_UpdateLangs($value, 'message_requests', 'Запросы сообщений');
            $lang_update_queries[] = PT_UpdateLangs($value, 'all_conversations', 'Все разговоры');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_can_chat_with_this_user_after', 'Вы можете общаться с этим профилем после');
            $lang_update_queries[] = PT_UpdateLangs($value, 'hours.', 'ч.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_user_decline_your_chat_before_so_you_can_chat_with_this_user_after', 'Этот пользователь отклонил ваш чат раньше, вы сможете общаться с этим пользователем после того, как');
            $lang_update_queries[] = PT_UpdateLangs($value, 'active', 'активный');
            $lang_update_queries[] = PT_UpdateLangs($value, 'declined', 'Отклонено');
            $lang_update_queries[] = PT_UpdateLangs($value, 'pending', 'в ожидании');
            $lang_update_queries[] = PT_UpdateLangs($value, 'night_mode', 'Ночной режим');
            $lang_update_queries[] = PT_UpdateLangs($value, 'day_mode', 'Дневной режим');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we___ll_be_back_soon_', 'Мы скоро вернемся!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sorry_for_the_inconvenience_but_we_rsquo_re_performing_some_maintenance_at_the_moment._if_you_need_help_you_can_always', 'Приносим извинения за неудобства, но в настоящее время мы проводим техническое обслуживание. Если вам нужна помощь, вы всегда можете');
            $lang_update_queries[] = PT_UpdateLangs($value, 'otherwise_we_rsquo_ll_be_back_online_shortly_', 'в противном случае мы скоро вернемся онлайн!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details', 'Мы отклонили ваш банковский перевод, пожалуйста, свяжитесь с нами для получения более подробной информации.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accepted_your_message_request_', 'принял ваш запрос на сообщение!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'declined_your_message_request_', 'отклонил ваш запрос на сообщение!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_approved_your_bank_transfer_of__d_', 'Мы одобрили ваш банковский перевод %d!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'note', 'Заметка');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_chat', 'Удалить чат');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x3_matches_will_expire_in', 'Ваши матчи х3 истекают через');
            $lang_update_queries[] = PT_UpdateLangs($value, 'to_get_your_profile_verified_you_have_to_verify_these.', 'Чтобы подтвердить свой профиль, вы должны подтвердить это.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'upload_at_least_5_image.', 'Загрузите как минимум 5 изображений.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'unblock', 'открыть');
        } else if ($value == 'spanish') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'social_accounts', 'Cuentas sociales');
            $lang_update_queries[] = PT_UpdateLangs($value, 'current_password', 'contraseña actual');
            $lang_update_queries[] = PT_UpdateLangs($value, 'chat_conversations_fetch_successfully', 'Conversaciones de chat logradas');
            $lang_update_queries[] = PT_UpdateLangs($value, 'messages_deleted_successfully.', 'Los mensajes fueron eliminados con éxito.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_chat.', 'Eliminar chat.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'by_creating_your_account__you_agree_to_our', 'Al crear su cuenta, usted acepta nuestra');
            $lang_update_queries[] = PT_UpdateLangs($value, 'choose_your_gender', 'Elige tu género');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_phone_number_is_already_exist.', 'Este número de teléfono ya existe.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_account_', '¿Borrar cuenta?');
            $lang_update_queries[] = PT_UpdateLangs($value, 'are_you_sure_you_want_to_delete_your_account__all_content_including_published_photos_and_other_data_will_be_permanetly_removed_', '¿Estás seguro de que quieres eliminar tu cuenta? ¡Todo el contenido, incluidas las fotos publicadas y otros datos, se eliminará permanentemente!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete', 'Borrar');
            $lang_update_queries[] = PT_UpdateLangs($value, 'enter_a_location', 'Introduce una ubicación');
            $lang_update_queries[] = PT_UpdateLangs($value, 'media_message_sent_successfully.', 'Mensaje enviado.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_are_a_pro_now.', '¡Eres un profesional ahora!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x10_visits_will_expire_in', 'Tus x10 visitas expirarán en');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x4_likes_will_expire_in', 'Tus x4 likes expirarán en');
            $lang_update_queries[] = PT_UpdateLangs($value, 'verification', 'Verificación');
            $lang_update_queries[] = PT_UpdateLangs($value, 'gift_added', 'Regalo añadido con éxito.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_a_gift_to_you.', 'Te envié un regalo.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number_cannot_be_empty', 'Se requiere numero de telefono');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_add_gift', 'Error al agregar un regalo');
            $lang_update_queries[] = PT_UpdateLangs($value, 'profile_avatar_uploaded_successfully.', 'Perfil de avatar subido con éxito.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_add_sticker', 'Error al añadir la pegatina');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_saving_notification', 'Error al guardar la notificación');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sticker_sent_successfully.', 'Etiqueta enviada con éxito.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'like_add_successfully.', 'Al igual que con éxito añadido.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_a_message_', '¡Te envié un mensaje!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details.', 'Hemos rechazado su transferencia bancaria, póngase en contacto con nosotros para obtener más detalles.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number__e.g__90..', 'Número de teléfono, p. Ej. +90 ..');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_verification_needed', 'Se requiere verificación por teléfono');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone', 'Teléfono');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_otp', 'Enviar OTP');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_activiation_', 'Activación del teléfono,');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_the_verification_code_sent_to_your_phone', 'Por favor ingrese el código de verificación que fue enviado a su teléfono.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'resend', 'Reenviar');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_verify_your_email_address', 'Por favor verifique su dirección de correo electrónico.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_sending_an_sms__please_try_again_later.', 'Error al enviar el SMS, inténtalo de nuevo más tarde.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_submitting_form.', 'Error al enviar el formulario.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_verification_needed', 'Se requiere verificación por correo electrónico');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_activiation_', 'Activación de correo electrónico,');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_the_verification_code_sent_to_your_email', 'Por favor ingrese el código de verificación que fue enviado a su correo electrónico.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'bank_transfer', 'Transferencia bancaria');
            $lang_update_queries[] = PT_UpdateLangs($value, 'close', 'Cerrar');
            $lang_update_queries[] = PT_UpdateLangs($value, 'bank_information', 'Información bancaria');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_transfer_the_amount_of', 'Por favor transfiera la cantidad de');
            $lang_update_queries[] = PT_UpdateLangs($value, 'to_this_bank_account_to_buy', 'a esta cuenta bancaria para comprar');
            $lang_update_queries[] = PT_UpdateLangs($value, 'upload_receipt', 'Cargar Recibo');
            $lang_update_queries[] = PT_UpdateLangs($value, 'confirm', 'Confirmar');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_receipt_uploaded_successfully.', 'Su recibo fue cargado correctamente.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'date', 'Fecha');
            $lang_update_queries[] = PT_UpdateLangs($value, 'processed_by', 'Procesado por');
            $lang_update_queries[] = PT_UpdateLangs($value, 'amount', 'Cantidad');
            $lang_update_queries[] = PT_UpdateLangs($value, 'type', 'Tipo');
            $lang_update_queries[] = PT_UpdateLangs($value, 'notes', 'Notas');
            $lang_update_queries[] = PT_UpdateLangs($value, 'plan_premium_membership', 'Membresía Premium Plan');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_boost_will_expire_in', 'Tu impulso expirará en');
            $lang_update_queries[] = PT_UpdateLangs($value, 'hide', 'Esconder');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_reach_the_max_of_swipes_per_day._you_have_to_wait__0__hours_before_you_can_redo_likes_or_upgrade_to_pro_to_for_unlimited.', 'Ha alcanzado la cantidad máxima de swipes por día, tiene que esperar {0} horas antes de poder rehacer swipes, O actualícese ahora a Pro Membership para swipes y me gusta ilimitados.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_payment_was_processed_successfully.', 'Su pago fue procesado con éxito.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sms', 'SMS');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_message_', '¡Te envié un mensaje!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_a_message_', '¡Te envié un mensaje!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accept', 'Aceptar');
            $lang_update_queries[] = PT_UpdateLangs($value, 'decline', 'Disminución');
            $lang_update_queries[] = PT_UpdateLangs($value, 'calling', 'Vocación');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_wait_for_your_friend_answer.', 'Por favor, espere la respuesta de su amigo.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_answer', 'Sin respuesta');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_try_again_later.', 'Por favor, inténtelo de nuevo más tarde.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'new_video_call', 'Nueva llamada de video entrante');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wants_to_video_chat_with_you.', 'quiere chatear con usted');
            $lang_update_queries[] = PT_UpdateLangs($value, 'call_declined', 'Llamada rechazada');
            $lang_update_queries[] = PT_UpdateLangs($value, 'the_recipient_has_declined_the_call__please_try_again_later.', 'El destinatario ha rechazado la llamada, inténtalo de nuevo más tarde.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accept___start', 'Aceptar y empezar');
            $lang_update_queries[] = PT_UpdateLangs($value, 'answered__', '¡Contestado!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_wait..', 'Por favor espera..');
            $lang_update_queries[] = PT_UpdateLangs($value, 'video_call', 'Videollamada');
            $lang_update_queries[] = PT_UpdateLangs($value, 'new_audio_call', 'Nueva llamada de audio entrante');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wants_to_talk_with_you.', 'quiere hablar contigo');
            $lang_update_queries[] = PT_UpdateLangs($value, 'audio_call', 'Llamada de audio');
            $lang_update_queries[] = PT_UpdateLangs($value, 'talking_with', 'Hablando con');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_website_uses_cookies_to_ensure_you_get_the_best_experience_on_our_website.', 'Este sitio web utiliza cookies para garantizar que obtenga la mejor experiencia en nuestro sitio web.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'got_it_', '¡Lo tengo!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'learn_more', 'Aprende más');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_result_found', 'No se han encontrado resultados');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_gif', 'Enviar GIF');
            $lang_update_queries[] = PT_UpdateLangs($value, 'search_gifs', 'Buscar GIFs');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sticker_added', 'Pegatina añadida');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_phone_number_is_required.', 'Se requiere su número de teléfono.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'select_your_country.', 'Por favor seleccione su país.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'select_your_birth_date.', 'Por favor seleccione su cumpleaños.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'my_location', 'Mi ubicacion');
            $lang_update_queries[] = PT_UpdateLangs($value, 'or', 'O');
            $lang_update_queries[] = PT_UpdateLangs($value, 'instagram', 'Instagram');
            $lang_update_queries[] = PT_UpdateLangs($value, 'disable', 'inhabilitar');
            $lang_update_queries[] = PT_UpdateLangs($value, 'enable', 'habilitar');
            $lang_update_queries[] = PT_UpdateLangs($value, 'travel_to_another_country__and_relocate_', '¡Viaja a otro país y múdate!');
            $lang_update_queries[] = PT_UpdateLangs($value, '4525', 'Masculino');
            $lang_update_queries[] = PT_UpdateLangs($value, '4526', 'Hembra');
            $lang_update_queries[] = PT_UpdateLangs($value, 'about_you', 'Acerca de ti');
            $lang_update_queries[] = PT_UpdateLangs($value, 'km', 'km');
            $lang_update_queries[] = PT_UpdateLangs($value, 'message_requests', 'Peticiones de mensajes');
            $lang_update_queries[] = PT_UpdateLangs($value, 'all_conversations', 'Todas las conversaciones');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_can_chat_with_this_user_after', 'Puedes chatear con este perfil después');
            $lang_update_queries[] = PT_UpdateLangs($value, 'hours.', 'horas');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_user_decline_your_chat_before_so_you_can_chat_with_this_user_after', 'Este usuario ha rechazado tu chat antes, podrás chatear con este usuario después de');
            $lang_update_queries[] = PT_UpdateLangs($value, 'active', 'activo');
            $lang_update_queries[] = PT_UpdateLangs($value, 'declined', 'Rechazado');
            $lang_update_queries[] = PT_UpdateLangs($value, 'pending', 'Pendiente');
            $lang_update_queries[] = PT_UpdateLangs($value, 'night_mode', 'Modo nocturno');
            $lang_update_queries[] = PT_UpdateLangs($value, 'day_mode', 'Modo día');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we___ll_be_back_soon_', '¡Estaremos de vuelta pronto!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sorry_for_the_inconvenience_but_we_rsquo_re_performing_some_maintenance_at_the_moment._if_you_need_help_you_can_always', 'Disculpe las molestias, pero estamos realizando algunas tareas de mantenimiento en este momento. Si necesitas ayuda siempre puedes');
            $lang_update_queries[] = PT_UpdateLangs($value, 'otherwise_we_rsquo_ll_be_back_online_shortly_', 'De lo contrario, volveremos a estar en línea pronto.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details', 'Hemos rechazado su transferencia bancaria, póngase en contacto con nosotros para obtener más detalles.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accepted_your_message_request_', 'aceptó su solicitud de mensaje!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'declined_your_message_request_', 'rechazó su solicitud de mensaje!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_approved_your_bank_transfer_of__d_', '¡Aprobamos su transferencia bancaria de %d!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'note', 'Nota');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_chat', 'Eliminar chat');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x3_matches_will_expire_in', 'Tus coincidencias x3 caducarán en');
            $lang_update_queries[] = PT_UpdateLangs($value, 'to_get_your_profile_verified_you_have_to_verify_these.', 'Para verificar tu perfil tienes que verificar estos.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'upload_at_least_5_image.', 'Sube al menos 5 imágenes.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'unblock', 'Desatascar');
        } else if ($value == 'turkish') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'social_accounts', 'Sosyal hesaplar');
            $lang_update_queries[] = PT_UpdateLangs($value, 'current_password', 'Şimdiki Şifre');
            $lang_update_queries[] = PT_UpdateLangs($value, 'chat_conversations_fetch_successfully', 'Sohbetler başarıyla alındı');
            $lang_update_queries[] = PT_UpdateLangs($value, 'messages_deleted_successfully.', 'Mesajlar başarıyla silindi.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_chat.', 'Sohbeti sil.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'by_creating_your_account__you_agree_to_our', 'Hesabınızı oluşturarak, kabul etmiş sayılırsınız.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'choose_your_gender', 'Cinsiyetinizi seçin');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_phone_number_is_already_exist.', 'Bu telefon numarası zaten var.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_account_', 'Hesabı sil?');
            $lang_update_queries[] = PT_UpdateLangs($value, 'are_you_sure_you_want_to_delete_your_account__all_content_including_published_photos_and_other_data_will_be_permanetly_removed_', 'Hesabınızı silmek istediğinizden emin misiniz? Yayınlanan fotoğraflar ve diğer veriler dahil olmak üzere tüm içerikler kalıcı olarak silinecek!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete', 'silmek');
            $lang_update_queries[] = PT_UpdateLangs($value, 'enter_a_location', 'Bir yer girin');
            $lang_update_queries[] = PT_UpdateLangs($value, 'media_message_sent_successfully.', 'Mesajı gönderildi.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_are_a_pro_now.', 'Sen artık bir profesyonelsin!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x10_visits_will_expire_in', 'X10 ziyaretleriniz sona erecek');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x4_likes_will_expire_in', 'X4 beğenilerinizin süresi dolar');
            $lang_update_queries[] = PT_UpdateLangs($value, 'verification', 'Doğrulama');
            $lang_update_queries[] = PT_UpdateLangs($value, 'gift_added', 'Hediye başarıyla eklendi.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_a_gift_to_you.', 'sana bir hediye yolladım.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number_cannot_be_empty', 'Telefon numarası gerekli');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_add_gift', 'Hediye eklerken hata oluştu');
            $lang_update_queries[] = PT_UpdateLangs($value, 'profile_avatar_uploaded_successfully.', 'Profil avatarı başarıyla yüklendi.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_add_sticker', 'Çıkartmayı eklerken hata oluştu');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_saving_notification', 'Bildirim kaydedilirken hata oluştu');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sticker_sent_successfully.', 'Sticker başarıyla gönderildi.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'like_add_successfully.', 'Başarıyla eklendi gibi.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_a_message_', 'sana bir mesaj yolladım!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details.', 'Banka havalenizi reddettik, daha fazla ayrıntı için lütfen bizimle iletişime geçin.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number__e.g__90..', 'Telefon numarası, örneğin, +90 ..');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_verification_needed', 'Telefon doğrulaması gerekli');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone', 'Telefon');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_otp', 'OTP gönder');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_activiation_', 'Telefon aktivasyonu');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_the_verification_code_sent_to_your_phone', 'Lütfen telefonunuza gönderilen doğrulama kodunu girin.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'resend', 'Yeniden gönder');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_verify_your_email_address', 'Lütfen email adresini doğrula.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_sending_an_sms__please_try_again_later.', 'SMS gönderilirken hata oluştu, lütfen daha sonra tekrar deneyin.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_submitting_form.', 'Form gönderilirken hata oluştu.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_verification_needed', 'E-posta doğrulaması gerekli');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_activiation_', 'E-posta aktivasyonu,');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_the_verification_code_sent_to_your_email', 'Lütfen E-postanıza gönderilen doğrulama kodunu girin.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'bank_transfer', 'Banka transferi');
            $lang_update_queries[] = PT_UpdateLangs($value, 'close', 'Kapat');
            $lang_update_queries[] = PT_UpdateLangs($value, 'bank_information', 'Banka bilgisi');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_transfer_the_amount_of', 'Lütfen tutarını aktarın');
            $lang_update_queries[] = PT_UpdateLangs($value, 'to_this_bank_account_to_buy', 'satın almak için bu banka hesabına');
            $lang_update_queries[] = PT_UpdateLangs($value, 'upload_receipt', 'Makbuzu Yükle');
            $lang_update_queries[] = PT_UpdateLangs($value, 'confirm', 'Onaylamak');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_receipt_uploaded_successfully.', 'Makbuzunuz başarıyla yüklendi.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'date', 'tarih');
            $lang_update_queries[] = PT_UpdateLangs($value, 'processed_by', 'Tarafından işlenmiş');
            $lang_update_queries[] = PT_UpdateLangs($value, 'amount', 'Miktar');
            $lang_update_queries[] = PT_UpdateLangs($value, 'type', 'tip');
            $lang_update_queries[] = PT_UpdateLangs($value, 'notes', 'notlar');
            $lang_update_queries[] = PT_UpdateLangs($value, 'plan_premium_membership', 'Premium Üyeliği Planlayın');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_boost_will_expire_in', 'Desteğiniz sona erecek');
            $lang_update_queries[] = PT_UpdateLangs($value, 'hide', 'Saklamak');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_reach_the_max_of_swipes_per_day._you_have_to_wait__0__hours_before_you_can_redo_likes_or_upgrade_to_pro_to_for_unlimited.', 'Günde maksimum swipe amuontuna ulaştınız, swipeları tekrar yapabilmeniz için {0} saat beklemeniz, VEYA sınırsız swipe ve beğenileriniz için Pro üyeliğine şimdi yükseltmeniz gerekiyor.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_payment_was_processed_successfully.', 'Ödemeniz başarıyla işlendi.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sms', 'SMS');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_message_', 'sana bir mesaj yolladım!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_a_message_', 'sana bir mesaj yolladım!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accept', 'Kabul etmek');
            $lang_update_queries[] = PT_UpdateLangs($value, 'decline', 'düşüş');
            $lang_update_queries[] = PT_UpdateLangs($value, 'calling', 'çağrı');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_wait_for_your_friend_answer.', 'Lütfen arkadaşınızın cevabını bekleyin.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_answer', 'Cevapsız');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_try_again_later.', 'Lütfen daha sonra tekrar deneyiniz.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'new_video_call', 'Yeni gelen video görüşmesi');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wants_to_video_chat_with_you.', 'seninle görüntülü sohbet etmek istiyor.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'call_declined', 'Çağrı reddedildi');
            $lang_update_queries[] = PT_UpdateLangs($value, 'the_recipient_has_declined_the_call__please_try_again_later.', 'Alıcı aramayı reddetti, lütfen daha sonra tekrar deneyin.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accept___start', 'Kabul Et ve Başlat');
            $lang_update_queries[] = PT_UpdateLangs($value, 'answered__', 'Yanıtlanmış!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_wait..', 'Lütfen bekle..');
            $lang_update_queries[] = PT_UpdateLangs($value, 'video_call', 'Görüntülü arama');
            $lang_update_queries[] = PT_UpdateLangs($value, 'new_audio_call', 'Yeni gelen sesli arama');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wants_to_talk_with_you.', 'seninle konuşmak istiyor');
            $lang_update_queries[] = PT_UpdateLangs($value, 'audio_call', 'Sesli arama');
            $lang_update_queries[] = PT_UpdateLangs($value, 'talking_with', 'ile konuşmak');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_website_uses_cookies_to_ensure_you_get_the_best_experience_on_our_website.', 'Bu web sitesi, web sitemizde en iyi deneyimi yaşamanızı sağlamak için çerezleri kullanır.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'got_it_', 'Anladım!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'learn_more', 'Daha fazla bilgi edin');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_result_found', 'sonuç bulunamadı');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_gif', 'GIF gönder');
            $lang_update_queries[] = PT_UpdateLangs($value, 'search_gifs', 'GIF’lerde ara');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sticker_added', 'Etiket eklendi');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_phone_number_is_required.', 'Telefon numaranız gereklidir.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'select_your_country.', 'Lütfen ülkenizi seçiniz.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'select_your_birth_date.', 'Lütfen doğum gününü seç.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'my_location', 'Benim konumum');
            $lang_update_queries[] = PT_UpdateLangs($value, 'or', 'VEYA');
            $lang_update_queries[] = PT_UpdateLangs($value, 'instagram', 'Instagram');
            $lang_update_queries[] = PT_UpdateLangs($value, 'disable', 'devre dışı');
            $lang_update_queries[] = PT_UpdateLangs($value, 'enable', 'etkinleştirme');
            $lang_update_queries[] = PT_UpdateLangs($value, 'travel_to_another_country__and_relocate_', 'Başka bir ülkeye seyahat et ve yerini değiştir!');
            $lang_update_queries[] = PT_UpdateLangs($value, '4525', 'Erkek');
            $lang_update_queries[] = PT_UpdateLangs($value, '4526', 'Kadın');
            $lang_update_queries[] = PT_UpdateLangs($value, 'about_you', 'Senin hakkında');
            $lang_update_queries[] = PT_UpdateLangs($value, 'km', 'km');
            $lang_update_queries[] = PT_UpdateLangs($value, 'message_requests', 'İleti İstekler');
            $lang_update_queries[] = PT_UpdateLangs($value, 'all_conversations', 'Tüm konuşmalar');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_can_chat_with_this_user_after', 'Sonra bu profille sohbet edebilirsiniz');
            $lang_update_queries[] = PT_UpdateLangs($value, 'hours.', 'saatler.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_user_decline_your_chat_before_so_you_can_chat_with_this_user_after', 'Bu kullanıcı daha önce sohbeti reddetti, daha sonra bu kullanıcıyla sohbet edebileceksiniz');
            $lang_update_queries[] = PT_UpdateLangs($value, 'active', 'aktif');
            $lang_update_queries[] = PT_UpdateLangs($value, 'declined', 'Reddedilen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'pending', 'kadar');
            $lang_update_queries[] = PT_UpdateLangs($value, 'night_mode', 'Gece modu');
            $lang_update_queries[] = PT_UpdateLangs($value, 'day_mode', 'Gün modu');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we___ll_be_back_soon_', 'Yakında döneceğiz!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sorry_for_the_inconvenience_but_we_rsquo_re_performing_some_maintenance_at_the_moment._if_you_need_help_you_can_always', 'Verdiğimiz rahatsızlıktan dolayı üzgünüz, ancak şu anda biraz bakım yapıyoruz. Yardıma ihtiyacınız olursa her zaman');
            $lang_update_queries[] = PT_UpdateLangs($value, 'otherwise_we_rsquo_ll_be_back_online_shortly_', 'yoksa kısa süre sonra tekrar çevrimiçi oluruz!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details', 'Banka havalenizi reddettik, daha fazla ayrıntı için lütfen bizimle iletişime geçin.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accepted_your_message_request_', 'mesaj isteğini kabul etti!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'declined_your_message_request_', 'mesaj isteğini reddetti!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_approved_your_bank_transfer_of__d_', '%d banka havalenizi onayladık!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'note', 'Not');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_chat', 'Sohbeti sil');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x3_matches_will_expire_in', 'X3 eşleşmelerinizin süresinin dolması');
            $lang_update_queries[] = PT_UpdateLangs($value, 'to_get_your_profile_verified_you_have_to_verify_these.', 'Profilinizi doğrulamak için bunları doğrulamanız gerekir.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'upload_at_least_5_image.', 'En az 5 resim yükleyin.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'unblock', 'engeli kaldırmak');
        } else if ($value == 'english') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'social_accounts', 'Social accounts');
            $lang_update_queries[] = PT_UpdateLangs($value, 'current_password', 'Current Password');
            $lang_update_queries[] = PT_UpdateLangs($value, 'chat_conversations_fetch_successfully', 'Chat conversations successfully fetched');
            $lang_update_queries[] = PT_UpdateLangs($value, 'messages_deleted_successfully.', 'Messages were successfully deleted.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_chat.', 'Delete chat.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'by_creating_your_account__you_agree_to_our', 'By creating your account, you agree to our');
            $lang_update_queries[] = PT_UpdateLangs($value, 'choose_your_gender', 'Choose your gender');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_phone_number_is_already_exist.', 'This phone number is already exist.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_account_', 'Delete account?');
            $lang_update_queries[] = PT_UpdateLangs($value, 'are_you_sure_you_want_to_delete_your_account__all_content_including_published_photos_and_other_data_will_be_permanetly_removed_', 'Are you sure you want to delete your account? All content including published photos and other data will be permanetly removed!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete', 'Delete');
            $lang_update_queries[] = PT_UpdateLangs($value, 'enter_a_location', 'Enter a location');
            $lang_update_queries[] = PT_UpdateLangs($value, 'media_message_sent_successfully.', 'Message sent.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_are_a_pro_now.', 'You are a pro now!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x10_visits_will_expire_in', 'Your x10 visits will expire in');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x4_likes_will_expire_in', 'Your x4 likes will expire in');
            $lang_update_queries[] = PT_UpdateLangs($value, 'verification', 'Verification');
            $lang_update_queries[] = PT_UpdateLangs($value, 'gift_added', 'Gift successfully added.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_a_gift_to_you.', 'sent a gift to you.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number_cannot_be_empty', 'Phone number is required');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_add_gift', 'Error while adding a gift');
            $lang_update_queries[] = PT_UpdateLangs($value, 'profile_avatar_uploaded_successfully.', 'Profile avatar successfully uploaded.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_add_sticker', 'Error while adding the sticker');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_saving_notification', 'Error while saving notification');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sticker_sent_successfully.', 'Sticker successfully sent.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'like_add_successfully.', 'Like successfully added.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_a_message_', 'sent you a message!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details.', 'We have rejected your bank transfer, please contact us for more details.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number__e.g__90..', 'Phone number, e.g +90..');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_verification_needed', 'Phone verification required');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone', 'Phone');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_otp', 'Send OTP');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_activiation_', 'Phone activiation,');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_the_verification_code_sent_to_your_phone', 'Please enter the verification code that was sent to your phone. ');
            $lang_update_queries[] = PT_UpdateLangs($value, 'resend', 'Re-send');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_verify_your_email_address', 'Please verify your email address.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_sending_an_sms__please_try_again_later.', 'Error while sending the SMS, please try again later.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_submitting_form.', 'Error while submitting form.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_verification_needed', 'Email verification required');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_activiation_', 'Email activiation,');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_the_verification_code_sent_to_your_email', 'Please enter the verification code that was sent to your E-mail.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'bank_transfer', 'Bank Transfer');
            $lang_update_queries[] = PT_UpdateLangs($value, 'close', 'Close');
            $lang_update_queries[] = PT_UpdateLangs($value, 'bank_information', 'Bank Information');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_transfer_the_amount_of', 'Please transfer the amount of');
            $lang_update_queries[] = PT_UpdateLangs($value, 'to_this_bank_account_to_buy', 'to this bank account to purchase');
            $lang_update_queries[] = PT_UpdateLangs($value, 'upload_receipt', 'Upload Receipt');
            $lang_update_queries[] = PT_UpdateLangs($value, 'confirm', 'Confirm');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_receipt_uploaded_successfully.', 'Your was receipt successfully uploaded.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'date', 'Date');
            $lang_update_queries[] = PT_UpdateLangs($value, 'processed_by', 'Processed By');
            $lang_update_queries[] = PT_UpdateLangs($value, 'amount', 'Amount');
            $lang_update_queries[] = PT_UpdateLangs($value, 'type', 'Type');
            $lang_update_queries[] = PT_UpdateLangs($value, 'notes', 'Notes');
            $lang_update_queries[] = PT_UpdateLangs($value, 'plan_premium_membership', 'Plan Premium Membership');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_boost_will_expire_in', 'Your boost will expire in');
            $lang_update_queries[] = PT_UpdateLangs($value, 'hide', 'Hide');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_reach_the_max_of_swipes_per_day._you_have_to_wait__0__hours_before_you_can_redo_likes_or_upgrade_to_pro_to_for_unlimited.', 'You have reached the maximum amuont of swipes per day, you have to wait {0} hours before you can redo swipes, OR upgrade now to Pro Membership for unlimited swipes and likes.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_payment_was_processed_successfully.', 'Your payment was successfully processed.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sms', 'SMS');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_message_', 'sent you a message!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_a_message_', 'sent you a message!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accept', 'Accept');
            $lang_update_queries[] = PT_UpdateLangs($value, 'decline', 'Decline');
            $lang_update_queries[] = PT_UpdateLangs($value, 'calling', 'Calling');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_wait_for_your_friend_answer.', 'Please wait for your friend\'s answer.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_answer', 'No answer');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_try_again_later.', 'Please try again later.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'new_video_call', 'New incoming video call');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wants_to_video_chat_with_you.', 'wants to video chat with you.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'call_declined', 'Call declined');
            $lang_update_queries[] = PT_UpdateLangs($value, 'the_recipient_has_declined_the_call__please_try_again_later.', 'The recipient has declined the call, please try again later.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accept___start', 'Accept & Start');
            $lang_update_queries[] = PT_UpdateLangs($value, 'answered__', 'Answered!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_wait..', 'Please wait..');
            $lang_update_queries[] = PT_UpdateLangs($value, 'video_call', 'Video Call');
            $lang_update_queries[] = PT_UpdateLangs($value, 'new_audio_call', 'New incoming audio call');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wants_to_talk_with_you.', 'wants to talk with you.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'audio_call', 'Audio call');
            $lang_update_queries[] = PT_UpdateLangs($value, 'talking_with', 'talking with');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_website_uses_cookies_to_ensure_you_get_the_best_experience_on_our_website.', 'This website uses cookies to ensure you get the best experience on our website.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'got_it_', 'Got It!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'learn_more', 'Learn More');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_result_found', 'No result found');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_gif', 'Send GIF');
            $lang_update_queries[] = PT_UpdateLangs($value, 'search_gifs', 'Search GIFs');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sticker_added', 'Sticker added');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_phone_number_is_required.', 'Your phone number is required.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'select_your_country.', 'Please select your country.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'select_your_birth_date.', 'Please select your birthday.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'my_location', 'My Location');
            $lang_update_queries[] = PT_UpdateLangs($value, 'or', 'OR');
            $lang_update_queries[] = PT_UpdateLangs($value, 'instagram', 'Instagram');
            $lang_update_queries[] = PT_UpdateLangs($value, 'disable', 'disable');
            $lang_update_queries[] = PT_UpdateLangs($value, 'enable', 'enable');
            $lang_update_queries[] = PT_UpdateLangs($value, 'travel_to_another_country__and_relocate_', 'Travel to another country, and relocate!');
            $lang_update_queries[] = PT_UpdateLangs($value, '4525', 'Male');
            $lang_update_queries[] = PT_UpdateLangs($value, '4526', 'Female');
            $lang_update_queries[] = PT_UpdateLangs($value, 'about_you', 'About You');
            $lang_update_queries[] = PT_UpdateLangs($value, 'km', 'km');
            $lang_update_queries[] = PT_UpdateLangs($value, 'message_requests', 'Message requests');
            $lang_update_queries[] = PT_UpdateLangs($value, 'all_conversations', 'All conversations');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_can_chat_with_this_user_after', 'You can chat with this profile after');
            $lang_update_queries[] = PT_UpdateLangs($value, 'hours.', 'hours.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_user_decline_your_chat_before_so_you_can_chat_with_this_user_after', 'This user has declined your chat before, you\'ll be able to chat with this user after');
            $lang_update_queries[] = PT_UpdateLangs($value, 'active', 'active');
            $lang_update_queries[] = PT_UpdateLangs($value, 'declined', 'Declined');
            $lang_update_queries[] = PT_UpdateLangs($value, 'pending', 'Pending');
            $lang_update_queries[] = PT_UpdateLangs($value, 'night_mode', 'Night mode');
            $lang_update_queries[] = PT_UpdateLangs($value, 'day_mode', 'Day mode');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we___ll_be_back_soon_', 'We’ll be back soon!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sorry_for_the_inconvenience_but_we_rsquo_re_performing_some_maintenance_at_the_moment._if_you_need_help_you_can_always', 'Sorry for the inconvenience but we\'re performing some maintenance at the moment. If you need help you can always');
            $lang_update_queries[] = PT_UpdateLangs($value, 'otherwise_we_rsquo_ll_be_back_online_shortly_', 'otherwise we\'ll be back online shortly!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details', 'We have rejected your bank transfer, please contact us for more details.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accepted_your_message_request_', 'accepted your message request!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'declined_your_message_request_', 'declined your message request!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_approved_your_bank_transfer_of__d_', 'We approved your bank transfer of %d!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'note', 'Note');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_chat', 'Delete chat');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x3_matches_will_expire_in', 'Your x3 matches will expire in');
            $lang_update_queries[] = PT_UpdateLangs($value, 'to_get_your_profile_verified_you_have_to_verify_these.', 'To get your profile verified you have to verify these.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'upload_at_least_5_image.', 'Upload at least 5 image.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'unblock', 'Unblock');
        } else if ($value != 'english') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'social_accounts', 'Social accounts');
            $lang_update_queries[] = PT_UpdateLangs($value, 'current_password', 'Current Password');
            $lang_update_queries[] = PT_UpdateLangs($value, 'chat_conversations_fetch_successfully', 'Chat conversations successfully fetched');
            $lang_update_queries[] = PT_UpdateLangs($value, 'messages_deleted_successfully.', 'Messages were successfully deleted.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_chat.', 'Delete chat.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'by_creating_your_account__you_agree_to_our', 'By creating your account, you agree to our');
            $lang_update_queries[] = PT_UpdateLangs($value, 'choose_your_gender', 'Choose your gender');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_phone_number_is_already_exist.', 'This phone number is already exist.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_account_', 'Delete account?');
            $lang_update_queries[] = PT_UpdateLangs($value, 'are_you_sure_you_want_to_delete_your_account__all_content_including_published_photos_and_other_data_will_be_permanetly_removed_', 'Are you sure you want to delete your account? All content including published photos and other data will be permanetly removed!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete', 'Delete');
            $lang_update_queries[] = PT_UpdateLangs($value, 'enter_a_location', 'Enter a location');
            $lang_update_queries[] = PT_UpdateLangs($value, 'media_message_sent_successfully.', 'Message sent.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_are_a_pro_now.', 'You are a pro now!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x10_visits_will_expire_in', 'Your x10 visits will expire in');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x4_likes_will_expire_in', 'Your x4 likes will expire in');
            $lang_update_queries[] = PT_UpdateLangs($value, 'verification', 'Verification');
            $lang_update_queries[] = PT_UpdateLangs($value, 'gift_added', 'Gift successfully added.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_a_gift_to_you.', 'sent a gift to you.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number_cannot_be_empty', 'Phone number is required');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_add_gift', 'Error while adding a gift');
            $lang_update_queries[] = PT_UpdateLangs($value, 'profile_avatar_uploaded_successfully.', 'Profile avatar successfully uploaded.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_add_sticker', 'Error while adding the sticker');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_saving_notification', 'Error while saving notification');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sticker_sent_successfully.', 'Sticker successfully sent.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'like_add_successfully.', 'Like successfully added.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_a_message_', 'sent you a message!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details.', 'We have rejected your bank transfer, please contact us for more details.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_number__e.g__90..', 'Phone number, e.g +90..');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_verification_needed', 'Phone verification required');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone', 'Phone');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_otp', 'Send OTP');
            $lang_update_queries[] = PT_UpdateLangs($value, 'phone_activiation_', 'Phone activiation,');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_the_verification_code_sent_to_your_phone', 'Please enter the verification code that was sent to your phone. ');
            $lang_update_queries[] = PT_UpdateLangs($value, 'resend', 'Re-send');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_verify_your_email_address', 'Please verify your email address.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_sending_an_sms__please_try_again_later.', 'Error while sending the SMS, please try again later.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'error_while_submitting_form.', 'Error while submitting form.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_verification_needed', 'Email verification required');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_activiation_', 'Email activiation,');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_the_verification_code_sent_to_your_email', 'Please enter the verification code that was sent to your E-mail.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'bank_transfer', 'Bank Transfer');
            $lang_update_queries[] = PT_UpdateLangs($value, 'close', 'Close');
            $lang_update_queries[] = PT_UpdateLangs($value, 'bank_information', 'Bank Information');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_transfer_the_amount_of', 'Please transfer the amount of');
            $lang_update_queries[] = PT_UpdateLangs($value, 'to_this_bank_account_to_buy', 'to this bank account to purchase');
            $lang_update_queries[] = PT_UpdateLangs($value, 'upload_receipt', 'Upload Receipt');
            $lang_update_queries[] = PT_UpdateLangs($value, 'confirm', 'Confirm');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_receipt_uploaded_successfully.', 'Your was receipt successfully uploaded.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'date', 'Date');
            $lang_update_queries[] = PT_UpdateLangs($value, 'processed_by', 'Processed By');
            $lang_update_queries[] = PT_UpdateLangs($value, 'amount', 'Amount');
            $lang_update_queries[] = PT_UpdateLangs($value, 'type', 'Type');
            $lang_update_queries[] = PT_UpdateLangs($value, 'notes', 'Notes');
            $lang_update_queries[] = PT_UpdateLangs($value, 'plan_premium_membership', 'Plan Premium Membership');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_boost_will_expire_in', 'Your boost will expire in');
            $lang_update_queries[] = PT_UpdateLangs($value, 'hide', 'Hide');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_reach_the_max_of_swipes_per_day._you_have_to_wait__0__hours_before_you_can_redo_likes_or_upgrade_to_pro_to_for_unlimited.', 'You have reached the maximum amuont of swipes per day, you have to wait {0} hours before you can redo swipes, OR upgrade now to Pro Membership for unlimited swipes and likes.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_payment_was_processed_successfully.', 'Your payment was successfully processed.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sms', 'SMS');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_message_', 'sent you a message!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sent_you_a_message_', 'sent you a message!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accept', 'Accept');
            $lang_update_queries[] = PT_UpdateLangs($value, 'decline', 'Decline');
            $lang_update_queries[] = PT_UpdateLangs($value, 'calling', 'Calling');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_wait_for_your_friend_answer.', 'Please wait for your friend\'s answer.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_answer', 'No answer');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_try_again_later.', 'Please try again later.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'new_video_call', 'New incoming video call');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wants_to_video_chat_with_you.', 'wants to video chat with you.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'call_declined', 'Call declined');
            $lang_update_queries[] = PT_UpdateLangs($value, 'the_recipient_has_declined_the_call__please_try_again_later.', 'The recipient has declined the call, please try again later.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accept___start', 'Accept & Start');
            $lang_update_queries[] = PT_UpdateLangs($value, 'answered__', 'Answered!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_wait..', 'Please wait..');
            $lang_update_queries[] = PT_UpdateLangs($value, 'video_call', 'Video Call');
            $lang_update_queries[] = PT_UpdateLangs($value, 'new_audio_call', 'New incoming audio call');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wants_to_talk_with_you.', 'wants to talk with you.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'audio_call', 'Audio call');
            $lang_update_queries[] = PT_UpdateLangs($value, 'talking_with', 'talking with');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_website_uses_cookies_to_ensure_you_get_the_best_experience_on_our_website.', 'This website uses cookies to ensure you get the best experience on our website.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'got_it_', 'Got It!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'learn_more', 'Learn More');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_result_found', 'No result found');
            $lang_update_queries[] = PT_UpdateLangs($value, 'send_gif', 'Send GIF');
            $lang_update_queries[] = PT_UpdateLangs($value, 'search_gifs', 'Search GIFs');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sticker_added', 'Sticker added');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_phone_number_is_required.', 'Your phone number is required.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'select_your_country.', 'Please select your country.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'select_your_birth_date.', 'Please select your birthday.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'my_location', 'My Location');
            $lang_update_queries[] = PT_UpdateLangs($value, 'or', 'OR');
            $lang_update_queries[] = PT_UpdateLangs($value, 'instagram', 'Instagram');
            $lang_update_queries[] = PT_UpdateLangs($value, 'disable', 'disable');
            $lang_update_queries[] = PT_UpdateLangs($value, 'enable', 'enable');
            $lang_update_queries[] = PT_UpdateLangs($value, 'travel_to_another_country__and_relocate_', 'Travel to another country, and relocate!');
            $lang_update_queries[] = PT_UpdateLangs($value, '4525', 'Male');
            $lang_update_queries[] = PT_UpdateLangs($value, '4526', 'Female');
            $lang_update_queries[] = PT_UpdateLangs($value, 'about_you', 'About You');
            $lang_update_queries[] = PT_UpdateLangs($value, 'km', 'km');
            $lang_update_queries[] = PT_UpdateLangs($value, 'message_requests', 'Message requests');
            $lang_update_queries[] = PT_UpdateLangs($value, 'all_conversations', 'All conversations');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_can_chat_with_this_user_after', 'You can chat with this profile after');
            $lang_update_queries[] = PT_UpdateLangs($value, 'hours.', 'hours.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_user_decline_your_chat_before_so_you_can_chat_with_this_user_after', 'This user has declined your chat before, you\'ll be able to chat with this user after');
            $lang_update_queries[] = PT_UpdateLangs($value, 'active', 'active');
            $lang_update_queries[] = PT_UpdateLangs($value, 'declined', 'Declined');
            $lang_update_queries[] = PT_UpdateLangs($value, 'pending', 'Pending');
            $lang_update_queries[] = PT_UpdateLangs($value, 'night_mode', 'Night mode');
            $lang_update_queries[] = PT_UpdateLangs($value, 'day_mode', 'Day mode');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we___ll_be_back_soon_', 'We’ll be back soon!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'sorry_for_the_inconvenience_but_we_rsquo_re_performing_some_maintenance_at_the_moment._if_you_need_help_you_can_always', 'Sorry for the inconvenience but we\'re performing some maintenance at the moment. If you need help you can always');
            $lang_update_queries[] = PT_UpdateLangs($value, 'otherwise_we_rsquo_ll_be_back_online_shortly_', 'otherwise we\'ll be back online shortly!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details', 'We have rejected your bank transfer, please contact us for more details.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'accepted_your_message_request_', 'accepted your message request!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'declined_your_message_request_', 'declined your message request!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_approved_your_bank_transfer_of__d_', 'We approved your bank transfer of %d!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'note', 'Note');
            $lang_update_queries[] = PT_UpdateLangs($value, 'delete_chat', 'Delete chat');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_x3_matches_will_expire_in', 'Your x3 matches will expire in');
            $lang_update_queries[] = PT_UpdateLangs($value, 'to_get_your_profile_verified_you_have_to_verify_these.', 'To get your profile verified you have to verify these.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'upload_at_least_5_image.', 'Upload at least 5 image.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'unblock', 'Unblock');
        }
    }
    if (!empty($lang_update_queries)) {
        foreach ($lang_update_queries as $key => $query) {
            $sql = mysqli_query($conn, $query);
        }
    }
    $query = mysqli_query($conn, "INSERT INTO `options` (`id`, `option_name`, `option_value`, `created_at`) VALUES (NULL, 'bank_description', '" . Secure('<div class="dt_settings_header bg_gradient">
                    <div class="dt_settings_circle-1"></div>
                    <div class="dt_settings_circle-2"></div>
                    <div class="bank_info_innr">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="currentColor" d="M11.5,1L2,6V8H21V6M16,10V17H19V10M2,22H21V19H2M10,10V17H13V10M4,10V17H7V10H4Z"></path></svg>
                        <h4 class="bank_name">BANK NAME</h4>
                        <div class="row">
                            <div class="col s12">
                                <div class="bank_account">
                                    <p>4796824372433055</p>
                                    <span class="help-block">Account number / IBAN</span>
                                </div>
                            </div>
                            <div class="col s12">
                                <div class="bank_account_holder">
                                    <p>Antoian Kordiyal</p>
                                    <span class="help-block">Account name</span>
                                </div>
                            </div>
                            <div class="col s6">
                                <div class="bank_account_code">
                                    <p>TGBATRISXXX</p>
                                    <span class="help-block">Routing code</span>
                                </div>
                            </div>
                            <div class="col s6">
                                <div class="bank_account_country">
                                    <p>Turkey</p>
                                    <span class="help-block">Country</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>') . "', NULL)");
    $name  = md5(microtime()) . '_updated.php';
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
                     <h2 class="light">Update to v1.1 </span></h2>
                     <div class="setting-well">
                        <h4>Changelog</h4>
                        <ul class="wo_update_changelog">
                             <li>[Added] video and audio calls.</li>
                            <li>[Added] fake user generator. </li> 
                            <li>[Added] announcement system.</li>
                            <li>[Added] messaging approval / decline system, user can decline a message from other users.</li>
                            <li>[Added] user now can see himself in pro sidebar if he boosted.</li>
                            <li>[Added] daily like limit, user can't like more than X users per day. </li>
                            <li>[Added] bank payments system, user can pay via bank local payments.</li>
                            <li>[Added] the ability to manage genders from admin panel.</li>
                            <li>[Added] cookie law bar.</li>
                            <li>[Added] the ability for pro user to change his country, and relocate.</li>
                            <li>[Added] SMS gateaway payment using: paysera.com</li>
                            <li>[Added] the ability to swtitch between Miles and KM.</li>
                            <li>[Added] Maintenance mode.</li>
                            <li>[Added] Night mode.</li>
                            <li>[Removed] landing page, user land on login page now.</li>
                            <li>[Improved] site speed.</li>
                            <li>[Fixed] few reported bugs.</li>
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
    "CREATE TABLE IF NOT EXISTS `audiocalls` (`id` int(11) NOT NULL, `call_id` varchar(30) NOT NULL DEFAULT '0', `access_token` text, `call_id_2` varchar(30) NOT NULL DEFAULT '',`access_token_2` text, `from_id` int(11) NOT NULL DEFAULT '0',`to_id` int(11) NOT NULL DEFAULT '0', `room_name` varchar(50) NOT NULL DEFAULT '', `active` int(11) NOT NULL DEFAULT '0', `called` int(11) NOT NULL DEFAULT '0', `time` int(11) NOT NULL DEFAULT '0', `declined` int(11) NOT NULL DEFAULT '0') ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;",
    "ALTER TABLE `audiocalls` ADD PRIMARY KEY (`id`), ADD KEY `to_id` (`to_id`), ADD KEY `from_id` (`from_id`), ADD KEY `call_id` (`call_id`), ADD KEY `called` (`called`),ADD KEY `declined` (`declined`);",
    "ALTER TABLE `audiocalls` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;",
    "CREATE TABLE IF NOT EXISTS `videocalles` (`id` int(11) NOT NULL,`access_token` text,`access_token_2` text,`from_id` int(11) NOT NULL DEFAULT '0',`to_id` int(11) NOT NULL DEFAULT '0',`room_name` varchar(50) NOT NULL DEFAULT '',`active` int(11) NOT NULL DEFAULT '0',`called` int(11) NOT NULL DEFAULT '0',`time` int(11) NOT NULL DEFAULT '0',`declined` int(11) NOT NULL DEFAULT '0') ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;",
    "ALTER TABLE `videocalles` ADD PRIMARY KEY (`id`), ADD KEY `to_id` (`to_id`),ADD KEY `from_id` (`from_id`), ADD KEY `called` (`called`), ADD KEY `declined` (`declined`);",
    "ALTER TABLE `videocalles` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;",
    "CREATE TABLE IF NOT EXISTS `bank_receipts` (`id` int(11) unsigned NOT NULL,`user_id` int(11) unsigned NOT NULL DEFAULT '0',`description` tinytext NOT NULL,`price` varchar(50) NOT NULL DEFAULT '',`mode` varchar(50) NOT NULL DEFAULT '',`approved` int(11) unsigned NOT NULL DEFAULT '0',`receipt_file` varchar(250) NOT NULL,`created_at` date NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "ALTER TABLE `bank_receipts` ADD PRIMARY KEY (`id`);",
    "ALTER TABLE `bank_receipts` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;",
    "CREATE TABLE IF NOT EXISTS `announcement` (`id` int(11) NOT NULL,`text` text,`time` int(32) NOT NULL DEFAULT '0',`active` enum('0','1') NOT NULL DEFAULT '1') ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "ALTER TABLE `announcement` ADD PRIMARY KEY (`id`), ADD KEY `active` (`active`);",
    "ALTER TABLE `announcement` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;",
    "CREATE TABLE IF NOT EXISTS `announcement_views` (`id` int(11) NOT NULL,`user_id` int(11) NOT NULL DEFAULT '0',`announcement_id` int(11) NOT NULL DEFAULT '0') ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "ALTER TABLE `announcement_views`ADD PRIMARY KEY (`id`),ADD KEY `user_id` (`user_id`),ADD KEY `announcement_id` (`announcement_id`);",
    "ALTER TABLE `announcement_views` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;",
    "ALTER TABLE `bank_receipts` CHANGE `description` `description` TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';",
    "ALTER TABLE `bank_receipts` CHANGE `price` `price` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0';",
    "ALTER TABLE `bank_receipts` CHANGE `receipt_file` `receipt_file` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';",
    "ALTER TABLE `bank_receipts` CHANGE `created_at` `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;",
    "ALTER TABLE `blocks` CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;",
    "ALTER TABLE `gifts` CHANGE `name` `name` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '';",
    "ALTER TABLE `langs` CHANGE `lang_key` `lang_key` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `likes` CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;",
    "ALTER TABLE `mediafiles` CHANGE `file` `file` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `mediafiles` CHANGE `created_at` `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP;",
    "ALTER TABLE `messages` CHANGE `media` `media` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '';",
    "ALTER TABLE `messages` CHANGE `sticker` `sticker` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '';",
    "ALTER TABLE `messages` CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;",
    "ALTER TABLE `notifications` CHANGE `text` `text` VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '';",
    "ALTER TABLE `notifications` CHANGE `url` `url` VARCHAR(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '';",
    "ALTER TABLE `notifications` CHANGE `full_url` `full_url` VARCHAR(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '';",
    "ALTER TABLE `options` CHANGE `option_name` `option_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `options` CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;",
    "ALTER TABLE `payments` CHANGE `pro_plan` `pro_plan` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '';",
    "ALTER TABLE `payments` CHANGE `via` `via` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '';",
    "ALTER TABLE `reports` CHANGE `report_text` `report_text` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '';",
    "ALTER TABLE `reports` CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;",
    "ALTER TABLE `stickers` CHANGE `file` `file` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `stickers` CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;",
    "ALTER TABLE `users` CHANGE `first_name` `first_name` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `last_name` `last_name` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `address` `address` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `facebook` `facebook` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `google` `google` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `twitter` `twitter` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `linkedin` `linkedin` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `website` `website` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `instagrem` `instagrem` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `web_device_id` `web_device_id` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `email_code` `email_code` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `ip_address` `ip_address` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `phone_number` `phone_number` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `timezone` `timezone` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `lat` `lat` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '0';",
    "ALTER TABLE `users` CHANGE `lng` `lng` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '0';",
    "ALTER TABLE `users` CHANGE `about` `about` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `country` `country` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT 0;",
    "ALTER TABLE `users` CHANGE `updated_at` `updated_at` TIMESTAMP NULL DEFAULT 0;",
    "ALTER TABLE `users` CHANGE `deleted_at` `deleted_at` TIMESTAMP NULL DEFAULT 0;",
    "ALTER TABLE `users` CHANGE `mobile_device_id` `mobile_device_id` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `web_token` `web_token` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `mobile_token` `mobile_token` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `height` `height` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `hair_color` `hair_color` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `web_token_created_at` `web_token_created_at` INT(11) NULL DEFAULT '0';",
    "ALTER TABLE `users` CHANGE `mobile_token_created_at` `mobile_token_created_at` INT(11) NULL DEFAULT '0';",
    "ALTER TABLE `users` CHANGE `web_device` `web_device` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `mobile_device` `mobile_device` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `interest` `interest` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `location` `location` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `relationship` `relationship` INT(10) UNSIGNED NULL DEFAULT '0';",
    "ALTER TABLE `users` CHANGE `work_status` `work_status` INT(10) UNSIGNED NULL DEFAULT '0';",
    "ALTER TABLE `users` CHANGE `education` `education` INT(10) UNSIGNED NULL DEFAULT '0';",
    "ALTER TABLE `users` CHANGE `ethnicity` `ethnicity` INT(10) UNSIGNED NULL DEFAULT '0';",
    "ALTER TABLE `users` CHANGE `body` `body` INT(10) UNSIGNED NULL DEFAULT '0';",
    "ALTER TABLE `users` CHANGE `character` `character` INT(10) UNSIGNED NULL DEFAULT '0';",
    "ALTER TABLE `users` CHANGE `children` `children` INT(10) UNSIGNED NULL DEFAULT '0';",
    "ALTER TABLE `users` CHANGE `friends` `friends` INT(10) UNSIGNED NULL DEFAULT '0';",
    "ALTER TABLE `users` CHANGE `pets` `pets` INT(10) UNSIGNED NULL DEFAULT '0';",
    "ALTER TABLE `users` CHANGE `live_with` `live_with` INT(10) UNSIGNED NULL DEFAULT '0';",
    "ALTER TABLE `users` CHANGE `car` `car` INT(10) UNSIGNED NULL DEFAULT '0';",
    "ALTER TABLE `users` CHANGE `religion` `religion` INT(10) UNSIGNED NULL DEFAULT '0';",
    "ALTER TABLE `users` CHANGE `smoke` `smoke` INT(10) UNSIGNED NULL DEFAULT '0';",
    "ALTER TABLE `users` CHANGE `drink` `drink` INT(10) UNSIGNED NULL DEFAULT '0';",
    "ALTER TABLE `users` CHANGE `travel` `travel` INT(10) UNSIGNED NULL DEFAULT '0';",
    "ALTER TABLE `users` CHANGE `music` `music` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `dish` `dish` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `song` `song` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `hobby` `hobby` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `city` `city` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `sport` `sport` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `book` `book` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `movie` `movie` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `colour` `colour` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `users` CHANGE `tv` `tv` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '';",
    "ALTER TABLE `user_chat_buy` CHANGE `created_at` `created_at` DATETIME NOT NULL DEFAULT '0000-00-00 00:00';",
    "ALTER TABLE `videocalles` CHANGE `access_token` `access_token` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '';",
    "ALTER TABLE `videocalles` CHANGE `access_token_2` `access_token_2` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '';",
    "ALTER TABLE `views` CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;",
    "ALTER TABLE `users` ADD `show_me_to` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' AFTER `xlikes_created_at`;",
    "INSERT INTO `options` (`id`, `option_name`, `option_value`, `created_at`) VALUES (NULL, 'bank_transfer_note', 'In order to confirm the bank transfer, you will need to upload a receipt or take a screenshot of your transfer within 1 day from your payment date. If a bank transfer is made but no receipt is uploaded within this period, your order will be cancelled. We will verify and confirm your receipt within 3 working days from the date you upload it.', NULL),(NULL, 'max_swaps', '50', NULL),(NULL, 'stripe_version', 'v1', NULL),(NULL, 'paysera_project_id', '0', NULL),(NULL, 'paysera_password', '', NULL),(NULL, 'paysera_test_mode', 'test', NULL),(NULL, 'message_request_system', 'on', NULL),(NULL, 'video_chat', '0', NULL),(NULL, 'audio_chat', '0', NULL),(NULL, 'video_accountSid', '', NULL),(NULL, 'video_apiKeySid', '', NULL),(NULL, 'video_apiKeySecret', '', NULL),(NULL, 'giphy_api', 'GIjbMwjlfGcmNEgB0eqphgRgwNCYN8gh', NULL),(NULL, 'default_unit', 'km', NULL);",
    "ALTER TABLE `bank_receipts` ADD `approved_at` INT(11) UNSIGNED NOT NULL DEFAULT '0' AFTER `created_at`;",
    "ALTER TABLE `conversations` ADD `status` INT(11) UNSIGNED NOT NULL DEFAULT '0' AFTER `created_at`;",
    "UPDATE `conversations` SET `status` = 1;",
    "ALTER TABLE `langs` DROP INDEX `langs_lang_key_unique`, ADD INDEX `langs_lang_key_unique` (`lang_key`) USING BTREE;",
    "INSERT INTO `options` (`id`, `option_name`, `option_value`, `created_at`) VALUES (NULL, 'maintenance_mode', '0', NULL), (NULL, 'displaymode', 'day', NULL);",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'bank_transfer');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'close');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'bank_information');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'please_transfer_the_amount_of');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'to_this_bank_account_to_buy');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'upload_receipt');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'confirm');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'your_receipt_uploaded_successfully.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'date');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'processed_by');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'amount');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'type');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'notes');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'plan_premium_membership');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'your_boost_will_expire_in');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'hide');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'you_reach_the_max_of_swipes_per_day._you_have_to_wait__0__hours_before_you_can_redo_likes_or_upgrade_to_pro_to_for_unlimited.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'your_payment_was_processed_successfully.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'sms');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'sent_you_message_');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'sent_you_a_message_');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'accept');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'decline');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'calling');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'please_wait_for_your_friend_answer.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'no_answer');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'please_try_again_later.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'new_video_call');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'wants_to_video_chat_with_you.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'call_declined');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'the_recipient_has_declined_the_call__please_try_again_later.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'accept___start');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'answered__');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'please_wait..');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'video_call');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'new_audio_call');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'wants_to_talk_with_you.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'audio_call');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'talking_with');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'this_website_uses_cookies_to_ensure_you_get_the_best_experience_on_our_website.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'got_it_');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'learn_more');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'no_result_found');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'send_gif');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'search_gifs');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'sticker_added');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'your_phone_number_is_required.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'select_your_country.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'select_your_birth_date.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'my_location');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'or');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'instagram');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'disable');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'enable');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'travel_to_another_country__and_relocate_');",
    "INSERT INTO `langs` (`id`, `ref`, `lang_key`) VALUES (NULL, 'gender', '4525');",
    "INSERT INTO `langs` (`id`, `ref`, `lang_key`) VALUES (NULL, 'gender', '4526');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'about_you');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'km');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'message_requests');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'all_conversations');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'you_can_chat_with_this_user_after');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'hours.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'this_user_decline_your_chat_before_so_you_can_chat_with_this_user_after');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'active');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'declined');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'pending');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'night_mode');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'day_mode');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'we___ll_be_back_soon_');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'sorry_for_the_inconvenience_but_we_rsquo_re_performing_some_maintenance_at_the_moment._if_you_need_help_you_can_always');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'otherwise_we_rsquo_ll_be_back_online_shortly_');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'declined_your_message_request_');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'accepted_your_message_request_');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'we_approved_your_bank_transfer_of__d_');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'note');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'delete_chat');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'your_x3_matches_will_expire_in');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'to_get_your_profile_verified_you_have_to_verify_these.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'upload_at_least_5_image.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'unblock');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'phone_number__e.g__90..');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'phone_verification_needed');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'phone');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'send_otp');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'phone_activiation_');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'please_enter_the_verification_code_sent_to_your_phone');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'resend');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'please_verify_your_email_address');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'error_while_sending_an_sms__please_try_again_later.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'error_while_submitting_form.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'email_verification_needed');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'email_activiation_');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'please_enter_the_verification_code_sent_to_your_email');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'social_accounts');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'current_password');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'chat_conversations_fetch_successfully');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'messages_deleted_successfully.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'delete_chat.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'by_creating_your_account__you_agree_to_our');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'choose_your_gender');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'this_phone_number_is_already_exist.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'delete_account_');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'are_you_sure_you_want_to_delete_your_account__all_content_including_published_photos_and_other_data_will_be_permanetly_removed_');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'delete');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'enter_a_location');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'media_message_sent_successfully.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'you_are_a_pro_now.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'your_x10_visits_will_expire_in');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'your_x4_likes_will_expire_in');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'verification');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'gift_added');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'send_a_gift_to_you.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'phone_number_cannot_be_empty');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'error_while_add_gift');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'profile_avatar_uploaded_successfully.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'error_while_add_sticker');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'error_while_saving_notification');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'sticker_sent_successfully.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'like_add_successfully.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'sent_you_a_message_');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details.');",
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