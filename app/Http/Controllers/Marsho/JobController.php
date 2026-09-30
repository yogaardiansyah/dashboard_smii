<?php

namespace App\Http\Controllers\Marsho;

use App\Http\Controllers\Controller;
use App\Models\Marsho\Area;
use App\Models\Marsho\JobMarsho;
use App\Models\Marsho\MarshoDepartment;
use App\Services\Marsho\MarshoJobService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;
use Illuminate\View\View as ViewResponse;

class JobController extends Controller
{
    public function __construct(
        protected MarshoJobService $jobService
    ) {}

    /**
     * Display the Kanban Job Board.
     */
    public function index(): ViewResponse
    {
        $kanbanData = $this->jobService->getKanbanData(Auth::user());

        return view('jobs.index', $kanbanData);
    }

    /**
     * Store a newly created Job.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'area_id'          => ['required', Rule::exists(Area::class, 'id')],
            'list_job'         => ['required', 'string'],
            'to_department_id' => ['required', Rule::exists(MarshoDepartment::class, 'id')],
            'start_date'       => ['required', 'date'],
            'deadline'         => ['required', 'date', 'after_or_equal:start_date'],
            'note'             => ['nullable', 'string', 'max:500'],
            'attachments'      => ['nullable', 'array', 'max:3'],
            'attachments.*'    => ['file', 'mimes:jpg,jpeg,png,pdf,doc,docx', 'max:5120'],
        ]);

        $files = $request->file('attachments', []);
        $job = $this->jobService->createJob($validated, Auth::id(), $files);

        $response = $this->jobService->prepareJobResponse($job, 'Job created successfully!');

        return response()->json($response);
    }

    /**
     * Change Job status and/or forward to another department.
     */
    public function changeStatus(Request $request, JobMarsho $job): JsonResponse
    {
        $request->validate([
            'status'           => ['required', 'in:scheduled,preparation,on_going'],
            'to_department_id' => ['nullable', Rule::exists(MarshoDepartment::class, 'id')],
            'note'             => ['required', 'string', 'max:1000'],
            'attachments'      => ['nullable', 'array', 'max:3'],
            'attachments.*'    => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $files = $request->file('attachments', []);
        $result = $this->jobService->changeStatus(
            $job,
            $request->input('status'),
            $request->input('to_department_id'),
            $request->input('note'),
            Auth::id(),
            $files
        );

        $response = $this->jobService->prepareJobResponse($result['job'], $result['message']);

        return response()->json($response);
    }

    /**
     * Forward Job to another department.
     */
    public function forward(Request $request, JobMarsho $job): JsonResponse
    {
        $request->validate([
            'to_department_id' => ['required', Rule::exists(MarshoDepartment::class, 'id')],
            'note'             => ['required', 'string', 'max:500'],
            'attachments'      => ['nullable', 'array', 'max:3'],
            'attachments.*'    => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $files = $request->file('attachments', []);
        $message = $this->jobService->forwardJob(
            $job,
            (int) $request->input('to_department_id'),
            $request->input('note'),
            Auth::id(),
            $files
        );

        $response = $this->jobService->prepareJobResponse($job->fresh(), $message);

        return response()->json($response);
    }

    /**
     * Complete Job.
     */
    public function complete(Request $request, JobMarsho $job): JsonResponse
    {
        $request->validate([
            'note'          => ['required', 'string'],
            'attachments'   => ['nullable', 'array'],
            'attachments.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $files = $request->file('attachments', []);
        $completedJob = $this->jobService->completeJob($job, $request->input('note'), Auth::user(), $files);

        $response = $this->jobService->prepareJobResponse($completedJob, 'Job marked as completed!');

        return response()->json($response);
    }

    /**
     * Cancel Job (Requester only).
     */
    public function cancel(Request $request, JobMarsho $job): JsonResponse
    {
        $user = Auth::user();

        if ($job->pengaju_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized. Only requester can cancel this job.'], 403);
        }

        $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $cancelledJob = $this->jobService->cancelJob($job, $request->input('reason'), $user);

        $response = $this->jobService->prepareJobResponse($cancelledJob, 'Job cancelled.');

        return response()->json($response);
    }

    /**
     * Close Job.
     */
    public function close(Request $request, JobMarsho $job): JsonResponse
    {
        $closedJob = $this->jobService->closeJob($job, Auth::user());

        $response = $this->jobService->prepareJobResponse($closedJob, 'Job closed and archived.');

        return response()->json($response);
    }

    /**
     * Get detail activities (timeline) of a Job.
     */
    public function showDetails(JobMarsho $job): JsonResponse
    {
        $activities = $this->jobService->getJobTimeline($job);

        $html = View::make('jobs.partials.job_detail_content', [
            'job'        => $job,
            'activities' => $activities,
        ])->render();

        return response()->json(['html' => $html]);
    }
}
