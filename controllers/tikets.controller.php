<?php
class TiketsController extends Controller
{
    public function __construct($data = array() )
    {
        parent::__construct($data);
        $this->model = new Tiket();

    }

    public function index()
    {
        if( Session::get('role')  == 'admin' || Session::get('role')  == 'user' || Session::get('role')  == 'owner') {
            //$this->data['pages'] = $this->model->getList();
            Router::redirect('/'.Session::get('role').'/tikets/');
        }
        else
        {
            Router::redirect('/login/');
        }


    }

    /*
    ADMIN Section
    */

//INDEX
//
    public function admin_index()
    {
        $this->data['ob'] = $this->model;
        $this->model->expire();
        //Get my tiket list
        //$this->data['tiket'] = $this->model->getList();
        $this->data['tiket'] = $this->model->getListCurUser(Session::get('id'));
        //Get assigned to my tikets list
        $this->data['tiket_as'] = $this->model->getListAs(Session::get('id'));
        //Get delited tikets list
        $this->data['tiket_del'] = $this->model->getListDel();
    }

    public function admin_view()
    {
        $params = App::getRouter()->getParams();

        if (isset($params[0]) )
        {
            $id = strtolower($params[0]);
            $this->data['tiket'] = $this->model->getById($id);
        }
    }
    public function admin_add()
    {
        // $this->model = new User();
        // $this->data['users'] = $this->model->getUserList();

        if ($_POST) {

            $result = $this->model->save($_POST);
            if ($result) {
                Session::setFlash('Page was saved.');
                print_r('NO ERROR');
            } else {
                print_r('ERROR');
                print_r($_POST);
                Session::setFlash('Error.');
            }
            Router::redirect('/' . Session::get('role') . '/tikets/');
        }
    }

    public function admin_edit()
        {

            $params = App::getRouter()->getParams();
            $tid = strtolower($params[0]);



            if (isset($params[0]) )
            {
                $this->data['users_dep'] = $this->model->getUserDep($tid);
            }

            $this->data['users'] = $this->model->getUserList();
            $this->data['task'] = $this->model->getTasks($tid);
/*
            if ($_FILES['file']['name']) {
                print_r('has file');
                if (!$_FILES['file']['error']) {
                    $name = md5(rand(100, 200));
                    $ext = explode('.', $_FILES['file']['name']);
                    $filename = $name . '.' . $ext[1];
                    $destination = '/img/tikets/'.$tid.'/'.$filename; //change this directory
                    $location = $_FILES["file"]["tmp_name"];
                    move_uploaded_file($location, $destination);
                    echo '/img/tikets/'.$tid.'/'.$filename; //change this URL
                }
                else
                {
                    echo  $message = 'Ooops!  Your upload triggered the following error:  '.$_FILES['file']['error'];
                }
            }else{print_r('dont have file');}

*/

            if($_POST)
            {




                //$tid = isset($_POST['id']) ? $_POST['id'] : null;
                //$uid = isset($_POST['extractor']) ? $_POST['extractor'] : null;

                $result_req = $this->model->getUserDepById($uid,$tid);
                if(!$result_req)
                {
                    $this->model->saveDepUser($tid, $uid);
                    Session::setFlash('Dependence was saved.');
                }else
                {
                    Session::setFlash('Dependence dont saved.');
                }

                $result = $this->model->save($_POST,$tid);
                if(!$result)
                {
                    Session::setFlash('Tiket was saved.');
                }else
                {
                    Session::setFlash('Tiket dont saved.');
                }
                Router::redirect('/'.Session::get('role').'/tikets/edit/'.$tid);
            }

            if (isset($this->params[0]) )
            {
                $this->data['tiket'] = $this->model->getById($this->params[0]);
            }else
            {
                Session::setFlash('Wrong page id.');
                //Router::redirect('/'.Session::get('role').'/tikets/edit/'.$tid);
        }
    }

    public function uploadimg()
    {
        $var = $_POST['file'];
        print_r($var);
        if($this->input->post('file')) {
            $config['upload_path'] = 'upload';
            $config['file_name'] = $var;
            $config['overwrite'] = 'TRUE';
            $config["allowed_types"] = 'jpg|jpeg|png|gif';
            $config["max_size"] = '1024';
            $config["max_width"] = '400';
            $config["max_height"] = '400';
            $this->load->library('upload', $config);

            if(!$this->upload->do_upload()) {
                $this->data['error'] = $this->upload->display_errors();
                print_r( $this->data['error']);
            } else {
                print_r("success");
            }
        }
    }

    public function admin_delete()
    {
        if(isset($this->params[0]) )
        {
            $result = $this->model->delete($this->params[0]);
            if ($result)
            {
                Session::setFlash('Page was deleted.');
            }else
            {
                Session::setFlash('Error.');
            }
        }
        Router::redirect('/'.Session::get('role').'/tikets/');
    }

    public function admin_recover()
    {
        if(isset($this->params[0]) )
        {
            $result = $this->model->recover($this->params[0]);
            if ($result)
            {
                Session::setFlash('Page was deleted.');
            }else
            {
                Session::setFlash('Error.');
            }
        }
        Router::redirect('/'.Session::get('role').'/tikets/');
    }

//TASK
//
    public function admin_addtask()
    {
        (int)$id = App::getRouter()->getParams();

        if(isset($id[0]))
        {
            $result = $this->model->addTask($id[0]);
            if ($result)
            {
                Session::setFlash('Page was deleted.');
            }else
            {
                Session::setFlash('Error.');
            }
        }
        Router::redirect('/'.Session::get('role').'/tikets/edit/'.$id[0]);
    }

    public function admin_deltask()
    {
        (int)$id = App::getRouter()->getParams();

        if(isset($id[0]) and isset($id[1]))
        {
            $result = $this->model->delTask($id[0]);
            if ($result)
            {
                Session::setFlash('Page was deleted.');
            }else
            {
                Session::setFlash('Error.');
            }
        }
        Router::redirect('/'.Session::get('role').'/tikets/edit/'.$id[1]);
    }

    public function admin_edittask()
    {
        $params = App::getRouter()->getParams();
        $ts_id = strtolower($params[0]);

        $this->data['task'] = $this->model->getTasksById($ts_id)['0'];

        if($_POST)
        {
            $result = $this->model->saveTask($_POST,$ts_id);
            if($result)
            {
                Session::setFlash('Task was saved.');
                Router::redirect('/'.Session::get('role').'/tikets/edit/'.$this->data['task']['tid']);

            }else
            {
                Session::setFlash('Task dont saved.');
                Router::redirect('/'.Session::get('role').'/tikets/edit/'.$this->data['task']['task_id']);
            }

            //Router::redirect('/'.Session::get('role').'/tikets/edit/'.$this->data['task']['tid']);
        }


    }



//DEPENDENS/EXTRACTOR
//
    public function admin_addext()
    {
        (int)$uid = $this->params[0];
        (int)$tid = $this->params[1];

        $result_req = $this->model->getUserDepById($uid,$tid);
        if(!$result_req)
        {
            $this->model->saveDepUser($tid,$uid);
            Session::setFlash('Page was deleted.');
        }else
        {
            Session::setFlash('Error.');
        }

        Router::redirect('/'.Session::get('role').'/tikets/edit/'.$tid);
    }

    public function admin_deldep()
    {
        if(isset($this->params[0]) and isset($this->params[1]))
        {
            $result = $this->model->deliteDep($this->params[0],$this->params[1]);
            if ($result)
            {
                Session::setFlash('Page was deleted.');
            }else
            {
                Session::setFlash('Error.');
            }
        }
        Router::redirect('/'.Session::get('role').'/tikets/edit/'.$this->params[1]);
    }



    /*
    USER Section
    */


    public function user_index()
    {
        $this->data['ob'] = $this->model;
        $this->model->expire();
        $this->data['tiket'] = $this->model->getListCurUser(Session::get('id'));
        $this->data['tiket_as'] = $this->model->getListAs(Session::get('id'));
        $this->data['users'] = $this->model->getUserList();

        if($_POST)
        {

            $_POST['email'] = Session::get('email');
            $_POST['shortName'] = Session::get('shortName');
            //print_r($_POST);
            $result = $this->model->saveAdd($_POST);

            if ($result)
            {
                Session::setFlash('Page was saved.');
            }else
                {
                    Session::setFlash('Error.');
                }

            $tid = $this->model->getByTitle($_POST['title']);
            $tid = $tid['0']['id'];
            $_POST['tid'] = $tid;
            $_POST['uid'] = Session::get('id');


            $mail = new MailController();
            $mail->creatorTiket($_POST);
            //print_r($_POST);

                if($_POST['users'])
                {

                    foreach ($_POST['users'] as $a){

                        $result = $this->model->getUserDepById($a,$tid);

                        if(!$result)
                        {
                            $_POST['uid'] = $a;
                            $this->model->saveDepUser($tid, $a);
                            $mail->extractorsNewTiket($_POST);
                            //print_r($_POST);
                        }

                    }
                }

            Router::redirect('/'.Session::get('role').'/tikets/');
        }

    }

    public function user_all()
    {
        $this->data['ob'] = $this->model;
        $this->model->expire();
        $this->data['tiket'] = $this->model->getList();
        $this->data['tiket_as'] = $this->model->getListDel();
        $this->data['users'] = $this->model->getUserList();

        if($_POST)
        {
            //print_r($_POST);

            $result = $this->model->saveAdd($_POST);
            if ($result)
            {
                Session::setFlash('Page was saved.');
            }else
            {
                Session::setFlash('Error.');
            }

            if($_POST['users'])
            {
                $tid = $this->model->getByTitle($_POST['title']);
                $tid = $tid['0']['id'];

                foreach ($_POST['users'] as $a){

                    $result = $this->model->getUserDepById($a,$tid);

                    if(!$result)
                    {
                        $this->model->saveDepUser($tid, $a);
                    }

                }
            }

            Router::redirect('/'.Session::get('role').'/tikets/');
        }

    }

    public function user_view()
    {
        if (isset($this->params[0]) )
        {
            $this->data['tiket'] = $this->model->getById($this->params[0]);
        }else
        {
            Session::setFlash('<div class="alert alert-danger">Не вірний id тікета.</div>');
            //Router::redirect('/'.Session::get('role').'/tikets/edit/'.$tid);
        }

        $params = App::getRouter()->getParams();
        $tid = strtolower($params[0]);
        $uid = Session::get('id');

        $this->data['users_dep'] = $this->model->getUserDep($tid);
        $this->data['users'] = $this->model->getUserList();
        $this->data['task'] = $this->model->getTasks($tid);
        $this->data['comments'] = $this->model->listComm($tid);
        $this->data['parent'] = $this->model;
        $this->data['allTikets'] = $this->model->getListForTiket();
        $this->data['allChildT'] = $this->model->allChildTikets($tid);
        $this->data['toDel'] = $this->model->allChildTikets($tid) ? 0 : 1 ;
        $this->data['assTo'] = $this->model->getUserDepById($uid,$tid) ? 1 : 0 ;

        if($_POST)
        {
            if ($this->data['assTo'] == '1')
            {
                unset($_POST['users']);
                unset($_POST['files']);
                //print_r($_POST);
                $result = $this->model->saveView($_POST,$tid);
                if($result)
                {
                    Session::setFlash('<div class="alert alert-success">Тікет збережено!</div>');
                }else
                {
                    Session::setFlash('<div class="alert alert-danger">Помилка збереженнф тікету!</div>');
                }
            }

            Router::redirect('/'.Session::get('role').'/tikets/view/'.$tid);
        }



    }



    public function user_add()
    {

        //$this->data['users_dep'] = $this->model->getUserDep($tid);
        $this->data['users'] = $this->model->getUserList();
        $this->data['parent'] = $this->model;
        $this->data['allTikets'] = $this->model->getListForTiket();
        //$this->data['allChildT'] = $this->model->allChildTikets($tid);
        //$this->data['toDel'] = $this->model->allChildTikets($tid) ? 0 : 1 ;

        //print_r($this->data['toDel']);
        if ($_POST) {
            $result = $this->model->saveadd($_POST);
            if ($result) {
                if ($_POST['users']) {
                    $tid = $this->model->getByTitle($_POST['title']);
                    $tid = $tid['0']['id'];
                    //$string = implode(",", $_POST['users']);
                    //print_r($tid);
                    //print_r($_POST['users']);

                    foreach ($_POST['users'] as $a) {

                        $result = $this->model->getUserDepById($a, $tid);

                        if (!$result) {
                            $this->model->saveDepUser($tid, $a);
                        }

                    }
                    Session::setFlash('<div class="alert alert-success">Тікет збережено!</div>');
                } else {
                    Session::setFlash('<div class="alert alert-danger">Помилка збереженнф тікету!</div>');
                }

                Router::redirect('/' . Session::get('role') . '/tikets/');
            }
        }
    }

    public function user_edit()
    {

        if (isset($this->params[0]) )
        {
            $this->data['tiket'] = $this->model->getById($this->params[0]);
        }else
        {
            Session::setFlash('<div class="alert alert-danger">Не вірний id тікета.</div>');
            //Router::redirect('/'.Session::get('role').'/tikets/edit/'.$tid);
        }

        $params = App::getRouter()->getParams();
        $tid = strtolower($params[0]);



        $this->data['users_dep'] = $this->model->getUserDep($tid);
        $this->data['users'] = $this->model->getUserList();
        $this->data['task'] = $this->model->getTasks($tid);
        $this->data['comments'] = $this->model->listComm($tid);
        $this->data['parent'] = $this->model;
        $this->data['allTikets'] = $this->model->getListForTiket();
        $this->data['allChildT'] = $this->model->allChildTikets($tid);
        $this->data['toDel'] = $this->model->allChildTikets($tid) ? 0 : 1 ;

        //$foo = $this->data['comments'];
        //print_r($this->data['toDel']);

        //$this->data['comments'] = $this->some($tid);



            //global $arr1;
            //$foo = new Tiket();

            //print_r($arr1."<<<<==");
            //echo '<div class="col-md-12">'.$tidd.'qwe';
           /* foreach ($data as $com)
                {
                    echo '<div class="panel panel-filled">
                               <div class="panel-body">

                                   <div class="btn-group pull-right m-b-md">
                                       <button class="btn btn-default btn-xs">Show</button>
                                       <button class="btn btn-default btn-xs">Messages</button>
                                   </div>

                                   <img alt="image" class="img-rounded image-lg" src="/img/0.jpg">

                                   <h5 class="m-b-none"><a href=""> Commentator name id: '.$com["uid"].' </a></h5>

                                   <div class="m-b-xs c-white small">Optoins of commentator aoc: <i class="text-danger">'.$com["aoc"].'</i></div>

                                   <p>
                                       '.$com["comment"].'
                                   </p>

                                   <small><i class="fa fa-clock-o"></i> Last active in: <i class="c-accent">'.$com["ct"].'</i></small>

                               </div>

                           </div>';

                    function getParent($tid)
                    {

                        $this->data['comments'] = $this->model->listComm($tid);
                    }
                }*/





       //print_r($_POST['users']);



        if($_POST)
        {
            if($_POST['users'])
            {
                $string = implode(",", $_POST['users']);
                $this->model->delUserDepFor($string,$tid);
                foreach ($_POST['users'] as $a){

                    $result = $this->model->getUserDepById($a,$tid);

                    if(!$result)
                    {
                        $this->model->saveDepUser($tid, $a);
                    }

                }
//            $id = implode(",".$this->data['tiket']['id']."),(", $_POST['users']);
//            $id = "(".$id.",".$this->data['tiket']['id'].")";
            }else
                {
                    $string = null;
                    $this->model->delUserDepFor($string,$tid);
                }

            $result = $this->model->save($_POST,$tid);
            if($result)
            {
                Session::setFlash('<div class="alert alert-success">Тікет збережено!</div>');
            }else
            {
                Session::setFlash('<div class="alert alert-danger">Помилка збереженнф тікету!</div>');
            }
            Router::redirect('/'.Session::get('role').'/tikets/edit/'.$tid);
        }


    }

    public function user_delete()
    {
        if(isset($this->params[0]) )
        {
            //Session::setFlash('hello');
            $tid = (int)$this->params[0];
            $this->data['tiket_id'] = $this->model->getById($this->params[0]);
            if(Session::get('id') ==  $this->data['tiket_id']['creator_id'] and $this->data['tiket_id']['status'] == 'closed')
            {

                $result = $this->model->delete($tid);
                if ($result) {
                    Session::setFlash('<div class="alert alert-success">Тікет видалено!</div>');
                } else {
                    Session::setFlash('<div class="alert alert-danger">Помилка видалення тікета!</div>');
                }
            }else
            {
                Session::setFlash('Error.');
            }

        }
        Router::redirect('/'.Session::get('role').'/tikets/');
    }
//TASK
//
    public function user_addtask()
    {
        (int)$id = App::getRouter()->getParams();

        if(isset($id[0]))
        {
            $result = $this->model->addTask($id[0]);
            if ($result)
            {

                Session::setFlash('<div class="alert alert-success">Завдання додано.</div>');
            }else
            {
                Session::setFlash('<div class="alert alert-danger">Помилка!Завдання не було доданно.</div>');
            }
        }
        Router::redirect('/'.Session::get('role').'/tikets/edit/'.$id[0]);
    }

    public function user_deltask()
    {
        (int)$id = App::getRouter()->getParams();

        if(isset($id[0]) and isset($id[1]))
        {
            $result = $this->model->delTask($id[0]);
            if ($result)
            {
                Session::setFlash('<div class="alert alert-success">Завдання видалено.</div>');
            }else
            {
                Session::setFlash('Error.');
            }
        }
        Router::redirect('/'.Session::get('role').'/tikets/edit/'.$id[1]);
    }

    public function user_edittask()
    {
        $params = App::getRouter()->getParams();
        $ts_id = strtolower($params[0]);

        $this->data['task'] = $this->model->getTasksById($ts_id)['0'];

        if($_POST)
        {
            $result = $this->model->saveTask($_POST,$ts_id);
            if($result)
            {
                Session::setFlash('<div class="alert alert-success">Зміни в завданні збережені</div>');
                Router::redirect('/'.Session::get('role').'/tikets/edit/'.$this->data['task']['tid']);

            }else
            {
                Session::setFlash('<div class="alert alert-success">Зміни в завданні не збережені.</div>');
                Router::redirect('/'.Session::get('role').'/tikets/edit/'.$this->data['task']['task_id']);
            }

            //Router::redirect('/'.Session::get('role').'/tikets/edit/'.$this->data['task']['tid']);
        }


    }
//DEPENDENS/EXTRACTOR
//

    public function user_addext()
    {
        (int)$uid = $this->params[0];
        (int)$tid = $this->params[1];

        $result_req = $this->model->getUserDepById($uid,$tid);
        if(!$result_req)
        {
            $this->model->saveDepUser($tid,$uid);
            Session::setFlash('<div class="alert alert-success "> Виконавця додано!</div>');
        }else
        {
            Session::setFlash('<div class="alert alert-danger"><span class="pe-7s-close-circle pe-4x"></span><h4> Помилка!Користувач вже є у списку виконавців.</h4></div>');
        }

        Router::redirect('/'.Session::get('role').'/tikets/edit/'.$tid);
    }
    public function user_deldep()
    {
        if(isset($this->params[0]) and isset($this->params[1]))
        {
            $result = $this->model->deliteDep($this->params[0],$this->params[1]);
            if ($result)
            {
                Session::setFlash('<div class="alert alert-success">Виконавця видаленно!</div>');
            }else
            {
                Session::setFlash('<div class="alert alert-info">Помилка видалення виконавця!</div>');
            }
        }
        Router::redirect('/'.Session::get('role').'/tikets/edit/'.$this->params[1]);
    }


    //COMMENTS
    //

    public function user_comments()
    {
        (int)$tid = App::getRouter()->getParams();
        (int)$uid = Session::get('id');
        $_POST['uid'] = $uid;
        $_POST['tid'] = $tid;
        // Устанавливаем значение даты
        //$date = date('d.m.Yв H:i');
        if($_POST)
        {
            $this->model->saveComm($_POST);
        }
        else
        {
            echo '<font color="red">Заполните правильно поля ввода!</font>';
        }
    }


}