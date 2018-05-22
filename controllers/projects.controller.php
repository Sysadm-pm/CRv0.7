<?php
class ProjectsController extends Controller
{
    public function __construct($data = array() )
    {
        parent::__construct($data);
        $this->model = new Projects();

    }
    public function user_index()
    {
        $this->data['projects'] = $this->model->getList();
    }

    public function user_view()
    {
        $params = App::getRouter()->getParams();
        $id = $params[0];
        $this->data['projects'] = $this->model->getById($id)['0'];
    }

}