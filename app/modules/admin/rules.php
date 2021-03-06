<?php
view(setting('themes').'/index');

if (isset($_GET['act'])) {
    $act = check($_GET['act']);
} else {
    $act = 'index';
}

if (isAdmin([101, 102])) {
    //show_title('Правила сайта');

    switch ($action):
    ############################################################################################
    ##                                    Главная страница                                    ##
    ############################################################################################
        case 'index':

            $rules = DB::run() -> queryFetch("SELECT * FROM `rules`;");

            if (!empty($rules)) {
                $rules['text'] = str_replace(['%SITENAME%', '%MAXBAN%'], [setting('title'), round(setting('maxbantime') / 1440)], $rules['text']);

                echo bbCode($rules['text']).'<hr>';

                echo 'Последнее изменение: '.dateFixed($rules['time']).'<br><br>';
            } else {
                showError('Правила сайта еще не установлены!');
            }

            echo '<i class="fa fa-pencil"></i> <a href="/admin/rules?act=edit">Редактировать</a><br>';
        break;

        ############################################################################################
        ##                                   Редактирование                                       ##
        ############################################################################################
        case 'edit':

            $rules = DB::run() -> queryFetch("SELECT * FROM `rules`;");

            echo '<div class="form">';
            echo '<form action="/admin/rules?act=change&amp;uid='.$_SESSION['token'].'" method="post">';

            echo '<textarea id="markItUp" cols="35" rows="20" name="msg">'.$rules['text'].'</textarea><br>';
            echo '<input type="submit" value="Изменить"></form></div><br>';

            echo '<b>Внутренние переменные:</b><br>';
            echo '%SITENAME% - Название сайта<br>';
            echo '%MAXBAN% - Максимальное время бана<br><br>';

            echo '<i class="fa fa-arrow-circle-left"></i> <a href="/admin/rules">Вернуться</a><br>';
        break;

        ############################################################################################
        ##                                     Изменение                                          ##
        ############################################################################################
        case 'change':

            $uid = check($_GET['uid']);
            $msg = check($_POST['msg']);

            if ($uid == $_SESSION['token']) {
                if (utfStrlen($msg) > 0) {
                    $msg = str_replace('&#37;', '%', $msg);

                    DB::run() -> query("REPLACE INTO `rules` (`id`, `text`, `time`) VALUES (?,?,?);", [1, $msg, SITETIME]);

                    setFlash('success', 'Правила успешно изменены!');
                    redirect("/admin/rules");
                } else {
                    showError('Ошибка! Вы не ввели текст с правилами сайта!');
                }
            } else {
                showError('Ошибка! Неверный идентификатор сессии, повторите действие!');
            }

            echo '<i class="fa fa-arrow-circle-left"></i> <a href="/admin/rules?act=edit">Вернуться</a><br>';
            echo '<i class="fa fa-arrow-circle-up"></i> <a href="/admin/rules">К правилам</a><br>';
        break;

    endswitch;

    echo '<i class="fa fa-wrench"></i> <a href="/admin">В админку</a><br>';

} else {
    redirect('/');
}

view(setting('themes').'/foot');
