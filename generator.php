<?php

error_reporting(0);

if (isset($_POST["amount"]) && $_POST["amount"] !== null) {
    $amount = $_POST["amount"];
    $cash = $_POST["cash"];
    $worker = $_POST["worker"];

    $filename = "database/" . substr(md5(rand(1000000, 9999999)), 4, 12);
    $content = [
        "amount" => $amount,
		"cash" => $cash,
		"worker" => $worker,
		"status" => 'wait'
    ];

    $content = json_encode($content);

    $file = fopen($filename, "w");
    fwrite($file, $content);
    fclose($file);

    $response = [
        "status" => "success",
        "url" => urlencode("https://" . str_replace('//', '/', urldecode($_SERVER["SERVER_NAME"] . dirname($_SERVER["REQUEST_URI"])) . "/?id=" . explode("/", $filename)[1]))
    ];

    echo json_encode($response);
    exit;
}

?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&amp;subset=cyrillic">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/common.css">
        <link rel="shortcut icon" type="image/png" href="img/fav-icon.png">

        <script type="text/javascript" src="/assets/js/jquery.js"></script>
        <script type="text/javascript" src="/assets/js/bootstrap.js"></script>
        <script type="text/javascript" src="/assets/js/commons.js"></script>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

        <title>Генератор ссылок</title>
    </head>

    <body>
        <div class="block-info">
            <h3 class="heading-info">
                Генератор ссылок
            </h3>

            <p class="description-info">
                Сгенерируйте платежную ссылку с помощью этой формы.
            </p>
        </div>

        <div class="divider" style="margin-bottom: 25px;"></div>

        <div class="block-form">
            <form class="form-payment" action="payment.php" method="post">
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
							<label>Сумма платежа</label>
							<input type="text" class="form-control" id="input-amount" placeholder="1000 ₽">
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
							<label>Работник</label>
							<input type="text" class="form-control" id="input-worker" placeholder="@worker">
                        </div>
                    </div>
                </div>

                <div class="col-xs-12">
                        <div class="form-group">
							<label>Действие</label>
							<select class="form-control" id="input-cash">
								<option value="pay">Оплата</option>
								<option value="ref">Возврат</option>
							</select>
                        </div>
                    </div>
            </form>

            <div class="block-form-info" style="text-align: left;">
                <h4 class="heading-total">Ссылка для оплаты: <b></b></h4>
                <p class="heading-secure label-url" style="margin-top: 10px; user-select: auto;">
                    ссылка не сгенерирована
                </p>
            </div>
        </div>

        <div class="divider"></div>

        <div class="block-footer">
            <div class="row">
                <div class="col-xs-12">
                    <a class="button-generate">
                        Сгенерировать
                    </a>
                </div>
            </div>
        </div>
    </body>

    <script type="text/javascript">

        $(".button-generate").click(function() {
            var amount = $("#input-amount");
            var cash = $("#input-cash");
            var worker = $("#input-worker");

            if (amount.val().length < 3) {
                amount.addClass("input-error");

                setTimeout(function() {
                    amount.removeClass("input-error");
                }, 2500);

                return false;
            }

            $.post("generator.php", { "amount": amount.val(), "cash": cash.val(), "worker": worker.val() })
            .done(function(response) {
                response = JSON.parse(response);

                if (response["status"] == "success") {
                    $(".label-url").text(decodeURIComponent(response["url"]));
                }
            });
        });
    </script>
</html>
