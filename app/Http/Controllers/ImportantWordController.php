<?php

namespace App\Http\Controllers;

use App\ImportantWord;
use Illuminate\Http\Request;

class ImportantWordController extends Controller
{
    private $carryWord = array();

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $importantWord = ImportantWord::where('is_usage', 1)->paginate(20);

        return view('ImportantWord/indexImportantWord', ['importantWord' => $importantWord]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //akan mengolah dokumen hingga menghasilkan word. 
        //lalu pilih word frek x 3 && tidak ada di IW
        //tampilkan hanya pertanyan dan jumlah word dihasilkan
        // disimpan di $carryWord pass ke store()
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->ask === true) {
            $importantWord = new ImportantWord();
            foreach ($this->carryWord as $value) {
                $importantWord->fill(['word' => $value]);
            }
            unset($this->carryWord);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $importantWord = ImportantWord::where('is_usage', 0)->paginate(20);

        return view('ImportantWord/editImportantWord', ['importantWord' => $importantWord]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $word)
    {
        $importantWord = ImportantWord::where('word', $word)->first();

        $importantWord->main_word = $request->main_word;
        $importantWord->is_usage = 1;

        // $importantWord->update();
        $importantWord->save();

        // 
        return redirect()->route('importantword.index');

        // $importantWord->update([
        // 'main_word' => $request->main_word,
        // 'is_usage' => true
        // ]);

        // $request->validate([
        // 'word' => 'required',
        // 'main_word' => 'required',
        // ]);
        // $importantWord = ImportantWord::where('word', $request->input('word'));
        // $importantWord->main_word = $request->input('main_word');
        // $importantWord->is_usage = 1;
        // 
        // 
        // return redirect()->route('importantword.index');

        return view('testing', ['data' => $importantWord]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($request)
    {
        $importantWord = ImportantWord::where('word', $request);

        $importantWord->delete();

        return redirect()->route('importantword.index');
    }
}