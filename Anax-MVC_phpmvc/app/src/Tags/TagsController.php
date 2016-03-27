<?php

namespace Anax\Tags;
 
/**
 * A controller for tags.
 *
 */
class TagsController implements \Anax\DI\IInjectionAware
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
		$this->tags = new \Anax\Tags\Tags();
        $this->tags->setDI($this->di);
	}
	
	public function setupAction()
	{
		require('setup.php');
		
		$this->di->theme->setTitle("Setting up");
        $this->di->views->add('default/page', [
            'content' => '<p>"Tags" setup done. <a href="' . $this->url->create('tags/list') . '">All tags</a></p>'
        ]);
	}
	
	public function indexAction()
	{
		$this->activeAction();
	}
	
	/**
	 * List all tags.
	 *
	 * @return void
	 */
	public function listAction()
	{
		if (empty($this->userInfo['admin'])) {
			die('You must be an admin to access this page.');
		} 
		
		$all = $this->tags->findAll();
		
		$all = $this->tags->findUser($all);
	 
		$this->theme->setTitle("List all tags");
		$this->views->add('tags/list', [
			'tags' => $all,
			'text' => '<h1>All tags</h1>',
		]);
	}
	
	/**
	 * View tag by id.
	 *
	 * @param int $id of tag to display
	 *
	 * @return void
	 */
	public function viewAction($id)
	{
		if (!$id) {
			$this->redirectTo('tags');
		}
		
		$this->db->select("questionID")
				->from('tag2question')
				->where("tagID = ?")
				->execute([$id]);
		
        $questionList = $this->db->fetchAll();
		$tag = $this->tags->find($id);
		
		if (empty($questionList)) {
			$this->views->add('default/page', [
				'title' => $tag->title,
				'content' => 'This tag has no questions yet!'
			]);
			return true;
		}
		
		$questions = [];
		foreach ($questionList as $questionID) {
			$question = new \Anax\Question\Question();
        	$question->setDI($this->di);
			$question = $question->find($questionID->questionID);
			$questions[] = $question;
		}
		
		$questions = $questions[0]->findUser($questions);
	 
	 	$this->theme->setTitle($tag->title);
		$this->views->add('question/list', [
			'title' => $tag->title,
			'questions' => $questions
		]);
	}
	
	/**
	 * List tags by question id.
	 *
	 * @param int $id of question
	 *
	 * @return void
	 */
	public function viewQuestionAction($questionID)
	{
		if (!$questionID) {
			$this->redirectTo('');
		}
		
		$this->db->select("tagID")
				->from('tag2question')
				->where("questionID = ?")
				->execute([$questionID]);
		
        $tagList = $this->db->fetchAll();
		
		$tags = [];
		foreach ($tagList as $tagID) {
			$tag = new \Anax\Tags\Tags();
        	$tag->setDI($this->di);
			$tag = $tag->find($tagID->tagID);
			$tags[] = $tag;
		}
	 
		$this->views->add('tags/view-tags', [
			'tags' => $tags
		]);
	}
	
	/**
	 * Add new tag.
	 *
	 *
	 * @return void
	 */
	public function addAction()
	{
		if (empty($this->userInfo['id'])) {
			die('You must log in to add a tag.');
		}
		
		$form = new \Anax\Tags\CFormAddTag();
        $form->setDI($this->di);
		
		// Check the status of the form
        $form->check();
	 
		$this->di->theme->setTitle("Add tag");
        $this->di->views->add('default/page', [
            'title' => "Add a tag",
            'content' => $form->getHTML()
        ]);
	}
	
	/**
	 * Edit a tag.
	 *
	 * @param string $id of tag to edit.
	 *
	 * @return void
	 */
	public function updateAction($id = null)
	{
		
		if (!$id) {
			$this->redirectTo('tag');
		}
		
		$tag = $this->tags->find($id);
		
		if (($this->userInfo['id'] != $tag->userID) && empty($this->userInfo['admin'])) {
			die('You can only edit your own tags.');
		}
		
		$form = new \Anax\Tags\CFormUpdateTag($tag);
        $form->setDI($this->di);
		
		// Check the status of the form
        $form->check();
	 
		$this->di->theme->setTitle("Update tag");
        $this->di->views->add('default/page', [
            'title' => "Update a tag",
            'content' => $form->getHTML()
        ]);
	}
	
	/**
	 * Delete tag.
	 *
	 * @param integer $id of tag to delete.
	 *
	 * @return void
	 */
	public function deleteAction($id = null)
	{
		if (!isset($id)) {
			die("Missing id");
		}
		if (empty($this->userInfo['admin'])) {
			die('You must be an admin to access this page.');
		} 
	 
		$res = $this->tags->delete($id);
	 
		$this->redirectTo('tags/list');
	}
	
	/**
	 * Delete (soft) tag.
	 *
	 * @param integer $id of tag to delete.
	 *
	 * @return void
	 */
	public function softDeleteAction($id = null)
	{
		if (!isset($id)) {
			die("Missing id");
		}
		
		if (empty($this->userInfo['admin'])) {
			die('You must be an admin to access this page.');
		} 
	 
		$now = gmdate('Y-m-d H:i:s');
	 
		$tag = $this->tags->find($id);
	 
		$tag->deleted = $now;
		$tag->save();
	
		$this->redirectTo('tags/trash');
	}
	
	/**
	 * Restore (soft) deleted tag.
	 *
	 * @param integer $id of tag to restore.
	 *
	 * @return void
	 */
	public function restoreAction($id = null)
	{
		if (!isset($id)) {
			die("Missing id");
		}
		if (empty($this->userInfo['admin'])) {
			die('You must be an admin to access this page.');
		} 
		
		$tag = $this->tags->find($id);
	 
		$tag->deleted = null;
		$tag->save();
	 
		$this->redirectTo('tags');
	}
	
	
	/**
	 * List all active and not deleted tags.
	 *
	 * @return void
	 */
	public function activeAction()
	{
		$all = $this->tags->query()
			->where('deleted IS NULL')
			->execute();
	 
		$this->theme->setTitle("Tags");
		$this->views->add('tags/view-tags', [
			'tags' => $all,
			'text' => '<h1>All tags</h1>',
		]);
	}

	
	/**
	 * List all deleted tags.
	 *
	 * @return void
	 */
	public function trashAction()
	{
		if (empty($this->userInfo['admin'])) {
			die('You must be an admin to access this page.');
		} 
		$all = $this->tags->query()
			->where('deleted IS NOT NULL')
			->execute();
	 
		$this->theme->setTitle("Tags that are deleted");
		$this->views->add('tags/view-tags', [
			'tags' => $all,
			'title' => "Tags that are deleted",
		]);
	}
	 
}