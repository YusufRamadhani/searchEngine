<?php

namespace App\Http\Controllers;

use App\ImportantWord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        return view('ImportantWord/createImportantWord');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'word' => 'required'
        ]);

        if (!empty($request->input('main_word'))) {
            $importantWord = new ImportantWord([
                'word' => $request->input('word'),
                'main_word' => $request->input('main_word'),
                'is_usage' => 1
            ]);
        } else {
            $importantWord = new ImportantWord([
                'word' => $request->input('word'),
                'is_usage' => 0
            ]);
        }

        $importantWord->save();
        return redirect()->route('importantword.index');
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
        DB::table('important_word')->where('word', $word)->update([
            'main_word' => $request->input('main_word'),
            'is_usage' => 1
        ]);
        return redirect()->route('importantword.index')->with('success', 'Word updated successfully');
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
