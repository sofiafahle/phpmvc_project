<?php

namespace Anax\Answer;
 
/**
 * A controller for answers.
 *
 */
class AnswerController implements \Anax\DI\IInjectionAware
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
		$this->answer = new \Anax\Answer\Answer();
		$this->answer->setDI($this->di);
	}
	
	public function setupAction()
	{
		require('setup.php');
		
		$this->di->theme->setTitle("Setting up");
        $this->di->views->add('default/page', [
            'content' => '<p>"Answer" setup done.</p>'
        ]);
	}
	
	public function indexAction()
	{
		$this->activeAction();
	}
	
	/**
	 * List all answers.
	 *
	 * @return void
	 */
	public function listAction()
	{
		if (empty($this->userInfo['admin'])) {
			die('You must be an admin to list all answers.');
		}
		
		$all = $this->answer->findAll();
	 
		$number = 1;
		foreach ($all as $answer) {
			
			$answer->number = $number;
			$number++;
			
			$answer->setDI($this->di);
			$answer = $answer->findUser([$answer])[0];
			$answer = $answer->findParent($answer, $answer->questionID);
		}
		
		$this->theme->setTitle('List answers');
		$this->views->add('answer/list', [
				'answers' => $all,
				'text' => '<h1>List all answers</h1>',
			]);
	}
	
	/**
	 * View answers by question id.
	 *
	 * @param int $id of question as parameter for query.
	 *
	 * @return void
	 */
	public function viewAction($questionID = null)
	{
		if (!$questionID) {
			$this->redirectTo('answer');
		}
		
		$answers = $this->answer->findWhere('questionID', $questionID);
		
		
		$number = 1;
		foreach ($answers as $answer) {
			if ($answer->deleted == null) {
					$answer->number = $number;
					$number++;
			}
		}
		
		usort($answers, function($a, $b)
		{
			if ($a->accepted == $b->accepted) {
				return 0;
			}
			return ($a->accepted < $b->accepted) ? 1 : -1;
		});
	 	
		
		foreach ($answers as $answer) {
			
			if ($answer->deleted == null) {
				
				$answer->setDI($this->di);
				$answer = $this->answer->findRelated([$answer])[0];
				
				$this->views->add('answer/view-answer', [
					'answer' => $answer
				]);
				
				if (($this->userInfo['id'] == $answer->question->userID || isset($this->userInfo['admin'])) && $answer->question->answered == null) {
					$this->views->add('default/page', [
						'content' => '<a href="' . $this->url->create('answer/accepted/' . $answer->id) . '">Mark as accepted answer</a>',
					]);
				}
				
				if ($answer->inactivated == null) {
					// Show comments
					$this->dispatcher->forward([
						'controller' => 'comment', 
						'action' => 'view', 
						'params' => ["Answer", $answer->id]
					]);
				}
			}
			
		}
	}
	
	/**
	 * View answer by id.
	 *
	 * @param int $id of answer to display.
	 *
	 * @return void
	 */
	public function idAction($id = null)
	{
		if (!$id) {
			$this->redirectTo('');
		}
		
		$this->answer = $this->answer->find($id);
		$this->answer = $this->answer->findRelated([$this->answer])[0];
			
		$this->di->theme->setTitle("View answer");
		$this->views->add('answer/view-answer', [
			'answer' => $this->answer,
		]);
		
		// Show comments
		$this->dispatcher->forward([
			'controller' => 'comment', 
			'action' => 'view', 
			'params' => ["Answer", $this->answer->id]
		]);
	}
	
	/**
	 * Add new answer.
	 *
	 *
	 * @return void
	 */
	public function addAction($questionID = null)
	{
		if (empty($this->userInfo['id'])) {
			die('You must log in to add an answer.');
		}
		
		$form = new \Anax\Answer\CFormAddAnswer($questionID);
		$form->setDI($this->di);
		
		// Check the status of the form
		$form->check();
	 
		$this->di->views->add('default/page', [
			'title' => "Add an answer",
			'content' => $form->getHTML(),
		]);
	}
	
	/**
	 * Edit a answer.
	 *
	 * @param string $id of answer to edit.
	 *
	 * @return void
	 */
	public function updateAction($id = null)
	{
		
		if (!$id) {
			$this->redirectTo('answer');
		}
		
		if (empty($this->userInfo['id'])) {
			die('You must log in to edit an answer.');
		}
		
		$answer = $this->answer->find($id);
		
		if (($this->userInfo['id'] != $answer->userID) && empty($this->userInfo['admin'])) {
			die('You can only edit your own answers.');
		}
		
		$form = new \Anax\Answer\CFormUpdateAnswer($answer);
        $form->setDI($this->di);
		
		// Check the status of the form
        $form->check();
	 
		$this->di->theme->setTitle("Update answer");
        $this->di->views->add('default/page', [
            'title' => "Update a answer",
            'content' => $form->getHTML()
        ]);
	}
	
	/**
	 * Delete answer.
	 *
	 * @param integer $id of answer to delete.
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
	 
		$res = $this->answer->delete($id);
	 
		$answer = $this->answer->findRelated([$answer])[0];
		$this->redirectTo('question/view/' . $answer->question->id);
	}
	
	/**
	 * Delete (soft) answer.
	 *
	 * @param integer $id of answer to delete.
	 *
	 * @return void
	 */
	public function softDeleteAction($id = null)
	{
		if (!isset($id)) {
			die("Missing id");
		}
	 
		$now = gmdate('Y-m-d H:i:s');
	 
		$answer = $this->answer->find($id);
		
		if (($this->userInfo['id'] != $answer->userID) && empty($this->userInfo['admin'])) {
			die('You can only edit your own answers.');
		}
	 
		$answer->deleted = $now;
		$answer->save();
	
		$answer = $this->answer->findRelated([$answer])[0];
		$this->redirectTo('question/view/' . $answer->question->id);
	}
	
	/**
	 * Restore (soft) deleted answer.
	 *
	 * @param integer $id of answer to restore.
	 *
	 * @return void
	 */
	public function restoreAction($id = null)
	{
		if (!isset($id)) {
			die("Missing id");
		}
	 
		$answer = $this->answer->find($id);
		
		if (($this->userInfo['id'] != $answer->userID) && empty($this->userInfo['admin'])) {
			die('You can only edit your own answers.');
		}
	 
		$answer->deleted = null;
		$answer->save();
	 
		$answer = $this->answer->findRelated([$answer])[0];
		$this->redirectTo('question/view/' . $answer->question->id);
	}
	
	/**
	 * Activate answer.
	 *
	 * @param integer $id of uanswer to activate.
	 *
	 * @return void
	 */
	public function activateAction($id = null)
	{
		if (!isset($id)) {
			die("Missing id");
		}
	 
		$answer = $this->answer->find($id);
		
		if (($this->userInfo['id'] != $answer->userID) && empty($this->userInfo['admin'])) {
			die('You can only edit your own answers.');
		}
	 
		$answer->inactivated = null;
		$answer->save();
	 
		$answer = $this->answer->findRelated([$answer])[0];
		$this->redirectTo('question/view/' . $answer->question->id);
	}
	
	/**
	 * Inactivate answer.
	 *
	 * @param integer $id of answer to inactivate.
	 *
	 * @return void
	 */
	public function inactivateAction($id = null)
	{
		if (!isset($id)) {
			die("Missing id");
		}
		
		$now = gmdate('Y-m-d H:i:s');
	 
		$answer = $this->answer->find($id);
		
		if (($this->userInfo['id'] != $answer->userID) && empty($this->userInfo['admin'])) {
			die('You can only edit your own answers.');
		}
	 
		$answer->inactivated = $now;
		$answer->save();
	 
		$answer = $this->answer->findRelated([$answer])[0];
		$this->redirectTo('question/view/' . $answer->question->id);
	}
	
	/**
	 * List all active and not deleted answers.
	 *
	 * @return void
	 */
	public function activeAction()
	{
		$all = $this->answer->query()
			->where('inactivated IS NULL')
			->andWhere('deleted IS NULL')
			->execute();
			
		$all = $this->answer->findRelated($all);
	 
		$this->theme->setTitle("Answers that are active");
		$this->views->add('answer/list', [
			'answers' => $all,
			'text' => "<h1>Answers that are active</h1>",
		]);
	}
	
	/**
	 * List all inactive answers.
	 *
	 * @return void
	 */
	public function inactiveAction()
	{
		if (empty($this->userInfo['admin'])) {
			die('You must be an admin to list inactive answers.');
		}
		
		$all = $this->answer->query()
			->where('inactivated IS NOT NULL')
			->execute();
			
		$all = $this->answer->findRelated($all);
	 
		$this->theme->setTitle("Answers that are inactive");
		$this->views->add('answer/list', [
			'answers' => $all,
			'text' => '<h1>Answers that are inactive</h1>',
		]);
	}
	
	/**
	 * List all deleted answers.
	 *
	 * @return void
	 */
	public function trashAction()
	{
		if (empty($this->userInfo['admin'])) {
			die('You must be an admin to list deleted answers.');
		}
		
		$all = $this->answer->query()
			->where('deleted IS NOT NULL')
			->execute();
			
		$all = $this->answer->findRelated($all);
	 
		$this->theme->setTitle("Answers that are deleted");
		$this->views->add('answer/list', [
			'answers' => $all,
			'text' => "<h1>Answers that are deleted</h1>",
		]);
	}
	
	/**
	 * Mark answer as accepted.
	 *
	 * @param int $id as id of answer to accept.
	 * @return void
	 */
	public function acceptedAction($id = null) {
		
		if (!isset($id)) {
			die("Missing id");
		}
	 
		$answer = $this->answer->find($id);
		$answer->setDI($this->di);
		
		$answer = $this->answer->findRelated([$answer])[0];
		
		
		if (($this->userInfo['id'] != $answer->question->userID) && empty($this->userInfo['admin'])) {
			die('You can only accept answers to your own questions.');
		}
		
		$question = $answer->question;
		
		unset($answer->question);
		unset($answer->user);
		
		$now = gmdate('Y-m-d H:i:s');
		
		$answer->accepted = $now;
		$answer->save();
		
		$question->answered = $now;
		$question->save();
		
		$this->redirectTo('question/view/' . $question->id);
		
	}
	 
}