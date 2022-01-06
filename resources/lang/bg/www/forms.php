<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Forms
	|--------------------------------------------------------------------------
	|
	| Input Fields, Labels, Placeholders, Buttons
	|
	*/

    // Buttons
    'subscribeButton' => 'Абонирай ме',
    'downloadButton' => 'Свали файл',
    'contactButton' => 'Изпрати съобщението',
    'viewOfferButton' => 'Разгледай офертата',
    'viewButton' => 'Разгледай',
    'bookButton' => 'Резервирай',
    'addButton' => 'Добави стая',
    'removeButton' => 'Премахни стаята',
    'nextButton' => 'Напред',
    'prevButton' => 'Назад',
    'payButton' => 'Плати онлайн',
    'consent' => 'Съгласен съм с <a target="_blank" href="' . \Locales::route('terms') . '">условията</a>',

    // Labels
    'subscribeLabel' => 'E-mail Адрес',
    'nameLabel' => 'Име',
    'phoneLabel' => 'Телефон',
    'emailLabel' => 'E-mail',
    'departmentLabel' => 'Отдел',
    'messageLabel' => 'Съобщение',
    'dfromLabel' => 'Дата на пристигане',
    'dtoLabel' => 'Дата на заминаване',
    'roomLabel' => 'Помещение',
    'viewLabel' => 'Изглед',
    'mealLabel' => 'Изхранване',
    'guestsLabel' => 'Брой гости',
    'adultsLabel' => 'Възрастни',
    'childrenLabel' => 'Деца',
    'infantsLabel' => 'Бебета',
    'priceLabel' => 'Цена',
    'companyLabel' => 'Фирма',
    'eikLabel' => 'ЕИК',
    'vatLabel' => 'ДДС номер',
    'molLabel' => 'МОЛ',
    'countryLabel' => 'Държава',
    'cityLabel' => 'Град',
    'addressLabel' => 'Адрес',
    'invoiceLabel' => 'Да, желая да получа фактура на юридическо лице',

    // Placeholders
    'emailPlaceholder' => 'E-mail Адрес',
    'namePlaceholder' => 'Име',
    'phonePlaceholder' => 'Телефон',
    'emailPlaceholder' => 'E-mail',
    'messagePlaceholder' => 'Съобщение',
    'dfromPlaceholder' => 'Дата на пристигане',
    'dtoPlaceholder' => 'Дата на заминаване',
    'adultsPlaceholder' => '0',
    'childrenPlaceholder' => '0',
    'infantsPlaceholder' => '0',
    'companyPlaceholder' => 'Фирма',
    'eikPlaceholder' => 'ЕИК',
    'vatPlaceholder' => 'ДДС номер',
    'molPlaceholder' => 'МОЛ',
    'countryPlaceholder' => 'Държава',
    'cityPlaceholder' => 'Град',
    'addressPlaceholder' => 'Адрес',

    // Success messages
    'subscribeSuccess' => 'Абонирахте се успешно!',
    'contactSuccess' => 'Вашето запитване беше изпратено успешно!',

    // Error messages
    'contactError' => 'Вашето запитване не беше изпратено!',
    'bookErrorCaptcha' => 'Възникна грешка! Моля, опитайте отново или се свържете с нас.',
    'bookErrorSave' => 'Възникна грешка! Моля, опитайте отново или се свържете с нас.',
    'bookErrorVerify' => 'Грешка при проверка на електронния подпис на банката',
    'bookError' => 'Възникна грешка! Моля, опитайте отново или се свържете с нас.',
    'bookErrorCode' => 'Код на грешката: :code',
    'bookErrorCodeDescription' => 'Описание на грешката: :description',

    // Subjects
    'contactSubject' => 'Съобщение от ' . env('APP_DOMAIN'),

    // Text
    'adults-help' => '18+',
    'children-help' => '3-17',
    'infants-help' => '0-2',

];
