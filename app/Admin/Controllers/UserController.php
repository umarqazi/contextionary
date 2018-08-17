<?php

namespace App\Admin\Controllers;

use \Illuminate\Database\Eloquent\Model;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use App\User;
use App\Profile;
use App\Services\UserService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    protected  $userServices;

    public function __construct(UserService $userServices)
    {
        $this->userServices = $userServices;
    }

    public function userCount(){
        $countUsers = $this->userServices->count();
        return view('admin::dashboard.blocks',
            [
                'value'      => $countUsers,
                'label'      => 'Users',
                'url'        => '/admin/auth/simple-users',
                'urlLabel'   => 'All Users'
            ]
        );
    }

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('Users'));
            $content->description(trans('User List'));
            $content->body($this->grid()->render());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(User::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->column('Name')->display(function () {
                return $this->first_name . ' ' . $this->last_name;
            });
            $grid->column('email','Email');
            $grid->roles()->pluck('name')->label();
            $grid->column('profile.native_language')->display(function () {
                return $this->profile['native_language'];
            });
            $grid->filter(function ($filter){
                $filter->like('name');
                $filter->like('email');
            });
            $grid->actions(function (Grid\Displayers\Actions $actions) {});
            $grid->tools(function (Grid\Tools $tools) {
                $tools->batch(function (Grid\Tools\BatchActions $actions) {
                    $actions->disableDelete();
                });
            });
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header('Users');
            $content->description('Create new user');
            $content->body($this->form());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     *
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('Users');
            $content->description('Edit User');
            $content->body($this->form($id)->edit($id));
        });
    }

    /**
     * Make a form builder.
     *
     * @param $id
     * @return Form
     */
    public function form($id = null)
    {
        $user = User::find($id);
        if($user){
            $current_role = $user->getRoleNames()->first();
        }else{
            $current_role = '';
        }
        $roles = Role::all()->pluck('name','name');
        return Admin::form(User::class, function (Form $form) use ($id, $roles, $user, $current_role) {
            $form->display('id', 'ID');
            $form->text('first_name', trans('First Name'))->rules('required')->placeholder('Enter Name...');
            $form->text('last_name', trans('Last Name'))->rules('required')->placeholder('Enter Name...');
            $form->email('email', trans('Email'))->rules('required')->placeholder('Enter Email...');
            $form->password('password', trans('Password'))->rules('required|confirmed')
                ->default(function ($form) {
                return $form->model()->password;
                })->placeholder('Enter Password...');
            $form->password('password_confirmation', trans('Password Confirmation'))->rules('required')
                ->default(function ($form) {
                    return $form->model()->password;
                })->placeholder('Confirm Password...');
            $form->radio('role', trans('Roles'))->options($roles)->default($current_role);
            $form->display('profile.pseudonyme', 'Pseudonyme');
            $form->display('profile.gender', 'Gender');
            $form->date('profile.date_birth', 'Date of Birth');
            $form->display('profile.phone_number', 'Phone #');
            $form->display('profile.native_language', 'Language');
            $form->display('profile.country', 'Country');
            $form->ignore(['password_confirmation', 'role']);
            $form->saving(function (Form $form) use ($user){
                if ($form->password && $form->model()->password != $form->password) {
                    $form->password = bcrypt($form->password);
                }
                if($user != null){
                    $role =request()->role;
                    $user->syncRoles([$role]);
                }
            });
            $form->saved(function () use ($id) {
                if($id){
                    admin_toastr(trans('Updated successfully!'));
                }else{
                    admin_toastr(trans('New User created successfully!'));
                }
                return redirect(admin_base_path('auth/simple-users'));
            });
        });
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Content
     */
    public function show($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('Users');
            $content->description('User profile');
            $content->body($this->displayForm($id)->view($id));
        });
    }



    /**
     * Make a display form builder.
     *
     * @param $id
     *
     * @return Form
     */
    public function displayForm($id = null)
    {
        return Admin::form(User::class, function (Form $form) {
            $form->image('profile_image', 'Profile Image')->setWidth(12);
            $form->display('id', 'ID')->setWidth(8,4);
            $form->display('first_name', trans('First Name'))->setWidth(8,4);
            $form->display('last_name', trans('Last Name'))->setWidth(8,4);
            $form->display('email', trans('Email'))->setWidth(8,4);
            $form->display('profile.pseudonyme', 'Pseudonyme')->setWidth(8,4);
            $form->display('profile.gender', 'Gender')->setWidth(8,4);
            $form->display('profile.date_birth', 'Date of Birth')->setWidth(8,4);
            $form->display('profile.phone_number', 'Phone #')->setWidth(8,4);
            $form->display('profile.native_language', 'Language')->setWidth(8,4);
            $form->display('profile.country', 'Country')->setWidth(8,4);
            $form->multipleSelect('roles', trans('Roles'))->options(function () {
                return Role::all()->pluck('name', 'id');
            })->rules('required')->placeholder('Select Role...')->attribute(['disabled'=>'true'])->setWidth(8,4);
//            echo '<pre>';
//            print_r($form->getRelations());
            $form->setView('admin.profile');
//            $form->display('created_at', trans('Created at'));
//            $form->display('updated_at', trans('Last Updated at'));
            $form->disableSubmit();
            $form->disableReset();
        });
    }


    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Form
     */
    public function update($id)
    {
        return $this->form($id)->update($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user->delete()) {
            admin_toastr(trans('admin.delete_succeeded'));
            return response()->json([
                'status'  => true,
                'message' => trans('admin.delete_succeeded'),
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'message' => trans('admin.delete_failed'),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        return $this->form()->store();
    }

}
