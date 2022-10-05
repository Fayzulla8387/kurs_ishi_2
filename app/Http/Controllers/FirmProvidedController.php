<?php

namespace App\Http\Controllers;

use App\Models\Firm;
use App\Models\FirmProvided;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class FirmProvidedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id = $request['id'];

        $from_date = $request['from_date'];
        $to_date = $request['to_date'];

        if ($from_date == NULL && $to_date == NULL) {
            $firm_provided = FirmProvided::orderby('date', 'DESC')->where('firm_id', $id)->get();
        } else {
            $firm_provided = FirmProvided::orderby('date', 'DESC')
                ->where('firm_id', $id)
                ->whereBetween('date', [$from_date, $to_date])
                ->get();
        }

        $sum_price = 0;
        foreach ($firm_provided as $date)
            $sum_price += $date['price'];
        return view("firm.firm_provided.index", compact("id", "firm_provided", "sum_price","from_date","to_date"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'firm_id' => "required",
            'price' => "required",
            'date' => "required",
        ]);
        $price = $request['price'];
        $id = $request['firm_id'];
        $firm_provided = new FirmProvided();
        $firm_provided['firm_id'] = $id;
        $firm_provided['price'] = $price;
        $firm_provided['date'] = $request['date'];
        $firm_provided->save();

        $firm = Firm::find($id);
        $firm['given_sum'] += $price;
        $firm['indebtedness'] = $firm['all_sum'] - $firm['given_sum'];
        $firm->save();
        return redirect()->back()->with("success", "Firma oldi berdi muvaffaqqiyatli yaratildi");
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $firm_provided = FirmProvided::find($id);
        $firm_id = $firm_provided['firm_id'];
        $price = $firm_provided['price'];
        $firm = Firm::find($firm_id);
        $firm['indebtedness'] += $price;
        $firm['given_sum'] -= $price;
        $firm->save();
        $firm_provided->delete();
        return redirect()->back()->with("success", "Firma oldi berdi muvaffaqqiyatli yaratildi");
    }

    public function download(Request $request)
    {
        $id = $request['id'];
        $from_date = $request['from_date'];
        $to_date = $request['to_date'];
        $page = $request['page'];
        if ($from_date == NULL && $to_date == NULL) {
            $firm_provided = FirmProvided::orderby('date', 'DESC')->where('firm_id', $id)->get();
        } else {
            $firm_provided = FirmProvided::orderby('date', 'DESC')
                ->where('firm_id', $id)
                ->whereBetween('date', [$from_date, $to_date])
                ->get();
        }
        $sum_price = 0;
        foreach ($firm_provided as $date)
            $sum_price += $date['price'];
        $pdf = PDF::loadView('firm.firm_provided.download', [
            'firm_provided' => $firm_provided,
            'id' => $id,
            'sum_price' => $sum_price,
            'from_date' => $from_date,
            'to_date' => $to_date,
        ]);
        $pdf->setPaper('A4', 'landscape');
        if ($page == 'download')
            return $pdf->download('firm_provided.pdf');
        if ($page == 'view')
            return $pdf->stream('firm_provided.pdf');
    }
}
