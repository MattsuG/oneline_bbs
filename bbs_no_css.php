



<?php
    //データベースに接続
    $dsn = 'mysql:dbname=oneline_bbs;host=localhost';
    //接続するためのユーザー情報
    $user = 'root';
    $password= '';
    //DB接続オブジェクトを作成
    $dbh = new PDO($dsn,$user,$password);
    //接続したDBオブジェクトで文字コードutf8を使うように指定
    $dbh ->query('SET NAMES utf8');


  //POST送信が行われたら、下記の処理を実行
  //テストコメント
  if(isset($_POST) && !empty($_POST)){

    //!は否定を意味する
    //emptyは、変数はあるが中身が空っぽの場合
    //こういうチェックをvalidation（検証）という

    

    //変数の宣言
    $nickname=$_POST['nickname'];
    $comment=$_POST['comment'];

    // //SQL文作成(INSERT文)
    // $sql = 'INSERT INTO `posts`(`nickname`, `comment`, `created`) VALUES ("'.$nickname.'","'.$comment.'",now())';


    $sql = sprintf('INSERT INTO `posts`(`nickname`, `comment`, `created`) VALUES (\'%s\',\'%s\',now())',$_POST['nickname'],$_POST['comment']);

    
    //INSERT文実行
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
  }

    //SQL文作成（SELECT文）
    $sql = 'SELECT * FROM `posts`';

    //SELECT文実行
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    //格納する変数の初期化
    $posts = array();

    while(1){

    //実行結果として得られたデータを取得
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);

    if($rec == false){
      break;
    }

    //取得したデータを配列に格納しておく
    $posts[] = $rec; 

    }

  //データベースから切断
    $dbh = null;
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>セブ掲示版</title>

</head>
<body>
    <form action="bbs_no_css.php" method="post">
    <!--action="bbs_no_css.php"を消したら自動的に自分のところに飛ぶようになる
    -->
      <input type="text" name="nickname" placeholder="nickname" required>
      <textarea type="text" name="comment" placeholder="comment" required></textarea>
      <button type="submit" >つぶやく</button>
    </form>

    <?php
    //指定した配列のデータ数分繰り返しを行う
    foreach ($posts as $post_each){
      echo'<h2><a href="#">'.$post_each['nickname'].'</a> <span>'.$post_each['created'].'</span></h2>';
      echo '<p>'.$post_each['comment'].'</p>';
    }

    ?>



</body>
</html>



