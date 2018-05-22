<?php

class Mail extends Model
{
    public function getUserList($only_active = false)
    {
        return $this->db->query($sql);
    }


}