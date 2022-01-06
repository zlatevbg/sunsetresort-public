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
    'subscribeButton' => 'Jetzt abonnieren',
    'downloadButton' => 'Herunterladen',
    'contactButton' => 'Ihre Nachricht senden',
    'viewOfferButton' => 'Siehe Angebot',
    'viewButton' => 'Jetzt nachsehen!',
    'bookButton' => 'Buchen',
    'addButton' => 'Hinzufügen',
    'removeButton' => 'Löschen',
    'nextButton' => 'Weiter',
    'prevButton' => 'Zurück',
    'payButton' => 'Online bezahlen',
    'consent' => 'Ich bestätige <a target="_blank" href="' . \Locales::route('terms') . '">die Geschäftsbedingungen</a>',

    // Labels
    'subscribeLabel' => 'E-mail-Adresse',
    'nameLabel' => 'Name',
    'phoneLabel' => 'Telefonnummer',
    'emailLabel' => 'E-mail',
    'departmentLabel' => 'Abteilung',
    'messageLabel' => 'Nachricht',
    'dfromLabel' => 'Ankunftsdatum',
    'dtoLabel' => 'Abreisedatum',
    'roomLabel' => 'Appartement',
    'viewLabel' => 'Appartementblick',
    'mealLabel' => 'Verpflegung',
    'guestsLabel' => 'Gästeanzahl',
    'adultsLabel' => 'Erwachsene',
    'childrenLabel' => 'Kinder',
    'infantsLabel' => 'Babys',
    'priceLabel' => 'Preis',
    'companyLabel' => 'Unternehmen',
    'eikLabel' => 'Identifikationsnummer',
    'vatLabel' => 'VAT-Nummer',
    'molLabel' => 'Verantwortlicher',
    'countryLabel' => 'Land',
    'cityLabel' => 'Wohnort',
    'addressLabel' => 'Adresse',
    'invoiceLabel' => 'Ja, ich will eine Firmenrechnung erhalten',

    // Placeholders
    'emailPlaceholder' => 'E-mail-Adresse',
    'namePlaceholder' => 'Name',
    'phonePlaceholder' => 'Telefonnummer',
    'emailPlaceholder' => 'E-mail',
    'messagePlaceholder' => 'Nachricht',
    'dfromPlaceholder' => 'Ankunftsdatum',
    'dtoPlaceholder' => 'Abreisedatum',
    'adultsPlaceholder' => '0',
    'childrenPlaceholder' => '0',
    'infantsPlaceholder' => '0',
    'companyPlaceholder' => 'Unternehmen',
    'eikPlaceholder' => 'Identifikationsnummer',
    'vatPlaceholder' => 'VAT-Nummer',
    'molPlaceholder' => 'Verantwortlicher',
    'countryPlaceholder' => 'Land',
    'cityPlaceholder' => 'Wohnort',
    'addressPlaceholder' => 'Adresse',

    // Success messages
    'subscribeSuccess' => 'Sie wurden erfolgreich angemeldet!',
    'contactSuccess' => 'Ihre Nachricht wurde erfolgreich gesendet!',

    // Error messages
    'contactError' => 'Ihre Nachricht wurde nicht gesendet!',
    'bookErrorCaptcha' => 'Ein Fehler ist aufgetreten! Bitte versuchen Sie erneut oder kontaktieren Sie uns.',
    'bookErrorSave' => 'Ein Fehler ist aufgetreten! Bitte versuchen Sie erneut oder kontaktieren Sie uns.',
    'bookErrorVerify' => 'Ein Fehler ist aufgetreten! Bitte versuchen Sie erneut oder kontaktieren Sie uns.',
    'bookError' => 'Ein Fehler ist aufgetreten! Bitte versuchen Sie erneut oder kontaktieren Sie uns.',
    'bookErrorCode' => 'Fehlercode: :code',
    'bookErrorCodeDescription' => 'Fehlerbeschreibung: :description',

    // Subjects
    'contactSubject' => 'Nachricht von ' . env('APP_DOMAIN'),

    // Text
    'adults-help' => '18+',
    'children-help' => '3-17',
    'infants-help' => '0-2',

];
