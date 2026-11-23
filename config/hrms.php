<?php

return [
    // Включённые языки интерфейса. Английский подключается добавлением 'en' и файла locales/en.json.
    'locales' => ['ru', 'tg'],

    // Хранение времени в UTC, отображение в этом поясе.
    'display_timezone' => env('HRMS_DISPLAY_TIMEZONE', 'Asia/Dushanbe'),

    'currency' => 'TJS',

    // Версия текста согласия на обработку персональных данных.
    // draft-0: юридический текст не утверждён, требует согласования (законодательство РТ).
    'consent_version' => env('HRMS_CONSENT_VERSION', 'draft-0'),
];
