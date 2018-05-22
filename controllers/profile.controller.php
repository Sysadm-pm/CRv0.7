<?php
class ProfileController extends Controller
{
    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new User();
    }

    /*    public function admin_login()
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
                    Session::set('avatar',$user['avatar']);
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
        }*/
    public function user_logout()
    {
        Session::destroy();
        Router::redirect('/');
    }


    public function user_index()
    {
        $user_login = Session::get('login');
        $this->data['users'] = $this->model->getByLogin($user_login);

    }


    /*  public function user_edit()
      {
          $user_login = Session::get('login');

          $this->data['users'] = $this->model->getByLogin($user_login);
         // $this->data['users_all']['login'] = $this->model->getUserList();

          if($_POST)
          {
              $id = isset($_POST['id']) ? $_POST['id'] : null;
              $result = $this->model->updateUser($_POST,$id);
              if ($result)
              {
                  Session::setFlash('Page was saved.');
              }else
              {
                  Session::setFlash('Error.');
              }
              Router::redirect('/user/profile/');
          }*/

    public function user_edit()
    {

        if (Session::get('id')) {
            $this->data['users'] = $this->model->getByUserId(Session::get('id'));
            $this->data['users_all'] = $this->model->getUserList();
        } else {
            Session::setFlash('Wrong page id.');
            // Router::redirect('/user/profile/edit/');
        }

        if ($_POST) {

            $id = isset($_POST['id']) ? $_POST['id'] : null;

            $_POST['shortName'] = $_POST['lname'] . ' ' . substr($_POST['fname'], 0, 2) . '.' . substr($_POST['sname'], 0, 2) . '.';
            //print_r($_POST);

            $result = $this->model->updateUser($_POST, $id);
            if ($result) {
                Session::setFlash('Page was saved.');
            } else {
                Session::setFlash('Error.');
            }


            if ($_FILES['avatar']['tmp_name']) {
                //var_dump($_FILES);
                if ($_FILES['avatar']['type'] != 'image/jpeg') Session::setFlash('Не вірний тип файла');
                if ($_FILES['avatar']['size'] > 200000) Session::setFlash('Розмір файла за великий');
                $image = imagecreatefromjpeg($_FILES['avatar']['tmp_name']);
                //$size = getimagesize($_FILES['avatar']['tmp_name']);
                $tmp = imagecreatetruecolor(250,250 );
                imageantialias($tmp, true);
                imagecopyresampled($tmp, $image, 0, 0, $_POST['skyle-x'], $_POST['skyle-y'], 250, 250,$_POST['data-w'], $_POST['data-h']);

                //var_dump($files);
                if ($files = glob('img/avatar/user_' . $_SESSION['id'].'/', GLOB_ONLYDIR)['0']) {
                    //$files = glob('img/avatar/user_' . $_SESSION['id'].'/', GLOB_ONLYDIR)['0'];
                    imagejpeg($tmp, $files . $_SESSION['login'] . '.jpg');
                    $avatar = '/'.$files . $_SESSION['login'] . '.jpg';
                    $this->model->updateAvatar($avatar);
                    imagedestroy($image);
                    imagedestroy($tmp);

                } else {
                    mkdir('img/avatar/user_' . $_SESSION['id'], 0777, true);
                    $files = glob('img/avatar/user_' . $_SESSION['id'].'/', GLOB_ONLYDIR)['0'];
                    imagejpeg($tmp, $files . $_SESSION['login'] . '.jpg');
                    $avatar = '/'.$files . $_SESSION['login'] . '.jpg';
                    $this->model->updateAvatar($avatar);
                    imagedestroy($image);
                    imagedestroy($tmp);
                    echo "HELLO";
                }


            }


        }


        //
    }
}