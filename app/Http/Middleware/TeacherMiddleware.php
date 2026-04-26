<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeacherMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isTeacher()) {
            abort(403, 'Teacher access required.');
        }

        // Ensure teacher profile is approved
        if ($request->user()->teacher && $request->user()->teacher->status !== 'approved') {
            return redirect()->route('teacher.pending');
        }

        return $next($request);
    }
}
