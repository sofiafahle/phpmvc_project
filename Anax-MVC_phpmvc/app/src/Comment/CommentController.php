<?php

namespace Sofa15\Comment;

/**
 * To attach comments-flow to content.
 *
 */
class CommentController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable,
        \Anax\MVC\TRedirectHelpers;
	
	/**
	 * Initialize the controller.
	 *
	 * @return void
	 */
	public function initialize()
	{
		$this->comment = new \Sofa15\Comment\Comment();
		$this->comment->setDI($this->di);
	}
	
	/**
	 *
	 * Setup the table for comments.
	 *
	 */
	public function setupAction()
	{	
		include('setup.php');
		
		$this->di->theme->setTitle("Setting up");
        $this->di->views->add('default/page', [
            'content' => '<p>"Comment" setup done.</p>'
        ]);
	}
	
	public function indexAction() 
	{
		$this->listAction();
	}
	
	/**
	 * List all comments.
	 *
	 * @return void
	 */
	public function listAction()
	{
		if (empty($this->userInfo['admin'])) {
			die('You must be an admin to list all comments.');
		}
		
		$comments = $this->comment->findAll();
		
		$comments = $this->comment->findRelated($comments);
	 
		$this->theme->setTitle("List all comments");
		$this->views->add('comment/comments', [
			'comments' => $comments,
		]);
		$this->views->add('comment/controls');
	}

    /**
     * View all comments for parent.
	 *
	 * @param int @parentID as id of parent.
     * @param string @parentType as name of parent model.
	 *
     * @return void
     */
    public function viewAction($parentType = null, $parentID = null, $inactive = null)
    {
		if (!$parentType || !$parentID) {
			$this->redirectTo('question');
		}
		
        $comments = $this->comment->query()
							  ->where("parentID = ?")
							  ->andWhere("parentType = ?")
							  ->andWhere("deleted IS NULL")
							  ->execute([$parentID, $parentType]);
		
		$number = 1;			  
        foreach ($comments as $comment) {
			$comment->number = $number;
			$number++;
		}
		
		$comments = $this->comment->findRelated($comments);
		
		
		if ($comments) {
			$this->views->add('comment/comments', [
				'comments' => $comments,
			]);
		}
		
		if ($inactive == null && isset($this->userInfo['id'])) {
			$this->views->add('default/page', [
				'content' => "<a class='right' href='" . $this->url->create('comment/add/' . $parentType . '/' . $parentID) . "'>add comment</a><br><br>",
			]);
		}
    }

	
	/**
     * Add a comment to parent.
     *
	 * @param int @parentID as id of parent.
     * @param string @parentType as name of parent model.
	 *
     * @return void
     */
    public function addAction($parentType, $parentID)
    {
		if (empty($this->userInfo['id'])) {
			die('You must be logged in to add a comment.');
		}
  		$form = new \Anax\Comment\CFormAddComment($parentType, $parentID);
        $form->setDI($this->di);
		
		// Check the status of the form
        $form->check();
		
		$this->theme->setTitle('Add comment');
		$this->views->add('default/page', [
			'content'	=> $form->getHTML(),
		]);
    }
	
	
	/**
     * Edit comment by id.
     *
	 * @return void
     */
    public function updateAction($id = null)
    {
		if (!$id) {
			$this->response->redirect($this->url->create(''));
		}
		
		$comment = $this->comment->find($id);
		$comment = $this->comment->findRelated([$comment])[0];
		
		$questionID = $this->comment->question->id;
		unset($this->comment->question);
		unset($this->comment->user);
		
		if (($this->userInfo['id'] != $comment->userID) && empty($this->userInfo['admin'])) {
			die('You can only edit your own comments.');
		}
	
		$form = new \Anax\Comment\CFormUpdateComment($comment, $questionID);
		$form->setDI($this->di);
		
		// Check the status of the form
		$form->check();
		
		$this->theme->setTitle("Redigera kommentar");
		$this->views->add('default/page', [
			'title' 	=> 'Edit comment',
			'content'	=> $form->getHTML(),
		]);
	}
	
	/**
	 * Delete (soft) comment.
	 *
	 * @param integer $id of comment to delete.
	 *
	 * @return void
	 */
	public function softDeleteAction($id = null)
	{
		if (!$id) {
			die("Missing id");
		}
	 
		$now = gmdate('Y-m-d H:i:s');
	 
		$comment = $this->comment->find($id);
		
		if (($this->userInfo['id'] != $comment->userID) && empty($this->userInfo['admin'])) {
			die('You can only edit your own comments.');
		}
	 
		$comment->deleted = $now;
		$comment->save();
	 
		$comment = $this->comment->findRelated([$comment])[0];
		$this->redirectTo('question/view/' . $comment->question->id);
	}
	
	/**
	 * Restore (soft) deleted comment.
	 *
	 * @param integer $id of comment to restore.
	 *
	 * @return void
	 */
	public function restoreAction($id = null)
	{
		if (!$id) {
			die("Missing id");
		}
	 
		$comment = $this->comment->find($id);
		
		if (($this->userInfo['id'] != $comment->userID) && empty($this->userInfo['admin'])) {
			die('You can only edit your own comments.');
		}
	 
		$comment->deleted = null;
		$comment->save();
		
		$comment = $this->comment->findRelated([$comment])[0];
		$this->redirectTo('question/view/' . $comment->question->id);
	}
	
	/**
	 * Delete comment.
	 *
	 * @param integer $id of comment to delete.
	 *
	 * @return void
	 */
	public function deleteAction($id = null)
	{
		if (!$id) {
			die("Missing id");
		}
		
		$comment = $this->comment->find($id);
		
	 	if (($this->userInfo['id'] != $comment->userID) && empty($this->userInfo['admin'])) {
			die('You can only edit your own comments.');
		}
		
		$res = $comment->delete($id);
	 
		$comment = $this->comment->findRelated([$comment])[0];
		$this->redirectTo('question/view/' . $comment->question->id);
	}
	
	/**
     * Remove all comments.
     *
     * @return void
     */
    public function deleteAllAction()
    {
		if (empty($this->userInfo['admin'])) {
			die('You must be an admin to delete comments.');
		}
		
        $comments = new \Sofa15\Comment\Comment();
        $comments->setDI($this->di);

        $comments->deleteAll();
		
		$comment = $this->comment->findRelated([$comment])[0];
		$this->redirectTo('question/view/' . $comment->question->id);
    }
}
