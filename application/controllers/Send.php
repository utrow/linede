<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Send extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Line', 'Db_mdl']);
    }

    public function index()
    {
        $output = ['code' => -1];

        $raw = file_get_contents('php://input');
        $post = json_decode($raw, true);

        $text = $post['text'];
        $id = $post['user'];
        // 必須項目チェック
        if (isset($id) && isset($text)) {
            // id存在チェック
            $user = $this->Db_mdl->get_user($id);
            if (!is_null($user)) {
                // push message.
                $messages = [];
                $messages[] = [
                    'type' => 'text',
                    'text' => $text,
                ];
                $res =$this->Line->push($user->line_userid, $messages);
                if(!$res===false){
                    // エラーレスポンスが空
                    $output['code'] = 1;
                }
                else{
                    $output['code'] = 0;
                }
            } else {
                $output['code'] = -2;
            }
        }
        // Output json. 送信結果
        echo json_encode($output);
    }

}
