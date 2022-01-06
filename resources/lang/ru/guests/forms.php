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
    'subscribeButton' => 'Подписаться на',
    'downloadButton' => 'Скачать',
    'contactButton' => 'Отправить сообшение',
    'viewButton' => 'Посмотреть',
    'bookButton' => 'Забронировать',
    'addButton' => 'Добавить',
    'removeButton' => 'Удалить',
    'nextButton' => 'Следующий',
    'prevButton' => 'Обратно',
    'payButton' => 'Оплата онлайн',
    'consent' => 'Я согласен с <a target="_blank" href="' . \Locales::route('terms') . '">общими условиями и положениями</a>',

    // Labels
    'subscribeLabel' => 'Ваш электронный адрес',
    'nameLabel' => 'Имя',
    'phoneLabel' => 'Телефон',
    'emailLabel' => 'E-mail',
    'departmentLabel' => 'Отдел',
    'messageLabel' => 'Сообщение',
    'dfromLabel' => 'Дата заезда',
    'dtoLabel' => 'Дата отъезда',
    'roomLabel' => 'Апартамент',
    'viewLabel' => 'Посмотреть',
    'mealLabel' => 'Питание',
    'guestsLabel' => 'Число гостей',
    'adultsLabel' => 'Взрослых',
    'childrenLabel' => 'Детей',
    'infantsLabel' => 'Младенцы',
    'priceLabel' => 'Цена',
    'companyLabel' => 'Компания',
    'eikLabel' => 'Идентификационный номер',
    'vatLabel' => 'Номер НДС',
    'molLabel' => 'Имя и фамилия управляющего',
    'countryLabel' => 'Страна',
    'cityLabel' => 'Город',
    'addressLabel' => 'Адрес',
    'invoiceLabel' => 'Да, я хотел бы получить счет',

    // Placeholders
    'emailPlaceholder' => 'Ваш электронный адрес',
    'namePlaceholder' => 'Имя',
    'phonePlaceholder' => 'Телефон',
    'emailPlaceholder' => 'E-mail',
    'messagePlaceholder' => 'Сообщение',
    'dfromPlaceholder' => 'Дата заезда',
    'dtoPlaceholder' => 'Дата отъезда',
    'adultsPlaceholder' => '0',
    'childrenPlaceholder' => '0',
    'infantsPlaceholder' => '0',
    'companyPlaceholder' => 'Компания',
    'eikPlaceholder' => 'Идентификационный номер',
    'vatPlaceholder' => 'Номер НДС',
    'molPlaceholder' => 'Имя и фамилия управляющего',
    'countryPlaceholder' => 'Страна',
    'cityPlaceholder' => 'Город',
    'addressPlaceholder' => 'Адрес',

    // Success messages
    'subscribeSuccess' => 'Вы успешно подписались!',
    'contactSuccess' => 'Ваше сообщение успешно отправлено!',

    // Error messages
    'contactError' => 'Сообщение не может быть отправлено!',
    'bookErrorCaptcha' => 'Произошла ошибка! Повторите попытку или свяжитесь с нами.',
    'bookErrorSave' => 'Произошла ошибка! Повторите попытку или свяжитесь с нами.',
    'bookErrorVerify' => 'Произошла ошибка! Повторите попытку или свяжитесь с нами.',
    'bookError' => 'Произошла ошибка! Повторите попытку или свяжитесь с нами.',
    'bookErrorCode' => 'Код ошибки: :code',
    'bookErrorCodeDescription' => 'Описание ошибки: :description',

    // Subjects
    'contactSubject' => 'Сообщение от ' . env('APP_DOMAIN'),

    // Text
    'adults-help' => '18+',
    'children-help' => '3-17',
    'infants-help' => '0-2',

];
