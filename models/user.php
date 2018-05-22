<?php

class User extends Model
{
    public function getUserList($only_active = false)
    {
        $sql = "SELECT * FROM users WHERE 1";
        if($only_active)
        {
            $sql .=" AND is_active = 1";
        }
        return $this->db->query($sql);
    }

    public function getUserListForm($only_active = false)
    {
        $sql = "SELECT `id`,`login`,`avatar` FROM users WHERE 1";
        if($only_active)
        {
            $sql .=" AND is_active = 1";
        }
        return $this->db->query($sql);
    }

    public function getByLogin($login)
     {
         $login = $this->db->escape($login);
         $sql = "SELECT * FROM users WHERE login = '{$login}' LIMIT 1";
         $result = $this->db->query($sql);
         return isset($result[0]) ? $result[0] : null;
     }

    public function getByUserId($id)
    {
        $id = (int)$id;
        $sql = "SELECT * FROM users WHERE id = '{$id}' LIMIT 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    public function saveUser($data, $id = null)
    {
        if(!isset($data['login']) || !isset($data['email']) || !isset($data['role']) || !isset($data['password']) || !isset($data['manager_id']) )
        {
            return false;
        }
        $id = (int)$id;
        $login = $this->db->escape($data['login']);
        $email = $this->db->escape($data['email']);
        $role = $this->db->escape($data['role']);
        $password = $this->db->escape($data['password']);
        $manager_id = $this->db->escape($data['manager_id']);
        $is_active = isset($data['is_active']) ? 1 : 0;
        $hash = md5(Config::get('salt').$password);

        if(!$id)
        {
            $sql = "
            INSERT INTO users SET
            login = '{$login}',
            email = '{$email}',
            role = '{$role}',
            password = '{$hash}',
            manager_id = '{$manager_id}',
            is_active = '{$is_active}'
            ";
        }else
        {
            $sql = "
                        UPDATE users SET 
                        login = '{$login}',
                        email = '{$email}',
                        role = '{$role}',
                        password = '{$hash}',
                        manager_id = '{$manager_id}',
                        is_active = '{$is_active}'
                        WHERE id = {$id}
                        ";
        }
        return $this->db->query($sql);
    }

    public function updateUser($data, $id = null)
    {
        if(!isset($data['email']) || !isset($data['fname']) || !isset($data['lname']) || !isset($data['sname']) || !isset($data['phone']) || !isset($data['shortName']))
        {
            return false;
        }
        $id = (int)$id;
        //$login = $this->db->escape($data['login']);
        $fname = $this->db->escape($data['fname']);
        $sname = $this->db->escape($data['sname']);
        $lname = $this->db->escape($data['lname']);
        $shortName = $this->db->escape($data['shortName']);
        $phone = $this->db->escape($data['phone']);
        $int_phone = $this->db->escape($data['int_phone']);
        $email = $this->db->escape($data['email']);
        //$password = $this->db->escape($data['password']);

        if (isset($data['avatar'])){$ava = $this->db->escape($data['avatar']);}
        //$hash = md5(Config::get('salt').$password);

        $sql = "UPDATE users SET 
                        email = '{$email}',
                        sname = '{$sname}',
                        lname = '{$lname}',
                        shortName = '{$shortName}',
                        phone = '{$phone}',
                        int_phone = '{$int_phone}',
                        fname = '{$fname}'";
        //if($password){$sql .=", password = '{$hash}'";}
        if(isset($ava)){$sql .=", avatar = '{$ava}'";}
        $sql .= " WHERE id = {$id}";
        //print_r($sql);
        return $this->db->query($sql);
    }
    public function updateAvatar($avatar)
    {
        if(!isset($avatar))
        {
        return false;
        echo "ERROR no avstar";
        }
        $id = (int)Session::get('id');
        $ava = $this->db->escape($avatar);
        Session::set('avatar',$avatar);

        $sql = "UPDATE users SET 
                                avatar = '{$ava}'
                                WHERE id = {$id}
                                ";

        return $this->db->query($sql);
    }
    public function addUserDomain($data)
    {
        if(!isset($data['login']) || !isset($data['email']) || !isset($data['fname']) || !isset($data['lname']) || !isset($data['sname']) || !isset($data['phone']) || !isset($data['shortName']))
        {
            return false;
        }

        $login = $this->db->escape($data['login']);
        $fname = $this->db->escape($data['fname']);
        $sname = $this->db->escape($data['sname']);
        $lname = $this->db->escape($data['lname']);
        $shortName = $this->db->escape($data['shortName']);
        $phone = $this->db->escape($data['phone']);
        $int_phone = $this->db->escape($data['int_phone']);
        $email = $this->db->escape($data['email']);
        $password = $this->db->escape($data['password']);
        $ava = $this->db->escape($data['avatar']);
        //$hash = md5(Config::get('salt').'Qwertyu1');

        $sql = "INSERT INTO users SET 
                        login = '{$login}',
                        email = '{$email}',
                        role = '2',
                        fname = '{$fname}',
                        sname = '{$sname}',
                        lname = '{$lname}',
                        shortName = '{$shortName}',
                        phone = '{$phone}',
                        int_phone = '{$int_phone}',
                        avatar = '{$ava}',
                        password = '{$password}'
                        ";
        //if($password){$sql .=", password = '{$hash}'";}
        //$sql .= " WHERE id = {$id}";
        //print_r($sql);
        return $this->db->query($sql);
    }


    public function deleteUser($id)
    {
        $id = (int)$id;
        $sql = "DELETE FROM users WHERE id = {$id}";
        return $this->db->query($sql);
    }

}