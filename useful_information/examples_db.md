```php

 dump(Capsule::insert("insert into  users (name, email, password) values (?, ?, ?)", ['Kirill', 'kIRfR@example.com', '123456']));

        получить все записи с таблицы
        $users = Capsule::table('users')->get();

        получить одну запись с таблицы
        $user = Capsule::table('users')->where('id', 1)->first();
        dump($user->name);

        выборка данных с помощью нашей модели Users которая наследуется от Model
        $users2 = User::all();
        dump($users2);

        проходимся в цикле для теста
        foreach ($users2 as $user) {
            echo $user->name . '<br>';
        }

       пример использования с select и where
       Этот запрос выберет поля name и email из таблицы всех пользователей, у которых id больше 1
        $users = Capsule::table('users')->select('name', 'email')->where('id', '>', 1)->get();



    // включаем логирование запросов
        Capsule::enableQueryLog();
        // использование with помогает в данном случае сразу подгружать данные из связанной таблицы в поле
        // relations
        $user = User::query()->with('phones')->find(3);
        dump($user);

        dump(Capsule::getQueryLog());


        // Примеры запросов к нашему классу db
        // получаем пользователей с помошью экземпляра класса db который был инициализирован в конструкторе класса Application
        $users = db()->query('SELECT * FROM users')->get();
        // получаем пользователей с id>5
        //  Важно!!!  не передавать параметр напрямую для предотвращения sql иньекций
        $users_5 = db()->query('SELECT * FROM users WHERE id > ?', [5])->get();
        // выводим пользователей на экран
        dump($users_5);
        $users_5 = db()->query('SELECT * FROM users WHERE id > ?', [5])->getAssoc();
        // выводим пользователей на экран
        dump($users_5);

        // пример выборки одного пользователя
        $user = db()->query('SELECT * FROM users WHERE id =?', [37])->getOne();
        // выводим пользователя на экран
        dump($user);


        // проверка работы метода FindOrFail
        // возвращает одну запись из БД
        // и делает перенаправление на страницу 404 если запись не найдена
        $user = db()->FindOrFail('users', 'mail@mail.com', 'email');
        dump($user);



        // добавляем телефон для пользователя с id 36
        db()->query("INSERT into phones (user_id, phone) VALUES (?, ?)", [36, '123456789']);
        // получаем последний вставленный id
        dump(db()->getInsertId());


         // проверка методов транзакции класса Database
        try {
            // перед транзакционными запросами важно дать понять базе данных что мы начинаем транзакцию
            db()->beginTransaction();
            // делаем транзакционные запросы
            db()->query('INSERT INTO phones (user_id, phone) VALUES (?, ?)', [10, '123']);
            db()->query('INSERT INTO users (name, email2, password) VALUES (?, ?, ?)', ['Vlad', 'FgXbO@example.com', '123']);
            // если все запросы прошли успешно, то мы выполняем коммит транзакции
            db()->commit();
        } catch(\PDOException $e) {
            // откатываем транзакцию если были ошибки
            db()->rollback();
            dump($e);
        }


```
