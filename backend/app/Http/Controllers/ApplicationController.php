<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    // Applicant applies to a job
    public function apply(Request $request, $jobId)
    {
        $user = auth()->user();

        // Validate input
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Prevent double-application
        $exists = Application::where('job_id', $jobId)
            ->where('user_id', $user->id)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'You already applied to this job.'], 409);
        }

        // Create application
        $application = Application::create([
            'job_id'   => $jobId,
            'user_id'  => $user->id, // or 'applicant_id' if that's your column
            'message'  => $request->message,
        ]);

        return response()->json(['message' => 'Application submitted successfully.'], 201);
    }

    // Employer views applicants for a job
    public function applicants($jobId)
    {
        $job = Auth::user()->jobs()->with('applications.applicant')->findOrFail($jobId);

        return response()->json($job->applications);
    }
}
