<?php

namespace Anax\Users;
 
/**
 * Model for Users.
 *
 */
class Users extends \Anax\Database\CDatabaseModel
{
	/**
	 * Find parent posts made by one user return them. 
	 *
	 * @param int $id as user to search for.
	 *
	 * @return array with questions and anwers.
	 */
	public function findPosts($id) {
		$question = new \Anax\Question\Question();
		$question->setDI($this->di);
		$questions = $question->findWhere('userID', $id);
		
		$answer = new \Anax\Answer\Answer();
		$answer->setDI($this->di);
		$answers = $answer->findWhere('userID', $id);
		
		$comment = new \Sofa15\Comment\Comment();
		$comment->setDI($this->di);
		$comments = $comment->findWhere('userID', $id);
		
		$posts = array_merge($questions, $answers, $comments);
		
		return ['questions' => $questions, 'answers' => $answers, 'comments' => $comments, 'all' => $posts];
	}
	
	
	public function getMostActive($count = 3) 
	{
		$users = $this->findAll();
		
		foreach ($users as $user) {
			$user->setDI($this->di);
			$user->posts = $this->findPosts($user->id);
			
			$user->postCount = count($user->posts['all']);
		}
		
		usort($users, function($a, $b)
		{
			if ($a->postCount == $b->postCount) {
				return 0;
			}
			return ($a->postCount < $b->postCount) ? 1 : -1;
		});
		
		$users = array_slice($users, 0, $count);
		
		return $users;
	
	}
}