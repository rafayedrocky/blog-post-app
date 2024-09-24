<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\BlogPost;

class BlogPostRepository
{
    function listBlogs($perPage)
    {
        $table = DB::table('blog_posts');

        $table->select(
            'id as blogId',
            'title as title',
            'content as content',
            'author_id as authorId',
            'updated_by as updatedBy'
        );

        $blogs = $table->paginate($perPage);
        return $blogs;
    }

    function showBlog($id)
    {

        $blog = BlogPost::where("id", $id)
            ->select(
                'id as blogId',
                'title as title',
                'content as content',
                'author_id as authorId',
                'updated_by as updatedBy'
            )->get();

        return $blog;
    }

    function addBlog($title, $content, $author_id, $updated_by)
    {

        $blog = new BlogPost();

        $blog->title = $title;
        $blog->content = $content;
        $blog->author_id = $author_id;
        $blog->updated_by = $updated_by;

        $blog->save();
        return $blog;
    }

    function updateBlog($Id, $properties, $updated_by)
    {

        $updateBlog = BlogPost::find($Id);

        if (empty($updateBlog)) {
            return null;
        }

        if (array_key_exists('title', $properties)) {
            $updateBlog->title = $properties['title'];
        }

        if (array_key_exists('content', $properties)) {
            $updateBlog->content = $properties['content'];
        }

        $updateBlog->updated_by = $updated_by;

        $updateBlog->save();

        return $updateBlog;
    }
    public function deleteBlog($id)
    {
        $blogPost = BlogPost::find($id);

        if ($blogPost) {
            $blogPost->delete();
            return true;
        }

        return false;
    }
}