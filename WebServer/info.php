<? //phpinfo(); ?>
<?

	ini_set('display_errors', 'on');
	error_reporting(E_ALL);

	function sendmail($mail_to, $sender_mail, $sender_name, $subject, $message) {
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=windows-1251\r\n";
		$headers .= "From: $sender_name <$sender_mail>\r\n";
		return mail($mail_to, $subject, $message, $headers);
	}

	if ( empty($_POST['data']) )
	{
		echo 'error';
		exit();
	}
	$data = $_POST['data'];

	$subject = '����������� �� ����������� ��������� �����������';
	$message = "����������������� ��������� ��������<br /><br />" . $data;
	$recipient = 'vsport@mail.ru';
	
//	$b_email_sent = sendmail( $recipient, 'robot@vsportv.ru', '���� �������� �� ���������� �������� � ������', $subject,  $message );
	$b_email_sent = sendmail( $recipient, 'vsport@mail.ru', '���� �������� �� ���������� �������� � ������', $subject,  $message );


	if ( $b_email_sent ) echo 'ok';
	else echo 'error';

?>