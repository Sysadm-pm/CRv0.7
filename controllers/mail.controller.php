<?php

class MailController extends Controller
{
    protected $mailer;
    public $mail;
    public $subject;
    public $body;
    public $altBody;
    protected $us;

    public function __construct($data = array() )
    {
        parent::__construct($data);
        $this->mailer = new \PHPMailer\PHPMailer\PHPMailer();
        //$this->model = new Mail();
        //namespace \PHPMailer\PHPMailer\PHPMailer::class;
        $this->us = new User();
    }
    public function send()
    {
        $this->mailer->send();
        //$this->mailer->ClearAllRecipients();

        $this->mailer->ClearAddresses();  // each AddAddress add to list
        $this->mailer->ClearCCs();
        //$this->mailer->ClearBCCs();

    }

    public function make()
    {
        $this->mailer->CharSet      = 'utf-8';

        $this->mailer->SMTPDebug    = 0;
        $this->mailer->isSMTP();
        $this->mailer->Host         = 'smtp.meta.ua';
        $this->mailer->SMTPAuth     = true;
        $this->mailer->Username     = 'dig.crm@meta.ua';
        $this->mailer->Password     = '8311018090';
        $this->mailer->SMTPSecure   = 'ssl';
        $this->mailer->Port         = 465;

        $this->mailer->setFrom('dig.crm@meta.ua', 'ROBOTiK');

       foreach ($this->mail as $key=>$userAs)
        {
            $uif=$this->us->getByUserId($userAs);
           //print_r($userAs.' << userAs <br>');
           //print_r($key.' << KEY <br>');


            if ($key == '0')
            {

                //print_r($uif['email'].' < Sender<br/>');
                $this->mailer->addAddress($uif['email'],$uif['shortName']);
            }else
                {
                    print_r('ERROR >> '.$uif['email'].' <br/>');
                    //$this->mailer->addCC($uif['email'],$uif['shortName']);
                }


        }
            //$this->mailer->addAddress('pmalyovanyi@digins.ua','pavel');
            //$this->mailer->addCC('i-kraz@meta.ua','dfg');
           // $this->mailer->addCC('i-kraz@i.ua','gggfds');


        $this->mailer->isHTML(true);
        $this->mailer->Subject      = $this->subject;
        $this->mailer->Body         = $this->body;
        $this->mailer->AltBody      = $this->altBody;

    }

    public function creatorTiket($data)
    {
        //$us = new User();
        $letter = '
        <html>
    <head>
    <style>
    * {
    margin: 0;
    padding: 0;
    font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
    box-sizing: border-box;
    font-size: 14px;
	}

	img {
		max-width: 100%;
	}

	body {
		-webkit-font-smoothing: antialiased;
		-webkit-text-size-adjust: none;
		width: 100% !important;
		height: 100%;
		line-height: 1.6;
	}
	table td {
		vertical-align: top;
	}

	body {
		background-color: #f6f6f6;
	}

	.body-wrap {
		background-color: #f6f6f6;
		width: 100%;
	}

	.container {
		display: block !important;
		max-width: 600px !important;
		margin: 0 auto !important;
		/* makes it centered */
		clear: both !important;
	}

	.content {
		max-width: 600px;
		margin: 0 auto;
		display: block;
		padding: 20px;
	}

	.main {
		background: #fff;
		border: 1px solid #e9e9e9;
		border-radius: 3px;
	}

	.content-wrap {
		padding: 20px;
	}

	.content-block {
		padding: 0 0 20px;
	}

	.header {
		width: 100%;
		margin-bottom: 20px;
	}

	.footer {
		width: 100%;
		clear: both;
		color: #999;
		padding: 20px;
	}
	.footer a {
		color: #999;
	}
	.footer p, .footer a, .footer unsubscribe, .footer td {
		font-size: 12px;
	}

	h1, h2, h3 {
		font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
		color: #000;
		margin: 40px 0 0;
		line-height: 1.2;
		font-weight: 400;
	}

	h1 {
		font-size: 32px;
		font-weight: 500;
	}

	h2 {
		font-size: 24px;
	}

	h3 {
		font-size: 18px;
	}

	h4 {
		font-size: 14px;
		font-weight: 600;
	}

	p, ul, ol {
		margin-bottom: 10px;
		font-weight: normal;
	}
	p li, ul li, ol li {
		margin-left: 5px;
		list-style-position: inside;
	}

	a {
		color: #1ab394;
		text-decoration: underline;
	}

	.btn-primary {
		text-decoration: none;
		color: #FFF;
		background-color: #1ab394;
		border: solid #1ab394;
		border-width: 5px 10px;
		line-height: 2;
		font-weight: bold;
		text-align: center;
		cursor: pointer;
		display: inline-block;
		border-radius: 5px;
		text-transform: capitalize;
	}

	.last {
		margin-bottom: 0;
	}

	.first {
		margin-top: 0;
	}

	.aligncenter {
		text-align: center;
	}

	.alignright {
		text-align: right;
	}

	.alignleft {
		text-align: left;
	}

	.clear {
		clear: both;
	}

	.alert {
		font-size: 16px;
		color: #fff;
		font-weight: 500;
		padding: 20px;
		text-align: center;
		border-radius: 3px 3px 0 0;
	}
	.alert a {
		color: #fff;
		text-decoration: none;
		font-weight: 500;
		font-size: 16px;
	}
	.alert.alert-warning {
		background: #f8ac59;
	}
	.alert.alert-bad {
		background: #ed5565;
	}
	.alert.alert-good {
		background: #1ab394;
	}

	.invoice {
		margin: 40px auto;
		text-align: left;
		width: 80%;
	}
	.invoice td {
		padding: 5px 0;
	}
	.invoice .invoice-items {
		width: 100%;
	}
	.invoice .invoice-items td {
		border-top: #eee 1px solid;
	}
	.invoice .invoice-items .total td {
		border-top: 2px solid #333;
		border-bottom: 2px solid #333;
		font-weight: 700;
	}

	@media only screen and (max-width: 640px) {
		h1, h2, h3, h4 {
			font-weight: 600 !important;
			margin: 20px 0 5px !important;
		}

		h1 {
			font-size: 22px !important;
		}

		h2 {
			font-size: 18px !important;
		}

		h3 {
			font-size: 16px !important;
		}

		.container {
			width: 100% !important;
		}

		.content, .content-wrap {
			padding: 10px !important;
		}

		.invoice {
			width: 100% !important;
		}
	} 
    </style>
</head>

<body>

<table class="body-wrap">
    <tr>
        <td></td>
        <td class="container" width="600">
            <div class="content">
                <table class="main" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="content-wrap">
                            <table  cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                        <img src="">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <h3>Ви створили тікет  #'.$data['tid'].' - '.$data['title'].'</h3>
                                        Створив: <small>' .$data['shortName'].'</small>
                                    </td>
                                    
                                </tr>
                                <tr>
                                
                                    <td class="content-block">
                                    Зміст:<br/><br/>
                                        '.$data['content'].'
                                    </td>
                                </tr>
                                <tr>
                                     <td class="content-block">
                                    Наступні користувачі призначені відповідальними:<br/>
                                    ';
        if (isset($data['users']))
        {
            foreach ($data['users'] as $userAs)
            {
                $uif = $this->us->getByUserId($userAs);
                $letter .= '<b> - '.$uif['shortName'].'</b><br/> ';
                if($uif['int_phone'] == '10-00'){$letter .= '';}else{$letter .= 'Вн.телефон: '.$uif['int_phone'].' ,';}
                $letter .= ' моб.телефон: '.$uif['phone'].', пошта: '.$uif['email'].'<br/>';

            }
        }
        $letter .='
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block aligncenter">
                                        <a href="http://s2.sysadm.digins.ua/user/tikets/view/'.$data['tid'].'" class="btn-primary">Перейти до тікету  '.$data['title'].'-#'.$data['tid'].'</a>
                                    </td>
                                </tr>
                              </table>
                        </td>
                    </tr>
                </table>
                <div class="footer">
                    <table width="100%">
                        <tr>
                            <td class="aligncenter content-block">Follow <a href="#">@Company</a> on CR+.</td>
                        </tr>
                    </table>
                </div></div>
        </td>
        <td></td>
    </tr>
</table>

</body>
</html>

        ';



        $this->mail['0']   = $data['uid'];
        $this->subject= 'Тікет #'.$data['tid'].' - "'.$data['title'].'". успішно створено!';
        $this->body   = $letter;
        //$this->altBody= '';
//        print_r($this->mail.'<<<');
//        print_r($this->body);
        self::make();
        self::send();
       // $this->mailer->ClearAllRecipients();
        //print_r($data);

    }

    public function extractorsNewTiket($data)
    {
        //$us = new User();
        $user = $this->us->getByUserId($data['uid']);

        $letter = '
        <html>
    <head>
    <style>
    * {
    margin: 0;
    padding: 0;
    font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
    box-sizing: border-box;
    font-size: 14px;
	}

	img {
		max-width: 100%;
	}

	body {
		-webkit-font-smoothing: antialiased;
		-webkit-text-size-adjust: none;
		width: 100% !important;
		height: 100%;
		line-height: 1.6;
	}
	table td {
		vertical-align: top;
	}

	body {
		background-color: #f6f6f6;
	}

	.body-wrap {
		background-color: #f6f6f6;
		width: 100%;
	}

	.container {
		display: block !important;
		max-width: 600px !important;
		margin: 0 auto !important;
		/* makes it centered */
		clear: both !important;
	}

	.content {
		max-width: 600px;
		margin: 0 auto;
		display: block;
		padding: 20px;
	}

	.main {
		background: #fff;
		border: 1px solid #e9e9e9;
		border-radius: 3px;
	}

	.content-wrap {
		padding: 20px;
	}

	.content-block {
		padding: 0 0 20px;
	}

	.header {
		width: 100%;
		margin-bottom: 20px;
	}

	.footer {
		width: 100%;
		clear: both;
		color: #999;
		padding: 20px;
	}
	.footer a {
		color: #999;
	}
	.footer p, .footer a, .footer unsubscribe, .footer td {
		font-size: 12px;
	}

	h1, h2, h3 {
		font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
		color: #000;
		margin: 40px 0 0;
		line-height: 1.2;
		font-weight: 400;
	}

	h1 {
		font-size: 32px;
		font-weight: 500;
	}

	h2 {
		font-size: 24px;
	}

	h3 {
		font-size: 18px;
	}

	h4 {
		font-size: 14px;
		font-weight: 600;
	}

	p, ul, ol {
		margin-bottom: 10px;
		font-weight: normal;
	}
	p li, ul li, ol li {
		margin-left: 5px;
		list-style-position: inside;
	}

	a {
		color: #1ab394;
		text-decoration: underline;
	}

	.btn-primary {
		text-decoration: none;
		color: #FFF;
		background-color: #1ab394;
		border: solid #1ab394;
		border-width: 5px 10px;
		line-height: 2;
		font-weight: bold;
		text-align: center;
		cursor: pointer;
		display: inline-block;
		border-radius: 5px;
		text-transform: capitalize;
	}

	.last {
		margin-bottom: 0;
	}

	.first {
		margin-top: 0;
	}

	.aligncenter {
		text-align: center;
	}

	.alignright {
		text-align: right;
	}

	.alignleft {
		text-align: left;
	}

	.clear {
		clear: both;
	}

	.alert {
		font-size: 16px;
		color: #fff;
		font-weight: 500;
		padding: 20px;
		text-align: center;
		border-radius: 3px 3px 0 0;
	}
	.alert a {
		color: #fff;
		text-decoration: none;
		font-weight: 500;
		font-size: 16px;
	}
	.alert.alert-warning {
		background: #f8ac59;
	}
	.alert.alert-bad {
		background: #ed5565;
	}
	.alert.alert-good {
		background: #1ab394;
	}

	.invoice {
		margin: 40px auto;
		text-align: left;
		width: 80%;
	}
	.invoice td {
		padding: 5px 0;
	}
	.invoice .invoice-items {
		width: 100%;
	}
	.invoice .invoice-items td {
		border-top: #eee 1px solid;
	}
	.invoice .invoice-items .total td {
		border-top: 2px solid #333;
		border-bottom: 2px solid #333;
		font-weight: 700;
	}

	@media only screen and (max-width: 640px) {
		h1, h2, h3, h4 {
			font-weight: 600 !important;
			margin: 20px 0 5px !important;
		}

		h1 {
			font-size: 22px !important;
		}

		h2 {
			font-size: 18px !important;
		}

		h3 {
			font-size: 16px !important;
		}

		.container {
			width: 100% !important;
		}

		.content, .content-wrap {
			padding: 10px !important;
		}

		.invoice {
			width: 100% !important;
		}
	} 
    </style>
    </head>

<body>

<table class="body-wrap">
    <tr>
        <td></td>
        <td class="container" width="600">
            <div class="content">
                <table class="main" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="content-wrap">
                            <table  cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                        <img src="">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                    Повідомлення для: ';
        foreach ($data['users'] as $userAs)
        {
            $uif=$this->us->getByUserId($userAs);
            $letter .= '<p> - '.$uif['shortName'].'</p> ';

        }

    $letter .='<h3>Вас призначили відповідальним за виконання тікету:<br/>  #'.$data['tid'].' - '.$data['title'].'</h3>
                                        Створив: <small>' .$data['shortName'].'</small>
                                    </td>
                                    
                                </tr>
                                <tr>
                                
                                    <td class="content-block">
                                    Зміст:<br/><br/>
                                        '.$data['content'].'
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                    Наступні користувачі призначені відповідальними:<br/>
                                    ';

        if (isset($data['users']))
        {
            foreach ($data['users'] as $userAs)
            {
                $uif=$this->us->getByUserId($userAs);
                $letter .= '<b> - '.$uif['shortName'].'</b><br/> ';
                if($uif['int_phone'] == '10-00'){$letter .= '';}else{$letter .= 'Вн.телефон: '.$uif['int_phone'].' ,';}
                $letter .= ' моб.телефон: '.$uif['phone'].', пошта: '.$uif['email'].'<br/>';

            }
        }
        $letter .='
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block aligncenter">
                                        <a href="http://s2.sysadm.digins.ua/user/tikets/view/'.$data['tid'].'" class="btn-primary">Перейти до тікету  '.$data['title'].'-#'.$data['tid'].'</a>
                                    </td>
                                </tr>
                              </table>
                        </td>
                    </tr>
                </table>
                <div class="footer">
                    <table width="100%">
                        <tr>
                            <td class="aligncenter content-block">Follow <a href="#">@Company</a> on Twitter.</td>
                        </tr>
                    </table>
                </div></div>
        </td>
        <td></td>
    </tr>
</table>

</body>
</html>

        ';


        $this->mailer->ClearAddresses();  // each AddAddress add to list
        $this->mailer->ClearCCs();
        $this->mailer->ClearBCCs();

//        foreach ($data['users'] as $userAs)
//        {
//            $uif=$us->getByUserId($userAs);
//            $this->mailer->addAddress($uif['email']);
//
//        }
        $this->mail['0']   = $data['uid'];
        $this->subject  = 'Тікет #'.$data['tid'].' - "'.$data['title'].'"". Ви призначені відповідальним за виконання!';
        $this->body     = $letter;
        $this->altBody  = '';
        //print_r($this->mail.'<<<');
        self::make();
        self::send();
        //$this->mailer->ClearAllRecipients();
        //print_r($this->body);
        //print_r($data);

    }

    public function user_index()
    {

        //$this->data['mail'] = new \PHPMailer\PHPMailer\PHPMailer();
        $this->data['mail']->CharSet = 'utf-8';
        //$this->data['mail'];

            //Server settings
        $this->data['mail']->SMTPDebug = 2;                                 // Enable verbose debug output
        $this->data['mail']->isSMTP();                                      // Set mailer to use SMTP
        $this->data['mail']->Host = 'smtp.meta.ua';  // Specify main and backup SMTP servers
        $this->data['mail']->SMTPAuth = true;                               // Enable SMTP authentication
        $this->data['mail']->Username = 'dig.crm@meta.ua';                 // SMTP username
        $this->data['mail']->Password = '8311018090';                           // SMTP password
        $this->data['mail']->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $this->data['mail']->Port = 465;                                    // TCP port to connect to(465)

            //Recipients
        $this->data['mail']->setFrom('dig.crm@meta.ua', 'ROBOTiK');
        $this->data['mail']->addAddress('pmalyovanyi@digins.ua');     // Add a recipient
//            $mail->addAddress('ellen@example.com');               // Name is optional
//        $this->data['mail']->addReplyTo('info@example.com', 'Information');
//            $mail->addCC('cc@example.com');
//            $mail->addBCC('bcc@example.com');

//            Attachments
//            $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//            $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            //Content
        $this->data['mail']->isHTML(true);                                  // Set email format to HTML
        $this->data['mail']->Subject = 'Test mail from tiket system';
        $this->data['mail']->Body    = '

';
        $this->data['mail']->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if(!$this->data['mail']->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $this->data['mail']->ErrorInfo;
        } else {
            echo 'ok';
        }
        //$this->data['mail']->send();
            echo 'Message has been sent';



    }
    public function user_viewuser()
    {

        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $user_login = strtolower($params[0]);
            //$this->data['users'] = $this->model->getUserList();
            $this->data['users'] = $this->model->getByLogin($user_login);
        } else {
            $this->data['users'] = $this->model->getUserList();
        }

    }
}