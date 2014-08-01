<?php

use Faxbox\Repositories\User\UserInterface as Users;
use Faxbox\Repositories\Fax\FaxInterface;

class FaxController extends BaseController {

    public function __construct(FaxInterface $faxes, Users $users)
    {
        parent::__construct();

        $this->users = $users;
        $this->faxes = $faxes;
        
        $this->beforeFilter('auth');
        $this->beforeFilter('hasAccess:send_fax', ['only' => ['store', 'create']]);
    }

    public function index()
    {
        // todo this should be moved into the repo
        $user = Sentry::getUser();

        // todo move admin check to repo
        if ($this->users->isAdmin($user->getId()))
        {
            $faxes = $this->faxes->all();
        } else
        {
            $faxes = $this->faxes->findByUserId($user->getId());
        }

        $this->view('fax.list', compact('faxes'));
    }
    
    public function create()
    {

    }

    public function store()
    {

    }

    public function show($id)
    {

    }

}
