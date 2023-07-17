<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Dashboard', [
            'userProjects' => auth()->user()->projects()->get(),
        ]);
    }

    /**
     * Fetch all user projects.
     *
     * @return JsonResponse
     */
    public function fetch(): JsonResponse
    {
        return response()->json(auth()->user()->projects()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:projects,title|max:255',
        ]);

        auth()->user()->projects()->create($request->all());
    }

    /**
     * Display the projects tasks.
     */
    public function tasks(Project $project): Response
    {
        return Inertia::render('Tasks', [
            'project' => $project,
            'otherProjects' => auth()->user()->projects()->where('id', '!=', $project->id)->get(),
            'userTasks' => $project->tasks()->orderBy('priority')->get(),
        ]);
    }
}
