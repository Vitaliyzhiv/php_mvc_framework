$(function () {

    // // выводит обьект который показывает данные о текущей локации(url)
    // console.log(location)

    // формируем uri исходя из атрибутов обьекта location, нам нужен url без параметров чтобы
    // динамичесски добавлять класс active  к активной категории 
    // так же обрезаем слеш в конце 
    let currentUri = location.origin + location.pathname.replace(/\/$/, '');
    // проходимся циклом по каждой ссылке в меню
    $('.navbar-nav a').each(function () {
        let href = $(this).attr('href').replace(/\/$/, '');
        // если ссылка совпадает с текущим uri, добавляем класс active
        if (href === currentUri) {
            $(this).addClass('active');
        }
    });


    let iziModalAlertSuccess = $('.iziModal-alert-success');
    let iziModalAlertError = $('.iziModal-alert-error');

    // вызываем модальное окно с уведомлением
    iziModalAlertSuccess.iziModal({
        padding: 20,
        title: 'Success',
        headerColor: '#00897b'
    });
    iziModalAlertError.iziModal(
        {
            padding: 20,
            title: 'Error',
            headerColor: '#e53935'
        }
    );

    // добавляем обработчик для формы  с использованием ajax
    $('.ajax-form').on('submit', function (e) {
        e.preventDefault();
        // получаем форму 
        let form = $(this);
        // кнопка формы
        let submitBtn = form.find('button');
        // текст кнопки 
        let btnText = submitBtn.text();
        // метод
        let method = form.attr('method');
        // если метод опредлен то приводим его к нижнему регистру
        if (method) {
            method = method.toLowerCase();
        }

        // адрес куда отправлять запрос
        let action = form.attr('action') ? form.attr('action') : location.href;
        console.log(action);

        // формируем аякс запрос
        $.ajax({
            url: action,
            type: method === 'post' ? 'post' : 'get',
            data: form.serialize(),
            // блокируем кнопку перед отправкой
            beforeSend: function (
            ) {
                submitBtn.prop('disabled', true);
                submitBtn.text('Отправка...');
            },
            success: function (res) {
                res = JSON.parse(res);
                // выводим alerts
                if (res.status === 'success') {
                    // добавляем контент динамичесски с помощью setContent
                    iziModalAlertSuccess.iziModal('setContent', {
                        content: res.data,
                    });
                    // очищаем форму
                    form.trigger('reset');
                    iziModalAlertSuccess.iziModal('open');
                    // делаем редирект если этот параметр установлен
                    if (res.redirect) {
                        // вызываем обработчик Closure чтобы редирект произошел только при закрытии модального окна
                        $(document).on('closed', iziModalAlertSuccess, function (e) {

                            // делаем редирект
                            location = res.redirect;
                        });
                    }
                } else {
                    // добавляем контент динамичесски с помощью setContent
                    iziModalAlertError.iziModal('setContent', {
                        content: res.data,
                    });
                    iziModalAlertError.iziModal('open');
                }
                submitBtn.prop('disabled', false).text(btnText);
            },
            error: function () {
                alert('Error!');

            },

        })



    })


})