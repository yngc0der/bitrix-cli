# Установка
Если вы используете Composer не в корне проекта, то необходимо сконфигурировать 
директорию для установки модулей.

Например, если файл **composer.json** находится в директории **/local/**:
```json
...
"extra": {
    "bitrix-dir": "../bitrix"
},
...
```

Модуль можно установить в **/local/modules/**:
```json
...
"extra": {
    "bitrix-dir": "../local"
},
...
```

После этого необходимо выполнить команду `composer require yngc0der/bitrix-cli`, 
либо добавить вручную пакет "**yngc0der/bitrix-cli**" в ваш composer.json
и выполнить регистрацию модуля в Bitrix через админку либо запустив скрипт post-install-cmd 
(`composer run-script post-install-cmd -d {module_install_dir}`).

После установки в директории `{bitrix-dir}` создасться точка входа для консольных команд.

# Использование
Пакет позволяет использовать **symfony/console** в контексте Bitrix Framework. 
Создание команд детально описано в документации (https://symfony.com/doc/current/console.html)

Для получения короткой справки и списка доступных команд выполните в консоли
`php {bitrix-dir}/cli`

Запустить нужную команду можно, выполнив
`php {bitrix-dir}/cli command args`. Например, `php local/cli orm:annotate -c -m main`

Для регистрации собственной команды нужно подписаться на событие **OnCommandsLoad** 
модуля **yngc0der.cli**
```php
\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'yngc0der.cli',
    'OnCommandsLoad',
    function (\Bitrix\Main\Event $event) {
        $result = new \Bitrix\Main\EventResult(
            \Bitrix\Main\EventResult::SUCCESS,
            [
                new \Bitrix\Main\Cli\OrmAnnotateCommand(),  // екземпляр класса Symfony\Component\Console\Command\Command
            ]
        );
        
        return $result;
    }
);
```
