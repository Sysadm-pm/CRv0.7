<?php
class PagesController extends Controller
{
    public function __construct($data = array() )
    {
        parent::__construct($data);
        $this->model = new Page();
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

    public function view()
    {
        $params = App::getRouter()->getParams();

        if (isset($params[0]) )
        {
            $alias = strtolower($params[0]);
            $this->data['page'] = $this->model->getByAlias($alias);
        }
    }
    public function admin_index()
    {
        $this->data['pages'] = $this->model->getList();
    }

    public function user_index()
    {
        $this->data['pages'] = $this->model->getList();
    }


    public function admin_add()
    {
        if($_POST)
        {
            $result = $this->model->save($_POST);
            if ($result)
            {
                Session::setFlash('Page was saved.');
            }else
                {
                    Session::setFlash('Error.');
                }
            Router::redirect('/'.Session::get('role').'/pages/');
        }
    }

    public function admin_edit()
    {
        if($_POST)
        {
            $id = isset($_POST['id']) ? $_POST['id'] : null;
            $result = $this->model->save($_POST,$id);
            if ($result)
            {
                Session::setFlash('Page was saved.');
            }else
            {
                Session::setFlash('Error.');
            }
            Router::redirect('/'.Session::get('role').'/pages/');
        }

        if (isset($this->params[0]) )
        {
            $this->data['page'] = $this->model->getById($this->params[0]);
        }else
            {
                Session::setFlash('Wrong page id.');
                Router::redirect('/'.Session::get('role').'/pages/');
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
        Router::redirect('/'.Session::get('role').'/pages/');
    }


}