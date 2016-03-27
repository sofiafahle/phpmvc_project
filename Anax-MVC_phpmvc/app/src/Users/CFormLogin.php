<?php

namespace Anax\Users;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormLogin extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;
		
	public $user;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        parent::__construct([], [
			'username' => [
                'type'        => 'text',
                'label'       => 'Username:',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'password' => [
                'type'        => 'password',
                'label'       => 'Password:',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'submit' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackSubmit'],
            ],
        ]);
    }



    /**
     * Customise the check() method.
     *
     * @param callable $callIfSuccess handler to call if function returns true.
     * @param callable $callIfFail    handler to call if function returns true.
     */
    public function check($callIfSuccess = null, $callIfFail = null)
    {
        return parent::check([$this, 'callbackSuccess'], [$this, 'callbackFail']);
    }



    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmit()
    {
		$this->user = new \Anax\Users\Users();
		$this->user->setDI($this->di);
		
		$users = $this->user->findWhere('acronym', $this->Value('username'));
		
		if ($users) {
			$this->user = $this->user->find($users[0]->id);
			$verified = password_verify($this->Value('password'), $this->user->password);
			
			if ($verified && $this->user->deleted == null) {
				$this->di->session->set('user', $this->user->id);
				$this->di->session->set('username', $this->user->acronym);
				$this->di->session->set('userLevel', 1);
				
				if ($this->user->id == 1) {
					$this->di->session->set('userLevel', 2);
				}
				
				return true;
			}
			
		} else {
			return false;
		}

    }



    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess()
    {
        $this->redirectTo('users/id/' . $this->user->id);
    }



    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        $this->AddOutput("<p><i>Form was submitted and the Check() method returned false.</i></p>");
		$this->redirectTo();
    }
}
