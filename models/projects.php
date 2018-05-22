<?php
class Projects extends Model
{
    public function getList($visible = false)
    {
        $sql = "SELECT *,substr(description,1,197)shortDes from projects";
        if($visible)
        {
            $sql .=" AND status = visible";
        }
        return $this->db->query($sql);
    }

    public function getListAs($uid)
    {
        $uid = (int)$uid;
        $sql = "SELECT * FROM tikets INNER JOIN tikets_dep WHERE tikets.id = tikets_dep.tid AND tikets_dep.uid = '$uid'";

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
        $sql = "SELECT * FROM projects WHERE prj_id = '{$id}' LIMIT 1";
        //$result = $this->db->query($sql);
        return $this->db->query($sql);
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
        $status = $this->db->escape($data['status']);
        $priority = $this->db->escape($data['priority']);
        $creator_id = $this->db->escape(Session::get('id'));
        $est_time = $this->db->escape($data['est_time']);
        $content = $this->db->escape($data['content']);
        $visible = isset($data['visible']) ? 1 : 0;

        if(!$id)
        {
            $sql = "INSERT INTO tikets SET
            status = '{$status}',
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

    public function saveDepUser($tid, $uid)
    {

        if(!isset($tid) || !isset($uid))
        {
            return false;
            //print_r("Some error with oid,uid");
        }
        $tid = (int)$tid;
        $uid = (int)$uid;

        $sql = "INSERT INTO tikets_dep SET
            tid = '{$tid}',
            uid = '{$uid}'
            ";

        return $this->db->query($sql);
    }

    public function getUserDep($tid)
    {
        $tid = (int)$tid;

        $sql = "SELECT `login`,`uid`,`avatar`,`tid` FROM users INNER JOIN tikets_dep WHERE users.id = tikets_dep.uid AND tikets_dep.tid = '$tid'";

        return $this->db->query($sql);
    }

    public function getUserDepId($tid,$uid)
    {
        $tid = (int)$tid;
        $uid = (int)$uid;
        $sql = "SELECT `login`,`uid`,`avatar` FROM users INNER JOIN tikets_dep WHERE users.id = tikets_dep.uid AND tikets_dep.tid = '$tid' AND tikets_dep.uid = '$uid'";

        return $this->db->query($sql);
    }

    public function deliteDep($uid,$tid)
    {
        $tid = (int)$tid;
        $uid = (int)$uid;
        $sql = "DELETE FROM tikets_dep WHERE uid = '$uid' AND tid = '$tid';";

        return $this->db->query($sql);
    }


    public function getUserDepById($uid,$tid)
    {

        $tid = (int)$tid;
        $uid = (int)$uid;
        $sql = "SELECT `uid`,`tid` FROM tikets_dep WHERE uid = '$uid' and tid = '$tid'";

        return $this->db->query($sql);
    }


    public function getUserList($only_active = false)
    {
        $sql = "SELECT `id`,`login`,`avatar` FROM users";
        if($only_active)
        {
            $sql .=" AND is_active = 1";
        }
        return $this->db->query($sql);
    }

    public function delete($id)
    {
        $id = (int)$id;
        $sql = "INSERT INTO `mvc`.`tikets_del` SELECT * FROM `mvc`.`tikets` Where id = {$id}";
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



}