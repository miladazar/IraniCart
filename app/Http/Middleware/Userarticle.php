<?php

namespace App\Http\Middleware;

use Closure;

class Userarticle
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

         $article = Article::find($request->id);
         if(!isset($article)){ 
            $response = [
                'data' => null,
                'success' => flase,
                'message' => "Article not found"
            ];
            return response()->json($response, 404);
          }

            if ($request->user()->email == $article->email) {
                return $next($request);
            }
           $response = [
                'data' => null,
                'success' => flase,
                'message' => "You do not have permission to access"
            ];
            return response()->json($response, 404);
    }
}
