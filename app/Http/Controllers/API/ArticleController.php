<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Article;
use Validator;

class ArticleController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::all();
        $data = $articles->toArray();
 
        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'Article retrieved successfully.'
        ];
 
        return response()->json($response, 200);
    }
 
 
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
 
        $validator = Validator::make($input, [
            'content' => 'required',
            'author' => 'required',
             'category'=>'required',
        ]);
 

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'data' =>  $request ,
                'message' => $validator->errors()
            ];
            return response()->json($response, 404);
        }
        
        $article = Article::create($input);
        $data = $article->toArray();
 
        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'Article stored successfully.'
        ];
 
        return response()->json($response, 200);
    }
 
 
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::find($id);
        $data = $article->toArray();
 
        if (is_null($article)) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => 'Article not found.'
            ];
            return response()->json($response, 404);
        }
 
 
        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'Article retrieved successfully.'
        ];
 
        return response()->json($response, 200);
    }
 
 
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $input = $request->all();
 
        $validator = Validator::make($input, [
            'content' => 'required',
            'author' => 'required',
            'category'=>'required',
            'id'=>'required',
        ]);
 
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'data' => 'Validation Error.',
                'message' => $validator->errors()
            ];
            return response()->json($response, 404);
        }
        
        $articles=Article::find(intval($input['id']));
        if(!empty($input['content']))$article->content = $input['content'];
         if(!empty($input['author']))$article->author = $input['author'];
         if(!empty($input['category']))$article->category = $input['category'];
        $article->save();
 
        $data = $article->toArray();
 
        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'Article updated successfully.'
        ];
 
        return response()->json($response, 200);
    }
 
 
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $article->delete();
        $data = $article->toArray();
 
        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'Article deleted successfully.'
        ];
 
        return response()->json($response, 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $input = $request->all();
           $articles =new   Article();
            if($input['category']){
                  $articles =  $articles->where('category',$input['category']);
            }
            if($input['author']!=""){
                $articles = $articles->where('author',$input['author']);
            }
            if($input['content']!=""){
               $articles = $articles->where('content', 'LIKE', '%' . $input['content'] . '%');
            }
 
        $response = [
            'success' => true,
            'data' =>  $articles->get(),
            'message' => 'Article search successfully.'
        ];
 
        return response()->json($response, 200);
    }



}
