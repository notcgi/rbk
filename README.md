## RBK

# Тестовое задание:
Получение курсов, кроскурсов ЦБ.
Необходимо написать небольшой REST API сервис как минимум с одним методом, который будет возвращать курсы/кроскурсы валют по данным ЦБ в формате JSON.

Требования:
- на входе: дата, код котируемой валюты, код базовой валюты (по-умолчанию RUR)
- получать курсы с cbr.ru
- на выходе: значение курса и разница с предыдущим торговым днем
- кешировать данные cbr.ru


  Реализация подразумевает не только код, но и окружение, которое можно запустить через docker-compose.
  Ограничений на использование фреймворков/сторонних пакетов нет, но желательно использовать либо нативный PHP, либо Symfony.
  Для получения данных ЦБ подойдёт любой способ. Один из самых простых и достоверных - https://www.cbr.ru/DailyInfoWebServ/DailyInfo.asmx?op=GetCursOnDate
  
# Starting
Copy .env and edit it, then build&run containers, generate key
```shell
cp .env.example .env
docker compose up -d
docker compose exec php php artisan key:generate --ansi
```
Openapi doc in `api.yaml`

Tests available at `docker compose exec php php artisan test`
