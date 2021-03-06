<?php

use Faxbox\Repositories\Permission\PermissionInterface;
use Faxbox\Repositories\Group\GroupInterface;
use Faxbox\Service\Form\Group\GroupForm;
use Faxbox\Repositories\User\UserInterface;

class GroupController extends BaseController {

    protected $permissions;
    protected $groups;

    public function __construct(
        PermissionInterface $permissions,
        GroupInterface $groups,
        GroupForm $groupForm,
        UserInterface $users
    ) {
        parent::__construct();

        $this->permissions = $permissions;
        $this->groups      = $groups;
        $this->groupForm   = $groupForm;
        $this->users       = $users;

        $this->beforeFilter('auth');

        // group administration is currently only available to the admin
        $this->beforeFilter('hasAccess:superuser');
    }

    public function index()
    {
        $groups = (array)$this->groups->allWithUsers();
        $users = $this->users->all();

        $this->view('groups.list', compact('groups', 'users'));
    }

    public function create()
    {
        $permissions = $this->permissions->allWithChecked([], 0);
        $this->view('groups.create', compact('permissions'));
    }

    public function store()
    {
        // Form Processing
        $result = $this->groupForm->save(Input::all());

        if ($result['success'])
        {
            // Success!
            Session::flash('success', $result['message']);

            return Redirect::action('GroupController@index');

        } else
        {
            Session::flash('error', $result['message']);

            return Redirect::action('GroupController@create')
                           ->withInput()
                           ->withErrors($this->groupForm->errors());
        }
    }

    public function edit($id)
    {
        $group = $this->groups->withUsers($id);
        $users = $this->users->all();
        
        $this->view('groups.edit', compact('group', 'users'));
    }

    public function update($id)
    {
        // Form Processing
        $result = $this->groupForm->update(Input::all());
        
        if ($result['success'])
        {
            // Success!
            Session::flash('success', $result['message']);

            return Redirect::action('GroupController@edit', ['id' => $id]);

        } else
        {
            Session::flash('error', $result['message']);

            return Redirect::action('GroupController@edit', ['id' => $id])
                           ->withInput()
                           ->withErrors($this->groupForm->errors());
        }
    }

    public function destroy($id)
    {
        if ($this->groups->destroy($id))
        {
            Session::flash('success', 'Group Deleted');
            return Redirect::action('GroupController@index');
        }
        else
        {
            Session::flash('error', 'Unable to Delete Group');
            return Redirect::action('GroupController@index');
        }
    }

}
