<?php
//DB接続
$dsn = データベース名;
$user = ユーザー名;
$password = パスワード;
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//テーブル作成
$sql = "CREATE TABLE IF NOT EXISTS tbtest"
." ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "name char(32),"
. "comment TEXT,"
. "pas TEXT"
.");";
$stmt = $pdo->query($sql);
//初期設定

error_reporting(E_ALL & ~E_NOTICE);





//データベース入力
if(!empty($_POST["name"]&&$_POST["comment"]&&$_POST["pas"])&&empty($_POST["check"])){
	$name=$_POST["name"];
    $comment=$_POST["comment"];
    $pas=$_POST["pas"];
	$sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, pas) VALUES (:name, :comment, :pas)");
	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql -> bindParam(':pas', $pas, PDO::PARAM_STR);
	$sql -> execute();
}
//削除機能
if(!empty($_POST["delete"]&&$_POST["deletepas"])){
	$delete=$_POST["delete"];
	$deletepas=$_POST["deletepas"];
	$id = $delete;
	$sql = 'delete from tbtest where id=:id AND pas=:pas';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->bindParam(':pas', $deletepas, PDO::PARAM_INT);
	$stmt->execute();
}
//編集機能
if(!empty($_POST["edit"]&&$_POST["editpas"])){
	$edit=$_POST["edit"];
	$editpas=$_POST["editpas"];
	$sql = 'SELECT * FROM tbtest';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	$editpas=$_POST["editpas"];
	foreach ($results as $row){
		if($row["id"]==$edit&&$row["pas"]==$editpas){
            $editname=$row["name"];
		    $editcomment=$row["comment"];
		    $editnumber=$row["id"];
		}
	}
}
if(!empty($_POST["check"])){
	$check=$_POST["check"];
	$id = $check; //変更する投稿番号
	$name = $_POST["name"];
	$comment = $_POST["comment"]; //変更したい名前、変更したいコメントは自分で決めること
	$sql = 'UPDATE tbtest SET name=:name,comment=:comment WHERE id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
}
//テーブル削除
if(!empty($_POST["alldelete"])&&$_POST["alldelete"]=="削除"){
	$sql = 'DROP TABLE tbtest';
	$stmt = $pdo->query($sql);
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission3-1</title>
</head>
<body>
    <form action="" method="post">
        <input type="text" name="name" placeholder="名前" value="<?php if(!empty($editname)){echo $editname;}; ?>">
        <input type="text" name="comment" placeholder="コメント" value="<?php if(!empty($editcomment)){echo $editcomment;} ?>">
		<input type="hidden" name="check" value="<?php if(!empty($editnumber)){echo $editnumber;} ?>">
		<input type="text" name="pas" placeholder="パスワード">
        <input type="submit" name="submit">
    </form>
    <form action"" method="post">
        <input type="text" name="delete" placeholder="投稿番号を入力">
		<input type="text" name="deletepas" placeholder="パスワード">
        <input type="submit" name="dele" placeholder="削除">
    </form>
	<form action="" method="post">
	    <input type="number" name="edit" placeholder="編集番号を入力">
		<input type="text" name="editpas" placeholder="パスワード">
        <input type="submit" name="edi" placeholder="編集">
	</form>
	<form action="" method="post">
	    <input type="text" name="alldelete" placeholder="削除と入力でテーブル削除">
        <input type="submit" name="all">
	</form>
	<?php
	//表示
	    $sql = 'SELECT * FROM tbtest';
		$stmt = $pdo->query($sql);
		$results = $stmt->fetchAll();
		foreach ($results as $row){
			echo $row['id'].',';
			echo $row['name'].',';
			echo $row['comment'].'<br>';
		    echo "<hr>";
		}
	?>
</body>
</html>