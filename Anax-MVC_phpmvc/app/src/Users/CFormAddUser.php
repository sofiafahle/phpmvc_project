<?php

namespace Anax\Users;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormAddUser extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;



    /**
     * Constructor
     *
     */
    public function __construct()
    {
        parent::__construct([], [
            'acronym' => [
                'type'        => 'text',
                'label'       => 'Username:',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
			'password' => [
                'type'        => 'password',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'name' => [
                'type'        => 'text',
                'label'       => 'Your name:',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'email' => [
                'type'        => 'email',
                'required'    => true,
                'validation'  => ['not_empty', 'email_adress'],
            ],
			'email2' => [
                'type'        => 'email',
                'label'       => 'Confirm email:',
                'required'    => true,
                "validation" => ['match' => 'email'],
				'description' => 'Your image is based on your email-adress and fetched from <a href="http://www.gravatar.com/" target="_blank">Gravatar</a>',
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
		$this->users = new \Anax\Users\Users();
        $this->users->setDI($this->di);
		
		$now = gmdate('Y-m-d H:i:s');
		
		$this->users->save([
			'acronym' => $this->Value('acronym'),
			'email' => $this->Value('email'),
			'name' => $this->Value('name'),
			'password' => password_hash($this->Value('password'), PASSWORD_DEFAULT),
			'gravatar' => 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($this->Value('email')))),
			'created' => $now,
		]);
		
        $this->saveInSession = true;
        return true;
    }



    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess()
    {
        $this->AddOUtput("<p><i>Form was submitted and the user was added successfully.</i></p>");
        $this->redirectTo();
    }



    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        $this->AddOutput("<p><i>Form was submitted and the Check() method returned false.</i></p>");
    }
}
