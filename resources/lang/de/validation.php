<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'tokenMismatchException' => 'Seems you could not submit form for a longtime. Please try again.',

    'accepted'             => ':attribute muss akzeptiert werden.',
    'active_url'           => ':attribute ist keine gültige Internet-Adresse.',
    'after'                => ':attribute muss ein Datum nach dem :date sein.',
    'alpha'                => ':attribute darf nur aus Buchstaben bestehen.',
    'alpha_dash'           => ':attribute darf nur aus Buchstaben, Zahlen, Binde- und Unterstrichen bestehen. Umlaute (ä, ö, ü) und Eszett (ß) sind nicht erlaubt.',
    'alpha_num'            => ':attribute darf nur aus Buchstaben und Zahlen bestehen.',
    'array'                => ':attribute muss ein Array sein.',
    'before'               => ':attribute muss ein Datum vor dem :date sein.',
    'between'              => [
        'numeric' => ':attribute muss zwischen :min & :max liegen.',
        'file'    => ':attribute muss zwischen :min & :max Kilobytes groß sein.',
        'string'  => ':attribute muss zwischen :min & :max Zeichen lang sein.',
        'array'   => ':attribute muss zwischen :min & :max Elemente haben.',
    ],
    'boolean'              => ":attribute muss entweder 'true' oder 'false' sein.",
    'confirmed'            => ':attribute stimmt nicht mit der Bestätigung überein.',
    'date'                 => ':attribute muss ein gültiges Datum sein.',
    'date_format'          => ':attribute entspricht nicht dem gültigen Format für :format.',
    'different'            => ':attribute und :other müssen sich unterscheiden.',
    'digits'               => ':attribute muss :digits Stellen haben.',
    'digits_between'       => ':attribute muss zwischen :min und :max Stellen haben.',
    'distinct'             => 'Das Feld :attribute beinhaltet einen bereits vorhandenen Wert.',
    'email'                => ':attribute Format ist ungültig.',
    'exists'               => 'Der gewählte Wert für :attribute ist ungültig.',
    'filled'               => ':attribute muss ausgefüllt sein.',
    'image'                => ':attribute muss ein Bild sein.',
    'in'                   => 'Der gewählte Wert für :attribute ist ungültig.',
    'in_array'             => 'Der gewählte Wert für :attribute kommt nicht in :other vor.',
    'integer'              => ':attribute muss eine ganze Zahl sein.',
    'ip'                   => ':attribute muss eine gültige IP-Adresse sein.',
    'json'                 => ':attribute muss ein gültiger JSON-String sein.',
    'max'                  => [
        'numeric' => ':attribute darf maximal :max sein.',
        'file'    => ':attribute darf maximal :max Kilobytes groß sein.',
        'string'  => ':attribute darf maximal :max Zeichen haben.',
        'array'   => ':attribute darf nicht mehr als :max Elemente haben.',
    ],
    'mimes'                => ':attribute muss den Dateityp :values haben.',
    'min'                  => [
        'numeric' => ':attribute muss mindestens :min sein.',
        'file'    => ':attribute muss mindestens :min Kilobytes groß sein.',
        'string'  => ':attribute muss mindestens :min Zeichen lang sein.',
        'array'   => ':attribute muss mindestens :min Elemente haben.',
    ],
    'not_in'               => 'Der gewählte Wert für :attribute ist ungültig.',
    'numeric'              => ':attribute muss eine Zahl sein.',
    'present'              => 'Das Feld :attribute muss vorhanden sein.',
    'regex'                => ':attribute Format ist ungültig.',
    'required'             => ':attribute muss ausgefüllt sein.',
    'required_if'          => ':attribute muss ausgefüllt sein, wenn :other :value ist.',
    'required_unless'      => ':attribute muss ausgefüllt sein, wenn :other nicht :values ist.',
    'required_with'        => ':attribute muss angegeben werden, wenn :values ausgefüllt wurde.',
    'required_with_all'    => ':attribute muss angegeben werden, wenn :values ausgefüllt wurde.',
    'required_without'     => ':attribute muss angegeben werden, wenn :values nicht ausgefüllt wurde.',
    'required_without_all' => ':attribute muss angegeben werden, wenn keines der Felder :values ausgefüllt wurde.',
    'same'                 => ':attribute und :other müssen übereinstimmen.',
    'size'                 => [
        'numeric' => ':attribute muss gleich :size sein.',
        'file'    => ':attribute muss :size Kilobyte groß sein.',
        'string'  => ':attribute muss :size Zeichen lang sein.',
        'array'   => ':attribute muss genau :size Elemente haben.',
    ],
    'string'               => ':attribute muss ein String sein.',
    'timezone'             => ':attribute muss eine gültige Zeitzone sein.',
    'unique'               => ':attribute ist schon vergeben.',
    'url'                  => 'Das Format von :attribute ist ungültig.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'dfrom' => [
            'before_or_equal' => ':attribute muss ein Datum vor oder gleich dem :date sein.',
        ],
        'g-recaptcha-response' => [
            'required' => 'Bitte klicken Sie auf "Ich bin kein Roboter"!',
        ],
        'select-room' => [
            'required' => 'Bitte Appartement auswählen.',
            'numeric' => 'Bitte Appartement auswählen.',
            'min' => 'Bitte Appartement auswählen.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'name' => 'Name',
        'namespace' => 'Namespace',
        'email' => 'E-mail',
        'phone' => 'Telefonnummer',
        'password' => 'Password',
        'slug' => 'Slug',
        'route' => 'Route',
        'locale' => 'Locale',
        'locales' => 'Locales',
        'default_locale_id' => 'Default Locale',
        'native' => 'Native Name',
        'script' => 'Script',
        'group' => 'Group',
        'country' => 'Country',
        'content' => 'Content',
        'directory' => 'Directory',
        'area' => 'Area',
        'capacity' => 'Capacity',
        'adults' => 'Adults',
        'children' => 'Children',
        'infants' => 'Infants',
        'department' => 'Abteilung',
        'message' => 'Nachricht',
        'type' => 'Type',
        'color' => 'Color',
        'subscribe_email' => 'E-mail',
        'dfrom' => 'Ankunftsdatum',
        'dto' => 'Abreisedatum',
        'views' => 'Appartementblick',
        'meals' => 'Verpflegung',
        'guests' => 'Gästeanzahl',
        'consent' => 'Allgemeine Geschäftsbedingungen',
        'company' => 'Unternehmen',
        'eik' => 'Identifikationsnummer',
        'vat' => 'VAT-Nummer',
        'address' => 'Adresse',
        'city' => 'Wohnort',
        'mol' => 'Verantwortlicher',
        'invoice' => '"Ja, ich will eine Firmenrechnung erhalten"',
    ],

];
