<?php

class UsersController extends Controller
{
    public function __construct($data = array() )
    {
        parent::__construct($data);
        $this->model = new User();
    }

    public function admin_login()
    {
        if ($_POST && isset($_POST['login']) && isset($_POST['password']) )
        {
            $user = $this->model->getByLogin($_POST['login']);
            $hash = md5(Config::get('salt').$_POST['password']);
            if ($user && $user['is_active'] && $hash == $user['password'])
            {
                Session::set('login',$user['login']);
                Session::set('role',$user['role']);
                Session::set('id',$user['id']);
                Session::set('email',$user['email']);
                Session::set('is_active',$user['is_active']);
            }
            if (Session::get('role') == 'admin')
            {
                Router::redirect('/admin/');
            }
            elseif(Session::get('role') == 'user')
            {
                Router::redirect('/user/');
            }

        }
    }
    public function admin_logout()
    {
        Session::destroy();
        Router::redirect('/admin/');
    }

    public function admin_view()
    {
        $this->data['users'] = $this->model->getUserList();
    }

    public function admin_viewuser()
    {

        $params = App::getRouter()->getParams();

        if (isset($params[0]) )
        {
            $user_login = strtolower($params[0]);
            //$this->data['users'] = $this->model->getUserList();
            $this->data['users'] = $this->model->getByLogin($user_login);
        }else{$this->data['users'] = $this->model->getUserList();}

    }
    public function admin_index()
    {
        $this->data['users'] = $this->model->getUserList();
    }

    public function admin_add()
    {
        $this->data['users_all'] = $this->model->getUserList();
        if($_POST)
        {
            $result = $this->model->saveUser($_POST);
            if ($result)
            {
                Session::setFlash('Page was saved.');
            }else
            {
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/users/view/');
        }
    }

    public function admin_edit()
    {
        if($_POST)
        {
            $id = isset($_POST['id']) ? $_POST['id'] : null;
            $result = $this->model->saveUser($_POST,$id);
            if ($result)
            {
                Session::setFlash('Page was saved.');
            }else
            {
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/users/view/');
        }

        if (isset($this->params[0]) )
        {
            $this->data['users'] = $this->model->getByUserId($this->params[0]);
            $this->data['users_all'] = $this->model->getUserList();
        }else
        {
            Session::setFlash('Wrong page id.');
            Router::redirect('/admin/users/view/');
        }

    }

    public function admin_delete()
    {
        if(isset($this->params[0]) )
        {
            $result = $this->model->deleteUser($this->params[0]);
            if ($result)
            {
                Session::setFlash('Page was deleted.');
            }else
            {
                Session::setFlash('Error.');
            }
        }
        Router::redirect('/admin/users/view/');
    }
    //

    public function user_index()
    {
        $this->data['users'] = $this->model->getUserList();
       // print_r($_POST['users']);
//        if(!isset($_POST['users']))
//        {$_POST['users'] = array();
//        print_r($_POST['users']);
//// продолжаем обрабатывать данные формы
//        }
    }
    public function user_viewuser()
    {

        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $user_login = strtolower($params[0]);
            //$this->data['users'] = $this->model->getUserList();
            $this->data['users'] = $this->model->getByLogin($user_login);
        } else {
            $this->data['users'] = $this->model->getUserList();
        }

    }
}