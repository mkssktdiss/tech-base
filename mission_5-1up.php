<html>
	<head>
		<title>mission5-1</title>
		<meta charset="utf-8">
	</head>
<body>

<h1>簡易掲示板</h1>

<?php
	$dsn='データベース名';
	$user='ユーザー名';
	$password='パスワード';
	$pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));

	//テーブル作成
	$sql="CREATE TABLE IF NOT EXISTS tbpro"
	."("
	."id INT AUTO_INCREMENT PRIMARY KEY,"
	."name char(50),"
	."comment TEXT,"
	."date DATETIME"
	.");";
	$stmt=$pdo->query($sql);

	//データベースへの登録
	if(!empty($_POST['name'])&&!empty($_POST['comment'])&&!empty($_POST['newpass'])){
		$sql=$pdo->prepare("INSERT INTO tbpro (name, comment,date) VALUES (:name, :comment,:date)");
		$sql->bindParam(':name',$name,PDO::PARAM_STR);
		$sql->bindParam(':comment',$comment,PDO::PARAM_STR);
		$sql->bindParam(':date',$date,PDO::PARAM_STR);
		$name=$_POST['name'];
		$comment=$_POST['comment'];
		$date=date('Y/m/d H:i:s');
		$sql->execute();
	}elseif(empty($_POST['name'])&&!empty($_POST['comment'])){
		echo "名前が未入力です。";
	}elseif(!empty($_POST['name'])&&empty($_POST['comment'])){
		echo "コメントが未入力です。";
	}elseif(!empty($_POST['name'])&&!empty($_POST['comment'])&&empty($_POST['newpass'])){
		echo "パスワードが未入力です。";
	}

	//削除機能
	if(!empty($_POST['delete'])&&!empty($_POST['dltpass'])){
		$delete=$_POST['delete'];
		$id=$delete;
		$dltpass=$_POST['dltpass'];
		$sql='delete from tbpro where id=:id';
		$stmt=$pdo->prepare($sql);
		$stmt->bindParam(':id',$id,PDO::PARAM_INT);
		$stmt->execute();
	}

	//編集選択
	if(!empty($_POST['edit'])&&!empty($_POST['edipass'])){
		$id=$_POST['edit'];
		$sql='SELECT * FROM tbpro';
		$stmt=$pdo->query($sql);
		$results=$stmt->fetchAll();
		foreach($results as $row){
			$edi_count=$row['id'];
			$edi_name=$row['name'];
			$edi_comment=$row['comment'];
		}
	}

	//編集実行
	if(!empty($_POST['edbox'])&&!empty($_POST['newpass'])){
		$edbox=$_POST['edbox'];
		$edipass=$_POST['newpass'];
		$sql='update tbpro set name=:name,comment=:comment where id=:id';
		$stmt=$pdo->prepare($sql);
		$stmt->bindParam(':name',$name,PDO::PARAM_STR);
		$stmt->bindParam(':comment',$comment,PDO::PARAM_STR);
		$stmt->bindParam(':id',$id,PDO::PARAM_INT);
		$name=$_POST['name'];
		$comment=$_POST['comment'];
		$date=date('Y/m/d H:i:s');
		$id=$edbox;
		$stmt->execute();
	}
?>
	<form action="mission_5-1.php"method="post">
		<h2>新規入力フォーム</h2>
		名前：<input type="text" name="name" size="20" value="<?php if(!empty($edit)){echo $edi_name;}?>"></br>
		コメント：<input type="text" name="comment" size="20" value="<?php if(!empty($edit)){echo $edi_comment;}?>"></br>
		パスワード：<input type="text" name="newpass" size="20">
		<input type="hidden" name="edbox" size="20" value="<?php if(!empty($edit)){echo $edi_count;}?>">
		<input type="submit" value="送信"></br></br>
		<h2>削除フォーム</h2>
		削除番号：<input type="text" name="delete" size="20"></br>
		パスワード：<input type="text" name="dltpass" size="20">
		<input type="submit" value="削除"></br></br>
		<h2>編集フォーム</h2>
		編集番号：<input type="text" name="edit" size="20"></br>
		パスワード：<input type="text" name="edipass" size="20">
		<input type="submit" value="編集"></br>
	</form></br></br>

<?php
	//表示
	$sql='SELECT * FROM tbpro';
	$stmt=$pdo->query($sql);
	$results=$stmt->fetchAll();
	foreach ($results as $row){
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['date'].'<br>';
		echo "<hr>";
	}
?>
</body>
</html>