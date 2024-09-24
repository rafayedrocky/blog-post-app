<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\BlogPostRepository;
use App\Models\EmailScheduler;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Events\EmailScheduled;
use App\Models\User;

class BlogPostController extends Controller
{
    private $repository;

    public function __construct(BlogPostRepository $repository)
    {
        $this->repository = $repository;
    }

    // List blogs
    public function listBlogs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'perPage' => 'required',
        ]);

        if ($validator->fails()) {
            $message = ['errors' => $validator->messages()->all()];
            $response = response($message, 400);
            return $response;
        }

        return $this->repository->listBlogs($request->perPage);
    }

    // show single blog
    public function showBlog($id)
    {

        return $this->repository->showBlog($id);

    }

    // Create a new blog post
    public function addBlog(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            $message = ['errors' => $validator->messages()->all()];
            $response = response($message, 400);
            return $response;

        }

        ini_set('max_execution_time', 300);

        $userId = Auth::user()->id;

        $blog = $this->repository
            ->addBlog(
                $request->title,
                $request->content,
                $userId, // Author ID
                $userId  // // Updated By
            );

        // Use the EmailScheduler model to insert a new email notification
        $emailData = EmailScheduler::create([
            'title' => "Blog create notification",
            'body' => "Created a new blog by " . $blog->author->name,
            'email_send_time' => Carbon::now()->addMinutes(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $response = array("status" => "success", "message" => "Blog post added successfully", "blogPostId" => $blog->id);
        return $response;
    }

    // Update a blog post
    public function updateBlog(Request $request, $Id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            $message = ['errors' => $validator->messages()->all()];
            return response($message, 400);
        }

        $userId = Auth::user()->id;

        $blog = $this->repository->updateBlog(
            $Id,
            $request->only(['title', 'content']),
            $userId  // Updated By
        );

        if (!empty($blog)) {

            $messageBody = "Updated blog $blog->id by " . Auth::user()->name;
            $admin = User::where('user_type', 1)->first();

            // Dispatch the EmailScheduled event
            event(new EmailScheduled($admin->email, $messageBody));

            return array('status' => 'success', 'message' => 'Blog post updated successfully', 'blogId' => $blog->id);
        } else {
            return response(['errors' => 'Blog post did not update'], 404);
        }
    }
    
    // Delete a blog post
    public function deleteBlog($id)
    {
        $result = $this->repository->deleteBlog($id);

        if ($result) {
            return response()->json([
                'status' => 'success',
                'message' => 'Blog post deleted successfully',
                'blogId' => $id
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Blog post not found or could not be deleted'
            ], 404);
        }
    }

}
