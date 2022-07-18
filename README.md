# Требования

| PHP       | Bitrix (main) |
|-----------|---------------|
| \>= 7.1.3 | \>= 14.00.00  |


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

После этого необходимо выполнить команду `composer require yngc0der/bitrix-cli`,
и выполнить регистрацию модуля в Bitrix через админку.

После установки создасться точка входа для консольных команд - `bitrix/tools/cli`.

# Использование

Пакет позволяет использовать **symfony/console** в контексте Bitrix Framework. 
Создание команд детально описано в документации (https://symfony.com/doc/current/console.html)

Для получения короткой справки и списка доступных команд выполните в консоли
`php bitrix/tools/cli`

Запустить нужную команду можно, выполнив
`php bitrix/tools/cli command args`. Например, `php bitrix/tools/cli orm:annotate -c -m main`

## Регистрация команд через подписку на событие

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
                new \Bitrix\Main\Cli\OrmAnnotateCommand(),  // instance of Symfony\Component\Console\Command\Command
            ]
        );
        
        return $result;
    }
);
```
