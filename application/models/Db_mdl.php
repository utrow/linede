<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Db_mdl extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    /* ----------------------------------------------------
    //    Image Messages
    ----------------------------------------------------*/
    public function insert_image_messages($id = null, $userid = null)
    {
        if (isset($id) && isset($userid)) {
            $this->db->insert('bedunder_image_messages', [
                'message_id' => $id,
                'userid' => $userid,
            ]);
        }
    }
    public function get_image_messages($userid = null, $limit = null, $top = null)
    {
        if (isset($userid)) {
            $this->db->from('bedunder_image_messages');
            $this->db->where('userid', $userid);
            $this->db->order_by('message_id', "asc");
            if (isset($limit)) {
                // LIMIT
            }
            if (isset($top)) {
                // TOP
            }
            $images = $this->db->get()->result();
            return $images;
        }
    }
    public function count_image_messages($userid = null)
    {
        if (isset($userid)) {
            $this->db->from('bedunder_image_messages');
            $this->db->where('userid', $userid);

            return 0;
        }
        return 0;
    }

    public function exist_image($userid, $id)
    {
        if (isset($id) && isset($userid)) {
            $this->db->from('bedunder_image_messages');
            $this->db->where('userid', $userid);
            $this->db->where('message_id', $id);
            $row = $this->db->get()->result();
            if (!empty($row)) {return true;}
            return false;
        }
    }
    /* ----------------------------------------------------
    //    users
    ----------------------------------------------------*/
/*
id //index
line_userid
statu
 */

    // public function add_user($id,$line_userid, $statu = null)
    // {
    //     if (isset($id) && isset($line_userid)) {
    //         return $this->db->insert('users', [
    //             'id' => $id,
    //             'line_userid' => $line_userid,
    //             'statu'=>$statu
    //         ]);
    //     }
    // }
    public function add_user($line_userid)
    {
        return $this->db->insert('users', [
            'line_userid' => $line_userid,
        ]);
    }

    public function get_user($id)
    {
        $this->db->from('users');
        $this->db->where('id', $id);
        $user = $this->db->get()->row();
        return $user;

    }
    public function get_lineuser($line_userid)
    {
        $this->db->from('users');
        $this->db->where('line_userid', $line_userid);
        $user = $this->db->get()->row();
        return $user;

    }
    public function set_id($line_userid, $id)
    {
        $data = ['id' => $id];
        $this->db->update('users', $data, ['line_userid' => $line_userid]);
    }

    public function set_action($line_userid, $action)
    {
        $data = ['action' => $action];
        $this->db->update('users', $data, ['line_userid' => $line_userid]);
    }
    public function del_user($line_userid)
    {
        $this->db->delete('users',['line_userid'=>$line_userid]);
    }
    /* ----------------------------------------------------
    //
    ----------------------------------------------------*/
    public function insert_line_send_requests($id = null, $userid = null)
    {
        if (isset($id) && isset($userid)) {
            $this->db->insert('bedunder_line_send_requests', [
                'message_id' => $id,
                'user_id' => $userid,
                'ts' => time(),
            ]);
        }
    }
    public function get_line_send_requests($userid = null, $limit = null, $top = null)
    {
        if (isset($userid)) {
            $this->db->from('bedunder_line_send_requests');
            $this->db->where('user_id', $userid);
            $this->db->order_by('ts', "asc");
            if (isset($limit)) {
                // LIMIT
            }
            $images = $this->db->get()->result();
            return $images;
        }
    }

    public function count_line_send_requests($userid = null)
    {
        if (isset($userid)) {
            $this->db->from('bedunder_line_send_requests');
            $this->db->where('user_id', $userid);
            return 0;
        }
        return 0;
    }
}
