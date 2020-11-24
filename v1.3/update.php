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
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details.', 'لقد رفضنا تحويلك المصرفي ، يرجى الاتصال بنا للحصول على مزيد من التفاصيل.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_transmitting_spam_messages._the_system_automatically_restricts_chat_for_you__so_you_can_chat_again_after', 'يمكنك نقل رسائل البريد المزعج. يقوم النظام تلقائيًا بتقييد الدردشة نيابة عنك ، حتى تتمكن من الدردشة مرة أخرى بعد');
            $lang_update_queries[] = PT_UpdateLangs($value, 'options', 'خيارات');
            $lang_update_queries[] = PT_UpdateLangs($value, 'blog', 'مدونة');
            $lang_update_queries[] = PT_UpdateLangs($value, 'success_stories', 'قصص النجاح');
            $lang_update_queries[] = PT_UpdateLangs($value, 'add_new_story', 'أضف قصتك');
            $lang_update_queries[] = PT_UpdateLangs($value, 'create_story', 'خلق قصة');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_more_stories_to_show.', 'لا مزيد من القصص لإظهارها.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'add_new_success_stories', 'أضف قصتك الناجحة');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story__html_allowed_', 'قصة (HTML مسموح)');
            $lang_update_queries[] = PT_UpdateLangs($value, 'quote', 'اقتبس');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_date', 'متى حدثت هذه القصة؟');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_select_user_first.', 'يرجى اختيار مع من كان لديك هذه القصة.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_select_when_story_started.', 'يرجى تحديد متى وقعت القصة.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_quote.', 'يرجى إدخال اقتباس.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_your_story.', 'الرجاء إدخال قصتك.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_add_successfully', 'تمت إضافة قصتك بنجاح.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_add_successfully__please_wait_while_admin_approve_this_story_and_it_will_show_on_site.', 'تمت إضافة قصتك بنجاح ، يرجى الانتظار لحين مراجعة قصتك والموافقة عليها.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story', 'قصة');
            $lang_update_queries[] = PT_UpdateLangs($value, '1309', 'كوميديا');
            $lang_update_queries[] = PT_UpdateLangs($value, '1310', 'السيارات والمركبات');
            $lang_update_queries[] = PT_UpdateLangs($value, '1311', 'الاقتصاد والتجارة');
            $lang_update_queries[] = PT_UpdateLangs($value, '1312', 'التعليم');
            $lang_update_queries[] = PT_UpdateLangs($value, '1313', 'وسائل الترفيه');
            $lang_update_queries[] = PT_UpdateLangs($value, '1314', 'أفلام & amp؛ حيوية');
            $lang_update_queries[] = PT_UpdateLangs($value, '1315', 'الألعاب');
            $lang_update_queries[] = PT_UpdateLangs($value, '1316', 'التاريخ والحقائق');
            $lang_update_queries[] = PT_UpdateLangs($value, '1317', 'أسلوب حياة');
            $lang_update_queries[] = PT_UpdateLangs($value, '1318', 'طبيعي >> صفة');
            $lang_update_queries[] = PT_UpdateLangs($value, '1319', 'الأخبار والسياسة');
            $lang_update_queries[] = PT_UpdateLangs($value, '1320', 'الناس والأمم');
            $lang_update_queries[] = PT_UpdateLangs($value, '1321', 'الحيوانات الأليفة والحيوانات');
            $lang_update_queries[] = PT_UpdateLangs($value, '1322', 'الأماكن والمناطق');
            $lang_update_queries[] = PT_UpdateLangs($value, '1323', 'العلوم والتكنولوجيا');
            $lang_update_queries[] = PT_UpdateLangs($value, '1324', 'رياضة');
            $lang_update_queries[] = PT_UpdateLangs($value, '1325', 'السفر والأحداث');
            $lang_update_queries[] = PT_UpdateLangs($value, '1326', 'آخر');
            $lang_update_queries[] = PT_UpdateLangs($value, 'read_more', 'قراءة المزيد');
            $lang_update_queries[] = PT_UpdateLangs($value, 'categories', 'الاقسام');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_more_articles_to_show.', 'لا مزيد من المقالات لإظهارها.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'article', 'مقالة - سلعة');
            $lang_update_queries[] = PT_UpdateLangs($value, 'share_to', 'حصة ل');
            $lang_update_queries[] = PT_UpdateLangs($value, 'hot_or_not', 'حار أم لا');
            $lang_update_queries[] = PT_UpdateLangs($value, 'image_verification', 'التحقق من الصورة');
            $lang_update_queries[] = PT_UpdateLangs($value, 'verify_your', 'تحقق الخاص بك');
            $lang_update_queries[] = PT_UpdateLangs($value, 'account', 'الحساب');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_will_be_required_to_take_a_selfie_holding_the_id_document_next_to_your_face__so_we_can_compare_your_photo_with_your_actual_look.this_is_just_an_additional_security_measure', 'سيُطلب منك التقاط صورة شخصية تحمل وثيقة الهوية بجوار وجهك ، حتى نتمكن من مقارنة صورتك بمظهرك الفعلي. هذا مجرد تدبير أمني إضافي.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'take_snapshot', 'خذ لقطة');
            $lang_update_queries[] = PT_UpdateLangs($value, 'retake_snapshot', 'استعادة لقطة');
            $lang_update_queries[] = PT_UpdateLangs($value, 'back', 'الى الخلف');
            $lang_update_queries[] = PT_UpdateLangs($value, 'keyword', 'الكلمة');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_articles_found', 'لا توجد مقالات');
            $lang_update_queries[] = PT_UpdateLangs($value, 'tags', 'الكلمات');
            $lang_update_queries[] = PT_UpdateLangs($value, 'free', 'حر');
            $lang_update_queries[] = PT_UpdateLangs($value, 'brings_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'تبرز روح المغامرة في داخلي! موقع الويب سهل الاستخدام للغاية وإمكانية مقابلة شخص من ثقافة أخرى تتعلق بي هي ببساطة مثيرة.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'brings_out_the_feelings_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'تبرز مشاعر المغامرة في داخلي! موقع الويب سهل الاستخدام للغاية وإمكانية مقابلة شخص من ثقافة أخرى تتعلق بي هي ببساطة مثيرة.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'produce_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'أنتج احساس المغامرة في داخلي! موقع الويب سهل الاستخدام للغاية وإمكانية مقابلة شخص من ثقافة أخرى تتعلق بي هي ببساطة مثيرة.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'bring_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'تبرز روح المغامرة في داخلي! موقع الويب سهل الاستخدام للغاية وإمكانية مقابلة شخص من ثقافة أخرى تتعلق بي هي ببساطة مثيرة.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_profile_is_verified_by_photos', 'يتم التحقق من هذا الملف الشخصي بواسطة صورة المستخدم.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_account_wait_admin_photo_verification._please_try_again_later.', 'حسابك قيد المراجعة ، يرجى الانتظار حتى نراجع صورتك ونعيد المحاولة لاحقًا.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_camera_is_off_or_disconnected__please_connect_your_camera_and_try_again.', 'تم إيقاف تشغيل الكاميرا أو فصلها ، يرجى توصيل الكاميرا والمحاولة مرة أخرى.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'try_again', 'حاول مجددا');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_have_previous_story_with_this_user', 'لديك قصة سابقة مع هذا المستخدم');
            $lang_update_queries[] = PT_UpdateLangs($value, 'with', 'مع');
            $lang_update_queries[] = PT_UpdateLangs($value, 'create_story_with_you', 'خلق قصة معك.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'approved_your_story_', 'وافق قصتك!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'has_rejected_your_story.', 'رفض قصتك.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'approve_story', 'الموافقة على القصة');
            $lang_update_queries[] = PT_UpdateLangs($value, 'disapprove_story', 'رفض القصة');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_have_story_with', 'لديك قصة مع');
            $lang_update_queries[] = PT_UpdateLangs($value, 'on', 'على');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_approved_successfully.', 'تمت الموافقة على قصتك.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_disapproved_successfully.', 'لقد تم رفض قصتك.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'social_accounts', 'الحسابات الاجتماعية');
            $lang_update_queries[] = PT_UpdateLangs($value, 'publish', 'نشر');
            $lang_update_queries[] = PT_UpdateLangs($value, 'thank_you_for_your_story__we_have_sent_the_story_to__0___once_approved_your_story_will_be_published.', 'شكرًا لك على قصتك ، لقد أرسلنا القصة إلى {0} ، بمجرد الموافقة على نشر قصتك.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_user_found_with_this_name', 'لم يتم العثور على مستخدم بهذا الاسم');
        } else if ($value == 'dutch') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details.', 'We hebben uw overboeking afgewezen. Neem contact met ons op voor meer informatie.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_transmitting_spam_messages._the_system_automatically_restricts_chat_for_you__so_you_can_chat_again_after', 'U verzendt spam-berichten. het systeem beperkt automatisch de chat voor u, zodat u later opnieuw kunt chatten');
            $lang_update_queries[] = PT_UpdateLangs($value, 'options', 'opties');
            $lang_update_queries[] = PT_UpdateLangs($value, 'blog', 'blog');
            $lang_update_queries[] = PT_UpdateLangs($value, 'success_stories', 'Succesverhalen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'add_new_story', 'Voeg je verhaal toe');
            $lang_update_queries[] = PT_UpdateLangs($value, 'create_story', 'Maak een verhaal');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_more_stories_to_show.', 'Geen verhalen meer om te laten zien.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'add_new_success_stories', 'Voeg uw succesvolle verhaal toe');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story__html_allowed_', 'Verhaal (HTML toegestaan)');
            $lang_update_queries[] = PT_UpdateLangs($value, 'quote', 'Citaat');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_date', 'Toen dit verhaal gebeurde?');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_select_user_first.', 'Kies met wie je dit verhaal had.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_select_when_story_started.', 'Selecteer wanneer het verhaal zich voordeed.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_quote.', 'Voer een offerte in.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_your_story.', 'Voer je verhaal in.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_add_successfully', 'Je verhaal is succesvol toegevoegd.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_add_successfully__please_wait_while_admin_approve_this_story_and_it_will_show_on_site.', 'Je verhaal is succesvol toegevoegd. Een ogenblik geduld. We beoordelen je verhaal en keuren het goed.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story', 'Verhaal');
            $lang_update_queries[] = PT_UpdateLangs($value, '1309', 'Komedie');
            $lang_update_queries[] = PT_UpdateLangs($value, '1310', 'Auto\'s en voertuigen');
            $lang_update_queries[] = PT_UpdateLangs($value, '1311', 'Economie en handel');
            $lang_update_queries[] = PT_UpdateLangs($value, '1312', 'Opleiding');
            $lang_update_queries[] = PT_UpdateLangs($value, '1313', 'vermaak');
            $lang_update_queries[] = PT_UpdateLangs($value, '1314', 'Films & amp; animatie');
            $lang_update_queries[] = PT_UpdateLangs($value, '1315', 'gaming');
            $lang_update_queries[] = PT_UpdateLangs($value, '1316', 'Geschiedenis en feiten');
            $lang_update_queries[] = PT_UpdateLangs($value, '1317', 'Levensstijl');
            $lang_update_queries[] = PT_UpdateLangs($value, '1318', 'natuurlijk');
            $lang_update_queries[] = PT_UpdateLangs($value, '1319', 'Nieuws en politiek');
            $lang_update_queries[] = PT_UpdateLangs($value, '1320', 'Mensen en naties');
            $lang_update_queries[] = PT_UpdateLangs($value, '1321', 'Huisdieren en dieren');
            $lang_update_queries[] = PT_UpdateLangs($value, '1322', 'Plaatsen en regio\'s');
            $lang_update_queries[] = PT_UpdateLangs($value, '1323', 'Wetenschap en technologie');
            $lang_update_queries[] = PT_UpdateLangs($value, '1324', 'Sport');
            $lang_update_queries[] = PT_UpdateLangs($value, '1325', 'Reizen en evenementen');
            $lang_update_queries[] = PT_UpdateLangs($value, '1326', 'anders');
            $lang_update_queries[] = PT_UpdateLangs($value, 'read_more', 'Lees verder');
            $lang_update_queries[] = PT_UpdateLangs($value, 'categories', 'Categorieën');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_more_articles_to_show.', 'Geen artikelen meer om te tonen.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'article', 'Artikel');
            $lang_update_queries[] = PT_UpdateLangs($value, 'share_to', 'Delen naar');
            $lang_update_queries[] = PT_UpdateLangs($value, 'hot_or_not', 'Heet of niet');
            $lang_update_queries[] = PT_UpdateLangs($value, 'image_verification', 'foto verificatie');
            $lang_update_queries[] = PT_UpdateLangs($value, 'verify_your', 'Verifieer jouw');
            $lang_update_queries[] = PT_UpdateLangs($value, 'account', 'account');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_will_be_required_to_take_a_selfie_holding_the_id_document_next_to_your_face__so_we_can_compare_your_photo_with_your_actual_look.this_is_just_an_additional_security_measure', 'U moet een selfie maken met het ID-document naast uw gezicht, zodat we uw foto kunnen vergelijken met uw werkelijke look. Dit is slechts een extra beveiligingsmaatregel.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'take_snapshot', 'Maak snapshot');
            $lang_update_queries[] = PT_UpdateLangs($value, 'retake_snapshot', 'Snapshot opnieuw maken');
            $lang_update_queries[] = PT_UpdateLangs($value, 'back', 'Terug');
            $lang_update_queries[] = PT_UpdateLangs($value, 'keyword', 'keyword');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_articles_found', 'Geen artikelen gevonden');
            $lang_update_queries[] = PT_UpdateLangs($value, 'tags', 'Tags');
            $lang_update_queries[] = PT_UpdateLangs($value, 'free', 'Gratis');
            $lang_update_queries[] = PT_UpdateLangs($value, 'brings_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'Brengt het gevoel van avontuur in mij naar boven! De website is zo gemakkelijk te gebruiken en de mogelijkheid om iemand te ontmoeten uit een andere cultuur die op mij betrekking heeft, is gewoon opwindend.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'brings_out_the_feelings_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'Brengt de gevoelens van avontuur in mij naar boven! De website is zo gemakkelijk te gebruiken en de mogelijkheid om iemand te ontmoeten uit een andere cultuur die op mij betrekking heeft, is gewoon opwindend.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'produce_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'produceer het gevoel van avontuur in mij! De website is zo gemakkelijk te gebruiken en de mogelijkheid om iemand te ontmoeten uit een andere cultuur die op mij betrekking heeft, is gewoon opwindend.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'bring_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'breng het gevoel van avontuur in mij naar boven! De website is zo gemakkelijk te gebruiken en de mogelijkheid om iemand te ontmoeten uit een andere cultuur die op mij betrekking heeft, is gewoon opwindend.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_profile_is_verified_by_photos', 'Dit profiel is geverifieerd door gebruikersfoto.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_account_wait_admin_photo_verification._please_try_again_later.', 'Uw account wordt beoordeeld. Wacht tot we uw foto hebben beoordeeld en probeer het later opnieuw.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_camera_is_off_or_disconnected__please_connect_your_camera_and_try_again.', 'Uw camera is uitgeschakeld of de verbinding is verbroken. Sluit uw camera aan en probeer het opnieuw.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'try_again', 'Probeer het opnieuw');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_have_previous_story_with_this_user', 'Je hebt een eerder verhaal met deze gebruiker');
            $lang_update_queries[] = PT_UpdateLangs($value, 'with', 'Met');
            $lang_update_queries[] = PT_UpdateLangs($value, 'create_story_with_you', 'creëerde een verhaal met jou.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'approved_your_story_', 'heeft je verhaal goedgekeurd!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'has_rejected_your_story.', 'heeft je verhaal afgewezen.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'approve_story', 'Goedkeuren verhaal');
            $lang_update_queries[] = PT_UpdateLangs($value, 'disapprove_story', 'Verwerp verhaal');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_have_story_with', 'Je hebt een verhaal met');
            $lang_update_queries[] = PT_UpdateLangs($value, 'on', 'op');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_approved_successfully.', 'Je verhaal is goedgekeurd.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_disapproved_successfully.', 'Je verhaal is afgewezen.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'social_accounts', 'Sociale accounts');
            $lang_update_queries[] = PT_UpdateLangs($value, 'publish', 'Publiceren');
            $lang_update_queries[] = PT_UpdateLangs($value, 'thank_you_for_your_story__we_have_sent_the_story_to__0___once_approved_your_story_will_be_published.', 'Bedankt voor je verhaal, we hebben het verhaal naar {0} gestuurd, zodra goedgekeurd zal je verhaal worden gepubliceerd.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_user_found_with_this_name', 'Geen gebruiker gevonden met deze naam');
        } else if ($value == 'french') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details.', 'Nous avons refusé votre virement bancaire, veuillez nous contacter pour plus de détails.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_transmitting_spam_messages._the_system_automatically_restricts_chat_for_you__so_you_can_chat_again_after', 'Vous transmettez des messages de spam. le système restreint automatiquement le chat pour que vous puissiez discuter à nouveau après');
            $lang_update_queries[] = PT_UpdateLangs($value, 'options', 'options');
            $lang_update_queries[] = PT_UpdateLangs($value, 'blog', 'Blog');
            $lang_update_queries[] = PT_UpdateLangs($value, 'success_stories', 'Réussites');
            $lang_update_queries[] = PT_UpdateLangs($value, 'add_new_story', 'Ajoutez votre histoire');
            $lang_update_queries[] = PT_UpdateLangs($value, 'create_story', 'Créer une histoire');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_more_stories_to_show.', 'Pas plus d\'histoires à montrer.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'add_new_success_stories', 'Ajoutez votre histoire réussie');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story__html_allowed_', 'Histoire (HTML autorisé)');
            $lang_update_queries[] = PT_UpdateLangs($value, 'quote', 'Citation');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_date', 'Quand cette histoire s\'est-elle passée?');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_select_user_first.', 'S\'il vous plaît choisir avec qui vous avez eu cette histoire.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_select_when_story_started.', 'Veuillez choisir quand l\'histoire a eu lieu.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_quote.', 'S\'il vous plaît entrer un devis.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_your_story.', 'S\'il vous plaît entrez votre histoire.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_add_successfully', 'Votre histoire a été ajoutée avec succès.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_add_successfully__please_wait_while_admin_approve_this_story_and_it_will_show_on_site.', 'Votre récit a été ajouté avec succès. Veuillez patienter pendant que nous examinons votre récit et l’approuvons.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story', 'Récit');
            $lang_update_queries[] = PT_UpdateLangs($value, '1309', 'La comédie');
            $lang_update_queries[] = PT_UpdateLangs($value, '1310', 'Voitures et véhicules');
            $lang_update_queries[] = PT_UpdateLangs($value, '1311', 'Économie et commerce');
            $lang_update_queries[] = PT_UpdateLangs($value, '1312', 'Éducation');
            $lang_update_queries[] = PT_UpdateLangs($value, '1313', 'Divertissement');
            $lang_update_queries[] = PT_UpdateLangs($value, '1314', 'Films & amp; Animation');
            $lang_update_queries[] = PT_UpdateLangs($value, '1315', 'Jeu');
            $lang_update_queries[] = PT_UpdateLangs($value, '1316', 'Histoire et faits');
            $lang_update_queries[] = PT_UpdateLangs($value, '1317', 'Style de vie');
            $lang_update_queries[] = PT_UpdateLangs($value, '1318', 'Naturel');
            $lang_update_queries[] = PT_UpdateLangs($value, '1319', 'Nouvelles et politique');
            $lang_update_queries[] = PT_UpdateLangs($value, '1320', 'Peuples et Nations');
            $lang_update_queries[] = PT_UpdateLangs($value, '1321', 'Animaux et Animaux');
            $lang_update_queries[] = PT_UpdateLangs($value, '1322', 'Lieux et régions');
            $lang_update_queries[] = PT_UpdateLangs($value, '1323', 'Science et technologie');
            $lang_update_queries[] = PT_UpdateLangs($value, '1324', 'sport');
            $lang_update_queries[] = PT_UpdateLangs($value, '1325', 'Voyage et événements');
            $lang_update_queries[] = PT_UpdateLangs($value, '1326', 'Autre');
            $lang_update_queries[] = PT_UpdateLangs($value, 'read_more', 'Lire la suite');
            $lang_update_queries[] = PT_UpdateLangs($value, 'categories', 'Les catégories');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_more_articles_to_show.', 'Pas plus d\'articles à montrer.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'article', 'Article');
            $lang_update_queries[] = PT_UpdateLangs($value, 'share_to', 'Partager à');
            $lang_update_queries[] = PT_UpdateLangs($value, 'hot_or_not', 'Chaud ou pas');
            $lang_update_queries[] = PT_UpdateLangs($value, 'image_verification', 'verification de l\'image');
            $lang_update_queries[] = PT_UpdateLangs($value, 'verify_your', 'Vérifier votre');
            $lang_update_queries[] = PT_UpdateLangs($value, 'account', 'Compte');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_will_be_required_to_take_a_selfie_holding_the_id_document_next_to_your_face__so_we_can_compare_your_photo_with_your_actual_look.this_is_just_an_additional_security_measure', 'Il vous sera demandé de prendre un selfie avec le document d\'identité à côté de votre visage afin que nous puissions comparer votre photo avec votre apparence réelle. Ceci est juste une mesure de sécurité supplémentaire.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'take_snapshot', 'Prendre un instantané');
            $lang_update_queries[] = PT_UpdateLangs($value, 'retake_snapshot', 'Reprendre un instantané');
            $lang_update_queries[] = PT_UpdateLangs($value, 'back', 'Retour');
            $lang_update_queries[] = PT_UpdateLangs($value, 'keyword', 'Mot-clé');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_articles_found', 'Aucun article trouvé');
            $lang_update_queries[] = PT_UpdateLangs($value, 'tags', 'Mots clés');
            $lang_update_queries[] = PT_UpdateLangs($value, 'free', 'Libre');
            $lang_update_queries[] = PT_UpdateLangs($value, 'brings_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'Fait ressortir le sens de l\'aventure en moi! Le site Web est si facile à utiliser et la possibilité de rencontrer quelqu\'un d\'une autre culture qui me concerne est tout simplement excitante.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'brings_out_the_feelings_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'Fait ressortir les sentiments d\'aventure en moi! Le site Web est si facile à utiliser et la possibilité de rencontrer quelqu\'un d\'une autre culture qui me concerne est tout simplement excitante.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'produce_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'Produire le sens de l\'aventure en moi! Le site Web est si facile à utiliser et la possibilité de rencontrer quelqu\'un d\'une autre culture qui me concerne est tout simplement excitante.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'bring_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'faire ressortir le sens de l\'aventure en moi! Le site Web est si facile à utiliser et la possibilité de rencontrer quelqu\'un d\'une autre culture qui me concerne est tout simplement excitante.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_profile_is_verified_by_photos', 'Ce profil est vérifié par la photo de l\'utilisateur.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_account_wait_admin_photo_verification._please_try_again_later.', 'Votre compte est en cours de révision. Veuillez patienter jusqu\'à ce que nous examinions votre photo, puis réessayez ultérieurement.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_camera_is_off_or_disconnected__please_connect_your_camera_and_try_again.', 'Votre appareil photo est éteint ou déconnecté. Veuillez connecter votre appareil photo et réessayer.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'try_again', 'Réessayer');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_have_previous_story_with_this_user', 'Vous avez une histoire précédente avec cet utilisateur');
            $lang_update_queries[] = PT_UpdateLangs($value, 'with', 'Avec');
            $lang_update_queries[] = PT_UpdateLangs($value, 'create_story_with_you', 'créé une histoire avec vous.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'approved_your_story_', 'approuvé votre histoire!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'has_rejected_your_story.', 'a rejeté votre histoire.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'approve_story', 'Approuver l\'histoire');
            $lang_update_queries[] = PT_UpdateLangs($value, 'disapprove_story', 'Désapprouver l\'histoire');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_have_story_with', 'Vous avez une histoire avec');
            $lang_update_queries[] = PT_UpdateLangs($value, 'on', 'sur');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_approved_successfully.', 'Votre histoire a été approuvée.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_disapproved_successfully.', 'Votre histoire a été refusée.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'social_accounts', 'Comptes sociaux');
            $lang_update_queries[] = PT_UpdateLangs($value, 'publish', 'Publier');
            $lang_update_queries[] = PT_UpdateLangs($value, 'thank_you_for_your_story__we_have_sent_the_story_to__0___once_approved_your_story_will_be_published.', 'Merci pour votre histoire. Nous l’avons envoyée à {0}. Une fois approuvée, votre histoire sera publiée.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_user_found_with_this_name', 'Aucun utilisateur trouvé avec ce nom');
        } else if ($value == 'german') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details.', 'Wir haben Ihre Überweisung abgelehnt. Bitte kontaktieren Sie uns für weitere Informationen.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_transmitting_spam_messages._the_system_automatically_restricts_chat_for_you__so_you_can_chat_again_after', 'Sie senden Spam-Nachrichten. Das System schränkt den Chat automatisch für Sie ein, sodass Sie danach erneut chatten können');
            $lang_update_queries[] = PT_UpdateLangs($value, 'options', 'Optionen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'blog', 'Blog');
            $lang_update_queries[] = PT_UpdateLangs($value, 'success_stories', 'Erfolgsgeschichten');
            $lang_update_queries[] = PT_UpdateLangs($value, 'add_new_story', 'Fügen Sie Ihre Geschichte hinzu');
            $lang_update_queries[] = PT_UpdateLangs($value, 'create_story', 'Erstelle eine Geschichte');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_more_stories_to_show.', 'Keine Geschichten mehr zu zeigen.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'add_new_success_stories', 'Fügen Sie Ihre Erfolgsgeschichte hinzu');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story__html_allowed_', 'Story (HTML erlaubt)');
            $lang_update_queries[] = PT_UpdateLangs($value, 'quote', 'Zitat');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_date', 'Wann ist diese Geschichte passiert?');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_select_user_first.', 'Bitte wählen Sie mit wem Sie diese Geschichte hatten.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_select_when_story_started.', 'Bitte wählen Sie aus, wann die Geschichte aufgetreten ist.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_quote.', 'Bitte geben Sie ein Angebot ein.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_your_story.', 'Bitte geben Sie Ihre Geschichte ein.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_add_successfully', 'Ihre Geschichte wurde erfolgreich hinzugefügt.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_add_successfully__please_wait_while_admin_approve_this_story_and_it_will_show_on_site.', 'Ihre Geschichte wurde erfolgreich hinzugefügt. Bitte warten Sie, während wir Ihre Geschichte überprüfen und genehmigen.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story', 'Geschichte');
            $lang_update_queries[] = PT_UpdateLangs($value, '1309', 'Komödie');
            $lang_update_queries[] = PT_UpdateLangs($value, '1310', 'Autos und Fahrzeuge');
            $lang_update_queries[] = PT_UpdateLangs($value, '1311', 'Wirtschaft und Handel');
            $lang_update_queries[] = PT_UpdateLangs($value, '1312', 'Bildung');
            $lang_update_queries[] = PT_UpdateLangs($value, '1313', 'Unterhaltung');
            $lang_update_queries[] = PT_UpdateLangs($value, '1314', 'Filme & amp; Animation');
            $lang_update_queries[] = PT_UpdateLangs($value, '1315', 'Gaming');
            $lang_update_queries[] = PT_UpdateLangs($value, '1316', 'Geschichte und Fakten');
            $lang_update_queries[] = PT_UpdateLangs($value, '1317', 'Lebensstil');
            $lang_update_queries[] = PT_UpdateLangs($value, '1318', 'Natürlich');
            $lang_update_queries[] = PT_UpdateLangs($value, '1319', 'Nachrichten und Politik');
            $lang_update_queries[] = PT_UpdateLangs($value, '1320', 'Menschen und Nationen');
            $lang_update_queries[] = PT_UpdateLangs($value, '1321', 'Haustiere und Tiere');
            $lang_update_queries[] = PT_UpdateLangs($value, '1322', 'Orte und Regionen');
            $lang_update_queries[] = PT_UpdateLangs($value, '1323', 'Wissenschaft und Technik');
            $lang_update_queries[] = PT_UpdateLangs($value, '1324', 'Sport');
            $lang_update_queries[] = PT_UpdateLangs($value, '1325', 'Reisen und Veranstaltungen');
            $lang_update_queries[] = PT_UpdateLangs($value, '1326', 'Andere');
            $lang_update_queries[] = PT_UpdateLangs($value, 'read_more', 'Weiterlesen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'categories', 'Kategorien');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_more_articles_to_show.', 'Keine weiteren Artikel zum Anzeigen.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'article', 'Artikel');
            $lang_update_queries[] = PT_UpdateLangs($value, 'share_to', 'Teilen mit');
            $lang_update_queries[] = PT_UpdateLangs($value, 'hot_or_not', 'Heiß oder nicht');
            $lang_update_queries[] = PT_UpdateLangs($value, 'image_verification', 'Bildverifizierung');
            $lang_update_queries[] = PT_UpdateLangs($value, 'verify_your', 'Überprüfen Sie Ihre');
            $lang_update_queries[] = PT_UpdateLangs($value, 'account', 'Konto');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_will_be_required_to_take_a_selfie_holding_the_id_document_next_to_your_face__so_we_can_compare_your_photo_with_your_actual_look.this_is_just_an_additional_security_measure', 'Sie müssen ein Selfie mit dem Personalausweis neben Ihrem Gesicht machen, damit wir Ihr Foto mit Ihrem tatsächlichen Aussehen vergleichen können. Dies ist nur eine zusätzliche Sicherheitsmaßnahme.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'take_snapshot', 'Schnappschuss machen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'retake_snapshot', 'Snapshot erneut aufnehmen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'back', 'Zurück');
            $lang_update_queries[] = PT_UpdateLangs($value, 'keyword', 'Stichwort');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_articles_found', 'Keine Artikel gefunden');
            $lang_update_queries[] = PT_UpdateLangs($value, 'tags', 'Stichworte');
            $lang_update_queries[] = PT_UpdateLangs($value, 'free', 'Kostenlos');
            $lang_update_queries[] = PT_UpdateLangs($value, 'brings_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'Bringt den Sinn für Abenteuer in mir hervor! Die Website ist so einfach zu bedienen und die Möglichkeit, jemanden aus einer anderen Kultur zu treffen, die mich betrifft, ist einfach aufregend.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'brings_out_the_feelings_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'Bringt die Abenteuergefühle in mir zum Vorschein! Die Website ist so einfach zu bedienen und die Möglichkeit, jemanden aus einer anderen Kultur zu treffen, die mich betrifft, ist einfach aufregend.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'produce_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'zeige den Sinn für Abenteuer in mir! Die Website ist so einfach zu bedienen und die Möglichkeit, jemanden aus einer anderen Kultur zu treffen, die mich betrifft, ist einfach aufregend.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'bring_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'Bring den Sinn für Abenteuer in mir zum Vorschein! Die Website ist so einfach zu bedienen und die Möglichkeit, jemanden aus einer anderen Kultur zu treffen, die mich betrifft, ist einfach aufregend.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_profile_is_verified_by_photos', 'Dieses Profil wird durch das Benutzerbild verifiziert.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_account_wait_admin_photo_verification._please_try_again_later.', 'Ihr Konto wird überprüft. Warten Sie, bis wir Ihr Bild überprüfen, und versuchen Sie es später erneut.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_camera_is_off_or_disconnected__please_connect_your_camera_and_try_again.', 'Ihre Kamera ist ausgeschaltet oder nicht angeschlossen. Bitte schließen Sie Ihre Kamera an und versuchen Sie es erneut.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'try_again', 'Versuchen Sie es nochmal');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_have_previous_story_with_this_user', 'Sie haben eine frühere Geschichte mit diesem Benutzer');
            $lang_update_queries[] = PT_UpdateLangs($value, 'with', 'Mit');
            $lang_update_queries[] = PT_UpdateLangs($value, 'create_story_with_you', 'hat mit dir eine Geschichte geschrieben.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'approved_your_story_', 'hat deine Geschichte genehmigt!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'has_rejected_your_story.', 'hat deine Geschichte abgelehnt.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'approve_story', 'Genehmige die Geschichte');
            $lang_update_queries[] = PT_UpdateLangs($value, 'disapprove_story', 'Geschichte ablehnen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_have_story_with', 'Du hast eine Geschichte mit');
            $lang_update_queries[] = PT_UpdateLangs($value, 'on', 'auf');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_approved_successfully.', 'Ihre Geschichte wurde genehmigt.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_disapproved_successfully.', 'Ihre Geschichte wurde abgelehnt.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'social_accounts', 'Soziale Konten');
            $lang_update_queries[] = PT_UpdateLangs($value, 'publish', 'Veröffentlichen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'thank_you_for_your_story__we_have_sent_the_story_to__0___once_approved_your_story_will_be_published.', 'Vielen Dank für Ihre Geschichte. Wir haben die Geschichte an {0} gesendet. Sobald die Genehmigung vorliegt, wird Ihre Geschichte veröffentlicht.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_user_found_with_this_name', 'Kein Benutzer mit diesem Namen gefunden');
        } else if ($value == 'italian') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details.', 'Abbiamo rifiutato il tuo bonifico bancario, ti preghiamo di contattarci per maggiori dettagli.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_transmitting_spam_messages._the_system_automatically_restricts_chat_for_you__so_you_can_chat_again_after', 'Stai trasmettendo messaggi spam. il sistema limita automaticamente la chat per te, quindi puoi chattare di nuovo dopo');
    $lang_update_queries[] = PT_UpdateLangs($value, 'options', 'opzioni');
    $lang_update_queries[] = PT_UpdateLangs($value, 'blog', 'blog');
    $lang_update_queries[] = PT_UpdateLangs($value, 'success_stories', 'Storie di successo');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_new_story', 'Aggiungi la tua storia');
    $lang_update_queries[] = PT_UpdateLangs($value, 'create_story', 'Crea una storia');
    $lang_update_queries[] = PT_UpdateLangs($value, 'no_more_stories_to_show.', 'Niente più storie da mostrare.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_new_success_stories', 'Aggiungi la tua storia di successo');
    $lang_update_queries[] = PT_UpdateLangs($value, 'story__html_allowed_', 'Storia (HTML consentito)');
    $lang_update_queries[] = PT_UpdateLangs($value, 'quote', 'Citazione');
    $lang_update_queries[] = PT_UpdateLangs($value, 'story_date', 'Quando è successa questa storia?');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_select_user_first.', 'Per favore, scegli con chi hai avuto questa storia.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_select_when_story_started.', 'Seleziona quando si è verificata la storia.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_quote.', 'Per favore, inserisci un preventivo');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_your_story.', 'Per favore, inserisci la tua storia.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'story_add_successfully', 'La tua storia è stata aggiunta con successo.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'story_add_successfully__please_wait_while_admin_approve_this_story_and_it_will_show_on_site.', 'La tua storia è stata aggiunta correttamente, attendi mentre la rivediamo e la approviamo.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'story', 'Storia');
    $lang_update_queries[] = PT_UpdateLangs($value, '1309', 'Commedia');
    $lang_update_queries[] = PT_UpdateLangs($value, '1310', 'Auto e veicoli');
    $lang_update_queries[] = PT_UpdateLangs($value, '1311', 'Economia e commercio');
    $lang_update_queries[] = PT_UpdateLangs($value, '1312', 'Formazione scolastica');
    $lang_update_queries[] = PT_UpdateLangs($value, '1313', 'Divertimento');
    $lang_update_queries[] = PT_UpdateLangs($value, '1314', 'Film e amp; Animazione');
    $lang_update_queries[] = PT_UpdateLangs($value, '1315', 'Gaming');
    $lang_update_queries[] = PT_UpdateLangs($value, '1316', 'Storia e fatti');
    $lang_update_queries[] = PT_UpdateLangs($value, '1317', 'Stile dal vivo');
    $lang_update_queries[] = PT_UpdateLangs($value, '1318', 'Naturale');
    $lang_update_queries[] = PT_UpdateLangs($value, '1319', 'Notizie e politica');
    $lang_update_queries[] = PT_UpdateLangs($value, '1320', 'Persone e nazioni');
    $lang_update_queries[] = PT_UpdateLangs($value, '1321', 'Animali domestici');
    $lang_update_queries[] = PT_UpdateLangs($value, '1322', 'Luoghi e regioni');
    $lang_update_queries[] = PT_UpdateLangs($value, '1323', 'Scienze e tecnologia');
    $lang_update_queries[] = PT_UpdateLangs($value, '1324', 'Sport');
    $lang_update_queries[] = PT_UpdateLangs($value, '1325', 'Viaggi ed Eventi');
    $lang_update_queries[] = PT_UpdateLangs($value, '1326', 'Altro');
    $lang_update_queries[] = PT_UpdateLangs($value, 'read_more', 'Leggi di più');
    $lang_update_queries[] = PT_UpdateLangs($value, 'categories', 'categorie');
    $lang_update_queries[] = PT_UpdateLangs($value, 'no_more_articles_to_show.', 'Non ci sono più articoli da mostrare.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'article', 'Articolo');
    $lang_update_queries[] = PT_UpdateLangs($value, 'share_to', 'Condividere a');
    $lang_update_queries[] = PT_UpdateLangs($value, 'hot_or_not', 'Caldo o no');
    $lang_update_queries[] = PT_UpdateLangs($value, 'image_verification', 'verifica immagine');
    $lang_update_queries[] = PT_UpdateLangs($value, 'verify_your', 'Verifica il tuo');
    $lang_update_queries[] = PT_UpdateLangs($value, 'account', 'account');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_will_be_required_to_take_a_selfie_holding_the_id_document_next_to_your_face__so_we_can_compare_your_photo_with_your_actual_look.this_is_just_an_additional_security_measure', 'Ti verrà richiesto di scattare un selfie con il documento di identità accanto al tuo viso, in modo da poter confrontare la tua foto con il tuo aspetto reale. Questa è solo un\'ulteriore misura di sicurezza.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'take_snapshot', 'Scatta un\'istantanea');
    $lang_update_queries[] = PT_UpdateLangs($value, 'retake_snapshot', 'Ripeti istantanea');
    $lang_update_queries[] = PT_UpdateLangs($value, 'back', 'Indietro');
    $lang_update_queries[] = PT_UpdateLangs($value, 'keyword', 'Parola chiave');
    $lang_update_queries[] = PT_UpdateLangs($value, 'no_articles_found', 'Nessun articolo trovato');
    $lang_update_queries[] = PT_UpdateLangs($value, 'tags', 'tag');
    $lang_update_queries[] = PT_UpdateLangs($value, 'free', 'Gratuito');
    $lang_update_queries[] = PT_UpdateLangs($value, 'brings_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'Esalta in me il senso dell\'avventura! Il sito web è così facile da usare e la possibilità di incontrare qualcuno di un\'altra cultura che mi riguarda è semplicemente elettrizzante.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'brings_out_the_feelings_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'Fa emergere i sentimenti di avventura in me! Il sito web è così facile da usare e la possibilità di incontrare qualcuno di un\'altra cultura che mi riguarda è semplicemente elettrizzante.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'produce_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'produce in me il senso dell\'avventura! Il sito web è così facile da usare e la possibilità di incontrare qualcuno di un\'altra cultura che mi riguarda è semplicemente elettrizzante.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'bring_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'fai emergere il senso dell\'avventura in me! Il sito web è così facile da usare e la possibilità di incontrare qualcuno di un\'altra cultura che mi riguarda è semplicemente elettrizzante.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'this_profile_is_verified_by_photos', 'Questo profilo è verificato dalla foto dell\'utente.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_account_wait_admin_photo_verification._please_try_again_later.', 'Il tuo account è in fase di revisione, attendi fino a quando non esamineremo la tua foto e riproveremo più tardi.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_camera_is_off_or_disconnected__please_connect_your_camera_and_try_again.', 'La fotocamera è spenta o disconnessa, collega la fotocamera e riprova.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'try_again', 'Riprova');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_have_previous_story_with_this_user', 'Hai una storia precedente con questo utente');
    $lang_update_queries[] = PT_UpdateLangs($value, 'with', 'Con');
    $lang_update_queries[] = PT_UpdateLangs($value, 'create_story_with_you', 'creato una storia con te.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'approved_your_story_', 'ha approvato la tua storia!');
    $lang_update_queries[] = PT_UpdateLangs($value, 'has_rejected_your_story.', 'ha rifiutato la tua storia.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'approve_story', 'Approvare la storia');
    $lang_update_queries[] = PT_UpdateLangs($value, 'disapprove_story', 'Non approvare la storia');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_have_story_with', 'Hai una storia con');
    $lang_update_queries[] = PT_UpdateLangs($value, 'on', 'sopra');
    $lang_update_queries[] = PT_UpdateLangs($value, 'story_approved_successfully.', 'La tua storia è stata approvata.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'story_disapproved_successfully.', 'La tua storia è stata rifiutata.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'social_accounts', 'Conti sociali');
    $lang_update_queries[] = PT_UpdateLangs($value, 'publish', 'Pubblicare');
    $lang_update_queries[] = PT_UpdateLangs($value, 'thank_you_for_your_story__we_have_sent_the_story_to__0___once_approved_your_story_will_be_published.', 'Grazie per la tua storia, abbiamo inviato la storia a {0}, una volta approvata la tua storia sarà pubblicata.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'no_user_found_with_this_name', 'Nessun utente trovato con questo nome');
        } else if ($value == 'portuguese') {
           $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details.', 'Rejeitamos sua transferência bancária. Entre em contato para mais detalhes.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_transmitting_spam_messages._the_system_automatically_restricts_chat_for_you__so_you_can_chat_again_after', 'Você está transmitindo mensagens de spam. o sistema restringe automaticamente o bate-papo para você, para que você possa conversar novamente depois');
    $lang_update_queries[] = PT_UpdateLangs($value, 'options', 'opções');
    $lang_update_queries[] = PT_UpdateLangs($value, 'blog', 'Blog');
    $lang_update_queries[] = PT_UpdateLangs($value, 'success_stories', 'Histórias de sucesso');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_new_story', 'Adicione sua história');
    $lang_update_queries[] = PT_UpdateLangs($value, 'create_story', 'Criar história');
    $lang_update_queries[] = PT_UpdateLangs($value, 'no_more_stories_to_show.', 'Não há mais histórias para mostrar.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_new_success_stories', 'Adicione sua história de sucesso');
    $lang_update_queries[] = PT_UpdateLangs($value, 'story__html_allowed_', 'História (HTML permitido)');
    $lang_update_queries[] = PT_UpdateLangs($value, 'quote', 'Citar');
    $lang_update_queries[] = PT_UpdateLangs($value, 'story_date', 'Quando esta história aconteceu?');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_select_user_first.', 'Por favor, escolha com quem você teve essa história.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_select_when_story_started.', 'Por favor, selecione quando a história ocorreu.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_quote.', 'Por favor insira uma cotação.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_your_story.', 'Por favor, insira sua história.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'story_add_successfully', 'Sua história foi adicionada com sucesso.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'story_add_successfully__please_wait_while_admin_approve_this_story_and_it_will_show_on_site.', 'Sua história foi adicionada com sucesso. Aguarde enquanto analisamos sua história e a aprovamos.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'story', 'História');
    $lang_update_queries[] = PT_UpdateLangs($value, '1309', 'Comédia');
    $lang_update_queries[] = PT_UpdateLangs($value, '1310', 'Carros e Veículos');
    $lang_update_queries[] = PT_UpdateLangs($value, '1311', 'Economia e Comércio');
    $lang_update_queries[] = PT_UpdateLangs($value, '1312', 'Educação');
    $lang_update_queries[] = PT_UpdateLangs($value, '1313', 'Entretenimento');
    $lang_update_queries[] = PT_UpdateLangs($value, '1314', 'Filmes e amp; Animação');
    $lang_update_queries[] = PT_UpdateLangs($value, '1315', 'Jogos');
    $lang_update_queries[] = PT_UpdateLangs($value, '1316', 'História e Fatos');
    $lang_update_queries[] = PT_UpdateLangs($value, '1317', 'Estilo Vivo');
    $lang_update_queries[] = PT_UpdateLangs($value, '1318', 'Natural');
    $lang_update_queries[] = PT_UpdateLangs($value, '1319', 'Notícias e Política');
    $lang_update_queries[] = PT_UpdateLangs($value, '1320', 'Pessoas e Nações');
    $lang_update_queries[] = PT_UpdateLangs($value, '1321', 'Animais de Estimação e Animais');
    $lang_update_queries[] = PT_UpdateLangs($value, '1322', 'Lugares e Regiões');
    $lang_update_queries[] = PT_UpdateLangs($value, '1323', 'Ciência e Tecnologia');
    $lang_update_queries[] = PT_UpdateLangs($value, '1324', 'Esporte');
    $lang_update_queries[] = PT_UpdateLangs($value, '1325', 'Viagens e Eventos');
    $lang_update_queries[] = PT_UpdateLangs($value, '1326', 'De outros');
    $lang_update_queries[] = PT_UpdateLangs($value, 'read_more', 'Consulte Mais informação');
    $lang_update_queries[] = PT_UpdateLangs($value, 'categories', 'Categorias');
    $lang_update_queries[] = PT_UpdateLangs($value, 'no_more_articles_to_show.', 'Não há mais artigos para mostrar.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'article', 'Artigo');
    $lang_update_queries[] = PT_UpdateLangs($value, 'share_to', 'Compartilhar com');
    $lang_update_queries[] = PT_UpdateLangs($value, 'hot_or_not', 'Quente ou não');
    $lang_update_queries[] = PT_UpdateLangs($value, 'image_verification', 'Verificação de Imagem');
    $lang_update_queries[] = PT_UpdateLangs($value, 'verify_your', 'Verificar o seu');
    $lang_update_queries[] = PT_UpdateLangs($value, 'account', 'conta');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_will_be_required_to_take_a_selfie_holding_the_id_document_next_to_your_face__so_we_can_compare_your_photo_with_your_actual_look.this_is_just_an_additional_security_measure', 'Você será solicitado a tirar uma selfie segurando o documento de identificação ao lado do seu rosto, para que possamos comparar sua foto com sua aparência real. Esta é apenas uma medida de segurança adicional.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'take_snapshot', 'Tire uma foto rápida');
    $lang_update_queries[] = PT_UpdateLangs($value, 'retake_snapshot', 'Recolher instantâneo');
    $lang_update_queries[] = PT_UpdateLangs($value, 'back', 'De volta');
    $lang_update_queries[] = PT_UpdateLangs($value, 'keyword', 'Palavra chave');
    $lang_update_queries[] = PT_UpdateLangs($value, 'no_articles_found', 'Nenhum artigo encontrado');
    $lang_update_queries[] = PT_UpdateLangs($value, 'tags', 'Tag');
    $lang_update_queries[] = PT_UpdateLangs($value, 'free', 'Livre');
    $lang_update_queries[] = PT_UpdateLangs($value, 'brings_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'Traz a sensação de aventura em mim! O site é tão fácil de usar e a possibilidade de conhecer alguém de outra cultura que se relaciona comigo é simplesmente emocionante.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'brings_out_the_feelings_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'Traz os sentimentos de aventura em mim! O site é tão fácil de usar e a possibilidade de conhecer alguém de outra cultura que se relaciona comigo é simplesmente emocionante.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'produce_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'produzir o senso de aventura em mim! O site é tão fácil de usar e a possibilidade de conhecer alguém de outra cultura que se relaciona comigo é simplesmente emocionante.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'bring_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'traga a sensação de aventura em mim! O site é tão fácil de usar e a possibilidade de conhecer alguém de outra cultura que se relaciona comigo é simplesmente emocionante.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'this_profile_is_verified_by_photos', 'Este perfil é verificado pela foto do usuário.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_account_wait_admin_photo_verification._please_try_again_later.', 'Sua conta está em revisão. Aguarde até revisarmos sua foto e tentarmos mais tarde.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_camera_is_off_or_disconnected__please_connect_your_camera_and_try_again.', 'Sua câmera está desligada ou desconectada. Conecte sua câmera e tente novamente.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'try_again', 'Tente novamente');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_have_previous_story_with_this_user', 'Você tem uma história anterior com este usuário');
    $lang_update_queries[] = PT_UpdateLangs($value, 'with', 'Com');
    $lang_update_queries[] = PT_UpdateLangs($value, 'create_story_with_you', 'criou uma história com você.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'approved_your_story_', 'aprovou sua história!');
    $lang_update_queries[] = PT_UpdateLangs($value, 'has_rejected_your_story.', 'rejeitou sua história.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'approve_story', 'Aprovar história');
    $lang_update_queries[] = PT_UpdateLangs($value, 'disapprove_story', 'Reprovar história');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_have_story_with', 'Você tem uma história com');
    $lang_update_queries[] = PT_UpdateLangs($value, 'on', 'em');
    $lang_update_queries[] = PT_UpdateLangs($value, 'story_approved_successfully.', 'Sua história foi aprovada.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'story_disapproved_successfully.', 'Sua história foi recusada.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'social_accounts', 'Contas sociais');
    $lang_update_queries[] = PT_UpdateLangs($value, 'publish', 'Publicar');
    $lang_update_queries[] = PT_UpdateLangs($value, 'thank_you_for_your_story__we_have_sent_the_story_to__0___once_approved_your_story_will_be_published.', 'Obrigado pela sua história, enviamos a história para {0}, uma vez aprovada, sua história será publicada.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'no_user_found_with_this_name', 'Nenhum usuário encontrado com este nome');
        } else if ($value == 'russian') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details.', 'Мы отклонили ваш банковский перевод, пожалуйста, свяжитесь с нами для получения более подробной информации.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_transmitting_spam_messages._the_system_automatically_restricts_chat_for_you__so_you_can_chat_again_after', 'Вы передаете спам-сообщения. система автоматически ограничивает чат, поэтому вы можете снова общаться после');
            $lang_update_queries[] = PT_UpdateLangs($value, 'options', 'опции');
            $lang_update_queries[] = PT_UpdateLangs($value, 'blog', 'Блог');
            $lang_update_queries[] = PT_UpdateLangs($value, 'success_stories', 'Истории успеха');
            $lang_update_queries[] = PT_UpdateLangs($value, 'add_new_story', 'Добавьте свою историю');
            $lang_update_queries[] = PT_UpdateLangs($value, 'create_story', 'Создать историю');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_more_stories_to_show.', 'Нет больше историй, чтобы показать.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'add_new_success_stories', 'Добавьте свою успешную историю');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story__html_allowed_', 'История (HTML допускается)');
            $lang_update_queries[] = PT_UpdateLangs($value, 'quote', 'котировка');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_date', 'Когда эта история произошла?');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_select_user_first.', 'Пожалуйста, выберите, с кем у вас была эта история.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_select_when_story_started.', 'Пожалуйста, выберите, когда история произошла.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_quote.', 'Пожалуйста, введите цитату.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_your_story.', 'Пожалуйста, введите вашу историю.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_add_successfully', 'Ваша история была успешно добавлена.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_add_successfully__please_wait_while_admin_approve_this_story_and_it_will_show_on_site.', 'Ваша история была успешно добавлена, пожалуйста, подождите, пока мы рассмотрим вашу историю и одобрим ее.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story', 'История');
            $lang_update_queries[] = PT_UpdateLangs($value, '1309', 'комедия');
            $lang_update_queries[] = PT_UpdateLangs($value, '1310', 'Автомобили и транспортные средства');
            $lang_update_queries[] = PT_UpdateLangs($value, '1311', 'Экономика и торговля');
            $lang_update_queries[] = PT_UpdateLangs($value, '1312', 'образование');
            $lang_update_queries[] = PT_UpdateLangs($value, '1313', 'Развлекательная программа');
            $lang_update_queries[] = PT_UpdateLangs($value, '1314', 'Кино & amp; Анимация');
            $lang_update_queries[] = PT_UpdateLangs($value, '1315', 'азартные игры');
            $lang_update_queries[] = PT_UpdateLangs($value, '1316', 'История и факты');
            $lang_update_queries[] = PT_UpdateLangs($value, '1317', 'Стиль жизни');
            $lang_update_queries[] = PT_UpdateLangs($value, '1318', 'натуральный');
            $lang_update_queries[] = PT_UpdateLangs($value, '1319', 'Новости и Политика');
            $lang_update_queries[] = PT_UpdateLangs($value, '1320', 'Люди и народы');
            $lang_update_queries[] = PT_UpdateLangs($value, '1321', 'Домашние животные и животные');
            $lang_update_queries[] = PT_UpdateLangs($value, '1322', 'Места и Регионы');
            $lang_update_queries[] = PT_UpdateLangs($value, '1323', 'Наука и технология');
            $lang_update_queries[] = PT_UpdateLangs($value, '1324', 'спорт');
            $lang_update_queries[] = PT_UpdateLangs($value, '1325', 'Путешествия и События');
            $lang_update_queries[] = PT_UpdateLangs($value, '1326', 'Другой');
            $lang_update_queries[] = PT_UpdateLangs($value, 'read_more', 'Прочитайте больше');
            $lang_update_queries[] = PT_UpdateLangs($value, 'categories', 'категории');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_more_articles_to_show.', 'Нет больше статей, чтобы показать.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'article', 'Статья');
            $lang_update_queries[] = PT_UpdateLangs($value, 'share_to', 'Поделиться с');
            $lang_update_queries[] = PT_UpdateLangs($value, 'hot_or_not', 'Горячий или нет');
            $lang_update_queries[] = PT_UpdateLangs($value, 'image_verification', 'Подтверждение изображения');
            $lang_update_queries[] = PT_UpdateLangs($value, 'verify_your', 'Проверьте свой');
            $lang_update_queries[] = PT_UpdateLangs($value, 'account', 'учетная запись');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_will_be_required_to_take_a_selfie_holding_the_id_document_next_to_your_face__so_we_can_compare_your_photo_with_your_actual_look.this_is_just_an_additional_security_measure', 'Вам нужно будет сделать селфи, держа удостоверение личности рядом с вашим лицом, чтобы мы могли сравнить вашу фотографию с вашим реальным видом. Это всего лишь дополнительная мера безопасности.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'take_snapshot', 'Моментальный снимок');
            $lang_update_queries[] = PT_UpdateLangs($value, 'retake_snapshot', 'Сделать снимок');
            $lang_update_queries[] = PT_UpdateLangs($value, 'back', 'назад');
            $lang_update_queries[] = PT_UpdateLangs($value, 'keyword', 'Ключевое слово');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_articles_found', 'Статьи не найдены');
            $lang_update_queries[] = PT_UpdateLangs($value, 'tags', 'Теги');
            $lang_update_queries[] = PT_UpdateLangs($value, 'free', 'Свободно');
            $lang_update_queries[] = PT_UpdateLangs($value, 'brings_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'Вызывает во мне чувство приключения! Веб-сайт очень прост в использовании, и возможность встретить кого-то другого человека, относящегося ко мне, просто волнует.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'brings_out_the_feelings_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'Вызывает во мне чувство приключения! Веб-сайт очень прост в использовании, и возможность встретить кого-то другого человека, относящегося ко мне, просто волнует.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'produce_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'создай во мне чувство приключения! Веб-сайт очень прост в использовании, и возможность встретить кого-то другого человека, относящегося ко мне, просто волнует.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'bring_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'выявить чувство приключения во мне! Веб-сайт очень прост в использовании, и возможность встретить кого-то другого человека, относящегося ко мне, просто волнует.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_profile_is_verified_by_photos', 'Этот профиль подтвержден фотографией пользователя.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_account_wait_admin_photo_verification._please_try_again_later.', 'Ваша учетная запись находится на рассмотрении. Подождите, пока мы просмотрим Вашу фотографию, и повторите попытку позже.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_camera_is_off_or_disconnected__please_connect_your_camera_and_try_again.', 'Ваша камера выключена или отключена. Пожалуйста, подключите камеру и попробуйте снова.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'try_again', 'Попробуйте снова');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_have_previous_story_with_this_user', 'У вас есть предыдущая история с этим пользователем');
            $lang_update_queries[] = PT_UpdateLangs($value, 'with', 'С');
            $lang_update_queries[] = PT_UpdateLangs($value, 'create_story_with_you', 'создал историю с вами.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'approved_your_story_', 'одобрил вашу историю!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'has_rejected_your_story.', 'отклонил вашу историю.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'approve_story', 'Одобрить историю');
            $lang_update_queries[] = PT_UpdateLangs($value, 'disapprove_story', 'Отклонить историю');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_have_story_with', 'У вас есть история с');
            $lang_update_queries[] = PT_UpdateLangs($value, 'on', 'на');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_approved_successfully.', 'Ваша история была одобрена.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_disapproved_successfully.', 'Ваша история была отклонена.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'social_accounts', 'Социальные аккаунты');
            $lang_update_queries[] = PT_UpdateLangs($value, 'publish', 'Публиковать');
            $lang_update_queries[] = PT_UpdateLangs($value, 'thank_you_for_your_story__we_have_sent_the_story_to__0___once_approved_your_story_will_be_published.', 'Спасибо за вашу историю. Мы отправили историю в {0}, после того как ваша история будет опубликована.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_user_found_with_this_name', 'Пользователь с таким именем не найден');
        } else if ($value == 'spanish') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details.', 'Hemos rechazado su transferencia bancaria, contáctenos para obtener más detalles.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_transmitting_spam_messages._the_system_automatically_restricts_chat_for_you__so_you_can_chat_again_after', 'Estás transmitiendo mensajes de spam. el sistema restringe automáticamente el chat para usted, por lo que puede volver a chatear después de');
            $lang_update_queries[] = PT_UpdateLangs($value, 'options', 'opciones');
            $lang_update_queries[] = PT_UpdateLangs($value, 'blog', 'Blog');
            $lang_update_queries[] = PT_UpdateLangs($value, 'success_stories', 'Historias de éxito');
            $lang_update_queries[] = PT_UpdateLangs($value, 'add_new_story', 'Agrega tu historia');
            $lang_update_queries[] = PT_UpdateLangs($value, 'create_story', 'Crear historia');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_more_stories_to_show.', 'No más historias para mostrar.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'add_new_success_stories', 'Agrega tu historia exitosa');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story__html_allowed_', 'Historia (HTML permitido)');
            $lang_update_queries[] = PT_UpdateLangs($value, 'quote', 'Citar');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_date', 'Cuando sucedió esta historia?');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_select_user_first.', 'Por favor, elija con quién tuvo esta historia.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_select_when_story_started.', 'Seleccione cuándo ocurrió la historia.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_quote.', 'Por favor ingrese una cotización.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_your_story.', 'Por favor ingrese su historia.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_add_successfully', 'Su historia ha sido agregada con éxito.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_add_successfully__please_wait_while_admin_approve_this_story_and_it_will_show_on_site.', 'Su historia se ha agregado correctamente, espere mientras revisamos su historia y la aprobamos.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story', 'Historia');
            $lang_update_queries[] = PT_UpdateLangs($value, '1309', 'Comedia');
            $lang_update_queries[] = PT_UpdateLangs($value, '1310', 'Autos y vehiculos');
            $lang_update_queries[] = PT_UpdateLangs($value, '1311', 'Economía y comercio');
            $lang_update_queries[] = PT_UpdateLangs($value, '1312', 'Educación');
            $lang_update_queries[] = PT_UpdateLangs($value, '1313', 'Entretenimiento');
            $lang_update_queries[] = PT_UpdateLangs($value, '1314', 'Películas y amplificadores; Animación');
            $lang_update_queries[] = PT_UpdateLangs($value, '1315', 'Juego de azar');
            $lang_update_queries[] = PT_UpdateLangs($value, '1316', 'Historia y hechos');
            $lang_update_queries[] = PT_UpdateLangs($value, '1317', 'Estilo de vida');
            $lang_update_queries[] = PT_UpdateLangs($value, '1318', 'Natural');
            $lang_update_queries[] = PT_UpdateLangs($value, '1319', 'Noticias y politica');
            $lang_update_queries[] = PT_UpdateLangs($value, '1320', 'Pueblos y naciones');
            $lang_update_queries[] = PT_UpdateLangs($value, '1321', 'Mascotas y animales');
            $lang_update_queries[] = PT_UpdateLangs($value, '1322', 'Lugares y Regiones');
            $lang_update_queries[] = PT_UpdateLangs($value, '1323', 'Ciencia y Tecnología');
            $lang_update_queries[] = PT_UpdateLangs($value, '1324', 'Deporte');
            $lang_update_queries[] = PT_UpdateLangs($value, '1325', 'Viajes y eventos');
            $lang_update_queries[] = PT_UpdateLangs($value, '1326', 'Otro');
            $lang_update_queries[] = PT_UpdateLangs($value, 'read_more', 'Lee mas');
            $lang_update_queries[] = PT_UpdateLangs($value, 'categories', 'Categorías');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_more_articles_to_show.', 'No hay más artículos para mostrar.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'article', 'Artículo');
            $lang_update_queries[] = PT_UpdateLangs($value, 'share_to', 'Compartir a');
            $lang_update_queries[] = PT_UpdateLangs($value, 'hot_or_not', 'Caliente o no');
            $lang_update_queries[] = PT_UpdateLangs($value, 'image_verification', 'verificación de imagen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'verify_your', 'Verifique su');
            $lang_update_queries[] = PT_UpdateLangs($value, 'account', 'cuenta');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_will_be_required_to_take_a_selfie_holding_the_id_document_next_to_your_face__so_we_can_compare_your_photo_with_your_actual_look.this_is_just_an_additional_security_measure', 'Se le pedirá que se tome una selfie con el documento de identificación junto a su cara, para que podamos comparar su foto con su aspecto real. Esta es solo una medida de seguridad adicional.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'take_snapshot', 'Tomar instantáneas');
            $lang_update_queries[] = PT_UpdateLangs($value, 'retake_snapshot', 'Repetir Instantánea');
            $lang_update_queries[] = PT_UpdateLangs($value, 'back', 'atrás');
            $lang_update_queries[] = PT_UpdateLangs($value, 'keyword', 'Palabra clave');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_articles_found', 'No se encontraron artículos.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'tags', 'Etiquetas');
            $lang_update_queries[] = PT_UpdateLangs($value, 'free', 'Gratis');
            $lang_update_queries[] = PT_UpdateLangs($value, 'brings_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', '¡Saca la sensación de aventura en mí! El sitio web es muy fácil de usar y la posibilidad de conocer a alguien de otra cultura que se relaciona conmigo es simplemente emocionante.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'brings_out_the_feelings_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', '¡Saca los sentimientos de aventura en mí! El sitio web es muy fácil de usar y la posibilidad de conocer a alguien de otra cultura que se relaciona conmigo es simplemente emocionante.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'produce_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', '¡Produce el sentido de aventura en mí! El sitio web es muy fácil de usar y la posibilidad de conocer a alguien de otra cultura que se relaciona conmigo es simplemente emocionante.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'bring_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', '¡Saca el sentido de la aventura en mí! El sitio web es muy fácil de usar y la posibilidad de conocer a alguien de otra cultura que se relaciona conmigo es simplemente emocionante.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_profile_is_verified_by_photos', 'Este perfil es verificado por la imagen del usuario.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_account_wait_admin_photo_verification._please_try_again_later.', 'Su cuenta está en proceso de revisión. Espere hasta que revisemos su imagen e intente nuevamente más tarde.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_camera_is_off_or_disconnected__please_connect_your_camera_and_try_again.', 'Su cámara está apagada o desconectada. Conecte su cámara e intente nuevamente.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'try_again', 'Inténtalo de nuevo');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_have_previous_story_with_this_user', 'Tienes una historia previa con este usuario');
            $lang_update_queries[] = PT_UpdateLangs($value, 'with', 'Con');
            $lang_update_queries[] = PT_UpdateLangs($value, 'create_story_with_you', 'Creé una historia contigo.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'approved_your_story_', 'aprobó tu historia!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'has_rejected_your_story.', 'ha rechazado tu historia');
            $lang_update_queries[] = PT_UpdateLangs($value, 'approve_story', 'Aprobar historia');
            $lang_update_queries[] = PT_UpdateLangs($value, 'disapprove_story', 'Desaprobar historia');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_have_story_with', 'Tienes una historia con');
            $lang_update_queries[] = PT_UpdateLangs($value, 'on', 'en');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_approved_successfully.', 'Tu historia ha sido aprobada.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_disapproved_successfully.', 'Tu historia ha sido rechazada.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'social_accounts', 'Cuentas sociales');
            $lang_update_queries[] = PT_UpdateLangs($value, 'publish', 'Publicar');
            $lang_update_queries[] = PT_UpdateLangs($value, 'thank_you_for_your_story__we_have_sent_the_story_to__0___once_approved_your_story_will_be_published.', 'Gracias por su historia, hemos enviado la historia a {0}, una vez aprobada, su historia será publicada.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_user_found_with_this_name', 'Ningún usuario encontrado con este nombre');
        } else if ($value == 'turkish') {
             $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details.', 'Banka havalenizi reddettik, daha fazla bilgi için lütfen bizimle iletişime geçin.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_transmitting_spam_messages._the_system_automatically_restricts_chat_for_you__so_you_can_chat_again_after', 'Spam mesajlarını iletiyorsun. sistem sohbeti sizin için otomatik olarak kısıtlar, böylece tekrar sohbete başlayabilirsiniz.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'options', 'seçenekleri');
    $lang_update_queries[] = PT_UpdateLangs($value, 'blog', 'Blog');
    $lang_update_queries[] = PT_UpdateLangs($value, 'success_stories', 'Başarı Öyküleri');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_new_story', 'Hikayeni ekle');
    $lang_update_queries[] = PT_UpdateLangs($value, 'create_story', 'Hikaye oluşturmak');
    $lang_update_queries[] = PT_UpdateLangs($value, 'no_more_stories_to_show.', 'Gösterilecek başka hikaye yok.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'add_new_success_stories', 'Başarılı hikayenizi ekleyin');
    $lang_update_queries[] = PT_UpdateLangs($value, 'story__html_allowed_', 'Öykü (HTML\'ye izin verilir)');
    $lang_update_queries[] = PT_UpdateLangs($value, 'quote', 'Alıntı');
    $lang_update_queries[] = PT_UpdateLangs($value, 'story_date', 'Bu hikaye ne zaman oldu?');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_select_user_first.', 'Lütfen bu hikayeyi kiminle aldığınızı seçin.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_select_when_story_started.', 'Lütfen hikayenin ne zaman gerçekleştiğini seçin.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_quote.', 'Lütfen bir fiyat teklifi girin.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_your_story.', 'Lütfen hikayeni yaz.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'story_add_successfully', 'Hikayen başarıyla eklendi.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'story_add_successfully__please_wait_while_admin_approve_this_story_and_it_will_show_on_site.', 'Hikayeniz başarıyla eklendi, lütfen hikayenizi gözden geçirip onaylarken bekleyin.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'story', 'Öykü');
    $lang_update_queries[] = PT_UpdateLangs($value, '1309', 'Komedi');
    $lang_update_queries[] = PT_UpdateLangs($value, '1310', 'Otomobiller ve Taşıtlar');
    $lang_update_queries[] = PT_UpdateLangs($value, '1311', 'Ekonomi ve Ticaret');
    $lang_update_queries[] = PT_UpdateLangs($value, '1312', 'Eğitim');
    $lang_update_queries[] = PT_UpdateLangs($value, '1313', 'Eğlence');
    $lang_update_queries[] = PT_UpdateLangs($value, '1314', 'Filmler ve amp; Animasyon');
    $lang_update_queries[] = PT_UpdateLangs($value, '1315', 'kumar');
    $lang_update_queries[] = PT_UpdateLangs($value, '1316', 'Tarihçe ve Gerçekler');
    $lang_update_queries[] = PT_UpdateLangs($value, '1317', 'Yaşam tarzı');
    $lang_update_queries[] = PT_UpdateLangs($value, '1318', 'Doğal');
    $lang_update_queries[] = PT_UpdateLangs($value, '1319', 'Haberler ve Politika');
    $lang_update_queries[] = PT_UpdateLangs($value, '1320', 'İnsanlar ve Milletler');
    $lang_update_queries[] = PT_UpdateLangs($value, '1321', 'Evcil Hayvanlar ve Hayvanlar');
    $lang_update_queries[] = PT_UpdateLangs($value, '1322', 'Yerler ve Bölgeler');
    $lang_update_queries[] = PT_UpdateLangs($value, '1323', 'Bilim ve Teknoloji');
    $lang_update_queries[] = PT_UpdateLangs($value, '1324', 'Spor');
    $lang_update_queries[] = PT_UpdateLangs($value, '1325', 'Seyahat ve Etkinlikler');
    $lang_update_queries[] = PT_UpdateLangs($value, '1326', 'Diğer');
    $lang_update_queries[] = PT_UpdateLangs($value, 'read_more', 'Daha fazla oku');
    $lang_update_queries[] = PT_UpdateLangs($value, 'categories', 'Kategoriler');
    $lang_update_queries[] = PT_UpdateLangs($value, 'no_more_articles_to_show.', 'Gösterilecek başka makale yok.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'article', 'makale');
    $lang_update_queries[] = PT_UpdateLangs($value, 'share_to', 'Paylaş');
    $lang_update_queries[] = PT_UpdateLangs($value, 'hot_or_not', 'Çekici mi değil mi');
    $lang_update_queries[] = PT_UpdateLangs($value, 'image_verification', 'Görüntü Doğrulama');
    $lang_update_queries[] = PT_UpdateLangs($value, 'verify_your', 'Doğrula');
    $lang_update_queries[] = PT_UpdateLangs($value, 'account', 'hesap');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_will_be_required_to_take_a_selfie_holding_the_id_document_next_to_your_face__so_we_can_compare_your_photo_with_your_actual_look.this_is_just_an_additional_security_measure', 'Kimlik belgesini yüzünüzün yanında tutan bir selfie almanız gerekecek, böylece fotoğrafınızı gerçek görünümünüzle karşılaştırabiliriz. Bu sadece ek bir güvenlik önlemidir.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'take_snapshot', 'Ekran alıntısı al');
    $lang_update_queries[] = PT_UpdateLangs($value, 'retake_snapshot', 'Anlık Fotoğrafı Yeniden Al');
    $lang_update_queries[] = PT_UpdateLangs($value, 'back', 'Geri');
    $lang_update_queries[] = PT_UpdateLangs($value, 'keyword', 'Kelimeler');
    $lang_update_queries[] = PT_UpdateLangs($value, 'no_articles_found', 'Makale bulunamadı');
    $lang_update_queries[] = PT_UpdateLangs($value, 'tags', 'Etiketler');
    $lang_update_queries[] = PT_UpdateLangs($value, 'free', 'Ücretsiz');
    $lang_update_queries[] = PT_UpdateLangs($value, 'brings_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'İçimdeki macera duygusunu ortaya çıkar! Web sitesi kullanımı çok kolay ve benimle ilgili başka bir kültürden biriyle tanışma olasılığı çok heyecan verici.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'brings_out_the_feelings_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'İçimdeki macera duygularını ortaya çıkar! Web sitesi kullanımı çok kolay ve benimle ilgili başka bir kültürden biriyle tanışma olasılığı çok heyecan verici.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'produce_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'İçimdeki macera duygusunu ortaya çıkarmak! Web sitesi kullanımı çok kolay ve benimle ilgili başka bir kültürden biriyle tanışma olasılığı çok heyecan verici.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'bring_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'İçimdeki macera duygusunu ortaya çıkarmak! Web sitesi kullanımı çok kolay ve benimle ilgili başka bir kültürden biriyle tanışma olasılığı çok heyecan verici.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'this_profile_is_verified_by_photos', 'Bu profil kullanıcı resmi tarafından doğrulandı.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_account_wait_admin_photo_verification._please_try_again_later.', 'Hesabınız inceleniyor. Lütfen resminizi gözden geçirene kadar bekleyin ve daha sonra tekrar deneyin.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'your_camera_is_off_or_disconnected__please_connect_your_camera_and_try_again.', 'Kameranız kapalı veya bağlantısı kesildi. Lütfen kameranızı bağlayın ve tekrar deneyin.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'try_again', 'Tekrar deneyin');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_have_previous_story_with_this_user', 'Bu kullanıcıyla daha önce bir hikayen var.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'with', 'İle');
    $lang_update_queries[] = PT_UpdateLangs($value, 'create_story_with_you', 'seninle bir hikaye yarattım.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'approved_your_story_', 'hikayeni onayladın!');
    $lang_update_queries[] = PT_UpdateLangs($value, 'has_rejected_your_story.', 'hikayeni reddetti.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'approve_story', 'Hikayeyi onayla');
    $lang_update_queries[] = PT_UpdateLangs($value, 'disapprove_story', 'Onaylamama hikayesi');
    $lang_update_queries[] = PT_UpdateLangs($value, 'you_have_story_with', 'Bir hikayen var');
    $lang_update_queries[] = PT_UpdateLangs($value, 'on', 'üzerinde');
    $lang_update_queries[] = PT_UpdateLangs($value, 'story_approved_successfully.', 'Hikayen onaylandı.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'story_disapproved_successfully.', 'Hikayen reddedildi.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'social_accounts', 'Sosyal hesaplar');
    $lang_update_queries[] = PT_UpdateLangs($value, 'publish', 'Yayınla');
    $lang_update_queries[] = PT_UpdateLangs($value, 'thank_you_for_your_story__we_have_sent_the_story_to__0___once_approved_your_story_will_be_published.', 'Hikayen için teşekkürler, hikayeni yayınlayacağını onayladıktan sonra hikayeyi {0} \'a gönderdik.');
    $lang_update_queries[] = PT_UpdateLangs($value, 'no_user_found_with_this_name', 'Bu ada sahip bir kullanıcı bulunamadı');
        } else if ($value == 'english') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details.', 'We have rejected your bank transfer, please contact us for more details.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_transmitting_spam_messages._the_system_automatically_restricts_chat_for_you__so_you_can_chat_again_after', 'You transmitting spam messages. the system automatically restricts chat for you, so you can chat again after');
            $lang_update_queries[] = PT_UpdateLangs($value, 'options', 'options');
            $lang_update_queries[] = PT_UpdateLangs($value, 'blog', 'Blog');
            $lang_update_queries[] = PT_UpdateLangs($value, 'success_stories', 'Success stories');
            $lang_update_queries[] = PT_UpdateLangs($value, 'add_new_story', 'Add your story');
            $lang_update_queries[] = PT_UpdateLangs($value, 'create_story', 'Create story');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_more_stories_to_show.', 'No more stories to show.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'add_new_success_stories', 'Add your successful story');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story__html_allowed_', 'Story (HTML allowed)');
            $lang_update_queries[] = PT_UpdateLangs($value, 'quote', 'Quote');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_date', 'When this story happened?');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_select_user_first.', 'Please choose with whom you had this story.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_select_when_story_started.', 'Please select when the story occurred.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_quote.', 'Please enter a quote.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_your_story.', 'Please enter your story.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_add_successfully', 'Your story has been added successfully.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_add_successfully__please_wait_while_admin_approve_this_story_and_it_will_show_on_site.', 'Your story has been added successfully, please wait while we review your story and approve it.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story', 'Story');
            $lang_update_queries[] = PT_UpdateLangs($value, '1309', 'Comedy');
            $lang_update_queries[] = PT_UpdateLangs($value, '1310', 'Cars and Vehicles');
            $lang_update_queries[] = PT_UpdateLangs($value, '1311', 'Economics and Trade');
            $lang_update_queries[] = PT_UpdateLangs($value, '1312', 'Education');
            $lang_update_queries[] = PT_UpdateLangs($value, '1313', 'Entertainment');
            $lang_update_queries[] = PT_UpdateLangs($value, '1314', 'Movies & Animation');
            $lang_update_queries[] = PT_UpdateLangs($value, '1315', 'Gaming');
            $lang_update_queries[] = PT_UpdateLangs($value, '1316', 'History and Facts');
            $lang_update_queries[] = PT_UpdateLangs($value, '1317', 'Live Style');
            $lang_update_queries[] = PT_UpdateLangs($value, '1318', 'Natural');
            $lang_update_queries[] = PT_UpdateLangs($value, '1319', 'News and Politics');
            $lang_update_queries[] = PT_UpdateLangs($value, '1320', 'People and Nations');
            $lang_update_queries[] = PT_UpdateLangs($value, '1321', 'Pets and Animals');
            $lang_update_queries[] = PT_UpdateLangs($value, '1322', 'Places and Regions');
            $lang_update_queries[] = PT_UpdateLangs($value, '1323', 'Science and Technology');
            $lang_update_queries[] = PT_UpdateLangs($value, '1324', 'Sport');
            $lang_update_queries[] = PT_UpdateLangs($value, '1325', 'Travel and Events');
            $lang_update_queries[] = PT_UpdateLangs($value, '1326', 'Other');
            $lang_update_queries[] = PT_UpdateLangs($value, 'read_more', 'Read more');
            $lang_update_queries[] = PT_UpdateLangs($value, 'categories', 'Categories');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_more_articles_to_show.', 'No more articles to show.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'article', 'Article');
            $lang_update_queries[] = PT_UpdateLangs($value, 'share_to', 'Share to');
            $lang_update_queries[] = PT_UpdateLangs($value, 'hot_or_not', 'Hot OR Not');
            $lang_update_queries[] = PT_UpdateLangs($value, 'image_verification', 'Image Verification');
            $lang_update_queries[] = PT_UpdateLangs($value, 'verify_your', 'Verify your');
            $lang_update_queries[] = PT_UpdateLangs($value, 'account', 'account');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_will_be_required_to_take_a_selfie_holding_the_id_document_next_to_your_face__so_we_can_compare_your_photo_with_your_actual_look.this_is_just_an_additional_security_measure', 'You will be required to take a selfie holding the ID document next to your face, so we can compare your photo with your actual look. This is just an additional security measure.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'take_snapshot', 'Take Snapshot');
            $lang_update_queries[] = PT_UpdateLangs($value, 'retake_snapshot', 'Retake Snapshot');
            $lang_update_queries[] = PT_UpdateLangs($value, 'back', 'Back');
            $lang_update_queries[] = PT_UpdateLangs($value, 'keyword', 'Keyword');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_articles_found', 'No articles found');
            $lang_update_queries[] = PT_UpdateLangs($value, 'tags', 'Tags');
            $lang_update_queries[] = PT_UpdateLangs($value, 'free', 'Free');
            $lang_update_queries[] = PT_UpdateLangs($value, 'brings_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'Brings out the sense of adventure in me! The website is so easy to use and the possibility of meeting someone from another culture that relates to me is simply thrilling.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'brings_out_the_feelings_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'Brings out the feelings of adventure in me! The website is so easy to use and the possibility of meeting someone from another culture that relates to me is simply thrilling.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'produce_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'produce out the sense of adventure in me! The website is so easy to use and the possibility of meeting someone from another culture that relates to me is simply thrilling.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'bring_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'bring out the sense of adventure in me! The website is so easy to use and the possibility of meeting someone from another culture that relates to me is simply thrilling.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_profile_is_verified_by_photos', 'This profile is verified by user picture. ');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_account_wait_admin_photo_verification._please_try_again_later.', 'Your account is under review, Please wait until we review your picture and try again later.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_camera_is_off_or_disconnected__please_connect_your_camera_and_try_again.', 'Your camera is off or disconnected, Please connect your camera and try again.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'try_again', 'Try again');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_have_previous_story_with_this_user', 'You have previous story with this user');
            $lang_update_queries[] = PT_UpdateLangs($value, 'with', 'With');
            $lang_update_queries[] = PT_UpdateLangs($value, 'create_story_with_you', 'created a story with you.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'approved_your_story_', 'approved your story!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'has_rejected_your_story.', 'has rejected your story.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'approve_story', 'Approve story');
            $lang_update_queries[] = PT_UpdateLangs($value, 'disapprove_story', 'Disapprove story');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_have_story_with', 'You have a story with');
            $lang_update_queries[] = PT_UpdateLangs($value, 'on', 'on');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_approved_successfully.', 'Your story has been approved.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_disapproved_successfully.', 'Your story has been declined.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'social_accounts', 'Social accounts');
            $lang_update_queries[] = PT_UpdateLangs($value, 'publish', 'Publish');
            $lang_update_queries[] = PT_UpdateLangs($value, 'thank_you_for_your_story__we_have_sent_the_story_to__0___once_approved_your_story_will_be_published.', 'Thank you for your story, we have sent the story to {0}, once approved your story will be published.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_user_found_with_this_name', 'No user found with this name');
        } else if ($value != 'english') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details.', 'We have rejected your bank transfer, please contact us for more details.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_transmitting_spam_messages._the_system_automatically_restricts_chat_for_you__so_you_can_chat_again_after', 'You transmitting spam messages. the system automatically restricts chat for you, so you can chat again after');
            $lang_update_queries[] = PT_UpdateLangs($value, 'options', 'options');
            $lang_update_queries[] = PT_UpdateLangs($value, 'blog', 'Blog');
            $lang_update_queries[] = PT_UpdateLangs($value, 'success_stories', 'Success stories');
            $lang_update_queries[] = PT_UpdateLangs($value, 'add_new_story', 'Add your story');
            $lang_update_queries[] = PT_UpdateLangs($value, 'create_story', 'Create story');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_more_stories_to_show.', 'No more stories to show.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'add_new_success_stories', 'Add your successful story');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story__html_allowed_', 'Story (HTML allowed)');
            $lang_update_queries[] = PT_UpdateLangs($value, 'quote', 'Quote');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_date', 'When this story happened?');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_select_user_first.', 'Please choose with whom you had this story.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_select_when_story_started.', 'Please select when the story occurred.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_quote.', 'Please enter a quote.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_enter_your_story.', 'Please enter your story.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_add_successfully', 'Your story has been added successfully.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_add_successfully__please_wait_while_admin_approve_this_story_and_it_will_show_on_site.', 'Your story has been added successfully, please wait while we review your story and approve it.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story', 'Story');
            $lang_update_queries[] = PT_UpdateLangs($value, '1309', 'Comedy');
            $lang_update_queries[] = PT_UpdateLangs($value, '1310', 'Cars and Vehicles');
            $lang_update_queries[] = PT_UpdateLangs($value, '1311', 'Economics and Trade');
            $lang_update_queries[] = PT_UpdateLangs($value, '1312', 'Education');
            $lang_update_queries[] = PT_UpdateLangs($value, '1313', 'Entertainment');
            $lang_update_queries[] = PT_UpdateLangs($value, '1314', 'Movies & Animation');
            $lang_update_queries[] = PT_UpdateLangs($value, '1315', 'Gaming');
            $lang_update_queries[] = PT_UpdateLangs($value, '1316', 'History and Facts');
            $lang_update_queries[] = PT_UpdateLangs($value, '1317', 'Live Style');
            $lang_update_queries[] = PT_UpdateLangs($value, '1318', 'Natural');
            $lang_update_queries[] = PT_UpdateLangs($value, '1319', 'News and Politics');
            $lang_update_queries[] = PT_UpdateLangs($value, '1320', 'People and Nations');
            $lang_update_queries[] = PT_UpdateLangs($value, '1321', 'Pets and Animals');
            $lang_update_queries[] = PT_UpdateLangs($value, '1322', 'Places and Regions');
            $lang_update_queries[] = PT_UpdateLangs($value, '1323', 'Science and Technology');
            $lang_update_queries[] = PT_UpdateLangs($value, '1324', 'Sport');
            $lang_update_queries[] = PT_UpdateLangs($value, '1325', 'Travel and Events');
            $lang_update_queries[] = PT_UpdateLangs($value, '1326', 'Other');
            $lang_update_queries[] = PT_UpdateLangs($value, 'read_more', 'Read more');
            $lang_update_queries[] = PT_UpdateLangs($value, 'categories', 'Categories');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_more_articles_to_show.', 'No more articles to show.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'article', 'Article');
            $lang_update_queries[] = PT_UpdateLangs($value, 'share_to', 'Share to');
            $lang_update_queries[] = PT_UpdateLangs($value, 'hot_or_not', 'Hot OR Not');
            $lang_update_queries[] = PT_UpdateLangs($value, 'image_verification', 'Image Verification');
            $lang_update_queries[] = PT_UpdateLangs($value, 'verify_your', 'Verify your');
            $lang_update_queries[] = PT_UpdateLangs($value, 'account', 'account');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_will_be_required_to_take_a_selfie_holding_the_id_document_next_to_your_face__so_we_can_compare_your_photo_with_your_actual_look.this_is_just_an_additional_security_measure', 'You will be required to take a selfie holding the ID document next to your face, so we can compare your photo with your actual look. This is just an additional security measure.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'take_snapshot', 'Take Snapshot');
            $lang_update_queries[] = PT_UpdateLangs($value, 'retake_snapshot', 'Retake Snapshot');
            $lang_update_queries[] = PT_UpdateLangs($value, 'back', 'Back');
            $lang_update_queries[] = PT_UpdateLangs($value, 'keyword', 'Keyword');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_articles_found', 'No articles found');
            $lang_update_queries[] = PT_UpdateLangs($value, 'tags', 'Tags');
            $lang_update_queries[] = PT_UpdateLangs($value, 'free', 'Free');
            $lang_update_queries[] = PT_UpdateLangs($value, 'brings_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'Brings out the sense of adventure in me! The website is so easy to use and the possibility of meeting someone from another culture that relates to me is simply thrilling.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'brings_out_the_feelings_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'Brings out the feelings of adventure in me! The website is so easy to use and the possibility of meeting someone from another culture that relates to me is simply thrilling.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'produce_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'produce out the sense of adventure in me! The website is so easy to use and the possibility of meeting someone from another culture that relates to me is simply thrilling.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'bring_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.', 'bring out the sense of adventure in me! The website is so easy to use and the possibility of meeting someone from another culture that relates to me is simply thrilling.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_profile_is_verified_by_photos', 'This profile is verified by user picture. ');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_account_wait_admin_photo_verification._please_try_again_later.', 'Your account is under review, Please wait until we review your picture and try again later.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_camera_is_off_or_disconnected__please_connect_your_camera_and_try_again.', 'Your camera is off or disconnected, Please connect your camera and try again.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'try_again', 'Try again');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_have_previous_story_with_this_user', 'You have previous story with this user');
            $lang_update_queries[] = PT_UpdateLangs($value, 'with', 'With');
            $lang_update_queries[] = PT_UpdateLangs($value, 'create_story_with_you', 'created a story with you.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'approved_your_story_', 'approved your story!');
            $lang_update_queries[] = PT_UpdateLangs($value, 'has_rejected_your_story.', 'has rejected your story.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'approve_story', 'Approve story');
            $lang_update_queries[] = PT_UpdateLangs($value, 'disapprove_story', 'Disapprove story');
            $lang_update_queries[] = PT_UpdateLangs($value, 'you_have_story_with', 'You have a story with');
            $lang_update_queries[] = PT_UpdateLangs($value, 'on', 'on');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_approved_successfully.', 'Your story has been approved.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'story_disapproved_successfully.', 'Your story has been declined.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'social_accounts', 'Social accounts');
            $lang_update_queries[] = PT_UpdateLangs($value, 'publish', 'Publish');
            $lang_update_queries[] = PT_UpdateLangs($value, 'thank_you_for_your_story__we_have_sent_the_story_to__0___once_approved_your_story_will_be_published.', 'Thank you for your story, we have sent the story to {0}, once approved your story will be published.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'no_user_found_with_this_name', 'No user found with this name');
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
                     <h2 class="light">Update to v1.3 </span></h2>
                     <div class="setting-well">
                        <h4>Changelog</h4>
                        <ul class="wo_update_changelog">
                            <li>[Added] the ability to make the site fully free for a spasefic gender.</li>
                            <li>[Added] the ability to make site completely free.</li>
                            <li>[Added] success stories system.</li>
                            <li>[Added] blog system (only admin can create blogs). </li>
                            <li>[Added] hot or not system. </li>
                            <li>[Added] image webcam verification system.</li>
                            <li>[Added] spam warning, user won't be able to send messages for 24 hours if they were marked as spam.</li>
                            <li>[Added] one signal support. </li>
                            <li>[Fixed] few reported bugs.</li>
                            <li>[Improved] SQL indexes + speed.</li>
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
    "UPDATE `options` SET `option_value`= '1.3' WHERE option_name = 'version';",
    "ALTER TABLE `langs` ADD `options` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL AFTER `ref`;",
    "ALTER TABLE `users` ADD `spam_warning` INT(11) UNSIGNED NULL DEFAULT '0' AFTER `last_email_sent`;",
    "CREATE TABLE IF NOT EXISTS `success_stories` (`id` int(11) unsigned NOT NULL,`user_id` int(11) unsigned NOT NULL,`story_user_id` int(11) unsigned NOT NULL DEFAULT '0',`quote` varchar(250) NOT NULL DEFAULT '',`description` text NOT NULL,`story_date` date NOT NULL,`status` int(11) unsigned NOT NULL DEFAULT '0',`created_at` int(11) NOT NULL DEFAULT '0') ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;",
    "ALTER TABLE `success_stories` ADD PRIMARY KEY (`id`);",
    "ALTER TABLE `success_stories` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;",
    "CREATE TABLE IF NOT EXISTS `blog` (`id` int(11) NOT NULL,`title` varchar(120) NOT NULL DEFAULT '',`content` text,`description` text,`posted` varchar(300) DEFAULT '0',`category` int(255) DEFAULT '0',`thumbnail` varchar(100) DEFAULT 'upload/photos/d-blog.jpg',`view` int(11) DEFAULT '0',`shared` int(11) DEFAULT '0',`tags` varchar(300) DEFAULT '',`created_at` int(11) NOT NULL DEFAULT '0') ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;",
    "ALTER TABLE `blog`ADD PRIMARY KEY (`id`),ADD KEY `title` (`title`),ADD KEY `category` (`category`);",
    "ALTER TABLE `blog` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;",
    "INSERT INTO `options` (`id`, `option_name`, `option_value`, `created_at`) VALUES (NULL, 'free_features', '0', CURRENT_TIMESTAMP);",
    "INSERT INTO `options` (`id`, `option_name`, `option_value`, `created_at`) VALUES (NULL, 'opposite_gender', '0', CURRENT_TIMESTAMP);",
    "INSERT INTO `options` (`id`, `option_name`, `option_value`, `created_at`) VALUES (NULL, 'image_verification', '0', CURRENT_TIMESTAMP);",
    "INSERT INTO `options` (`id`, `option_name`, `option_value`, `created_at`) VALUES (NULL, 'pending_verification', '0', CURRENT_TIMESTAMP);",
    "ALTER TABLE `users` ADD `hot_count` INT(11) UNSIGNED NULL DEFAULT '0' AFTER `last_email_sent`;",
    "ALTER TABLE `users` ADD `snapshot` VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' AFTER `last_email_sent`;",
    "ALTER TABLE `users` ADD `approved_at` INT(11) UNSIGNED NULL DEFAULT '0' AFTER `last_email_sent`;",
    "ALTER TABLE `users` CHANGE `status` `status` ENUM('0','1','2','3') CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '0';",
    "ALTER TABLE `users` ADD INDEX(`xmatches_created_at`);",
    "ALTER TABLE `users` ADD INDEX(`smscode`);",
    "ALTER TABLE `users` ADD INDEX(`password`);",
    "ALTER TABLE `users` ADD INDEX(`gender`);",
    "ALTER TABLE `users` ADD INDEX(`email_code`);",
    "ALTER TABLE `users` ADD INDEX(`type`);",
    "ALTER TABLE `users` ADD INDEX(`country`);",
    "ALTER TABLE `users` ADD INDEX(`balance`);",
    "ALTER TABLE `users` ADD INDEX(`active`);",
    "ALTER TABLE `users` ADD INDEX(`status`);",
    "ALTER TABLE `users` ADD INDEX(`admin`);",
    "ALTER TABLE `users` ADD INDEX(`location`);",
    "ALTER TABLE `users` ADD INDEX(`relationship`);",
    "ALTER TABLE `users` ADD INDEX(`work_status`);",
    "ALTER TABLE `users` ADD INDEX(`education`);",
    "ALTER TABLE `users` ADD INDEX(`body`);",
    "ALTER TABLE `users` ADD INDEX( `character`, `children`, `friends`, `pets`, `live_with`, `car`, `religion`);",
    "ALTER TABLE `users` ADD INDEX(`height`);",
    "ALTER TABLE `users` ADD INDEX(`show_me_to`);",
    "ALTER TABLE `bank_receipts` ADD INDEX(`user_id`);",
    "ALTER TABLE `blog` ADD INDEX(`tags`);",
    "ALTER TABLE `conversations` ADD INDEX(`from_delete`);",
    "ALTER TABLE `conversations` ADD INDEX(`to_delete`);",
    "ALTER TABLE `gifts` ADD INDEX(`name`);",
    "ALTER TABLE `mediafiles` ADD INDEX(`is_private`);",
    "ALTER TABLE `notifications` ADD INDEX(`seen`);",
    "ALTER TABLE `reports` ADD INDEX(`seen`);",
    "ALTER TABLE `success_stories` ADD INDEX(`user_id`);",
    "ALTER TABLE `success_stories` ADD INDEX(`story_user_id`);",
    "ALTER TABLE `success_stories` ADD INDEX(`status`);",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'we_have_rejected_your_bank_transfer__please_contact_us_for_more_details.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'you_transmitting_spam_messages._the_system_automatically_restricts_chat_for_you__so_you_can_chat_again_after');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'options');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'blog');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'success_stories');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'add_new_story');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'create_story');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'no_more_stories_to_show.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'add_new_success_stories');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'story__html_allowed_');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'quote');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'story_date');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'please_select_user_first.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'please_select_when_story_started.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'please_enter_quote.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'please_enter_your_story.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'story_add_successfully');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'story_add_successfully__please_wait_while_admin_approve_this_story_and_it_will_show_on_site.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'story');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, '1309');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, '1310');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, '1311');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, '1312');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, '1313');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, '1314');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, '1315');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, '1316');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, '1317');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, '1318');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, '1319');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, '1320');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, '1321');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, '1322');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, '1323');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, '1324');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, '1325');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, '1326');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'read_more');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'categories');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'no_more_articles_to_show.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'article');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'share_to');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'hot_or_not');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'image_verification');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'verify_your');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'account');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'you_will_be_required_to_take_a_selfie_holding_the_id_document_next_to_your_face__so_we_can_compare_your_photo_with_your_actual_look.this_is_just_an_additional_security_measure');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'take_snapshot');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'retake_snapshot');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'back');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'keyword');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'no_articles_found');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'tags');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'free');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'brings_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'brings_out_the_feelings_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'produce_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'bring_out_the_sense_of_adventure_in_me__the_website_is_so_easy_to_use_and_the_possibility_of_meeting_someone_from_another_culture_that_relates_to_me_is_simply_thrilling.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'this_profile_is_verified_by_photos');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'your_account_wait_admin_photo_verification._please_try_again_later.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'your_camera_is_off_or_disconnected__please_connect_your_camera_and_try_again.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'try_again');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'you_have_previous_story_with_this_user');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'with');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'create_story_with_you');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'approved_your_story_');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'has_rejected_your_story.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'approve_story');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'disapprove_story');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'you_have_story_with');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'on');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'story_approved_successfully.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'story_disapproved_successfully.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'social_accounts');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'publish');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'thank_you_for_your_story__we_have_sent_the_story_to__0___once_approved_your_story_will_be_published.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'no_user_found_with_this_name');",
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