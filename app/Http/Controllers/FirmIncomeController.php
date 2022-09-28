<?php

namespace App\Http\Controllers;

use App\Models\Firm;
use App\Models\FirmIncome;
use Illuminate\Http\Request;

class FirmIncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id = $request['id'];
        $firms = Firm::all();
        $firm_incomes = FirmIncome::orderby('created_at', 'DESC')->where('firm_id', $id)->get();
        $sum_total_price = 0;
        $sum_brutto = 0;
        $sum_netto = 0;
        $sum_tara = 0;
        $sum_soil = 0;
        $sum_price = 0;
        foreach ($firm_incomes as $date){
            $sum_total_price += $date['total_price'];
            $sum_brutto += $date['brutto'];
            $sum_netto += $date['netto'];
            $sum_tara += $date['tara'];
            $sum_soil += $date['soil'];
            $sum_price += $date['price'];
        }
        return view("admin.firm_incomes.index", [
            'firm_incomes' => $firm_incomes,
            'firms' => $firms,
            'id' => $id,
            'sum_total_price' => $sum_total_price,
            'sum_brutto' => $sum_brutto,
            'sum_netto' => $sum_netto,
            'sum_tara' => $sum_tara,
            'sum_soil' => $sum_soil,
            'sum_price' => $sum_price,
        ]);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = $request['firm_id'];
        $data = $request->validate([
           'firm_id' => "required",
           'car_number' => "required",
           'brutto' => "required",
           'tara' => "required",
           'price' => "required",
        ]);
        $netto = $request['brutto'] - $request['tara'];
        $firm_income = new FirmIncome();
        $firm_income['firm_id'] = $request['firm_id'];
        $firm_income['car_number'] = $request['car_number'];
        $firm_income['brutto'] = $request['brutto'];
        $firm_income['netto'] = $netto;
        $firm_income['tara'] = $request['tara'];
        $firm_income['soil'] = 0;
        $firm_income['price'] = $request['price'];
        $firm_income['total_price'] = intval($request['price'] * $netto);
        $firm_income->save();
        $firm = Firm::find($id);
        $firm['all_sum'] += $firm_income['total_price'];
        $firm['indebtedness'] = $firm['all_sum'] - $firm['given_sum'];
        $firm->save();
        return redirect()->back()->with("success", "Firma kirim muvaffaqqiyatli yaratildi");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FirmIncome  $firmIncome
     * @return \Illuminate\Http\Response
     */
    public function show(FirmIncome $firmIncome)
    {
        dd("show");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FirmIncome  $firmIncome
     * @return \Illuminate\Http\Response
     */
    public function edit(FirmIncome $firmIncome)
    {
        dd("edit");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FirmIncome  $firmIncome
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id = $request['id'];
        $firm_income = FirmIncome::find($id);
        $old_price = $firm_income['total_price'];
        $old_soil = $firm_income['soil'];
        $new_soil = $request['soil'];
        $netto = $firm_income['netto'] - ($new_soil - $old_soil);
        $firm_income['soil'] = $request['soil'];
        $firm_income['netto'] = $netto;
        $firm_income['total_price'] = $netto * $firm_income['price'];
        $firm_income->save();
        $new_price = $firm_income['total_price'];
        $id = $firm_income['firm_id'];
        $firm = Firm::find($id);
        $firm['all_sum'] += ($new_price - $old_price);
        $firm['indebtedness'] = $firm['all_sum'] - $firm['given_sum'];
        $firm->save();
        return redirect()->back()->with("success", "Firma kirim muvaffaqqiyatli tahrirlandi");;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FirmIncome  $firmIncome
     * @return \Illuminate\Http\Response
     */
    public function destroy(FirmIncome $firmIncome)
    {
        $id = $firmIncome['firm_id'];
        $total_price = $firmIncome['total_price'];
        $firm = Firm::find($id);
        $firm['all_sum'] -= $total_price;
        $firm->save();
        $firmIncome->delete();
        return redirect()->back()->with("success", "Firma kirim muvaffaqqiyatli o'chirildi");;
    }
}