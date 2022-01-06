<?php

return [

	/*
	|--------------------------------------------------------------------------
	| JavaScript Plugins
	|--------------------------------------------------------------------------
	|
	*/

    'magnificPopup' => [
        'prev' => 'Предишна (Лява стрелка)',
        'next' => 'Следваща (Дясна стрелка)',
        'close' => 'Затвори (клавиш ESC)',
        'loading' => 'Зарежда се...',
        'ajaxError' => '<a href="%url%">Съдържанието</a> не можа да се зареди.',
    ],

    'multiselect' => [
        'checkAll' => 'Избери всичко',
        'uncheckAll' => 'Изчисти избраните',
        'noneSelected' => 'Изберете стойност',
        'noneSelectedSingle' => 'Изберете стойност',
        'selected' => '# от # избрани',
        'filterLabel' => '',
        'filterPlaceholder' => 'Ключови думи',
    ],

    'fineUploader' => [
        'uploadComplete' => 'Качването завърши успешно.',
        'defaultResponseError' => 'Качването е неуспешно. Причината е неизвестна.',
        'emptyError' => '{file} е празен (с нулев размер).',
        'maxHeightImageError' => 'Снимката е твърде висока.',
        'maxWidthImageError' => 'Снимката е твърде широка.',
        'minHeightImageError' => 'Снимката не е достатъчно висока.',
        'minWidthImageError' => 'Снимката не е достатъчно широка.',
        'minSizeError' => 'Размерът на {file} е твърде малък, минималния позволен размер е {minSizeLimit}.',
        'noFilesError' => 'Няма избрани файлове за качване.',
        'onLeave' => 'Файловете все още се качват, ако напуснете сега, качването ще бъде прекратено.',
        'retryFailTooManyItemsError' => 'Повторният опит се провали - достигнахте лимита за качване.',
        'sizeError' => 'Размерът на {file} е твърде голям, максималния позволен размер е {sizeLimit}.',
        'tooManyItemsError' => 'Опитвате се да качите твърде много файлове ({netItems}). Лимитът е {itemLimit} файла наведнъж.',
        'typeError' => 'Разширението на {file} не е позволено за качване. Позволените разширения са: {extensions}.',
        'unsupportedBrowserIos8Safari' => 'Unrecoverable error - this browser does not permit file uploading of any kind due to serious bugs in iOS8 Safari. Please use iOS8 Chrome until Apple fixes these issues.',
    ],

    'datepicker' => [
        'en' => [
            'closeText' => 'Done',
            'prevText' => 'Prev',
            'nextText' => 'Next',
            'currentText' => 'Today',
            'monthNames' => ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
            'monthNamesShort' => ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            'dayNames' => ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
            'dayNamesShort' => ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
            'dayNamesMin' => ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
            'weekHeader' => 'Wk',
            'dateFormat' => 'dd.mm.yy',
            'firstDay' => 1,
            'isRTL' => false,
            'showMonthAfterYear' => false,
            'yearSuffix' => '',
            'changeMonth' => true,
            'changeYear' => true,
            'showOtherMonths' => true,
        ],
        'bg' => [
            'closeText' => 'затвори',
            'prevText' => 'назад',
            'nextText' => 'напред',
            'currentText' => 'днес',
            'monthNames' => ["Януари","Февруари","Март","Април","Май","Юни","Юли","Август","Септември","Октомври","Ноември","Декември"],
            'monthNamesShort' => ["Яну","Фев","Мар","Апр","Май","Юни","Юли","Авг","Сеп","Окт","Нов","Дек"],
            'dayNames' => ["Неделя","Понеделник","Вторник","Сряда","Четвъртък","Петък","Събота"],
            'dayNamesShort' => ["Нед","Пон","Вто","Сря","Чет","Пет","Съб"],
            'dayNamesMin' => ["Не","По","Вт","Ср","Че","Пе","Съ"],
            'weekHeader' => 'Wk',
            'dateFormat' => 'dd.mm.yy',
            'firstDay' => 1,
            'isRTL' => false,
            'showMonthAfterYear' => false,
            'yearSuffix' => '',
            'changeMonth' => true,
            'changeYear' => true,
            'showOtherMonths' => true,
        ],
    ],

];
