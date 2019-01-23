<?php

use PHPMailer\PHPMailer\PHPMailer;

class make extends cmd {
	/**
	 * @return bool|string
	 * @throws \PHPMailer\PHPMailer\Exception
	 */
	protected function send_sms() {
		$sPhoneNum = '+33763207630'; // Le numéro de téléphone qui recevra l'SMS (avec le préfixe, ex: +33)
		$aProviders = ['mms.bouyguestelecom.fr', 'sfr.fr', 'orange.fr', 'smtp.free.fr', 'pop.free.fr'];

		$mailer = new PHPMailer(true);
		$mailer->IsSMTP(); // active SMTP
		$mailer->SMTPDebug = 1;  // debogage: 1 = Erreurs et messages, 2 = messages seulement
		$mailer->SMTPAuth = true;  // Authentification SMTP active
		$mailer->SMTPSecure = 'ssl'; // Gmail REQUIERT Le transfert securise
		$mailer->Host = 'smtp.gmail.com';
		$mailer->Port = 465;
		$mailer->Username = 'nicolachoquet06250@gmail.com';
		$mailer->Password = '12042107NicolasChoquet2669!';
		$mailer->SetFrom($mailer->Username, 'Pizzygo');
		$mailer->Subject = 'SMS';
		$mailer->Body = 'SMS';
		$to = 'nicolachoquet06250@gmail.com';
		$mailer->AddAddress($to);
//		foreach ($aProviders as $sProvider) {
//			$to = $sPhoneNum . '@' . $sProvider . '.com';
//			$mailer->AddAddress($to);
//		}
		if(!$mailer->Send()) {
			return 'Mail error: '.$mailer->ErrorInfo;
		} else {
			return true;
		}
//		foreach ($aProviders as $sProvider) {
//			if(mail($sPhoneNum . '@' . $sProvider . '.com', '', 'Ce texto a été envoyé avec PHP, tout simplement !')) {
//				echo 'envoyé'."\n";
//				break;
//			}
//			else {
//				echo 'pas envoyé'."\n";
//				continue;
//			}
//		}
	}
}