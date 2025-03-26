<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PostController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    /**
     * @return JsonResponse
     * @throws \Exception
     */
    public function getPosts(): JsonResponse
    {

        /** @var User $userId */
        $user = Auth::user();
        $posts = $user->posts()->select(['id', 'title', 'description', 'created_at'])->get();

        return DataTables::of($posts)
            ->editColumn('created_at', function ($post) {
                return $post->created_at->format('d-m-Y H:i');
            })
            ->addColumn('actions', function ($post) {
                return '<button class="btn btn-sm btn-primary" onclick="editPost(' . $post->id . ')">Edit</button>';
            })
            ->rawColumns(['actions'])
            ->make();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function addPost(Request $request): JsonResponse
    {
        /** @var User $userId */
        $user = Auth::user();
        $post = new Post();
        $post->title = $request->title;
        $post->description = $request->description;
        $post->user_id = $user->id;
        $post->save();

        return response()->json(['success' => true, 'post' => $post]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function updatePost(Request $request, int $id): JsonResponse
    {
        /** @var User $userId */
        $user = Auth::user();

        $post = Post::find($id);
        if (!$post || $post->user_id != $user->id) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        $post->title = $request->title;
        $post->description = $request->description;
        $post->save();

        return response()->json(['success' => true, 'post' => $post]);
    }
}
