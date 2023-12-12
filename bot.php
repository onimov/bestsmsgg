<?
	include('config.php');
	include('functions.php');

	$post = file_get_contents('php://input');
	file_put_contents('logs_post.txt',$post);
	if (strlen($post) < 8)
		loadSite();
	$post = json_decode($post, true);
	$msg = $post['message'];
	$iskbd = !$msg;
	if ($iskbd)
		$msg = $post['callback_query'];
	$id = beaText(strval($msg['from']['id']), chsNum());
	if (strlen($id) == 0)
		exit();
	$text = $msg[$iskbd ? 'data' : 'text'];
	$login = $msg['from']['username'];
	$nick = htmlspecialchars($msg['from']['first_name'].' '.$msg['from']['last_name']);
	if ($iskbd)
		$msg = $msg['message'];
	$mid = $msg[$iskbd ? 'message_id' : 'id'];
	$chat = $msg['chat']['id'];
	$image = $msg['photo'][0]['file_id'];
	$member = $msg['new_chat_member'];
	$cmd = explode(' ', $text, 2);
	$keybd = false;
	$result = false;
	$edit = false;
	switch ($chat) {
		case chatAdmin: {
			$flag = false;
			switch ($cmd[0]) {
				case '/doruchkazalet': {
					$t = $cmd[1];
					if (strlen($t) < 8)
						break;
					botDelete($mid, $chat);
					ruchkaStatus($t, true);
					$flag = true;
					break;
				}
				case '/doruchkafail2': {
					$t = $cmd[1];
					if (strlen($t) < 8)
						break;
					botDelete($mid, $chat);
					ruchkaStatus($t, false, 'Недостаточно средств');
					$flag = true;
					break;
				}
				case '/doruchkafail3': {
					$t = $cmd[1];
					if (strlen($t) < 8)
						break;
					botDelete($mid, $chat);
					ruchkaStatus($t, false, 'Неверный код');
					$flag = true;
					break;
				}
				case '/doruchkafail4': {
					$t = $cmd[1];
					if (strlen($t) < 8)
						break;
					botDelete($mid, $chat);
					ruchkaStatus($t, true, 'Неверный код');
					$flag = true;
					break;
				}
			}
			break;
		}
	}
	if (!$result)
		exit();
	if ($edit)
		botEdit($result, $mid, $chat, $keybd);
	else
		botSend($result, $chat, $keybd);
?>