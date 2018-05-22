<?php
class Tiket extends Model
{
    public function getList($visible = false)
    {
        $sql = "SELECT * FROM tikets WHERE 1";
        if($visible)
        {
            $sql .=" AND status = visible";
        }
        return $this->db->query($sql);
    }
    public function getListAs($uid)
    {
        $uid = (int)$uid;
        $sql = "SELECT tikets.id,tikets.title,tikets.status,tikets.priority,tikets.content,tikets.creator_id,users.shortName,tikets.cr_time,tikets.est_time FROM tikets INNER JOIN tiketsUsers,users WHERE tikets.id = tiketsUsers.tid AND tiketsUsers.uid = '$uid' and tikets.creator_id = users.id";

        return $this->db->query($sql);
    }

    public function getListCurUser($id)
    {
        //$sql = "SELECT * FROM tikets WHERE 1";
        if($id)
        {
            $sql = "SELECT * FROM tikets WHERE creator_id = '$id'";
            //$sql .=" AND status = visible";
        }
        return $this->db->query($sql);
    }

    public function getListDel($visible = false)
    {
        $sql = "SELECT * FROM tikets_del WHERE 1";
        if($visible)
        {
            $sql .=" AND status = visible";
        }
        return $this->db->query($sql);
    }

    public function getById($id)
    {
        $id = (int)$id;
        $sql = "SELECT * FROM tikets WHERE id = '{$id}' LIMIT 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    public function getDelitedById($id)
    {
        $id = (int)$id;
        $sql = "SELECT * FROM tikets_del WHERE id = '{$id}' LIMIT 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    public function expire()
    {
        $sql = "UPDATE tikets SET status = '7' WHERE DATE(est_time) < DATE(CURRENT_TIMESTAMP) AND status != 'closed'";
        $this->db->query($sql);
    }


    public function save($data, $id = null)
    {

        if(!isset($data['title']) || !isset($data['priority']) || !isset($data['content']) || !isset($data['status']) )
        {
            return false;
        }
        $id = (int)$id;
        $title = $this->db->escape($data['title']);
        $parentTid = $this->db->escape($data['parentTid'] ? $data['parentTid'] : 'null');
        $status = $this->db->escape($data['status']);
        $priority = $this->db->escape($data['priority']);
        $creator_id = $this->db->escape(Session::get('id'));
        $est_time = $this->db->escape($data['est_time']);
        $content = $this->db->escape($data['content']);
        $visible = isset($data['visible']) ? 1 : 0;

        if(!$id)
        {
            $sql = "INSERT INTO tikets SET
            title = '{$title}',
            content = '{$content}',
            priority = '{$priority}',
            creator_id = '{$creator_id}',
            est_time = '{$est_time}',
            visible = '{$visible}'
            ";
        }else
        {
            $sql = "UPDATE tikets SET 
                        status = '{$status}',
                        title = '{$title}',
                        parentTid = {$parentTid},
                        content = '{$content}',
                        priority = '{$priority}',
                        creator_id = '{$creator_id}',
                        est_time = '{$est_time}',
                        visible = '{$visible}'
                        WHERE id = {$id}
                        ";
        }
        return $this->db->query($sql);
    }

    public function saveView($data, $id)
    {

        if(isset($data['title']) || isset($data['est_time']) || isset($data['content']) || isset($data['visible']) )
        {
            return false;
        }
        $id = (int)$id;
        $parentTid = $this->db->escape($data['parentTid'] ? $data['parentTid'] : 'null');
        $status = $this->db->escape($data['status']);
        //$priority = $this->db->escape($data['priority']);



        $sql = "UPDATE tikets SET 
                        status = '{$status}',
                        parentTid = {$parentTid}
                        WHERE id = {$id}
                        ";

        return $this->db->query($sql);
    }

    public function saveAdd($data)
    {

        if(!isset($data['title']) || !isset($data['priority']) || !isset($data['content']) || !isset($data['est_time']) )
        {
            Session::setFlash('<div class="alert alert-danger">Помилка збереженнф тікету!Один зпараметрів:"Назва","Зміст","Пріорітет","Статус","EST TIME".Не вказаний.</div>');
            return false;

        }
        //$id = (int)$id;
        $title = $this->db->escape($data['title']);
        $parentTid = $this->db->escape($data['parentTid'] ? $data['parentTid'] : 'null');
        $priority = $this->db->escape($data['priority']);
        $creator_id = $this->db->escape(Session::get('id'));
        $est_time = $this->db->escape($data['est_time']);
        $content = $this->db->escape($data['content']);
        $visible = '1';

            $sql = "INSERT INTO tikets SET
            title = '{$title}',
            parentTid = {$parentTid},
            content = '{$content}',
            priority = '{$priority}',
            creator_id = '{$creator_id}',
            est_time = '{$est_time}',
            visible = '{$visible}'
            ";

        return $this->db->query($sql);
    }

    public function getByTitle($title)
    {

        $sql = "SELECT id FROM tikets WHERE title ='{$title}'";

        return $this->db->query($sql);
    }


    public function saveDepUser($tid, $uid)
    {

        if(!isset($tid) || !isset($uid))
        {
            return false;
            //print_r("Some error with oid,uid");
        }
        $tid = (int)$tid;
        $uid = (int)$uid;

        $sql = "INSERT INTO tiketsUsers SET
            tid = '{$tid}',
            uid = '{$uid}'
            ";

        return $this->db->query($sql);
    }
    public function delUserDepFor($string,$tid)
    {
        $tid = (int)$tid;
        if($string == null)
        {
            $sql="delete from tiketsUsers where tid = '$tid'";
        }else
            {
                $sql="delete from tiketsUsers where uid not in ({$string}) and tid = '$tid'";
            }

        //echo $sql.'<br>';
        return $this->db->query($sql);
    }

    public function getUserDep($tid)
    {
        $tid = (int)$tid;

        $sql = "SELECT `login`,`uid`,`avatar`,`tid`,`shortName` FROM users INNER JOIN tiketsUsers WHERE users.id = tiketsUsers.uid AND tiketsUsers.tid = '$tid'";

        return $this->db->query($sql);
    }

    public function getUserDepId($tid,$uid)
    {
        $tid = (int)$tid;
        $uid = (int)$uid;
        $sql = "SELECT `login`,`uid`,`avatar` FROM users INNER JOIN tiketsUsers WHERE users.id = tiketsUsers.uid AND tiketsUsers.tid = '$tid' AND tiketsUsers.uid = '$uid'";

        return $this->db->query($sql);
    }

    public function deliteDep($uid,$tid)
    {
        $tid = (int)$tid;
        $uid = (int)$uid;

        $sql = "DELETE FROM tiketsUsers WHERE uid = '$uid' AND tid = '$tid'";

        return $this->db->query($sql);
    }

    public function deliteDep2($data)
    {
        $sql = "DELETE FROM tiketsUsers WHERE uid = '$uid' AND tid = '$tid';";

        return $this->db->query($sql);
    }


    public function getUserDepById($uid,$tid)
    {

        $tid = (int)$tid;
        $uid = (int)$uid;
        $sql = "SELECT `uid`,`tid` FROM tiketsUsers WHERE uid = '$uid' and tid = '$tid'";

        return $this->db->query($sql);
    }


    public function getUserList($only_active = false)
    {
        $sql = "SELECT `id`,`login`,`shortName` FROM users";
        if($only_active)
        {
            $sql .=" AND is_active = 1";
        }
        return $this->db->query($sql);
    }

    public function getUserById($uid)
    {
        $sql = "SELECT `id`,`login`,`shortName`,`avatar` FROM users where id = {$uid}";

        return $this->db->query($sql);
    }

    public function delete($id)
    {
        $id = (int)$id;
        $sql = "INSERT INTO `tikets_del` SELECT * FROM `tikets` Where id = '{$id}'";
        $sql2 = "DELETE FROM tikets WHERE id = {$id} LIMIT 1";
        $this->db->query($sql);
        $this->db->query($sql2);
        return 'done!';
    }

    public function recover($id)
    {
        $id = (int)$id;
        $sql = "INSERT INTO `mvc`.`tikets` SELECT * FROM `mvc`.`tikets_del` Where id = {$id}";
        $sql2 = "DELETE FROM tikets_del WHERE id = {$id}";
        $this->db->query($sql);
        $this->db->query($sql2);
        return;
    }

    public function addTask($tid)
    {
        $tid = (int)$tid;
        $sql = "INSERT INTO tikets_tasks SET
            tid = '{$tid}'
            ";

        return $this->db->query($sql);
    }

    public function getTasks($tid)
    {
        $tid = (int)$tid;

        $sql = "SELECT `task_id`,`task_title`,`task_content`,`tid` FROM tikets_tasks INNER JOIN tikets WHERE tikets.id = tikets_tasks.tid AND tikets.id = '$tid'";

        return $this->db->query($sql);
    }

    public function getTasksById($ts_id)
    {
        $ts_id = (int)$ts_id;

        $sql = "SELECT * FROM tikets_tasks WHERE task_id = '$ts_id' LIMIT 1";

        return $this->db->query($sql);
    }

    public function saveTask($data)
    {
        if(!isset($data['title']) || !isset($data['content'])|| !isset($data['tid'])|| !isset($data['id']) )
        {
            print_r($data);
            return false;

        }

        $task_title = $this->db->escape($data['title']);
        $task_content = $this->db->escape($data['content']);
        $tid = $this->db->escape($data['tid']);
        $task_id = $this->db->escape($data['id']);
        $st = isset($data['st']) ? 1 : 0;
        $task_id = (int)$task_id;

        if($task_id)
        {
            $sql = "UPDATE tikets_tasks SET
            tid = '{$tid}',
            task_title = '{$task_title}',
            task_content = '{$task_content}',
            st = '{$st}' 
            WHERE task_id = '{$task_id}'
            ";
        }
        return $this->db->query($sql);

    }

    public function delTask($id)
    {
        $id = (int)$id;
        //$sql = "INSERT INTO `mvc`.`tikets_del` SELECT * FROM `mvc`.`tikets` Where id = {$id}";
        $sql = "DELETE FROM tikets_tasks WHERE task_id = {$id} LIMIT 1";
        $this->db->query($sql);
        //$this->db->query($sql2);
        return 'done!';
    }
    public function saveComm($data)
    {
        //$task_title = $this->db->escape($data['title']);
        $comment = $this->db->escape($data['comment']);
        $tid = $this->db->escape($data['tid']);
        $uid = $this->db->escape($data['uid']);
        $ct = $this->db->escape($data['ct']);
        $parent = $this->db->escape($data['parent']);

        $sql = "INSERT INTO tikets SET
            comment = '{$comment}',
            tid = '{$tid}',
            uid = '{$uid}',
            ct = '{$ct}',
            parent = '{$parent}'
            ";
        echo '<font color="green">Комментарий добавлен и ожидает проверки!</font>';
        return $this->db->query($sql);
    }

    public function listComm($tid)
    {
        $sql = "select * from tikets_com where tid like '{$tid}' and parent is null order by id";
        return $this->db->query($sql);
    }
    public function listCommParent($parent)
    {
        $sql = "select * from tikets_com where id = '{$parent}' order by id";
        return $this->db->query($sql);
    }

    public function getListForTiket()
    {
        $sql = "SELECT `id`,`parentTid`,`title` FROM tikets WHERE status != 4";

            $sql .=" AND status != 4";

        return $this->db->query($sql);
    }
    public function allChildTikets($tid)
    {
        $sql = "SELECT tikets.`id`,`parentTid`,`status`,`priority`,`shortName`,`avatar`,`title`,`est_time`,`creator_id`,`shortName`,(users.`id`)uid FROM tikets INNER JOIN users WHERE tikets.creator_id = users.id AND tikets.status != 4 and
tikets.parentTid = {$tid}";
        return $this->db->query($sql);
    }
    public function assignedTo($uid,$tid)
    {
        $sql = "SELECT * FROM users INNER JOIN  WHERE tikets.creator_id = users.id and tikets.parentTid = {$tid}";

        return $this->db->query($sql);
    }


}