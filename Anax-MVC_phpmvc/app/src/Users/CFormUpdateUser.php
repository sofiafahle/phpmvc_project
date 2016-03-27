<?php

namespace Anax\Users;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormUpdateUser extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

	public $user;

    /**
     * Constructor
     *
     */
    public function __construct($user)
    {
		if(!$user) {
			die('Not a valid user!');
		}
		
		$this->user = $user;
		
        parent::__construct([], [
			'id' => [
				'type'  	  => 'hidden',
				'value'       => $user->id,
				'required'    => true,
				'validation'  => ['not_empty'],
			],
            'acronym' => [
                'type'        => 'text',
                'label'       => 'Username:',
                'required'    => true,
                'validation'  => ['not_empty'],
				'value' 	  => $user->acronym,
				'readonly'	  => true
            ],
			'password' => [
                'type'        => 'password',
                'required'    => true,
                'validation'  => ['not_empty'],
				'value' 	  => $user->password,
            ],
            'name' => [
                'type'        => 'text',
                'label'       => 'Your name:',
                'required'    => true,
                'validation'  => ['not_empty'],
				'value' 	  => $user->name,
            ],
            'email' => [
                'type'        => 'email',
                'required'    => true,
                'validation'  => ['not_empty', 'email_adress'],
				'value' 	  => $user->email,
				'description' => 'Your image is based on your email-adress and fetched from <a href="http://www.gravatar.com/" target="_blank">Gravatar</a>',
            ],
			'about' => [
                'type'        => 'textarea',
                'label'       => 'About you:',
				'value' 	  => $user->about,
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
		$now = gmdate('Y-m-d H:i:s');
		
		$this->user->save([
			'id'  => $this->Value('id'),
			'email' => $this->Value('email'),
			'name' => $this->Value('name'),
			'about' => htmlentities($this->Value('about')),
			'password' => password_hash($this->Value('password'), PASSWORD_DEFAULT),
			'updated' => $now,
		]);

        return true;
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
