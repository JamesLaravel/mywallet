<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\MobileTopup;
use App\Models\Bill_payment;
use App\Models\Transaction;
use App\User;
use App\Models\Wallet;
use App\Models\Take_loan;
use Auth;
use DB;
use Carbon\Carbon;
use App\Services\UserServices;

class DatatablesController extends Controller
{
    protected $user;
    public function __construct(UserServices $user)
    {
        $this->user = $user;
    }
    public function getrecentTopups(Request $request)
    {
        $id = Auth::user()->id;
        $wallet = User::find($id)->wallet;
        $topups = $this->user->topups($wallet->wallet_key);
        
        return Datatables::of($topups)
                ->editColumn('created_at', '{!! $created_at !!}')
                 
                ->addColumn('action', function($topup) {
                    if($topup->status == '0') {
                        return '<a href="javascript:void(0)" class="btn btn-xs btn-danger">'.'failed'.'</a>';
                    }elseif($topup->status == '1') {
                        return '<a href="javascript:void(0)" class="btn btn-xs btn-info">'.'awaiting confirmation'.'</a>'; 
                    }elseif($topup->status == '2') {
                        return '<a href="javascript:void(0)" class="btn btn-xs btn-primary">'.'successful'.'</a>';
                    }
                    
                })
                ->addColumn('number', function($topup) {
                    return '0'.$topup->mobile_number;
                })
               
                ->make(true);
    }
 
    public function getTransaction()
    {
        
        return view('dashboard.transaction');
    }

    public function gettrans(Request $request)
    {
         $id = Auth::user()->id;
        $wallet = User::find($id)->wallet;
        $trans = $this->user->getTransactions($wallet->wallet_key);
        
        return Datatables::of($trans)
                            ->editColumn('created_at', '{!! $created_at !!}')
                            ->addColumn('action', function($tran) {
                                if($tran->trans_status == '0') {
                                    return '<a href="javascript:void(0)" class="btn btn-xs btn-danger">'.'failed'.'</a>';
                                }elseif($tran->trans_status == '1') {
                                    return '<a href="javascript:void(0)" class="btn btn-xs btn-info">'.'awaiting approval'.'</a>';
                                }elseif($tran->trans_status == '2') {
                                    return '<a href="javascript:void(0)" class="btn btn-xs btn-primary">'.'successfull'.'</a>';
                                }
                            
                            })
                
                ->make(true);
    }

    public function Bill(Request $request)
    {
        $id = Auth::user()->id;
        $wallet = User::find($id)->wallet;

        $bills = $this->user->bills($wallet->wallet_key);
                    
        
        return Datatables::of($bills)
                ->editColumn('created_at', '{!! $created_at !!}')
                
                ->addColumn('action', function($bill) {
                    if($bill->status == '2'){
                        return '<a href="javascript:void(0)" class="btn btn-xs btn-primary">'.'successfull'.'</a>';
                       }else {
                        return '<a href="javascript:void(0)" class="btn btn-xs btn-danger">'.'failed'.'</a>';
                       }
                })
                
                ->make(true);
    }

    public function Loans()
    {
        $id = Auth::user()->id;
        $wallet = User::find($id)->wallet;
        $loans = Take_loan::where('wallet_key', $wallet->wallet_key)
                        ->select(['id','loan_pid','loan_amount','loan_length', 'verified', 'created_at', 'repayment_amount', 'amount_left', 'amount_paid', 'expiration_date', 'payment_status']);
                        

        return Datatables::of($loans)
                    ->editColumn('created_at', function($loan) {
                        return $loan->created_at->isoFormat('MMM Do YYYY');
                        
                    })
                    ->editColumn('expiration_date', function($loan) {
                        return $loan->expiration_date->isoFormat('MMM Do YYYY');
                    })
                    ->addColumn('payment_status', function($loan) {
                        if($loan->payment_status == '1') {
                            return 'Paid';
                        }elseif($loan->payment_status == '0'){
                            return 'on going';
                        }
                    })
                    ->addColumn('loan_status', function($loan) {
                        if($loan->verified == '2'){
                            return 'Approved';
                        }elseif($loan->verified == '1'){
                            return 'Pending';
                        }else{
                            return 'Rejected';
                        }
                    })
                    ->editColumn('action', function($loan) {
                        $buttons= '';
                        if($loan->verified == '2'){
                            $buttons = '<a href="#" class="btn btn-xs btn-primary viewloan" data-edit-id="'.$loan->id.'" data-toggle="modal"> <i class="fa fa-eye"></i></a>';
                            // $buttons .= '<a href="#" class="btn btn-xs btn-danger deleteloan"  data-edit-id="'.$loan->id.'" data-toggle="modal"><i class="fa fa-trash"></i></a>';
                        }else{
                            $buttons = '<a href="#" class="btn btn-xs btn-primary editloan" id="editloan" data-edit-id="'.$loan->id.'" data-toggle="modal"><i class="fa fa-edit"></i></a>';
                            $buttons .= '&nbsp;&nbsp;<a href="#" class="btn btn-xs btn-danger deleteloan"  data-edit-id="'.$loan->id.'" data-toggle="modal"><i class="fa fa-trash"></i></a>';
                        }
                        
                        return $buttons;
                    })
                   
                    
                    ->rawColumns(['status'=>'status','action' => 'action'])
                    ->make(true);


        
    }

    public function getrecent()
    {
        $id = Auth::user()->id;
        $wallet = User::find($id)->wallet;
        $topups = DB::table('pay_loan_takens')
                    ->join('wallets', 'wallets.wallet_key', '=', 'pay_loan_takens.wallet_key')
                    ->join('take_loans', 'take_loans.loan_pid', '=', 'pay_loan_takens.loan_pid')
                    ->where('pay_loan_takens.wallet_key', '=', $wallet->wallet_key)
                    ->select('pay_loan_takens.loan_pid', 'pay_loan_takens.amount_paid','pay_loan_takens.amount_left','pay_loan_takens.verified', 'take_loans.loan_amount','wallets.wallet_balance', 'pay_loan_takens.created_at');

    return Datatables::of($topups)
                ->editColumn('pay_loan_takens.created_at', '{!! $created_at !!}')
                
                
                ->addColumn('action', function($topup) {
                    $buttons = '';
                    if($topup->verified == '1'){
                        $buttons = '<button class="btn btn-xs btn-primary">'.'successfull'.'</button>';
                        //$buttons .= '&nbsp;&nbsp;<a href="#" class="btn btn-xs btn-primary viewtopup" data-edit-id="'.$topup->id.'" data-toggle="modal"> <i class="fa fa-eye"></i></a>';
                    }else {
                        $buttons =  '<button class="btn btn-xs btn-danger">'.'Failed'.'</button>';
                        //$buttons .= '&nbsp;&nbsp;<a href="#" class="btn btn-xs btn-primary viewtopup" data-edit-id="'.$topup->id.'" data-toggle="modal"> <i class="fa fa-eye"></i></a>';
                    }
                    return $buttons;
                })
                ->make(true);
    }

    public function deleteloan(Request $request)
    {
       $loan = Take_loan::find($request['data']);
       $loan->delete();
       return response()->json(['message'=> 'success']);
    }
}
