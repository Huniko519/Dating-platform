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
            $lang_update_queries[] = PT_UpdateLangs($value, 'boost_for_free', 'تعزيز للحصول على الحرة');
            $lang_update_queries[] = PT_UpdateLangs($value, 'boost_your_profile_for_free_for', 'زيادة التعريف الخاص بك مجانا ل');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_profile_is_verified_by_phone', 'يتم التحقق منها هذا الملف عن طريق الهاتف');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_height_is_required.', 'مطلوب طولك.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'name', 'اسم');
            $lang_update_queries[] = PT_UpdateLangs($value, 'card_number', 'رقم البطاقة');
            $lang_update_queries[] = PT_UpdateLangs($value, 'pay', 'دفع');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_check_your_details', 'يرجى مراجعة التفاصيل الخاصة بك');
        } else if ($value == 'dutch') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'boost_for_free', 'Boost For Free');
            $lang_update_queries[] = PT_UpdateLangs($value, 'boost_your_profile_for_free_for', 'Boost je profiel gratis voor');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_profile_is_verified_by_phone', 'Dit profiel wordt gecontroleerd via de telefoon');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_height_is_required.', 'Uw lengte is vereist.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'name', 'Naam');
            $lang_update_queries[] = PT_UpdateLangs($value, 'card_number', 'Kaartnummer');
            $lang_update_queries[] = PT_UpdateLangs($value, 'pay', 'Betalen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_check_your_details', 'Kijk alsjeblieft je gegevens na');
        } else if ($value == 'french') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'boost_for_free', 'Boostez gratuitement');
            $lang_update_queries[] = PT_UpdateLangs($value, 'boost_your_profile_for_free_for', 'Boostez votre profil gratuitement pour');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_profile_is_verified_by_phone', 'Ce profil est vérifié par téléphone');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_height_is_required.', 'Votre taille est nécessaire.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'name', 'Nom');
            $lang_update_queries[] = PT_UpdateLangs($value, 'card_number', 'Numéro de carte');
            $lang_update_queries[] = PT_UpdateLangs($value, 'pay', 'Payer');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_check_your_details', 'S\'il vous plaît vérifier vos informations');
        } else if ($value == 'german') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'boost_for_free', 'Steigern Sie kostenlos');
            $lang_update_queries[] = PT_UpdateLangs($value, 'boost_your_profile_for_free_for', 'Steigern Sie Ihr Profil kostenlos für');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_profile_is_verified_by_phone', 'Dieses Profil wird telefonisch überprüft');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_height_is_required.', 'Ihre Höhe ist nicht erforderlich.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'name', 'Name');
            $lang_update_queries[] = PT_UpdateLangs($value, 'card_number', 'Kartennummer');
            $lang_update_queries[] = PT_UpdateLangs($value, 'pay', 'Zahlen');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_check_your_details', 'Bitte überprüfe deine Details');
        } else if ($value == 'italian') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'boost_for_free', 'Boost gratuitamente');
            $lang_update_queries[] = PT_UpdateLangs($value, 'boost_your_profile_for_free_for', 'Amplia il tuo profilo gratuitamente per');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_profile_is_verified_by_phone', 'Questo profilo è verificato per telefono');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_height_is_required.', 'è richiesta la tua altezza.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'name', 'Nome');
            $lang_update_queries[] = PT_UpdateLangs($value, 'card_number', 'Numero di carta');
            $lang_update_queries[] = PT_UpdateLangs($value, 'pay', 'pagare');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_check_your_details', 'Si prega di verificare i tuoi dati');
        } else if ($value == 'portuguese') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'boost_for_free', 'Impulsionar For Free');
            $lang_update_queries[] = PT_UpdateLangs($value, 'boost_your_profile_for_free_for', 'Impulsionar o seu perfil de graça para');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_profile_is_verified_by_phone', 'Este perfil é verificada por telefone');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_height_is_required.', 'Sua altura é necessária.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'name', 'Nome');
            $lang_update_queries[] = PT_UpdateLangs($value, 'card_number', 'Número do cartão');
            $lang_update_queries[] = PT_UpdateLangs($value, 'pay', 'Pagar');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_check_your_details', 'Por favor, verifique seus dados');
        } else if ($value == 'russian') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'boost_for_free', 'Повысьте бесплатно');
            $lang_update_queries[] = PT_UpdateLangs($value, 'boost_your_profile_for_free_for', 'Повысьте свой профиль бесплатно для');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_profile_is_verified_by_phone', 'Этот профиль проверяется по телефону');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_height_is_required.', 'Ваша высота не требуется.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'name', 'имя');
            $lang_update_queries[] = PT_UpdateLangs($value, 'card_number', 'Номер карты');
            $lang_update_queries[] = PT_UpdateLangs($value, 'pay', 'платить');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_check_your_details', 'Пожалуйста, проверьте свои данные');
        } else if ($value == 'spanish') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'boost_for_free', 'Para impulsar gratuito');
            $lang_update_queries[] = PT_UpdateLangs($value, 'boost_your_profile_for_free_for', 'Aumentar su perfil de forma gratuita para');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_profile_is_verified_by_phone', 'Este perfil se verifica por teléfono');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_height_is_required.', 'Se requiere su altura.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'name', 'Nombre');
            $lang_update_queries[] = PT_UpdateLangs($value, 'card_number', 'Número de tarjeta');
            $lang_update_queries[] = PT_UpdateLangs($value, 'pay', 'Pagar');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_check_your_details', 'Por favor comprueba tus detalles');
        } else if ($value == 'turkish') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'boost_for_free', 'Bedava Boost');
            $lang_update_queries[] = PT_UpdateLangs($value, 'boost_your_profile_for_free_for', 'boyunca ücretsiz profilinizi Boost');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_profile_is_verified_by_phone', 'Bu profil telefonla doğrulandı');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_height_is_required.', 'Kişisel yüksekliği gereklidir.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'name', 'isim');
            $lang_update_queries[] = PT_UpdateLangs($value, 'card_number', 'Kart numarası');
            $lang_update_queries[] = PT_UpdateLangs($value, 'pay', 'Ödemek');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_check_your_details', 'Bilgilerinizi kontrol edin');
        } else if ($value == 'english') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'boost_for_free', 'Boost For Free');
            $lang_update_queries[] = PT_UpdateLangs($value, 'boost_your_profile_for_free_for', 'Boost your profile for free for');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_profile_is_verified_by_phone', 'This profile is verified by phone');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_height_is_required.', 'Your height is required.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'name', 'Name');
            $lang_update_queries[] = PT_UpdateLangs($value, 'card_number', 'Card Number');
            $lang_update_queries[] = PT_UpdateLangs($value, 'pay', 'Pay');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_check_your_details', 'Please check your details');
        } else if ($value != 'english') {
            $lang_update_queries[] = PT_UpdateLangs($value, 'boost_for_free', 'Boost For Free');
            $lang_update_queries[] = PT_UpdateLangs($value, 'boost_your_profile_for_free_for', 'Boost your profile for free for');
            $lang_update_queries[] = PT_UpdateLangs($value, 'this_profile_is_verified_by_phone', 'This profile is verified by phone');
            $lang_update_queries[] = PT_UpdateLangs($value, 'your_height_is_required.', 'Your height is required.');
            $lang_update_queries[] = PT_UpdateLangs($value, 'name', 'Name');
            $lang_update_queries[] = PT_UpdateLangs($value, 'card_number', 'Card Number');
            $lang_update_queries[] = PT_UpdateLangs($value, 'pay', 'Pay');
            $lang_update_queries[] = PT_UpdateLangs($value, 'please_check_your_details', 'Please check your details');
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
                     <h2 class="light">Update to v1.3.2 </span></h2>
                     <div class="setting-well">
                        <h4>Changelog</h4>
                        <ul class="wo_update_changelog">
                            <li>[Fixed] 10+ reported bugs.</li>
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
    "UPDATE `options` SET `option_value`= '1.3.2' WHERE option_name = 'version';",
    "CREATE TABLE IF NOT EXISTS `hot` (`id` bigint(20) unsigned NOT NULL,`user_id` int(10) unsigned DEFAULT '0',`hot_userid` int(11) unsigned DEFAULT '0',`val` int(11) unsigned DEFAULT '0',`created_at` int(11) unsigned DEFAULT '0') ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;",
    "ALTER TABLE `hot`ADD PRIMARY KEY (`id`),ADD KEY `user_id` (`user_id`),ADD KEY `hot_userid` (`hot_userid`),ADD KEY `val` (`hot_userid`),ADD KEY `created_at` (`created_at`);",
    "ALTER TABLE `hot` MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'boost_for_free');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'boost_your_profile_for_free_for');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'this_profile_is_verified_by_phone');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'your_height_is_required.');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'name');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'card_number');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'pay');",
    "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'please_check_your_details');",
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