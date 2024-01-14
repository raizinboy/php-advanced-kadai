<?php 
$dsn = 'mysql:dbname=php_book_app;host=localhost;charset=utf8mb4';
$user = 'root';
$password = '';

if(isset($_POST['submit'])){
    try {
        //インスタンス化
        $pdo =new PDO($dsn, $user, $password);

        $sql_update="UPDATE books SET
                    book_code = :book_code,
                    book_name = :book_name,
                    price = :price,
                    stock_quantity = :stock_quantity,
                    genre_code = :genre_code
                    WHERE id = :id
                    ";
        
        //SQL文を用意する
        $stmt_update=$pdo->prepare($sql_update);

        $stmt_update->bindValue(':book_code', $_POST['book_code'], PDO::PARAM_INT);
        $stmt_update->bindValue(':book_name', $_POST['book_name'], PDO::PARAM_STR);
        $stmt_update->bindValue(':price', $_POST['price'], PDO::PARAM_INT);
        $stmt_update->bindValue(':stock_quantity', $_POST['stock_quantity'], PDO::PARAM_INT);
        $stmt_update->bindValue(':genre_code', $_POST['genre_code'], PDO::PARAM_INT);
        $stmt_update->bindValue(':id', $_GET['id'], PDO::PARAM_INT);

        $stmt_update->execute();

        $count = $stmt_update->rowCount();

        $message = "書籍を{$count}件を編集しました。";

        header("Location: read.php?message={$message}");
    } catch (PDOException $e){
        exit($e->getMessage());
    }
}

if (isset($_GET['id'])){
    try {
        //インスタンス化
        $pdo = new PDO($dsn, $user, $password);

        //SQL文を用意する
        $sql_select = 'SELECT * FROM books WHERE id = :id';
        $stmt_select = $pdo->prepare($sql_select);

        //:idに値を割り当てる
        $stmt_select->bindValue(':id', $_GET['id'], PDO::PARAM_INT);

        //SQL文の実行
        $stmt_select->execute();

        //1つのレコードなのでfetchを使う
        $book = $stmt_select->fetch(PDO::FETCH_ASSOC);

        //fetch()メソッドは値がないときFALSEを返す
        if($book === FALSE){
            exit('idバラメータの値が不正です');
        }

        $sql_genre ="SELECT genre_code FROM genres";

        //SQL文を用意する (静的であるのでquery)
        $stmt_genre = $pdo->query($sql_genre);

        //SQL文の実行結果を配列で取得する
        $genre_codes = $stmt_genre->fetchAll(PDO::FETCH_COLUMN);

    } catch(PDOException $e){
            exit($e->getMessage());
    }
} else {
    //idが存在しない場合
    exit('idのパラメータが存在しません。');
}   
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset='UTF-8'>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>書籍編集</title>
        <link rel="stylesheet" href="css/style.css">

        <!--googleフォントの取り組み-->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
    </head>
    <body>
        <header>
            <nav>
                <a href="index.php">書籍管理アプリ</a>
            </nav>
        </header>
        <main>
            <article class="registration">
                <h1>書籍編集</h1>
                <div class="back">
                    <a href="read.php" class="btn">&lt; 戻る</a>
                </div>
                <form action="update.php?id=<?= $_GET['id'] ?>" method="post" class="registration-form">
                    <div>
                        <label for="book_code">書籍コード</label>
                        <input type="number" name="book_code" min="0" max="100000000" value= "<?= $book['book_code']?>" required>

                        <label for="book_name">書籍名</label>
                        <input type="text" name="book_name" maxlength="50" value="<?= $book['book_name'] ?>" required>

                        <label for="price">単価</label>
                        <input type="number" name="price" min="0" max="100000000" value="<?= $book['price'] ?>" required>

                        <label for="stock_quantity">在庫数</label>
                        <input type="number" name="stock_quantity" min="0" max="100000000" value="<?= $book['stock_quantity'] ?>" required>

                        <label for="genre_code">ジャンルコード</label>
                        <select name="genre_code" required>
                            <option disable selected value>選択してください</option>
                            <?php
                            foreach ($genre_codes as $genre_code){
                                if ($genre_code === $book['genre_code']){
                                echo "<option value='{$genre_code}' selected>{$genre_code}</option>";
                                } else {
                                echo "<option value='{$genre_code}'>{$genre_code}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="submit-btn" name="submit" value="create">更新</button>
                </form>
            </article> 
        </main>
        <footer>
            <p class="copyright"> &copy;書籍管理アプリ All rights reserved</p>
        </footer>
    </body>
</html>