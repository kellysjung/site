<?php
include_once('init.php');

// GETS THE SPECIFIC TAG AND POSTS ASSOCIATED; USED IN view-tagged-posts.php
function getTag($tagId) {
	$getSpecificTag = dbQuery("
		SELECT * FROM tags WHERE tagId = :tagId",
		array ("tagId"=>$tagId));
	return $getSpecificTag->fetch();
	foreach ($getSpecificTag as $tag) {
		echo "<a href='view-post.php?postId=".$post['postId']."'>".$post['title']."</a><hr>";
	}
}

// LISTS ALL THE TAG NAMES; USED IN blog-posts.php
function listTags() {
	$tags = dbQuery("SELECT * FROM tags")->fetchAll();
	echo "| ";
	foreach ($tags as $tag) {
		echo "<a href='view-tagged-posts.php?tagId=".$tag['tagId']."'>".$tag['tagName']."</a> | ";
	}
}

function listAllTags() {
	$allTags = dbQuery("SELECT * FROM tags")->fetchAll();
	foreach ($allTags as $tag) {
		echo "<a href='view-tagged-posts.php?tagId=".$tag['tagId']."'>".$tag['tagName']."</a> | &nbsp;&nbsp;&nbsp; ";
	}
}

function returnTagList() {
	$count = dbQuery("
		SELECT COUNT(*), tags.* FROM tags
		INNER JOIN blogPost_tag_link
		WHERE blogPost_tag_link.tagId = :tags.tagId",
		array("tagId"=>$tag['tagId']))->fetchAll();
	var_dump($count);
}

// LISTS ALL POSTS PERTAINING TO TAG; USED IN view-tagged-posts.php
function listTaggedPosts($tagId) {
	$taggedPosts = dbQuery("
		SELECT * FROM blog_posts
		INNER JOIN blogPost_tag_link ON blogPost_tag_link.postId = blog_posts.postId
		INNER JOIN tags ON tags.tagId = blogPost_tag_link.tagId
		WHERE tags.tagId = :tagId", array ("tagId"=>$tagId))->fetchAll();
	if(!$taggedPosts) {
		echo "(Sorry! There are no posts with this tag found. Come back and check again later.)";
		exit;
	}
	foreach ($taggedPosts as $post) {
		if ($post['draft'] == 0) {
			echo "<a href='view-post.php?postId=".$post['postId']."'>".$post['title']."</a><br><br>";
		}
	}
}

// LISTS ALL THE TAGS ON A POST (LINKS TO PAGE WITH ALL POSTS TO THE TAG); USED IN edit-post.php, edit-tags.php, view-post.php
function listPostsTags($postId) {
	$tags = dbQuery("
		SELECT * FROM tags
		INNER JOIN blogPost_tag_link ON blogPost_tag_link.tagId = tags.tagId
		INNER JOIN blog_posts ON blog_posts.postId=blogPost_tag_link.postId
		WHERE blog_posts.postId = :postId", array ("postId"=>$postId))->fetchAll();
	foreach ($tags as $tag) {
		echo " #";
		echo "<a href='view-tagged-posts.php?tagId=".$tag['tagId']."'>".$tag['tagName']."</a>";
		// echo $tag['tagName'];
	}
}