<?php

namespace App\Http\Controllers\Admin;

use Backpack\PermissionManager\app\Http\Controllers\UserCrudController as PM_UserCrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends PM_UserCrudController
{
    public function setup()
    {
        parent::setup();

        // Permisos User
        if (!backpack_user()->hasRole('admin', 'web')) {
            CRUD::denyAccess(['list', 'create', 'edit', 'delete']);
        }
    }

    public function setupListOperation()
    {
        parent::setupListOperation();
        $this->crud->removeColumn('permissions');
    }

    
}
