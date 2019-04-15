<?php

namespace App\Admin\Controllers;

use App\Coin;
use App\Services\RedeemService;
use App\Services\UserService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Database\Eloquent\Model;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\AdaptivePayments;
use App\RedeemPoint;


class RedeemController extends Controller
{
    /**
     * @var RedeemService
     */
    protected $redeem_service;

    /**
     * @var UserService
     */
    protected $user_service;

    /**
     * @var AdaptivePayments
     */
    protected $provider;

    /**
     * RedeemController constructor.
     */
    public function __construct()
    {
        $this->redeem_service   = new RedeemService();
        $this->user_service     = new UserService();
        $this->provider         = new AdaptivePayments;
    }

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('Redeem Points'));
            $content->description(trans('Redeemable Points List'));
            $content->body($this->grid()->render());
        });
    }

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index2()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('Redeem Points'));
            $content->description(trans('Redeemed Points List'));
            $content->body($this->grid2()->render());
        });
    }


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(RedeemPoint::class, function (Grid $grid) {
            $grid->model()->where('status', 0);
            $grid->id('ID')->sortable();
            $grid->disableExport();
            $grid->disableFilter();
            $grid->option('useWidth', true);
            $grid->column('users.name', 'User Name')->display(function () {
                return $this->users['first_name'].' '.$this->users['last_name'];
            });
            $grid->points();
            $grid->earning();
            $grid->column('created_at','Created at')->sortable();
            $grid->column('updated_at','Last Updated at')->sortable();
            $grid->actions(function ($actions) use ($grid) {
                $actions->disableEdit();
                $action = "redeem-points/".$actions->getKey()."";
                $actions->prepend('<a href="'.$action.'"><i class="fa fa-money"></i></a>');
            });
            $grid->disableRowSelector();
            $grid->disableCreateButton();
        });
    }

    protected function grid2()
    {
        return Admin::grid(RedeemPoint::class, function (Grid $grid) {
            $grid->model()->where('status', 1);
            $grid->id('ID')->sortable();
            $grid->disableExport();
            $grid->disableFilter();
            $grid->option('useWidth', true);
            $grid->column('users.name', 'User Name')->display(function () {
                return $this->users['first_name'].' '.$this->users['last_name'];
            });
            $grid->points();
            $grid->earning();
            $grid->column('status')->display(function ($status) {
                if($status == 0){
                    return " ";
                }else{
                    return "<i class='fa fa-check-circle' style='color: green;'></i>";
                }
            });
            $grid->column('created_at','Created at')->sortable();
            $grid->column('updated_at','Last Updated at')->sortable();
            $grid->disableRowSelector();
            $grid->disableActions();
            $grid->disableCreateButton();
        });
    }


    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function redeem($id){
        $redeem_request = $this->redeem_service->findById($id);
        $user           = $this->user_service->get($redeem_request->user_id);
        $data = [
            'receivers'  => [
                [
                    'email'     => $user->paypal_email,
                    'amount'    => $redeem_request->earning,
                ],
            ],
            'payer' => 'EACHRECEIVER',
            'return_url' => url('/admin/auth/redeem'),
            'cancel_url' => url('/admin/auth/redeem'),
        ];
        $response = $this->provider->createPayRequest($data);
        if($response['responseEnvelope']['ack'] == 'Failure'){
            $this->redeem_service->update($id, ['status' => 0]);
            $message    = $response['error'][0]['message'];
            admin_toastr(trans($message));
            return Redirect::to('/admin/auth/redeem');
        }
        else{
            $this->redeem_service->update($id, ['status' => 1]);
            $redirect_url = $this->provider->getRedirectUrl('approved', $response['payKey']);
            return redirect($redirect_url);
        }
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
        $record = RedeemPoint::find($id);
        if ($record->delete()) {
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
}
