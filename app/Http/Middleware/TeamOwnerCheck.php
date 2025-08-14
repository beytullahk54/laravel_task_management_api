<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Team;
use Symfony\Component\HttpFoundation\Response;

class TeamOwnerCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $teamId = $request->route('id');

        $team = Team::find($teamId);
        
        if ($team->owner_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Bu işlemi sadece takım yöneticisi yapabilir.'
            ], 403);
        }

        return $next($request);
    }
}
