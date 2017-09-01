<?php

namespace App\Controllers;

class ApiController extends BaseController
{
    /**
     * Главная страница
     */
    public function index()
    {
        view('api/index');
    }

    /**
     * Api пользователей
     */
    public function user()
    {
        header('Content-type: application/json');
        header('Content-Disposition: inline; filename="user.json";');

        $token = check(Request::get('token'));

        if (! $token) {
            echo json_encode(['error'=>'no token']);
            exit();
        }

        $user = User::where('apikey', $token)->first();

        if (! $user) {
            echo json_encode(['error'=>'no user']);
            exit();
        }

        echo json_encode([
            'login'     => $user->login,
            'email'     => $user->email,
            'name'      => $user->name,
            'country'   => $user->country,
            'city'      => $user->city,
            'site'      => $user->site,
            'icq'       => $user->icq,
            'skype'     => $user->skype,
            'gender'    => $user->gender,
            'birthday'  => $user->birthday,
            'newwall'   => $user->newwall,
            'point'     => $user->point,
            'money'     => $user->money,
            'ban'       => $user->ban,
            'allprivat' => userMail($user),
            'newprivat' => $user->newprivat,
            'status'    => userStatus($user),
            'avatar'    => setting('home').'/uploads/avatars/'.$user->avatar,
            'picture'   => setting('home').'/uploads/photos/'.$user->picture,
            'rating'    => $user->rating,
            'lastlogin' => $user->timelastlogin,
        ]);
    }

    /**
     * Api приватных сообщений
     */
    public function private()
    {
        header('Content-type: application/json');
        header('Content-Disposition: inline; filename="private.json";');

        $token = check(Request::get('token'));
        $count = abs(intval(Request::get('count', 10)));

        if (! $token) {
            echo json_encode(['error'=>'no token']);
            exit();
        }

        $user = User::where('apikey', $token)->first();
        if (! $user) {
            echo json_encode(['error'=>'no user']);
            exit();
        }

        $inbox = Inbox::where('user_id', $user->id)
            ->orderBy('created_at')
            ->limit($count)
            ->get();

        if ($inbox->isEmpty()) {
            echo json_encode(['error'=>'no messages']);
            exit();
        }

        $total = $inbox->count();

        $messages = [];
        foreach ($inbox as $data) {

            $data['text'] = str_replace('<img src="/uploads/smiles/', '<img src="'.setting('home').'/uploads/smiles/', bbCode($data['text']));

            $messages[] = [
                'author_id'  => $data->author_id,
                'login'      => $data->getAuthor()->login,
                'text'       => $data['text'],
                'created_at' => $data['created_at'],
            ];
        }

        echo json_encode([
            'total'    => $total,
            'messages' => $messages
        ]);
    }

    /**
     * Api постов темы в форуме
     */
    public function forum()
    {
        header('Content-type: application/json');
        header('Content-Disposition: inline; filename="forum.json";');

        $token = check(Request::get('token'));
        $id    = abs(intval(Request::get('id')));

        if (! $token) {
            echo json_encode(['error'=>'no token']);
            exit();
        }

        $user = User::where('apikey', $token)->first();
        if (! $user) {
            echo json_encode(['error'=>'no user']);
            exit();
        }

        $topic = Topic::find($id);
        if (! $topic) {
            echo json_encode(['error'=>'no topic']);
            exit();
        }

        $posts = Post::where('topic_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        $messages = [];
        foreach ($posts as $post) {

            $post['text'] = str_replace('<img src="/uploads/smiles/', '<img src="'.setting('home').'/uploads/smiles/', bbCode($post['text']));

            $messages[] = [
                'post_id'    => $post->id,
                'user_id'    => $post->user_id,
                'login'      => $post->getUser()->login,
                'text'       => $post->text,
                'rating'     => $post->rating,
                'updated_at' => $post->updated_at,
                'created_at' => $post->created_at,
            ];
        }

        echo json_encode([
            'id'         => $topic->id,
            'forum_id'   => $topic->forum_id,
            'user_id'    => $topic->user_id,
            'login'      => $topic->getUser()->login,
            'title'      => $topic->title,
            'closed'     => $topic->closed,
            'locked'     => $topic->locked,
            'note'       => $topic->note,
            'moderators' => $topic->moderators,
            'updated_at' => $topic->updated_at,
            'created_at' => $topic->created_at,
            'messages'   => $messages,
        ]);
    }
}