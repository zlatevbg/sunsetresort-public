<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Messages
	|--------------------------------------------------------------------------
	|
	| Main language file
	|
	*/

    'altLogo' => 'Лого',
    'changeLanguage' => 'Избери език',
    'contactAdmin' => 'Контакт с Администратор',
    'all' => 'Всичко',
    'menu' => 'Меню',
    'settings' => 'Настройки',
    'localeIsHidden' => ' (скрит в URL)',
    'footerText' => '&copy; ' . date("Y") . ' Сънсет Ризорт',

    'errCodes' => [
        '' => 'Timeout',
        '-1' => 'A mandatory request field is not filled in / В заявката не е попълнено задължително поле',
        '-2' => 'Bad CGI request / Заявката съдържа поле с некоректно име',
        '-3' => "Acquirer host (NS) does not respond or wrong format of e-gateway response template file / Aвторизационният хост не отговаря или форматът на отговора е неправилен",
        '-4' => 'No connection to the acquirer host (NS) / Няма връзка с авторизационния хост',
        '-9' => 'Error in the "Card expiration date" request field / Грешна дата на валидност на картата',
        '-11' => 'Error in the "Currency" request field / Грешка в поле "Валута" в заявката',
        '-12' => 'Error in the "Merchant ID" request field / Грешка в поле "Merchant ID / Идентификатор на търговец"',
        '-15' => 'Error in the "RRN" request field / Грешка в поле "RRN" в заявката',
        '-17' => 'The terminal is denied access to the e-Gateway / Отказан достъп до платежния сървър (Грешка при проверка на P_SIGN)',
        '-19' => 'Error in the authentication information request or authentication failed / Грешка в искането за автентификация или неуспешна автентификация',
        '-20' => 'A permitted time interval (1 hour by default) between the transaction Time Stamp request field and the e-Gateway time is exceeded / Разрешената разлика между времето на сървъра на търговеца и e-Gateway сървъра е надвишена',
        '-21' => 'The transaction has already been executed / Транзакцията вече е била изпълнена',
        '-24' => 'Transaction context mismatch / Заявката съдържа стойности за полета които не могат да бъдат обработени. Например валутата е различна от валутата на терминала.',
        '-25' => 'Transaction canceled (e.g. by user) / Транзакцията е отказана (напр. от картодържателя)',
        '-27' => 'Invalid merchant name / Неправилно име на търговеца',
        '-32' => 'Duplicate declined transaction / Дублирана отказана транзакция',
        '00' => 'Нормално изпълнена авторизация',
        '01' => 'Refer to card issuer',
        '02' => 'Refer to card issuer, special condition',
        '03' => 'Invalid merchant or service provider',
        '04' => 'Pickup',
        '05' => 'Do not honor',
        '06' => 'General error',
        '07' => 'Pickup card, special condition (other than lost/stolen card)',
        '08' => 'Honor with identification',
        '09' => 'Request in progress',
        '10' => 'Partial approval',
        '11' => 'VIP approval',
        '12' => 'Invalid transaction',
        '13' => 'Invalid amount (currency conversion field overflow) or amount exceeds maximum for card program',
        '14' => 'Invalid account number (no such number)',
        '15' => 'No such issuer',
        '16' => 'Insufficient funds',
        '17' => 'Customer cancellation',
        '18' => 'Customer dispute',
        '19' => 'Re-enter transaction',
        '20' => 'Invalid response',
        '21' => 'No action taken (unable to back out prior transaction)',
        '22' => 'Suspected Malfunction',
        '23' => 'unacceptable transaction fee',
        '24' => 'File update not supported by receiver',
        '25' => 'Unable to locate record in file, or account number is missing from the inquiry',
        '26' => 'Duplicate file update record, old record replaced',
        '27' => 'File update field edit error',
        '28' => 'File is temporarily unavailable',
        '29' => 'File update not successful, contact acquirer',
        '30' => 'Format error',
        '31' => 'Bank not supported',
        '32' => 'Completed partially',
        '33' => 'Expired card',
        '34' => 'Suspected fraud',
        '35' => 'Card acceptor contact acquirer',
        '36' => 'Restricted card',
        '37' => 'Card acceptor call acquirer security',
        '38' => 'Allowed PIN tries exceeded',
        '39' => 'No credit account',
        '40' => 'Requested function not supported',
        '41' => 'Merchant should retain card (card reported lost)',
        '42' => 'No universal account',
        '43' => 'Merchant should retain card (card reported stolen)',
        '51' => 'Insufficient funds',
        '52' => 'No checking account',
        '53' => 'No savings account',
        '54' => 'Expired card',
        '55' => 'Incorrect PIN',
        '56' => 'No Card Record',
        '57' => 'Transaction not permitted to cardholder',
        '58' => 'Transaction not allowed at terminal',
        '59' => 'Suspected fraud',
        '61' => 'Activity amount limit exceeded',
        '62' => 'Restricted card (for example, in country exclusion table)',
        '63' => 'Security violation',
        '65' => 'Activity count limit exceeded',
        '68' => 'Response received too late',
        '75' => 'Allowable number of PIN-entry tries exceeded',
        '76' => 'Unable to locate previous message (no match on retrieval reference number)',
        '77' => 'Previous message located for a repeat or reversal, but repeat or reversal data are inconsistent with original message',
        '78' => '"Blocked, first used" — The transaction is from a new cardholder, and the card has not been properly unblocked.',
        '80' => 'Visa transactions: credit issuer unavailable. Private label and check acceptance: Invalid date',
        '81' => 'PIN cryptographic error found (error found by VIC security module during PIN decryption)',
        '82' => 'Negative CAM, dCVV, iCVV, or CVV results',
        '83' => 'Unable to verify PIN',
        '85' => 'No reason to decline a request for account number verification, address verification, CVV2 verification; or a credit voucher or merchandise return',
        '88' => 'Cryptographic failure',
        '89' => 'Authentication failure',
        '91' => 'Issuer unavailable or switch inoperative (STIP not applicable or available for this transaction)',
        '92' => 'Destination cannot be found for routing',
        '93' => 'Transaction cannot be completed, violation of law',
        '94' => 'Duplicate transmission',
        '95' => 'Reconcile error',
        '96' => 'System malfunction, System malfunction or certain field error conditions',
        'B1' => 'Surcharge amount not permitted on Visa cards (U.S. acquirers only)',
        'N0' => 'Force STIP',
        'N3' => 'Cash service not available',
        'N4' => 'Cashback request exceeds issuer limit',
        'N7' => 'Decline for CVV2 failure',
        'P2' => 'Invalid biller information',
        'P5' => 'PIN change/unblock request declined',
        'P6' => 'Unsafe PIN',
        'Q1' => 'Card authentication failed',
        'R0' => 'Stop payment order',
        'R1' => 'Revocation of authorization order',
        'R3' => 'Revocation of all authorizations order',
        'XA' => 'Forward to issuer',
        'XD' => 'Forward to issuer',
        'Z3' => 'Unable to go online',
    ],
];
