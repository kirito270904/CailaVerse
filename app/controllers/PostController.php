<?php
class PostController extends Controller
{
    public function feed(): void
    {
        $me = $this->requireLogin();
        $posts = $this->attachComments((new PostModel())->feed((int) $me['id']));
        $people = (new UserModel())->suggestions((int) $me['id'], 5);
        $this->view('post/feed', ['posts' => $posts, 'people' => $people, 'title' => 'Newsfeed']);
    }

    public function store(): void
    {
        $me = $this->requireLogin();
        $this->requirePost();

        $content = clean($_POST['content'] ?? '');
        if ($content === '' || mb_strlen($content) > 1000) {
            $this->respond(false, 'Post cannot be empty or longer than 1000 characters.');
        }

        $error = null;
        $image = upload_image($_FILES['image'] ?? [], $error);
        if ($error) {
            $this->respond(false, $error);
        }

        $posts = new PostModel();
        $id = $posts->create((int) $me['id'], $content, $image);

        if (is_ajax()) {
            $post = $posts->findFull($id, (int) $me['id']);
            $post['comments'] = [];
            ob_start();
            require __DIR__ . '/../views/partials/post.php';
            $html = ob_get_clean();
            json_out(['ok' => true, 'message' => 'Post published!', 'html' => $html]);
        }
        $this->respond(true, 'Post published!');
    }

    public function edit(): void
    {
        $me = $this->requireLogin();
        $post = (new PostModel())->find((int) ($_GET['id'] ?? 0));
        if (!$post || (int) $post['user_id'] !== (int) $me['id']) {
            flash('danger', 'You can only edit your own posts.');
            redirect(url());
        }
        $this->view('post/edit', ['post' => $post, 'title' => 'Edit Post']);
    }

    public function update(): void
    {
        $me = $this->requireLogin();
        $this->requirePost();
        $posts = new PostModel();
        $post = $posts->find((int) ($_POST['id'] ?? 0));
        if (!$post || (int) $post['user_id'] !== (int) $me['id']) {
            flash('danger', 'You can only edit your own posts.');
            redirect(url());
        }
        $content = clean($_POST['content'] ?? '');
        if ($content === '' || mb_strlen($content) > 1000) {
            flash('danger', 'Post cannot be empty or longer than 1000 characters.');
            redirect(url('post', 'edit', ['id' => $post['id']]));
        }
        $error = null;
        $newImage = upload_image($_FILES['image'] ?? [], $error);
        if ($error) {
            flash('danger', $error);
            redirect(url('post', 'edit', ['id' => $post['id']]));
        }
        $image = $post['image'];
        if ($newImage) {
            delete_image($image);
            $image = $newImage;
        } elseif (!empty($_POST['remove_image'])) {
            delete_image($image);
            $image = null;
        }
        $posts->update((int) $post['id'], $content, $image);
        flash('success', 'Post updated.');
        redirect(url());
    }

    public function delete(): void
    {
        $me = $this->requireLogin();
        $this->requirePost();
        $posts = new PostModel();
        $post = $posts->find((int) ($_POST['id'] ?? 0));
        if (!$post || (int) $post['user_id'] !== (int) $me['id']) {
            $this->respond(false, 'You can only delete your own posts.');
        }
        delete_image($post['image']);
        $posts->delete((int) $post['id']);
        $this->respond(true, 'Post deleted.');
    }

    public function like(): void
    {
        $me = $this->requireLogin();
        $this->requirePost();
        $postId = (int) ($_POST['id'] ?? 0);
        $reaction = clean($_POST['reaction'] ?? 'like');
        if (!(new PostModel())->find($postId)) {
            $this->respond(false, 'Post not found.');
        }
        $likes = new LikeModel();
        $res = $likes->react($postId, (int) $me['id'], $reaction);
        $this->respond(true, '', [
            'liked'         => $res['reacted'],
            'reaction_type' => $res['reaction_type'],
            'count'         => $res['count'],
            'top_reactions' => $res['top_reactions']
        ]);
    }

    public function share(): void
    {
        $me = $this->requireLogin();
        $this->requirePost();
        $postId = (int) ($_POST['post_id'] ?? 0);
        $content = clean($_POST['content'] ?? '');
        $posts = new PostModel();
        $orig = $posts->find($postId);
        if (!$orig) {
            $this->respond(false, 'Original post not found.');
        }
        $rootPostId = !empty($orig['shared_post_id']) ? (int) $orig['shared_post_id'] : (int) $orig['id'];
        $newId = $posts->share((int) $me['id'], $rootPostId, $content);

        if (is_ajax()) {
            $post = $posts->findFull($newId, (int) $me['id']);
            $post['comments'] = [];
            ob_start();
            require __DIR__ . '/../views/partials/post.php';
            $html = ob_get_clean();
            json_out(['ok' => true, 'message' => 'Shared to your feed!', 'html' => $html]);
        }
        $this->respond(true, 'Shared to your feed!');
    }
}
