<?php

namespace Sofa15\Comment;
 
/**
 * Model for Comments.
 *
 */
class Comment extends \Anax\Database\CDatabaseModel
{
	public function findRelated($comments) {
		$comments = $this->findUser($comments);
		foreach ($comments as $comment) {
			$comment->setDI($this->di);
			$comment = $this->findParent($comment, $comment->parentID);
		}
		
		return $comments;
	}
}