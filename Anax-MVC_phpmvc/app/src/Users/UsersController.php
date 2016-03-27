<?php

namespace Anax\Users;
 
/**
 * A controller for users and admin related events.
 *
 */
class UsersController implements \Anax\DI\IInjectionAware
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
		$this->users = new \Anax\Users\Users();
		$this->users->setDI($this->di);
	}
	
	public function setupAction()
	{
		include('setup.php');
		
		$this->di->theme->setTitle("Setting up");
        $this->di->views->add('default/page', [
            'content' => '<p>"Users" setup done. <a href="' . $this->url->create('users/list') . '">All users</a></p>'
        ]);
	}
	
	public function indexAction() {
		$this->activeAction();
	}
	
	
	/**
	 * List all users.
	 *
	 * @return void
	 */
	public function listAction()
	{
		if (empty($this->userInfo['admin'])) {
			die('You must be an admin to list all users.');
		}
		
		$all = $this->users->findAll();
	 
		$this->theme->setTitle("List all users");
		$this->views->add('users/list', [
			'users' => $all,
			'title' => "View all users",
		]);
	}
	
	/**
	 * List user with id.
	 *
	 * @param int $id of user to display
	 *
	 * @return void
	 */
	public function idAction($id = null)
	{
		if (!isset($id)) {
			die("Missing id");
		}
		
		$this->users = $this->users->find($id);
		
		if ($this->users->deleted !== null || $this->users->id == null) {
			die("This user doesn't exist or has been removed.");
		}
		
		$this->users->posts = $this->users->findPosts($id);
		
		foreach ($this->users->posts['answers'] as $answer) {
			$answer->setDI($this->di);
			$answer = $answer->findParent($answer, $answer->questionID);
		}
		
		foreach ($this->users->posts['comments'] as $comment) {
			$comment->setDI($this->di);
			$comment = $comment->findParent($comment, $comment->parentID);
		} 
	 
		$this->theme->setTitle("View user with id");
		$this->views->add('users/view', [
			'user' => $this->users,
			'title' => $this->users->acronym,
		]);
	}
	
	/**
	 * List all users.
	 *
	 * @return void
	 */
	public function idPostsAction($id = null)
	{
		if (!isset($id)) {
			die("Missing id");
		}
		
		$this->users = $this->users->find($id);
		
		if (($this->userInfo['id'] !== $this->users->id) && empty($this->userInfo['admin'])) {
			die('You can only list your own deleted/inactive items.');
		}
		
		$this->users->posts = $this->users->findPosts($id);
		
		foreach ($this->users->posts['answers'] as $answer) {
			$answer->setDI($this->di);
			$answer = $answer->findParent($answer, $answer->questionID);
		}
		
		foreach ($this->users->posts['comments'] as $comment) {
			$comment->setDI($this->di);
			$comment = $comment->findParent($comment, $comment->parentID);
		} 
	 
		$this->theme->setTitle("View user with id");
		$this->views->add('users/list-posts', [
			'user' => $this->users,
			'title' => $this->users->acronym,
		]);
	}
	
	/**
	 * Add new user.
	 *
	 *
	 * @return void
	 */
	public function addAction()
	{
		$form = new \Anax\Users\CFormAddUser();
        $form->setDI($this->di);
		
		// Check the status of the form
        $form->check();
	 
		$this->di->theme->setTitle("Add user");
        $this->di->views->add('default/page', [
            'title' => "Add a user",
            'content' => $form->getHTML()
        ]);
	}
	
	/**
	 * Edit a user.
	 *
	 * @param string $id of user to edit.
	 *
	 * @return void
	 */
	public function updateAction($id = null)
	{
		if (!$id) {
			$this->redirectTo('users');
		}
		
		$user = $this->users->find($id);
		
		if (($this->userInfo['id'] !== $user->id) && empty($this->userInfo['admin'])) {
			die('You can only edit your own account.');
		}
		
		$form = new \Anax\Users\CFormUpdateUser($user);
        $form->setDI($this->di);
		
		// Check the status of the form
        $form->check();
	 
		$this->di->theme->setTitle("Update user");
        $this->di->views->add('default/page', [
            'title' => "Update a user",
            'content' => $form->getHTML()
        ]);
	}
	
	/**
	 * Delete user.
	 *
	 * @param integer $id of user to delete.
	 *
	 * @return void
	 */
	public function deleteAction($id = null)
	{
		if (!isset($id)) {
			die("Missing id");
		}
		
		if (empty($this->userInfo['admin'])) {
			die('You must be an admin to delete users.');
		}
	 
		$res = $this->users->delete($id);
	 
		$url = $this->url->create('users');
		$this->response->redirect($url);
	}
	
	/**
	 * Delete (soft) user.
	 *
	 * @param integer $id of user to delete.
	 *
	 * @return void
	 */
	public function softDeleteAction($id = null)
	{
		if (!isset($id)) {
			die("Missing id");
		}
	 
		$now = gmdate('Y-m-d H:i:s');
	 
		$user = $this->users->find($id);
		
		if (($this->userInfo['id'] !== $user->id) && empty($this->userInfo['admin'])) {
			die('You can only edit your own account.');
		}
	 
		$user->deleted = $now;
		$user->save();
	 
		$url = $this->url->create('users/trash');
		$this->response->redirect($url);
	}
	
	/**
	 * Restore (soft) deleted user.
	 *
	 * @param integer $id of user to restore.
	 *
	 * @return void
	 */
	public function restoreAction($id = null)
	{
		if (!isset($id)) {
			die("Missing id");
		}
		
		if (empty($this->userInfo['admin'])) {
			die('You must be an admin to restore users.');
		}
	 
		$user = $this->users->find($id);
	 
		$user->deleted = null;
		$user->save();
	 
		$url = $this->url->create('users/active');
		$this->response->redirect($url);
	}
	
	/**
	 * Activate user.
	 *
	 * @param integer $id of user to activate.
	 *
	 * @return void
	 */
	public function activateAction($id = null)
	{
		if (!isset($id)) {
			die("Missing id");
		}
	 
		$user = $this->users->find($id);
		
		if (($this->userInfo['id'] !== $user->id) && empty($this->userInfo['admin'])) {
			die('You can only edit your own account.');
		}
	 
		$user->inactivated = null;
		$user->save();
	 
		$url = $this->url->create('users/active');
		$this->response->redirect($url);
	}
	
	/**
	 * Inactivate user.
	 *
	 * @param integer $id of user to inactivate.
	 *
	 * @return void
	 */
	public function inactivateAction($id = null)
	{
		if (!isset($id)) {
			die("Missing id");
		}
	 
		$user = $this->users->find($id);
		
		if (($this->userInfo['id'] !== $user->id) && empty($this->userInfo['admin'])) {
			die('You can only edit your own account.');
		}
		
		$now = gmdate('Y-m-d H:i:s');
	 
		$user->inactivated = $now;
		$user->save();
	 
		$url = $this->url->create('users/inactive');
		$this->response->redirect($url);
	}
	
	/**
	 * List all active and not deleted users.
	 *
	 * @return void
	 */
	public function activeAction()
	{
		$all = $this->users->query()
			->where('inactivated IS NULL')
			->andWhere('deleted is NULL')
			->execute();
	 
		$this->theme->setTitle("Users");
		$this->views->add('users/list', [
			'users' => $all,
			'title' => "Users",
		]);
	}
	
	/**
	 * List all inactive users.
	 *
	 * @return void
	 */
	public function inactiveAction()
	{
		if (empty($this->userInfo['admin'])) {
			die('You must be an admin to list inactive users.');
		}
		
		$all = $this->users->query()
			->where('inactivated IS NOT NULL')
			->execute();
	 
		$this->theme->setTitle("Users that are inactive");
		$this->views->add('users/list', [
			'users' => $all,
			'title' => "Users that are inactive",
		]);
	}
	
	/**
	 * List all deleted users.
	 *
	 * @return void
	 */
	public function trashAction()
	{
		if (empty($this->userInfo['admin'])) {
			die('You must be an admin to list deleted users.');
		}
		
		$all = $this->users->query()
			->where('deleted IS NOT NULL')
			->execute();
	 
		$this->theme->setTitle("Users that are deleted");
		$this->views->add('users/list', [
			'users' => $all,
			'title' => "Users that are deleted",
		]);
	}
	
		
	/**
	 * Log on user.
	 *
	 * @return void
	 */
	public function loginAction()
	{
		if ($this->session->get('user') == null) {
			$form = new \Anax\Users\CFormLogin();
			$form->setDI($this->di);
			
			// Check the status of the form
			$form->check();
		 
			$this->theme->setTitle("List all users");
			$this->views->add('default/page', [
				'content' => $form->getHTML(),
				'title' => "View all users",
			]);
			
		} else {
			$this->redirectTo('');
		}
	}
	
	/**
	 * Log off user.
	 *
	 * @return void
	 */
	public function logoutAction()
	{
		$this->di->session->set('user', null);
		$this->redirectTo('');
	}
	 
}