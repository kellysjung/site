<?php
include('init.php');

$postId = $_REQUEST['postId'];
$specificPost = getPost($postId);

navbar('Blog App');
echo "<div class='large-break'><br></div><br><hr>";
phpProjectsNavbar();

if(isset($_REQUEST['newCommentForm'])) {
	$errors = array();

	if(!@$_REQUEST['commentUser']) {
		$errors['commentUser'] = "Required";
	}
	if(!@$_REQUEST['commentBody']) {
		$errors['commentBody'] = "Required";
	}
	if(sizeof($errors) == 0) {
		addComment();
	} else {
		echo "<span class='error'>Please fill out required fields.</span>";
	}
}

echo "
<div class='large-break'><br></div><br>
<div class='main'>
	<div class='blogPosts'>";
		viewPost($postId);
		echo "
		<div class='smallRight'>
			Tags:"; 
			listPostsTags($postId);
			echo "
		</div>
	</div>
</div>
<br>
";
// echo "<button onclick='popUpForm_show()' class='defaultBtn'>Add Comment</button>";
echo "
<div class='main'>
	<div class='blogPosts'>
		<h4 id='comments'>Comments</h4>";
		listComments($postId);
		echo "<br>
		<div id='popUpForm'>
			<form class='defaultForm' action='' method='POST' style='margin-left: 10px;'>
				<input type='hidden' name='postId' value='".$postId."'>";
				formTextInput('commentUser', 'text', '100', 'Name');
				formTextArea('defaultFormBody', 'commentBody', 'Comment');
				formTextInput("commentEmail", 'email', '', 'Email Address (Not Displayed)');
				inputBtn('defaultBtn', 'newCommentForm', 'Add Comment');
				echo "
			</form>
		</div>
	</div>
</div>
<div class='large-break'><br></div>";

footer();

function addComment() {
	$postId = $_REQUEST['postId'];
	$created = getTimeCreated();
	$createdString = getTimeCreatedString();

	$addComment = dbQuery(
		"INSERT INTO comments (postId, commentUser, commentEmail, commentBody, commentCreated, commentCreatedString)
		VALUES (:postId, :cUser, :cEmail, :cBody, :cCreated, :cCreatedString)",
		array("postId"=>$postId, "cUser"=>htmlspecialchars($_POST['commentUser']), "cEmail"=>htmlspecialchars($_POST['commentEmail']), "cBody"=>htmlspecialchars($_POST['commentBody']), "cCreated"=>$created, "cCreatedString"=>$createdString));

	if ($addComment) {
		header('Location:view-post.php?postId='.$postId.'#comments');
	} else {
		echo "Error. Could not add comment. Please try again. <br>";
	}
	exit;
}
