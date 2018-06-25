<?php
defined('BASEPATH') or exit('No direct script access allowed');

class _Hook_Line extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Line', 'Db_mdl']);
    }

    public function index()
    {
        // Webhookで受け取った情報
        $webhook = $this->Line->webhook();
        if (!isset($webhook->type)) {
            return;
        }
        switch ($webhook->type) {
            // 友だち追加
            case 'follow':
                $this->Db_mdl->add_user($webhook->userid);
                $this->Db_mdl->set_action($webhook->userid, 'start');
                // 返信メッセージ
                $messages = [];
                $messages[] = [
                    'type' => 'text',
                    'text' => '友だち登録ありがとう！' . PHP_EOL . '「あとでLINEをみる」はリンクやテキストを手軽にWebから自分のLINEへ送ることができます。',
                ];
                $messages[] = [
                    'type' => 'text',
                    'text' => 'IDを設定するだけでお使いいただけます。',
                ];
                $messages[] = [
                    'type' => 'text',
                    'text' => '送信する際のIDは何にしますか？'.PHP_EOL.'半角英数字と_(アンダーバー)のみ',
                ];
                // リプライする
                $this->Line->reply($webhook->reply_token, $messages);

                break;

            // 友だちブロック
            case 'unfollow':
                $this->Db_mdl->del_user($webhook->userid);
                break;

            // テキストメッセージ
            case 'message':
                $user = $this->Db_mdl->get_lineuser($webhook->userid); //DBからuser情報取得->actionカラム
                switch ($user->action) {
                    case 'start':
                        // 登録: 送られてきたメッセージはID
                        $messages = $this->setup($webhook->userid, $webhook->message_text);
                        $this->Line->reply($webhook->reply_token, $messages);
                        break;
                    case 'set_id':
                        // 登録: 送られてきたメッセージはID
                        $messages = $this->setup($webhook->userid, $webhook->message_text, true);
                        $this->Line->reply($webhook->reply_token, $messages);
                        break;
                    default:
                        switch ($webhook->message_text) {
                            case 'ID変更':
                                $this->Db_mdl->set_action($webhook->userid, 'set_id');
                                // 返信メッセージ
                                $messages[] = [
                                    'type' => 'text',
                                    'text' => '新しく設定したいIDを教えてください。'.PHP_EOL.'(半角英数字と_(アンダーバー)のみ)',
                                ];
                                // リプライする
                                $this->Line->reply($webhook->reply_token, $messages);
                                break;

                            default:
                                # code...
                                break;
                        }
                        // 返信メッセージ
                        $messages[] = [
                            'type' => 'text',
                            'text' => $webhook->message_text,
                            // 'text' =>$user->action
                        ];
                        // リプライする
                        $this->Line->reply($webhook->reply_token, $messages);
                        break;
                }

                break;

            default:
                # code...
                break;
        }

    }

    private function setup($line_userid, $id, $changeid = false)
    {
        if(!preg_match('/[0-9a-zA-Z\\_]+$/',$id)){
            // 使用可能文字チェック
            $messages = [];
            $messages[] = [
                'type' => 'text',
                'text' => '半角英数字と_(アンダーバー)のみお使いいただけます。',
            ];
            return $messages;
        }
        if ($this->Db_mdl->get_user($id) !== null) {
            // 既に存在するID
            $messages = [];
            $messages[] = [
                'type' => 'text',
                'text' => $id . 'は既に使用されているため、登録できません。',
            ];
            $messages[] = [
                'type' => 'text',
                'text' => 'もう一度、ご希望のIDを教えてください',
            ];
            return $messages;
        } else {
            // 登録
            $user = $this->Db_mdl->set_id($line_userid, $id);
            if ($user !== false) {
                $this->Db_mdl->set_action($line_userid, null);
                $messages = [];
                if ($changeid) {
                    $messages[] = [
                        'type' => 'text',
                        'text' => 'IDを' . $id . 'に変更しました',
                    ];

                } else {
                    $messages[] = [
                        'type' => 'text',
                        'text' => 'IDは' . $id . 'で登録しました',
                    ];
                    $messages[] = [
                        'type' => 'text',
                        'text' => 'まずは試してみてください！' . PHP_EOL . 'https://linede.herokuapp.com/',
                    ];
                }
                return $messages;
            }
        }
    }
}
