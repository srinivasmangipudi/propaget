<?php namespace App\Http\Middleware;

use Closure;

use Carbon\Carbon;
use Auth;
use Str;
use App\User;
use App\Tokens;



class AuthTokenMiddleware {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        $tokens = new Tokens;
        $authToken = $request->header('X-Auth-Token');

        if(!$authToken) {
            abort(403, 'NO TOKEN PASSED');
        }else {

            $data = \DB::table('tokens')->where('api_token', $authToken)->first();

            if(!$data) {
                abort(403, 'INVALID TOKEN PASSED.');
            }
            $validToken = $tokens->scopeValid($data->expires_on);
            if (!$validToken) {

                $token = Tokens::find($data->id);
                $token->api_token = hash('sha256', Str::random(10), false);
                $token->user_id = $data->user_id;
                //$tokens['client'] = \BrowserDetect::toString();

                $Carbon = new Carbon;
                $token->expires_on = $Carbon->now()->addSeconds(60)->toDateTimeString();
                $token->save();
                abort(403, 'TOKEN EXPIRED');

            }
            \Log::error('Valid' . $validToken);
        }
		return $next($request);
	}

}
