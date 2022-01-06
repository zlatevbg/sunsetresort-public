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
    'subscribeButton' => 'Subscribe me',
    'downloadButton' => 'Download',
    'contactButton' => 'Send the message',
    'viewOfferButton' => 'View Offer',
    'viewButton' => 'View',
    'bookButton' => 'Book now',
    'addButton' => 'Add room',
    'removeButton' => 'Remove room',
    'nextButton' => 'Next',
    'prevButton' => 'Back',
    'payButton' => 'Pay online',
    'consent' => 'I agree with the <a target="_blank" href="' . \Locales::route('terms') . '">general terms and conditions</a>',

    // Labels
    'subscribeLabel' => 'E-mail Address',
    'nameLabel' => 'Name',
    'phoneLabel' => 'Phone',
    'emailLabel' => 'E-mail',
    'departmentLabel' => 'Department',
    'messageLabel' => 'Message',
    'dfromLabel' => 'Check-in date',
    'dtoLabel' => 'Check-out date',
    'roomLabel' => 'Unit',
    'viewLabel' => 'View',
    'mealLabel' => 'Board',
    'guestsLabel' => 'Number of guests',
    'adultsLabel' => 'Adults',
    'childrenLabel' => 'Children',
    'infantsLabel' => 'Infants',
    'priceLabel' => 'Price',
    'companyLabel' => 'Company',
    'eikLabel' => 'ID number',
    'vatLabel' => 'VAT number',
    'molLabel' => 'Person in charge',
    'countryLabel' => 'Country',
    'cityLabel' => 'City',
    'addressLabel' => 'Address',
    'invoiceLabel' => 'Yes, I would like to receive a company invoice',

    // Placeholders
    'emailPlaceholder' => 'E-mail Address',
    'namePlaceholder' => 'Name',
    'phonePlaceholder' => 'Phone',
    'emailPlaceholder' => 'E-mail',
    'messagePlaceholder' => 'Message',
    'dfromPlaceholder' => 'Check-in date',
    'dtoPlaceholder' => 'Check-out date',
    'adultsPlaceholder' => '0',
    'childrenPlaceholder' => '0',
    'infantsPlaceholder' => '0',
    'companyPlaceholder' => 'Company',
    'eikPlaceholder' => 'ID number',
    'vatPlaceholder' => 'VAT number',
    'molPlaceholder' => 'Person in charge',
    'countryPlaceholder' => 'Country',
    'cityPlaceholder' => 'City',
    'addressPlaceholder' => 'Address',

    // Success messages
    'subscribeSuccess' => 'You have subscribed successfully!',
    'contactSuccess' => 'Message sent successfully!',

    // Error messages
    'contactError' => 'Message cannot be sent!',
    'bookErrorCaptcha' => 'An error occurred! Please try again or contact us.',
    'bookErrorSave' => 'An error occurred! Please try again or contact us.',
    'bookErrorVerify' => 'An error occurred! Please try again or contact us.',
    'bookError' => 'An error occurred! Please try again or contact us.',
    'bookErrorCode' => 'Error code: :code',
    'bookErrorCodeDescription' => 'Error description: :description',

    // Subjects
    'contactSubject' => 'Message from ' . env('APP_DOMAIN'),

    // Text
    'adults-help' => '18+',
    'children-help' => '3-17',
    'infants-help' => '0-2',

];
