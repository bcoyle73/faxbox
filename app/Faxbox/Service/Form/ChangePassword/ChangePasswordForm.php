<?php namespace Faxbox\Service\Form\ChangePassword;

use Faxbox\Service\Validation\ValidableInterface;
use Faxbox\Repositories\User\UserInterface;

class ChangePasswordForm {

	/**
	 * Form Data
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * Validator
	 *
	 * @var \Faxbox\Service\Form\ValidableInterface 
	 */
	protected $validator;

	/**
	 * Session Repository
	 *
	 * @var \Faxbox\Repositories\Session\SessionInterface 
	 */
	protected $user;

	public function __construct(ValidableInterface $validator, UserInterface $user)
	{
		$this->validator = $validator;
		$this->user = $user;

	}

	/**
     * Create a new user
     *
     * @return integer
     */
    public function change(array $input)
    {
        if( ! $this->valid($input) )
        {
            return false;
        }

        return $this->user->changePassword($input);
    }

	/**
	 * Return any validation errors
	 *
	 * @return array 
	 */
	public function errors()
	{
		return $this->validator->errors();
	}

	/**
	 * Test if form validator passes
	 *
	 * @return boolean 
	 */
	protected function valid(array $input)
	{

		return $this->validator->with($input)->passes();
		
	}


}