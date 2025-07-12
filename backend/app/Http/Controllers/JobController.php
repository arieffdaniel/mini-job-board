<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    // GET /api/jobs (for applicants)
    public function index()
    {
        return Job::where('status', 'open')->get();
    }

    // GET /api/employer/jobs (for employers)
    public function myJobs()
    {
        return Auth::user()->jobs;
    }

    // POST /api/employer/jobs
    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string',
            'description'  => 'required|string',
            'location'     => 'required|string',
            'salary_range' => 'required|string',
            'is_remote'    => 'required|boolean',
            'status'       => 'required|in:open,closed'
        ]);

        $job = Auth::user()->jobs()->create($request->all());

        return response()->json($job, 201);
    }

    // PUT /api/employer/jobs/{id}
    public function update(Request $request, $id)
    {
        $job = Auth::user()->jobs()->findOrFail($id);

        $job->update($request->only(['title', 'description', 'location', 'salary_range', 'is_remote', 'status']));

        return response()->json($job);
    }

    // DELETE /api/employer/jobs/{id}
    public function destroy($id)
    {
        $job = Auth::user()->jobs()->findOrFail($id);
        $job->delete();

        return response()->json(['message' => 'Job deleted.']);
    }
}
