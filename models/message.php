<?php

class Message extends Model
{
    public function save($data, $id = null)
    {
        if(!isset($data['name']) || !isset($data['email']) || !isset($data['message']) || !isset($data['cr_time']) )
        {
            return false;
        }
        $id = (int)$id;
        $name = $this->db->escape($data['name']);
        $email = $this->db->escape($data['email']);
        $message = $this->db->escape($data['message']);
        $cr_time = $this->db->escape($data['cr_time']);
        if(!$id)
        {
            $sql = "
            INSERT INTO messages SET
            name = '{$name}',
            email = '{$email}',
            cr_time = '{$cr_time}',
            messages = '{$message}'
            ";
        }else
            {
                $sql = "
                        UPDATE messages 
                        SET name = '{$name}',
                        email = '{$email}',
                        cr_time = '{$cr_time}',
                        messages = '{$message}'
                        WHERE id = {$id}
                        ";
            }
    return $this->db->query($sql);
    }

    public function getList()
    {
        $sql = "SELECT * FROM messages WHERE 1";
        return $this->db->query($sql);
    }

}