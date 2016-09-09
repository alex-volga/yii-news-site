На проект затрачено 16 часов

Установка и настройка:
1. скопировать файлы или клонировать репозиторий в web-директорию
2. прописать настройки подключения к базе данных в basic/config/db.php
3. прописать настройки для отправки почты через потовый сервер (если требуется). По умолчанию вся почта сохраняется в файлы в директории basic/runtime/mail
4. выполнить миграции:
    1. yii migrate --migrationPath=vendor/webvimark/module-user-management/migrations/
    2. yii migrate
5. для рассылки чере email настроить в кроне выполнение команды yii email/notify . За 1 запуск команда рассылает до 100 писем. Это надо учесть для выбора правильного интервала рассылки.
6. доступ к аккаунту администратора: логин superadmin, пароль superadmin

Добавление нового типа уведомления:
1. придумать название типа уведомления длинной до 30 символов.
2. создать роль с таким названием
3. добавить это название в списки \app\models\Notifications::getAllList() и \app\models\Notifications::getAllNames()
4. в /notifications/название_типа_уведомления создать шаблоны аналогично каталогам /notifications/EmailNotify или /notifications/SiteNotify
5. после этого пользователи могут подписываться на этот тип уведомления и уведомления будут добавляться в очередь
6. для доставки уведомления можно создать команду, которая будет обрабатывать очередь этого типа уведомлений (например, как в commands/EmailController::actionNotify) или компонент (например, как в components/SiteNotification). Что бы получить список сообщений для определенного типа уведомления можно воспользоваться \app\models\Notifications::getMessagesList($type), где $type - название типа уведомления. После успешной отправки уведомления, его нужно удалить из очереди.