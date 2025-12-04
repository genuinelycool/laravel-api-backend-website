<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;


class BlogController extends Controller
{
    public function BlogCategory()
    {
        $category = BlogCategory::latest()->get();
        return view('backend.blog.blog_category', compact('category'));
    }

    public function BlogCategoryStore(Request $request)
    {

        BlogCategory::create([
            'blog_category' => $request->blog_category,
            'slug' => strtolower(str_replace(' ', '-', $request->blog_category)),
        ]);


        $notification = array(
            'message' => 'BlogCategory Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function EditBlogCategory($id)
    {
        $categories = BlogCategory::find($id);

        return response()->json($categories);
    }

    public function BlogCategoryUpdate(Request $request)
    {
        $cat_id = $request->cat_id;

        $category = BlogCategory::find($cat_id);

        $category->update([
            'blog_category' => $request->blog_category,
            'slug' => strtolower(str_replace(' ', '-', $request->blog_category)),
        ]);


        $notification = array(
            'message' => 'BlogCategory Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function DeleteBlogCategory($id)
    {
        BlogCategory::find($id)->delete();

        $notification = array(
            'message' => 'BlogCategory Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    // All Method for Blog Post

    public function AllBlogPost()
    {
        $blogpost = BlogPost::latest()->get();
        return view('backend.blog.all_blog_post', compact('blogpost'));
    }

    public function AddBlogPost()
    {
        $blogcat = BlogCategory::latest()->get();
        return view('backend.blog.add_blog_post', compact('blogcat'));
    }

    public function StoreBlogPost(Request $request)
    {
        if ($request->file('image')) {
            $image = $request->file('image');

            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(688, 436)->save(public_path('upload/blog/' . $name_gen));
            $save_url = 'upload/blog/' . $name_gen;

            BlogPost::create([
                'post_title' => $request->post_title,
                'post_slug' => strtolower(str_replace(' ', '-', $request->post_title)),
                'blogcat_id' => $request->blogcat_id,
                'long_descp' => $request->long_descp,
                'image' => $save_url,
            ]);
        }

        $notification = array(
            'message' => 'Blog Post Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.blog.post')->with($notification);
    }

    public function EditBlogPost($id)
    {
        $blogcat = BlogCategory::latest()->get();
        $post = BlogPost::find($id);

        return view('backend.blog.edit_blog_post', compact('blogcat', 'post'));
    }

    public function UpdateBlogPost(Request $request)
    {
        $blog_id = $request->id;
        $blogpost = BlogPost::find($blog_id);

        if ($request->file('image')) {
            $image = $request->file('image');

            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(688, 436)->save(public_path('upload/blog/' . $name_gen));
            $save_url = 'upload/blog/' . $name_gen;

            if (file_exists(public_path($blogpost->image))) {
                unlink(public_path($blogpost->image));
            }

            $blogpost->update([
                'post_title' => $request->post_title,
                'post_slug' => strtolower(str_replace(' ', '-', $request->post_title)),
                'blogcat_id' => $request->blogcat_id,
                'long_descp' => $request->long_descp,
                'image' => $save_url,
            ]);

            $notification = array(
                'message' => 'Blog Post Updated with Image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.blog.post')->with($notification);
        } else {
            $blogpost->update([
                'post_title' => $request->post_title,
                'post_slug' => strtolower(str_replace(' ', '-', $request->post_title)),
                'blogcat_id' => $request->blogcat_id,
                'long_descp' => $request->long_descp,
            ]);

            $notification = array(
                'message' => 'Blog Post Updated without Image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.blog.post')->with($notification);
        }
    }
}
