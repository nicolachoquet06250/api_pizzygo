<?php

use PHPMailer\PHPMailer\PHPMailer;

class make extends cmd {
	/**
	 * @return bool|string
	 * @throws \PHPMailer\PHPMailer\Exception
	 */
	protected function send_sms() {
//		$sPhoneNum  = '+33763207630'; // Le numéro de téléphone qui recevra l'SMS (avec le préfixe, ex: +33)
//		$aProviders = ['mms.bouyguestelecom.fr', 'sms.bouyguestelecom.fr', 'sfr.fr', 'orange.fr', 'smtp.free.fr', 'pop.free.fr'];
//
//		$mailer = new PHPMailer();
//
//		$mailer->IsSMTP(); // active SMTP
//		$mailer->SMTPDebug  = 1;  // debogage: 1 = Erreurs et messages, 2 = messages seulement
//		$mailer->SMTPAuth   = true;  // Authentification SMTP active
//		$mailer->SMTPSecure = 'tls'; // Gmail REQUIERT Le transfert securise
//
//		$mailer->Host = 'smtp.gmail.com';
//		$mailer->Port = 587;
//
//		$mailer->Username = 'nicolachoquet06250@gmail.com';
//		$mailer->Password = '1204210795NicolasChoquet2669!';
//
//		$mailer->SetFrom($mailer->Username, 'Pizzygo');
//
//		$mailer->Subject = 'SMS';
//		$mailer->Body    = 'SMS';
//
//		$to = 'nicolachoquet06250@gmail.com';
//		$mailer->AddAddress($to);
//		foreach ($aProviders as $sProvider) {
//			$to = $sPhoneNum.'@'.$sProvider;
//			$mailer->AddAddress($to);
//		}
//		if (!$mailer->Send()) {
//			return 'Mail error: '.$mailer->ErrorInfo;
//		} else {
//			return true;
//		}
	}
}