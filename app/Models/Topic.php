<?php

namespace App\Models;

class Topic extends BaseModel
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Возвращает последнее сообщение
     */
    public function lastPost()
    {
        return $this->belongsTo(Post::class, 'last_post_id');
    }

    /**
     * Возвращает раздел форума
     */
    public function forum()
    {
        return $this->belongsTo(Forum::class, 'forum_id');
    }

    /**
     * Возвращает последнее сообщение
     */
    public function getLastPost()
    {
        return $this->lastPost ? $this->lastPost : new Post();
    }

    /**
     * Возвращает модель форума
     */
    public function getForum()
    {
        return $this->forum ? $this->forum : new Forum();
    }

    /**
     * Возвращает иконку в зависимости от статуса
     * @return string иконка топика
     */
    public function getIcon()
    {
        if ($this->closed)
            $icon = 'fa-lock';
        elseif ($this->locked)
            $icon = 'fa-thumb-tack';
        else
            $icon = 'fa-folder-open';
        return $icon;
    }

    /**
     * Генерирует постраничную навигация для форума
     * @param  array  $topic массив данных
     * @return string        сформированный блок
     */
    public function pagination()
    {
        if ($this->posts) {

            $pages = [];
            $link = '/topic/'.$this->id;

            $pg_cnt = ceil($this->posts / setting('forumpost'));

            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $pg_cnt) {
                    $pages[] = [
                        'page' => $i,
                        'title' => $i.' страница',
                        'name' => $i,
                    ];
                }
            }

            if (5 < $pg_cnt) {

                if (6 < $pg_cnt) {
                    $pages[] = array(
                        'separator' => true,
                        'name' => ' ... ',
                    );
                }

                $pages[] = array(
                    'page' => $pg_cnt,
                    'title' => $pg_cnt.' страница',
                    'name' => $pg_cnt,
                );
            }

            view('forum._pagination', compact('pages', 'link'));
        }
    }
}