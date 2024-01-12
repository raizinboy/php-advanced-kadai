<?php 
$dsn = 'mysql:dbname=php_book_app;host=localhost;charset=utf8mb4';
$user = 'root';
$password = '';

try {
    //インスタンス化
    $pdo = new PDO($dsn, $user, $password);

    $sql_delete ="DELETE FROM books WHERE id=:id";

    //動的なのでprepareメソッドを用いる
    $stmt_delete = $pdo->prepare($sql_delete);

    //:idに値を割り当てる
    $stmt_delete->bindValue ( ':id', $_GET['id'], PDO::PARAM_INT);

    //SQL文を実行する
    $stmt_delete->execute();

    $count = $stmt_delete->rowCount();

    $message ="商品を{$count}件削除しました。";

    header("Location: read.php?message={$message}");
} catch(PDOException $e){
    exit($e->getMessage());
}
?>
