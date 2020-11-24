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
            $lang_update_queries[] = PT_UpdateLangs($value, 'video_and_audio_calls_to_all_users', 'إنشاء عدد غير محدود من مكالمات الفيديو والصوت.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'latest_users', 'أحدث المستخدمين');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wanna_get_more__get_new_stickers_for', 'هل تريد الحصول على المزيد؟ الحصول على ملصقات جديدة ل');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_image_now_is_private.', 'هذه الصورة خاصة.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'emails', 'رسائل البريد الإلكتروني');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_views_your_profile', 'البريد الإلكتروني لي عندما شخص ما ينظر ملف التعريف الخاص بي.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_you_get_a_new_message', 'البريد الإلكتروني لي عندما أتلقى رسالة جديدة.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_like_your_profile', 'البريد الإلكتروني لي عندما شخص مثلي.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_purchase_notifications', 'أرسل لي إخطارات الشراء الخاصة بي.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_special_offers___promotions', 'أرسل لي العروض الخاصة & amp؛ الترقيات.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_feature_announcements', 'أرسل لي إعلانات المستقبل.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_like_my_profile', 'البريد الإلكتروني لي عندما شخص مثل ملف التعريف الخاص بي.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_gift', 'أرسل لي رسالة إلكترونية عندما أحصل على هدية جديدة.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_match', 'أرسل لي رسالة إلكترونية عندما أحصل على تطابق جديد.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_chat_request', 'أرسل لي رسالة إلكترونية عندما أحصل على طلب دردشة جديد.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'why__0__is_best', 'لماذا {0} هو الأفضل');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_account_is_safe_on__0_._we_never_share_your_data_with_third_party.', 'حسابك آمن في {0}. نحن لا نشارك بياناتك مع طرف ثالث.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'connect_with_your_perfect_soulmate_here__on__0_.', 'تواصل مع صديقك المثالي هنا ، على {0}.');
        } else if ($value == 'dutch') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'video_and_audio_calls_to_all_users', 'Creëer onbeperkt video- en audio-oproepen.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'latest_users', 'Laatste gebruikers');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wanna_get_more__get_new_stickers_for', 'Wil je meer krijgen? krijg nieuwe stickers voor');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_image_now_is_private.', 'Deze foto is privé.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'emails', 'emails');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_views_your_profile', 'E-mail mij als iemand mijn profiel bekijkt.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_you_get_a_new_message', 'E-mail mij als ik een nieuw bericht ontvang.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_like_your_profile', 'E-mail mij als iemand zoals ik.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_purchase_notifications', 'Stuur me een e-mail met mijn aankoopmeldingen.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_special_offers___promotions', 'E-mail mij speciale aanbiedingen & promoties.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_feature_announcements', 'E-mail mij toekomstige aankondigingen.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_like_my_profile', 'E-mail mij als iemand mijn profiel leuk vindt.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_gift', 'E-mail mij als ik een nieuw cadeau ontvang.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_match', 'E-mail mij als ik een nieuwe match krijg.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_chat_request', 'E-mail mij als ik een nieuw chatverzoek ontvang.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'why__0__is_best', 'Waarom {0} het beste is');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_account_is_safe_on__0_._we_never_share_your_data_with_third_party.', 'Je account is veilig op {0}. Wij delen uw gegevens nooit met derden.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'connect_with_your_perfect_soulmate_here__on__0_.', 'Maak hier verbinding met je perfecte Soulmate, op {0}.');
        } else if ($value == 'french') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'video_and_audio_calls_to_all_users', 'Créez des appels vidéo et audio illimités.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'latest_users', 'Derniers utilisateurs');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wanna_get_more__get_new_stickers_for', 'Tu veux en avoir plus? obtenir de nouveaux autocollants pour');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_image_now_is_private.', 'Cette photo est privée.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'emails', 'Courriels');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_views_your_profile', 'Envoyez-moi un email quand quelqu\'un regarde mon profil.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_you_get_a_new_message', 'Email moi quand je reçois un nouveau message.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_like_your_profile', 'Email moi quand quelqu\'un comme moi.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_purchase_notifications', 'Envoyez-moi mes notifications d\'achat.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_special_offers___promotions', 'Envoyez-moi des offres spéciales & promotions.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_feature_announcements', 'Envoyez-moi des annonces futures.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_like_my_profile', 'Envoyez-moi un courriel lorsque quelqu\'un aime mon profil.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_gift', 'Email moi quand je reçois un nouveau cadeau.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_match', 'Ecrivez-moi quand je reçois un nouveau match.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_chat_request', 'Envoyez-moi un email quand je reçois une nouvelle demande de chat.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'why__0__is_best', 'Pourquoi {0} est le meilleur');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_account_is_safe_on__0_._we_never_share_your_data_with_third_party.', 'Votre compte est en sécurité le {0}. Nous ne partageons jamais vos données avec des tiers.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'connect_with_your_perfect_soulmate_here__on__0_.', 'Connectez-vous avec votre âme sœur parfaite ici, sur {0}.');
        } else if ($value == 'german') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'video_and_audio_calls_to_all_users', 'Erstellen Sie unbegrenzte Video- und Audioanrufe.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'latest_users', 'Neueste Benutzer');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wanna_get_more__get_new_stickers_for', 'Willst du mehr bekommen? Bekommen Sie neue Aufkleber für');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_image_now_is_private.', 'Dieses Foto ist privat.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'emails', 'E-Mails');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_views_your_profile', 'Mailen Sie mir, wenn jemand mein Profil anzeigt.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_you_get_a_new_message', 'Senden Sie mir eine E-Mail, wenn ich eine neue Nachricht erhalte.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_like_your_profile', 'Mailen Sie mir, wenn mir jemand gefällt.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_purchase_notifications', 'Senden Sie mir meine Kaufbenachrichtigungen per E-Mail.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_special_offers___promotions', 'Mailen Sie mir spezielle Angebote & Promotionen.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_feature_announcements', 'Mailen Sie mir zukünftige Ankündigungen.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_like_my_profile', 'Mailen Sie mir, wenn jemand mein Profil mag.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_gift', 'Senden Sie mir eine E-Mail, wenn ich ein neues Geschenk bekomme.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_match', 'Senden Sie mir eine E-Mail, wenn ich ein neues Spiel bekomme.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_chat_request', 'Senden Sie mir eine E-Mail, wenn ich eine neue Chat-Anfrage bekomme.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'why__0__is_best', 'Warum ist {0} am besten?');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_account_is_safe_on__0_._we_never_share_your_data_with_third_party.', 'Ihr Konto ist am {0} sicher. Wir geben Ihre Daten niemals an Dritte weiter.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'connect_with_your_perfect_soulmate_here__on__0_.', 'Verbinde dich hier mit deinem perfekten Soulmate auf {0}.');
        } else if ($value == 'italian') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'video_and_audio_calls_to_all_users', 'Crea chiamate video e audio illimitate.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'latest_users', 'Ultimi utenti');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wanna_get_more__get_new_stickers_for', 'Vuoi ottenere di più? ottenere nuovi adesivi per');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_image_now_is_private.', 'Questa foto è privata.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'emails', 'Messaggi di posta elettronica');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_views_your_profile', 'Inviami un\'email quando qualcuno visualizza il mio profilo.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_you_get_a_new_message', 'Mandami una email quando ricevo un nuovo messaggio.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_like_your_profile', 'Mandami una mail quando qualcuno come me.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_purchase_notifications', 'Inviami le mie notifiche di acquisto via email.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_special_offers___promotions', 'Inviami le offerte speciali & promozioni.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_feature_announcements', 'Inviami annunci futuri.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_like_my_profile', 'Inviami un\'email quando qualcuno come il mio profilo.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_gift', 'Mandami una email quando ricevo un nuovo regalo.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_match', 'Mandami una email quando avrò una nuova partita.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_chat_request', 'Mandami una email quando avrò una nuova richiesta di chat.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'why__0__is_best', 'Perché {0} è il migliore');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_account_is_safe_on__0_._we_never_share_your_data_with_third_party.', 'Il tuo account è sicuro su {0}. Non condividiamo mai i tuoi dati con terze parti.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'connect_with_your_perfect_soulmate_here__on__0_.', 'Connettiti con la tua anima gemella perfetta qui, su {0}.');
        } else if ($value == 'portuguese') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'video_and_audio_calls_to_all_users', 'Crie chamadas ilimitadas de vídeo e áudio.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'latest_users', 'Usuários mais recentes');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wanna_get_more__get_new_stickers_for', 'Queres mais? obter novos adesivos para');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_image_now_is_private.', 'Esta foto é privada.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'emails', 'Emails');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_views_your_profile', 'Envie-me um email quando alguém visualizar meu perfil.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_you_get_a_new_message', 'Envie-me um email quando receber uma nova mensagem.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_like_your_profile', 'Envie-me um email quando alguém como eu.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_purchase_notifications', 'Envie-me minhas notificações de compra.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_special_offers___promotions', 'Envie-me um email com ofertas especiais & promoções.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_feature_announcements', 'Envie-me futuros anúncios.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_like_my_profile', 'Envie-me um email quando alguém gostar do meu perfil.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_gift', 'Envie-me um email quando eu receber um novo presente.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_match', 'Envie-me um email quando obtiver um novo jogo.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_chat_request', 'Envie-me um email quando obtiver um novo pedido de chat.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'why__0__is_best', 'Por que {0} é o melhor');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_account_is_safe_on__0_._we_never_share_your_data_with_third_party.', 'Sua conta está segura em {0}. Nós nunca compartilhamos seus dados com terceiros.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'connect_with_your_perfect_soulmate_here__on__0_.', 'Conecte-se com seu Soulmate perfeito aqui, em {0}.');
        } else if ($value == 'russian') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'video_and_audio_calls_to_all_users', 'Создавайте неограниченные видео и аудио звонки.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'latest_users', 'Последние пользователи');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wanna_get_more__get_new_stickers_for', 'Хотите получить больше? получить новые наклейки для');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_image_now_is_private.', 'Это частное фото');
            $lang_update_queries[] = PT_UpdateLangs($value, 'emails', 'Сообщения электронной почты');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_views_your_profile', 'Напишите мне, когда кто-то просматривает мой профиль.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_you_get_a_new_message', 'Напишите мне, когда я получу новое сообщение.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_like_your_profile', 'Напишите мне, когда кто-то, как я.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_purchase_notifications', 'Отправьте мне по электронной почте мои уведомления о покупке.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_special_offers___promotions', 'Напишите мне специальные предложения & промо акции.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_feature_announcements', 'Пишите мне будущие объявления.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_like_my_profile', 'Напишите мне, когда кому-то понравится мой профиль.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_gift', 'Напишите мне, когда я получу новый подарок.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_match', 'Напишите мне, когда я получу новый матч.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_chat_request', 'Напишите мне, когда я получу новый запрос чата.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'why__0__is_best', 'Почему {0} лучше');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_account_is_safe_on__0_._we_never_share_your_data_with_third_party.', 'Ваш аккаунт в безопасности {0}. Мы никогда не передаем ваши данные третьим лицам.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'connect_with_your_perfect_soulmate_here__on__0_.', 'Свяжись со своей идеальной родственной душой здесь, {0}.');
        } else if ($value == 'spanish') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'video_and_audio_calls_to_all_users', 'Crea llamadas ilimitadas de video y audio.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'latest_users', 'Últimos usuarios');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wanna_get_more__get_new_stickers_for', '¿Quieres conseguir más? conseguir nuevas pegatinas para');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_image_now_is_private.', 'Esta foto es privada.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'emails', 'Correos electronicos');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_views_your_profile', 'Envíame un correo electrónico cuando alguien vea mi perfil.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_you_get_a_new_message', 'Envíeme un correo electrónico cuando reciba un nuevo mensaje.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_like_your_profile', 'Envíame un correo electrónico cuando alguien como yo.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_purchase_notifications', 'Envíeme un correo electrónico mis notificaciones de compra.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_special_offers___promotions', 'Envíeme un correo electrónico ofertas especiales & promociones');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_feature_announcements', 'Envíeme un correo electrónico futuros anuncios.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_like_my_profile', 'Envíame un correo electrónico cuando alguien como mi perfil.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_gift', 'Envíame un correo electrónico cuando reciba un nuevo regalo.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_match', 'Envíame un correo electrónico cuando reciba un nuevo partido.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_chat_request', 'Envíeme un correo electrónico cuando reciba una nueva solicitud de chat.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'why__0__is_best', 'Porque {0} es mejor');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_account_is_safe_on__0_._we_never_share_your_data_with_third_party.', 'Su cuenta está segura en {0}. Nunca compartimos sus datos con terceros.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'connect_with_your_perfect_soulmate_here__on__0_.', 'Conecta con tu alma gemela perfecta aquí, en {0}.');
        } else if ($value == 'turkish') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'video_and_audio_calls_to_all_users', 'Sınırsız video ve sesli arama oluşturun.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'latest_users', 'Son kullanıcılar');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wanna_get_more__get_new_stickers_for', 'Daha fazla almak ister misin? için yeni çıkartmalar al');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_image_now_is_private.', 'Bu fotoğraf özel.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'emails', 'E-postalar');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_views_your_profile', 'Birisi profilimi görüntülediğinde bana e-posta gönder.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_you_get_a_new_message', 'Yeni bir mesaj aldığımda bana e-posta gönder.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_like_your_profile', 'Birisi benim gibi olduğunda bana e-posta gönder.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_purchase_notifications', 'Satın alma bildirimlerimi e-postayla gönder.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_special_offers___promotions', 'Bana özel teklifleri e-postayla gönder & promosyonlar.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_feature_announcements', 'Bana gelecekteki duyuruları e-postayla gönder.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_like_my_profile', 'Birisi profilimden hoşlandığında bana e-posta gönder.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_gift', 'Yeni bir hediye aldığımda bana e-posta gönder.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_match', 'Yeni bir eşleşme olduğunda bana e-posta gönder.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_chat_request', 'Yeni bir sohbet isteği aldığımda bana e-posta gönder.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'why__0__is_best', '{0} Neden En İyi?');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_account_is_safe_on__0_._we_never_share_your_data_with_third_party.', 'Hesabınız {0} tarihinde güvende. Verilerinizi asla üçüncü taraflarla paylaşmayız.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'connect_with_your_perfect_soulmate_here__on__0_.', '{0} adresindeki mükemmel Soulmate\'inize buradan bağlanın.');
        } else if ($value == 'english') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'video_and_audio_calls_to_all_users', 'Create unlimited video and audio calls.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'latest_users', 'Latest Users');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wanna_get_more__get_new_stickers_for', 'Wanna get more? get new stickers for');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_image_now_is_private.', 'This photo is private.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'emails', 'Emails');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_views_your_profile', 'Email me when someone views my profile.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_you_get_a_new_message', 'Email me when I get a new message.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_like_your_profile', 'Email me when someone like me.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_purchase_notifications', 'Email me my purchase notifications.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_special_offers___promotions', 'Email me special offers & promotions.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_feature_announcements', 'Email me future announcements.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_like_my_profile', 'Email me when someone like my profile.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_gift', 'Email me when I get a new gift.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_match', 'Email me when I get a new match.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_chat_request', 'Email me when I get a new chat request.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'why__0__is_best', 'Why {0} is Best');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_account_is_safe_on__0_._we_never_share_your_data_with_third_party.', 'Your account is safe on {0}. We never share your data with third party.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'connect_with_your_perfect_soulmate_here__on__0_.', 'Connect with your perfect Soulmate here, on {0}.');
        } else if ($value != 'english') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'video_and_audio_calls_to_all_users', 'Create unlimited video and audio calls.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'latest_users', 'Latest Users');
            $lang_update_queries[] = PT_UpdateLangs($value, 'wanna_get_more__get_new_stickers_for', 'Wanna get more? get new stickers for');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_image_now_is_private.', 'This photo is private.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'emails', 'Emails');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_views_your_profile', 'Email me when someone views my profile.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_you_get_a_new_message', 'Email me when I get a new message.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_like_your_profile', 'Email me when someone like me.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_purchase_notifications', 'Email me my purchase notifications.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_special_offers___promotions', 'Email me special offers & promotions.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_feature_announcements', 'Email me future announcements.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_someone_like_my_profile', 'Email me when someone like my profile.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_gift', 'Email me when I get a new gift.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_match', 'Email me when I get a new match.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'email_me_when_i_get_new_chat_request', 'Email me when I get a new chat request.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'why__0__is_best', 'Why {0} is Best');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_account_is_safe_on__0_._we_never_share_your_data_with_third_party.', 'Your account is safe on {0}. We never share your data with third party.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'connect_with_your_perfect_soulmate_here__on__0_.', 'Connect with your perfect Soulmate here, on {0}.');
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
                     <h2 class="light">Update to v1.2 </span></h2>
                     <div class="setting-well">
                        <h4>Changelog</h4>
                        <ul class="wo_update_changelog">
                            <li>[Added] email notifications.</li>
                            <li>[Added] the ability to see suggestion of users living in the same country or state if there is no user from same country then other should appear. </li>
                            <li>[Added] the ability to enable disable pro system. </li>
                            <li>[Added] the ability to randomize pro users section at the moment it shows latest.</li>
                            <li>[Added] the ability to create custom pages. </li>
                            <li>[Added] the ability for users to upload private pictures, and once the user matched with them, they can see their pictures. </li>
                            <li>[Added] video / audio calls API.</li>
                            <li>[Added] the ability to create custom fields for search and profile. </li>
                            <li>[Added] the ability to turn off or on audio/video calls for pro users only or all users.</li>
                            <li>[Improved] search system.</li>
                            <li>[Improved] MySQL queries.</li>
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
    "INSERT INTO `options` (`id`, `option_name`, `option_value`, `created_at`) VALUES (NULL, 'version', '1.2', CURRENT_TIMESTAMP);",
    "INSERT INTO `options` (`id`, `option_name`, `option_value`, `created_at`) VALUES (NULL, 'google_tag_code', NULL, CURRENT_TIMESTAMP);",
    "INSERT INTO `options` (`id`, `option_name`, `option_value`, `created_at`) VALUES (NULL, 'avcall_pro', '1', CURRENT_TIMESTAMP);",
    "INSERT INTO `options` (`id`, `option_name`, `option_value`, `created_at`) VALUES (NULL, 'pro_system', '1', CURRENT_TIMESTAMP);",
    "INSERT INTO `options` (`id`, `option_name`, `option_value`, `created_at`) VALUES (NULL, 'img_blur_amount', '50', CURRENT_TIMESTAMP);",
    "INSERT INTO `options` (`id`, `option_name`, `option_value`, `created_at`) VALUES (NULL, 'emailNotification', '1', CURRENT_TIMESTAMP);",
    "ALTER TABLE `stickers` ADD `is_pro` INT(11) UNSIGNED NULL DEFAULT '0' AFTER `file`;",
    "ALTER TABLE `mediafiles` ADD `is_private` INT(11) UNSIGNED NULL DEFAULT '0' AFTER `file`, ADD `private_file` VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' AFTER `is_private`;",
    "ALTER TABLE `users` ADD `email_on_get_gift` INT(11) UNSIGNED NULL DEFAULT '0' AFTER `show_me_to`, ADD `email_on_got_new_match` INT(11) UNSIGNED NULL DEFAULT '0' AFTER `email_on_get_gift`, ADD `email_on_chat_request` INT(11) UNSIGNED NULL DEFAULT '0' AFTER `email_on_got_new_match`;",
    "ALTER TABLE `users` ADD `last_email_sent` INT(32) UNSIGNED NULL DEFAULT '0' AFTER `email_on_chat_request`;",
    "CREATE TABLE `custom_pages` (`id` int(11) NOT NULL,`page_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',`page_title` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',`page_content` text COLLATE utf8_unicode_ci,`page_type` int(11) NOT NULL DEFAULT '0') ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
    "ALTER TABLE `custom_pages` ADD PRIMARY KEY (`id`);",
    "ALTER TABLE `custom_pages` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;",
    "CREATE TABLE IF NOT EXISTS `emails` (`id` int(11) NOT NULL,`user_id` int(11) NOT NULL DEFAULT '0',`email_to` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',`subject` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',`message` text COLLATE utf8_unicode_ci) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
    "ALTER TABLE `emails` ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`);",
    "ALTER TABLE `emails` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;",
    "CREATE TABLE IF NOT EXISTS `profilefields` (`id` int(11) NOT NULL,`name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',`description` text COLLATE utf8_unicode_ci,`type` text COLLATE utf8_unicode_ci,`length` int(11) NOT NULL DEFAULT '0',`placement` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'profile',`registration_page` int(11) NOT NULL DEFAULT '0',`profile_page` int(11) NOT NULL DEFAULT '0',`select_type` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'none',`active` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0') ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
    "CREATE TABLE IF NOT EXISTS `userfields` (`id` int(11) NOT NULL,`user_id` int(11) NOT NULL DEFAULT '0') ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
    "ALTER TABLE `profilefields`ADD PRIMARY KEY (`id`),ADD KEY `registration_page` (`registration_page`),ADD KEY `active` (`active`),ADD KEY `profile_page` (`profile_page`);",
    "ALTER TABLE `userfields` ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`);",
    "ALTER TABLE `profilefields` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;",
    "ALTER TABLE `userfields` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'video_and_audio_calls_to_all_users');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'latest_users');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'wanna_get_more__get_new_stickers_for');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'this_image_now_is_private.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'emails');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'email_me_when_someone_views_your_profile');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'email_me_when_you_get_a_new_message');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'email_me_when_someone_like_your_profile');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'email_me_purchase_notifications');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'email_me_special_offers___promotions');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'email_me_feature_announcements');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'email_me_when_someone_like_my_profile');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'email_me_when_i_get_new_gift');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'email_me_when_i_get_new_match');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'email_me_when_i_get_new_chat_request');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'why__0__is_best');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'your_account_is_safe_on__0_._we_never_share_your_data_with_third_party.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'connect_with_your_perfect_soulmate_here__on__0_.');",
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