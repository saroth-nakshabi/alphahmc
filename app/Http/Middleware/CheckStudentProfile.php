<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class CheckStudentProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::findOrFail(Auth::user()->id);

        if ($user && $user->hasRole('Student')) {
            $student = $user->student->first();
            //  check the enrollment form is filled
            if ($student->is_enrollment_form_filled == false) {
                if (!$request->routeIs('profile.edit') && !$request->routeIs('student.update-info')) {
                    session()->flash('profile_incomplete', 'Complete the required fields to move forward.');
                    return redirect()->route('profile.edit');
                }
            }
        }
        return $next($request);
    }
}
