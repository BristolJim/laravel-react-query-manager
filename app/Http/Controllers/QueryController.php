<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Query;
use DB;

class QueryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Query $query
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, Query $query): \Illuminate\Http\JsonResponse
    {
        $allQueries = $query->whereIn('user_id', $request->user())->with('user');
        $queries = $allQueries->orderBy('created_at', 'desc')->take(20)->get();

        return response()->json([
            'queries' => $queries,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): ?\Illuminate\Http\Response
    {
        return response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'query' => 'required',
        ]);
        $query = $request->user()->queries()->create([
            'query' => $request['query'],
            'name' => $request['name'],
        ]);

        return response()->json(Query::with('user')->find($query->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): ?\Illuminate\Http\Response
    {
        return response();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id): ?\Illuminate\Http\JsonResponse
    {
        $query = Query::findOrFail($id);
        return response()->json([
            'query' => $query,
        ]);
    }

    /**
     * Execute the given query and return the results.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function execute($id): ?\Illuminate\Http\JsonResponse
    {
        $response = [];
        $response['query'] = Query::findOrFail($id);

        try {
            $response['result'] = DB::select($response['query']['query']);
            $response['error'] = '';
        } catch(\Illuminate\Database\QueryException $exception) {
            $response['result'] = [];
            $response['error'] = $exception->getMessage();
        }

        return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id): ?\Illuminate\Http\JsonResponse
    {
        $input = $request->all();
        $query = Query::findOrFail($id);
        $query->update($input);

        return response()->json(Query::with('user')->find($query->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response|null
     * @throws \Exception
     */
    public function destroy($id): ?\Illuminate\Http\Response
    {
        Query::findOrFail($id)->delete();

        return null;
    }
}
