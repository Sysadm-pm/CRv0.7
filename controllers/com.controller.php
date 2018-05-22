<?php

class ComController extends Controller
{
    public function __construct($data = array() )
    {
        parent::__construct($data);
        $this->model = new Page();
    }


    public function user_index()
    {
        function funcCommentAdd($idparent,
                                $user,
                                $email,
                                $message,
                                $host,
                                $url)
        {
            //Соединение с БД
            $db = mysql_connect("localhost", "root", "");
            mysql_select_db("mvc~");
            //mysql_query("SET NAMES CP1251");

            //Запрос в БД
            $query = " INSERT INTO `comments`
                      VALUES (
                        NULL,
                        '{$idparent}',
                        '{$user}',
                        '{$email}',
                        '{$message}',
                        '{$host}',
                        '{$url}',
                        NOW()
                  )";
                  $result = mysql_query($query);
                  mysql_close($db);
                  return $result;
    }

        function getComments($CommentID,$host,$url)
        {
            //Соединение с БД
           $db = mysql_connect("localhost", "root", "");
           mysql_select_db("mvc~");
           // mysql_query("SET NAMES CP1251");

//==================================================================
           // $host=str_replace("www.","",mysql_real_escape_string($host));
           // $url=mysql_real_escape_string($url);

            //Формирования строки для запроса
            //Сборка ID всех родителей для нахождения по ним дочерних комментариев
            $query=" and (";
            for ($i=0;$i<count($CommentID);$i++){
                $CommentID[$i]=intval($CommentID[$i]);
                if ($i==0)
                {
                    $query.=" `idparent`='{$CommentID[$i]}' ";
                }
                else{$query.=" or `idparent`='{$CommentID[$i]}' ";}
            }
            $query.=" )";
//==================================================================

            $query="Select * 
          From `comments`
          Where `host` LIKE '%{$host}' and 
                `url`='{$url}'
                {$query}
          Order by `date` DESC";

          $result = mysql_query($query);
          $result_row=array();
          $kol=-1;
      while ($row = mysql_fetch_array($result,MYSQL_BOTH)) {
          $kol++;
          $result_row[$kol]["id"]=$row["id"];
          $result_row[$kol]["idparent"]=$row["idparent"];
          $result_row[$kol]["user"]=$row["user"];
          $result_row[$kol]["email"]=$row["email"];
          $result_row[$kol]["message"]=$row["message"];
          $result_row[$kol]["date"]=$row["date"];
      }

    mysql_close($db);
    return $result_row;
}

        $this->data['q'] = "qweqwe";

    }

    public function get_total()
    {
        $this->data['total'] = $this->model->get_total();
    }

    public function user_2()
    {

    }



}