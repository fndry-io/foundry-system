<?php

namespace Foundry\System\Console\Commands;

use Foundry\System\Inputs\User\UserRegisterInput;
use Illuminate\Console\Command;
use Foundry\System\Services\UserService;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;

class UsersRegisterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'foundry-system:users:register
        {username : The username}
        {display_name : The users display name} 
        {email : The email address of the user to create}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Registers a new user in the application';

	/**
	 * @var UserService
	 */
    protected $service;

	/**
	 * UsersRegisterCommand constructor.
	 *
	 * @param UserService $service
	 */
    public function __construct(UserService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	    $arguments = $this->arguments();
	    $password = $this->secret('What is the password? (leave empty to auto generate a password)');
	    $password_confirmation = null;
	    if ($password) {
		    $password_confirmation = $this->secret('Confirm your password');
	    }
	    $super = $this->choice('Should this user be a Super Admin', ['No', 'Yes'], 0);

	    if (empty($password)) {
		    $password = $password_confirmation = Str::random(8);
	    }
	    $arguments['password'] = $password;
	    $arguments['password_confirmation'] = $password_confirmation;

	    $entity = new UserRegisterInput($arguments);
	    if ($super) {
		    $entity->super_admin = true;
	    }

	    $response = $entity->validate();
	    if ($response->isSuccess()) {
		    $response = $this->service->register($entity);
		    if ($response->isSuccess()) {
			    $user = $response->getData();
			    $user = $user->only(['id', 'username', 'display_name', 'email', 'super_admin']);
			    $user['password'] = $password;
			    $this->info('User registered');
			    $this->table(['ID', 'Username', 'Display Name', 'Email', 'Super Admin', 'Password'], [$user]);
			    return;
		    }
	    }

	    $this->error('User could not be registered. See below for errors.');
	    if ($response->getCode() == 422) {
		    /**
		     * @var MessageBag $errors
		     */
	    	$errors = $response->getData();
		    $this->table(['Error'], $errors->getMessages());
	    } else {
		    $error = $response->getError();
		    $this->table(['Error'], [(array) $error]);
	    }
    }
}
