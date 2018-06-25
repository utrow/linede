<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Line extends CI_Model
{
    private $access_token = '';
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Db_mdl']);
    }

    public function webhook()
    {
        // receive json data from line webhook
        $raw = file_get_contents('php://input');
        $receive = json_decode($raw, true);

        file_put_contents("receive.json", var_export($receive, true));
        // parse received events
        $event = $receive['events'][0];

        return (object) [
            'event' => $event,
            'type' => $event['type'],
            'reply_token' => $event['replyToken'],
            'message_text' => $event['message']['text'],
            'userid' => $event['source']['userId'],
            'id' => $event['message']['id'],
        ];

    }

    public function reply($reply_token, $messages)
    {

        $url = 'https://api.line.me/v2/bot/message/reply';
        // build request headers
        {
            $headers = ['Content-Type: application/json',
                'Authorization: Bearer ' . $this->access_token];
        }
        $body = json_encode(array('replyToken' => $reply_token,
            'messages' => $messages));
        // post json with curl
        $options = [CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $body];
        $deb = '';
        $deb .= var_export($options, true);
        if (!empty($messages)) {
            $curl = curl_init();
            curl_setopt_array($curl, $options);
            $res = curl_exec($curl);
            curl_close($curl);
            $deb .= $res;

        } else {
            $deb .= 'No run curl';
        }
        file_put_contents("res.json", $deb);

    }
    public function api_profile($id = null)
    {
        if (!isset($id)) {
            return null;
        }
        $url = 'https://api.line.me/v2/bot/profile/' . $id;

        // build request headers
        $headers = ['Content-Type: application/json',
            'Authorization: Bearer ' . $this->access_token];
        // post json with curl
        $options = [CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers];

        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $deb .= curl_exec($curl);
        curl_close($curl);

        return $deb;

    }
    public function push($user, $messages)
    {
        $url = 'https://api.line.me/v2/bot/message/push';
        $body = json_encode(array('to' => $user,
            'messages' => $messages));
        // build request headers
        $headers = ['Content-Type: application/json',
            'Authorization: Bearer ' . $this->access_token];

        // post json with curl
        $options = [CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $body];
        $deb = '';
        $deb .= var_export($options, true);
        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        curl_close($curl);

        $deb .= $response;
        file_put_contents("res.json", $deb);

        return json_decode($response);

    }

    public function api_content($id = null)
    {
        if (!isset($id)) {
            return null;
        }
        $url = 'https://api.line.me/v2/bot/message/' . $id . '/content';

        // build request headers
        $headers = ['Content-Type: application/json',
            'Authorization: Bearer ' . $access_token];

        // post json with curl
        $options = [CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers];

        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response);

    }

}
