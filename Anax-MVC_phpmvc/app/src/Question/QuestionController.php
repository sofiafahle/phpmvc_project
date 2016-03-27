<?php

namespace Anax\Question;
 
/**
 * A controller for questions.
 *
 */
class QuestionController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable,
        \Anax\MVC\TRedirectHelpers;
		
	public $tags;
	public $answer;
	public $question;
	
	/**
	 * Initialize the controller.
	 *
	 * @return void
	 */
	public function initialize()
	{
		$this->question = new \Anax\Question\Question();
		$this->question->setDI($this->di);
		
		$this->answer = new \Anax\Answer\Answer();
        $this->answer->setDI($this->di);
		
		$this->tags = new \Anax\Tags\Tags();
        $this->tags->setDI($this->di);
	}
	
	public function setupAction()
	{
		require('setup.php');
		
		$this->di->theme->setTitle("Setting up");
        $this->di->views->add('default/page', [
            'content' => '<p>"Question" setup done. <a href="' . $this->url->create('question/list') . '">All questions</a></p>'
        ]);
	}
	
	public function indexAction()
	{
		$this->activeAction();
	}
	
	/**
	 * List all questions.
	 *
	 * @return void
	 */
	public function listAction()
	{	
		if (empty($this->userInfo['admin'])) {
			die('You must be an admin to list all questions.');
		}
		
		$all = $this->question->findAll();
		
		$all = $this->question->findUser($all);
	 
		$this->theme->setTitle("List all questions");
		$this->views->add('question/list', [
			'questions' => $all,
			'text' => "<h1>All questions</h1>",
		]);
	}
	
	/**
	 * View question by id.
	 *
	 * @param int $id of question to display
	 *
	 * @return void
	 */
	public function viewAction($id = null)
	{
		if (!$id) {
			$this->redirectTo('question');
		}
		
		$this->question = $this->question->find($id);
		
		if ($this->question->deleted !== null || $this->question->id == null) {
			die("This question doesn't exist or has been removed.");
		}
		
		$inactive = null;
		if ($this->question->inactivated !== null) {
			$inactive = 1;
		}
		
		$this->question = $this->question->findUser([$this->question])[0];
	 
		$this->theme->setTitle($this->question->title);
		$this->views->add('question/view-question', [
			'question' => $this->question
		]);
		
		// Show tags
		$this->dispatcher->forward([
			'controller' => 'tags', 
			'action' => 'view-question', 
			'params' => [$this->question->id]
		]);
		
		// Show comments
		$this->dispatcher->forward([
			'controller' => 'comment', 
			'action' => 'view', 
			'params' => ["Question", $this->question->id, $inactive]
		]);
		
		// Show answers
		$this->dispatcher->forward([
			'controller' => 'answer', 
			'action' => 'view', 
			'params' => [$this->question->id]
		]);
		
		if ($inactive == null && isset($this->userInfo['id'])) {
			// Show answer form
			$this->dispatcher->forward([
				'controller' => 'answer', 
				'action' => 'add', 
				'params' => [$this->question->id]
			]);
		}
	}
	
	/**
	 * Add new question.
	 *
	 *
	 * @return void
	 */
	public function addAction()
	{
		if (empty($this->userInfo['id'])) {
			die('You must log in to add a question.');
		}
		
		$tags = $this->tags->prepareForForm();
		
		$form = new \Anax\Question\CFormAddQuestion($tags);
        $form->setDI($this->di);
		
		// Check the status of the form
        $form->check();
	 
		$this->di->theme->setTitle("Add question");
        $this->di->views->add('default/page', [
            'title' => "Add a question",
            'content' => $form->getHTML(),
        ]);
	}
	
	/**
	 * Edit a question.
	 *
	 * @param string $id of question to edit.
	 *
	 * @return void
	 */
	public function updateAction($id = null)
	{
		
		if (!$id) {
			$this->redirectTo('question');
		}

		$question = $this->question->find($id);
		
		if (($this->userInfo['id'] != $question->userID) && empty($this->userInfo['admin'])) {
			die('You can only edit your own questions.');
		}
		
		$tags = $this->tags->prepareForForm();
		$form = new \Anax\Question\CFormUpdateQuestion($question, $tags);
        $form->setDI($this->di);
		
		// Check the status of the form
        $form->check();
	 
		$this->di->theme->setTitle("Update question");
        $this->di->views->add('default/page', [
            'title' => "Update a question",
            'content' => $form->getHTML(),
        ]);
	}
	
	/**
	 * Delete question.
	 *
	 * @param integer $id of question to delete.
	 *
	 * @return void
	 */
	public function deleteAction($id = null)
	{
		if (!isset($id)) {
			die("Missing id");
		}
		
		if (empty($this->userInfo['admin'])) {
			die('You must be an admin to fully delete a question.');
		}
	 
		$res = $this->question->delete($id);
	 
		$this->redirectTo('question');
	}
	
	/**
	 * Delete (soft) question.
	 *
	 * @param integer $id of question to delete.
	 *
	 * @return void
	 */
	public function softDeleteAction($id = null)
	{
		if (!isset($id)) {
			die("Missing id");
		}
		
		$question = $this->question->find($id);
		
		if (($this->userInfo['id'] != $question->userID) && empty($this->userInfo['admin'])) {
			die('You can only edit your own questions.');
		}
	 
		$now = gmdate('Y-m-d H:i:s');
	 
		$question->deleted = $now;
		$question->save();
	
		$this->redirectTo('question');
	}
	
	/**
	 * Restore (soft) deleted question.
	 *
	 * @param integer $id of question to restore.
	 *
	 * @return void
	 */
	public function restoreAction($id = null)
	{
		if (!isset($id)) {
			die("Missing id");
		}
	 
		$question = $this->question->find($id);
		
		if (($this->userInfo['id'] != $question->userID) && empty($this->userInfo['admin'])) {
			die('You can only edit your own questions.');
		}
	 
		$question->deleted = null;
		$question->save();
	 
		$this->redirectTo('question/view/' . $question->id);
	}
	
	/**
	 * Activate question.
	 *
	 * @param integer $id of uquestion to activate.
	 *
	 * @return void
	 */
	public function activateAction($id = null)
	{
		if (!isset($id)) {
			die("Missing id");
		}
	 
		$question = $this->question->find($id);
		
		if (($this->userInfo['id'] != $question->userID) && empty($this->userInfo['admin'])) {
			die('You can only edit your own questions.');
		}
	 
		$question->inactivated = null;
		$question->save();
	 
		$this->redirectTo('question/view/' . $question->id);
	}
	
	/**
	 * Inactivate question.
	 *
	 * @param integer $id of question to inactivate.
	 *
	 * @return void
	 */
	public function inactivateAction($id = null)
	{
		if (!isset($id)) {
			die("Missing id");
		}
		
		$now = gmdate('Y-m-d H:i:s');
	 
		$question = $this->question->find($id);
		
		if (($this->userInfo['id'] != $question->userID) && empty($this->userInfo['admin'])) {
			die('You can only edit your own questions.');
		}
	 
		$question->inactivated = $now;
		$question->save();
	 
		$this->redirectTo('question/view/' . $question->id);
	}
	
	/**
	 * List all active and not deleted questions.
	 *
	 * @return void
	 */
	public function activeAction()
	{
		$all = $this->question->query()
			->where('inactivated IS NULL')
			->andWhere('deleted IS NULL')
			->execute();
			
		
		$all = $this->question->findUser($all);
	 
		$this->theme->setTitle("Questions that are active");
		$this->views->add('question/list', [
			'questions' => $all,
			'text' => "<h1>Active questions</h1>",
		]);
	}
	
	/**
	 * List all inactive questions.
	 *
	 * @return void
	 */
	public function inactiveAction()
	{
		if (empty($this->userInfo['admin'])) {
			die('You must be an admin to list inactive questions.');
		}
		
		$all = $this->question->query()
			->where('inactivated IS NOT NULL')
			->execute();
			
		$all = $this->question->findUser($all);
	 
		$this->theme->setTitle("Questions that are inactive");
		$this->views->add('question/list', [
			'questions' => $all,
			'text' => "<h1>Questions that are inactive</h1>",
		]);
	}
	
	/**
	 * List all deleted questions.
	 *
	 * @return void
	 */
	public function trashAction()
	{
		if (empty($this->userInfo['admin'])) {
			die('You must be an admin to list deleted questions.');
		}
		
		$all = $this->question->query()
			->where('deleted IS NOT NULL')
			->execute();
			
		$all = $this->question->findUser($all);
	 
		$this->theme->setTitle("Questions that are deleted");
		$this->views->add('question/list', [
			'questions' => $all,
			'text' => "<h1>Questions that are deleted</h1>",
		]);
	}
	 
}