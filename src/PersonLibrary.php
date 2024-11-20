<?php

class MemberInfo {
    public function __construct(
        private string $username,
        private string $email
    )
    {}
    public function showMethod(){
        echo $this -> username . " : " . $this -> email;
    }
}

class SerchMember{
    private string $membername;
    public function __construct($name)
    {
        $this -> membername = $name;
    }
    public function serchDB(){
    }
}


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class & session introduction</title>
</head>
<body>
    <header>
        <h1>オブジェクト指向構文を身につけよう</h1>
        <p>バックエンドにuserクラスを用意しています。登録情報を入力してデータを登録、検索でデータを取得して表示させてみましょう</p>
    </header>
    
    <h3>登録はこちら</h3>
    <form action="PersonLibrary.php" method="POST">
        <label for="username"><p>ユーザー名を入力してください</p></label>
        <input type="text" id="username" name="username">
        <label for="email"><p>メールアドレスを入力してください</p></label>
        <input type="text" id="email" name="email">
        <button type="submit">送信</button>
    </form>
    <?php
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $member = new MemberInfo($username, $email);
        $member -> showMethod();
        
        if($_POST){
            $host = 'db';
            $dbname = 'mydatabase';
            $user = 'myuser';
            $password = 'mypassword';

            try{
                $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
                $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e){
                echo '接続エラー: ' . $e -> getMessage();
                exit;
            }

            $sql = 'INSERT INTO users (username, email) VALUES (:username, :email)';
            $stmt = $pdo -> prepare($sql);

            $stmt -> bindParam(':username', $username, PDO::PARAM_STR);
            $stmt -> bindParam(':email', $email, PDO::PARAM_STR);

            try{
                $stmt -> execute();
                echo 'データが正常に挿入されました!';
            } catch (PDOException $e) {
                echo '挿入エラー: ' . $e -> getMessage();
            }
        }
    }
    ?>
    <h3>検索はこちら</h3>
    <form action="PersonLibrary.php" method="GET">
        <label for="username">検索するユーザー名を入力してください</label>
        <input type="text" id="serchname" name="username">
        <button type="submit">検索</button>
    </form>
    <?php
    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        $host = 'db';
        $dbname = 'mydatabase';
        $user = 'myuser';
        $password = 'mypassword';

        try{
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
            $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $targetname = $_GET['username'] ?? '';

            $sql = 'SELECT *FROM users WHERE username = :targetname';
            $stmt = $pdo -> prepare($sql);

            $stmt -> bindParam(':targetname', $targetname, PDO::PARAM_STR);
            $stmt -> execute();

            $result = $stmt -> fetch(PDO::FETCH_ASSOC);

            if ($result){
                echo '<p>ID: ' . $result['id'] ."<br />". PHP_EOL;
                echo '名前: ' . $result['username'] ."<br />". PHP_EOL;
                echo 'メール: ' . $result['email'] ."<br />". PHP_EOL;
                echo '登録日時: ' .$result['created_at'] ."<p>". PHP_EOL;
            }else {
                echo '指定した人物のデータは見つかりませんでした。';
            }
        }catch (PDOException $e){
            echo 'エラー: ' . $e -> getMessage();
        }
    }
?>
</body>
</html>