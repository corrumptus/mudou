<?php

namespace App\Http\Middleware\Web;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserHomePageSelectionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $route = $request->route()->getName();

        $user = Auth::user();

        if (!$this->isProtectedRoute($route))
            return $next($request);

        if (
            $user->is_admin && $this->isAdminRoute($route)
            ||
            $user->is_teacher && $this->isTeacherRoute($route)
            ||
            $user->is_student && $this->isStudentRoute($route)
            ||
            $user->is_monitor && $this->isMonitorRoute($route)
        )
            return $next($request);

        if ($user->is_admin)
            return redirect('/admin/people');

        if ($user->is_teacher)
            return redirect('/teacher/course-subject');

        return redirect('/course-subject');
    }

    private function isProtectedRoute(string $route) {
        return
            $this->isAdminRoute($route)
            ||
            $this->isTeacherRoute($route)
            ||
            $this->isStudentRoute($route)
            ||
            $this->isMonitorRoute($route);
    }

    private function isAdminRoute(string $route) {
        return array_search($route, [
            'admin.user.show.all',
            'admin.course.show.all',
            'admin.courseSubject.show.all',
            'admin.classroom.show.all'
        ]) !== false;
    }

    private function isTeacherRoute(string $route) {
        return array_search($route, [
            'teacher.courseSubject'
        ]) !== false;
    }

    private function isStudentRoute(string $route) {
        return array_search($route, [
            'student.courseSubject'
        ]) !== false;
    }

    private function isMonitorRoute(string $route) {
        return array_search($route, [

        ]) !== false;
    }
}
