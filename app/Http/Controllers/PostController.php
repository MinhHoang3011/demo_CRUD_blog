<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $currentUser = auth()->user();
        $data = [
            'user_id' => $currentUser->id,
            'title' => $request->input('title'),
            'detail' => $request->input('detail'),
        ];

        $insertId = DB::table('posts')->insertGetId($data);

        if ($insertId) {
            $item = DB::table('posts')->find($insertId);
            return response()->json([
                'status' => 'success',
                'message' => trans('Thêm mới thành công.'),
                'data' => ['item' => $item]
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => trans('Thêm mới không thành công.'),
                'errors' => []
            ], 200);
        }
    }

    public function update(Request $request, $id)
    {
        // Lấy thông tin bài viết cần cập nhật
        $post = DB::table('posts')->find($id);

        // Kiểm tra xem bài viết có tồn tại không
        if (!$post) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bài viết không tồn tại.',
                'errors' => []
            ], 404);
        }

        $data = [];

        // Thực hiện cập nhật các trường dữ liệu cần thiết
        if ($request->has('title')) {
            $data['title'] = $request->title;
        }
        if ($request->has('detail')) {
            $data['detail'] = $request->detail;
        }

        // Cập nhật thông tin bài viết
        DB::table('posts')->where('id', $id)->update($data);

        // Lấy thông tin bài viết sau khi cập nhật
        $updatedPost = DB::table('posts')->find($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật thành công.',
            'data' => ['item' => $updatedPost]
        ], 200);
    }

    public function index()
    {
        $posts = Post::all();

        return response()->json(['posts' => $posts]);
    }

    public function show($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return response()->json(['post' => $post]);
    }

    public function destroy($id)
    {
        $user = auth()->user();

        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        // Kiểm tra xem người dùng có quyền xóa bài viết không
        if ($user->id !== $post->user_id) {
            return response()->json(['message' => 'You are not authorized to delete this post'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Xóa bản ghi thành công']);
    }

    public function multipleDestroy(Request $request)
    {
        $table = 'posts';

        if ($request->has('ids')) {
            $data = [];
            foreach ($request->ids as $id) {
                // Xóa bài viết trong bảng posts
                DB::table($table)->where('id', $id)->delete();

                $data[] = [
                    "id" => $id,
                    "status" => "success",
                    "message" => trans("Xóa thành công.")
                ];
            }
            return response()->json([
                'status' => 'success',
                'message' => trans('Xóa thành công.'),
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => trans('Chọn bản ghi cần xóa.'),
                'errors' => []
            ], 204);
        }
    }


}
