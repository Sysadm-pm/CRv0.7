<h3><b>Comments for</b></h3>

<br/>

<div class="">
    <?php

    $commetMAX=10;
    $counter=-1;
    $CommentID=array();
    $CommentID[0]=0;

    ?>
    <div CLASS="form-group panel-body col-md-pull-12" id="CommentView">
        <?php

        WHILE ($counter<=$commetMAX){
            $counter++;
            //Запрос комментариев из БАЗЫ
            $commentInfo=array();
            $commentInfo=getComments($CommentID, $_SERVER["HTTP_HOST"], $_SERVER["REQUEST_URI"]);

            //Если комментариев для вывода на страницу нет, то выходить из цикла
            if (count($commentInfo)==0){break;}

            $CommentID=array();
            for ($i=0;$i<count($commentInfo);$i++){
                //В массив $CommentID помещаются `ID` комментариев выводимых на страницу
                $CommentID[$i]=$commentInfo[$i]["id"];
                ?>

                <div class="panel-body" id="CommentView_Parent<?php print $commentInfo[$i]["id"];?>">
                    <!--Каждый следующий уровень вложенности вдвигается на 20px вправо-->
                    <div style="padding-left:<?php print (20*$counter)?>px;">
                        <div class="Message">

                            <?php
                            print $commentInfo[$i]["message"];
                            ?>

                        </div>
                        <div class="UserInfo" style="background: #0d74b1;color: #1d1f24">
                            <div>
                                <img src="img/0.JPG" border="0" />
                            </div>
                            <div class="Username"style="background: #27b122;color: #1d1f24">
                                <?php
                                print "<b>{$commentInfo[$i]["user"]}</b><br />{$commentInfo[$i]["date"]}";
                                ?>
                            </div>
                            <div>
                                <br />
                                <a href="#" title="Ответить на комментарий" onclick="CommentView_FormView("<?php print $commentInfo[$i]["id"];?>");return false;">Ответить</a>
                            </div>
                        </div>

                        <!--Контейнер для вставки в него формы добавления комментариев при клике на ссылку "Ответить"-->
                        <div id="CommentView_Form<?php print $commentInfo[$i]["id"];?>" style="padding-top:5px;clear:left;"></div>

                    </div>

                    <!--Контейнер для вставки в него вложенных (дочерних) комментариев-->
                    <div id="CommentView_Child<?php print $commentInfo[$i]["id"];?>"></div>
                </div>
                <?php
                //JAVA SCRIPT функция переносит вложенный комментарий его родителю
                if ($counter<>0)
                {

                    print "<script type="text/javascript">CommentView_TeleportChild("{$commentInfo[$i]["idparent"]}","{$commentInfo[$i]["id"]}");</script>";
                            }
                        }
                   }
                    ?>
        <br />
        <div>
            <a href="#" onclick="CommentView_FormView("0");return false;">Добавить комментарий</a>
        </div>
        <!--Контейнер для вставки в него формы добавления комментариев при клике на ссылку "Добавить комментарий"-->
        <div id="CommentView_Form0"></div>
    </div>

</div>
