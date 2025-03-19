<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Product;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{

    public function dashboardPage(Request $request){

        $list = Invoice::when($request->query('fromDate') && $request->query('toDate'), function ($query) use ($request) {
            $fromDate = date('Y-m-d', strtotime($request->fromDate));
            $toDate = date('Y-m-d', strtotime($request->toDate));
            $query->whereDate('created_at', '>=', $fromDate)->whereDate('created_at', '<=', $toDate);
        })->with('customer', 'invoiceProducts.product')
            ->get();

        $total = Invoice::when($request->query('fromDate') && $request->query('toDate'), function ($query) use ($request) {
            $fromDate = date('Y-m-d', strtotime($request->fromDate));
            $toDate = date('Y-m-d', strtotime($request->toDate));
            $query->whereDate('created_at', '>=', $fromDate)->whereDate('created_at', '<=', $toDate);
        })->sum('total');

        return Inertia::render('Invoice/InvoiceListPage', [
            'list' => $list,
            'total' => $total
        ]);
    }

    public function salePage(Request $request){
        $userId=$request->header('id');
        $customers=Customer::get();
        $products=Product::get();

        return Inertia::render('Sale/SalePage',['customers'=>$customers,'products'=>$products]);
    }

    public function reportPage(Request $request){

        return Inertia::render('Report/ReportPage');
    }

    public function salesReport(Request $request){
        $fromDate=date('Y-m-d', strtotime($request->input('fromDate')));
        $toDate=date('Y-m-d', strtotime($request->input('toDate')));

        $total=Invoice::whereDate('created_at','>=',$fromDate)->whereDate('created_at','<=',$toDate)->sum('total');
        $vat=Invoice::whereDate('created_at','>=',$fromDate)->whereDate('created_at','<=',$toDate)->sum('vat');
        $payable=Invoice::whereDate('created_at','>=',$fromDate)->whereDate('created_at','<=',$toDate)->sum('payable');
        $discount=Invoice::whereDate('created_at','>=',$fromDate)->whereDate('created_at','<=',$toDate)->sum('discount');

        $list=Invoice::whereDate('created_at','>=',$fromDate)->whereDate('created_at','<=',$toDate)->with('customer')->get();

        $data=[
            'total'=>$total,
            'vat'=>$vat,
            'payable'=>$payable,
            'discount'=>$discount,
            'list'=>$list,
            'fromDate'=>$fromDate,
            'toDate'=>$toDate
        ];
        $pdf=Pdf::loadView('Report.sales-report', $data);
        return $pdf->download('sales-report.pdf');

    }

}
