<?php
class CommentController extends Controller
{
    public function store(): void
    {
        $me = $this->requireLogin();
        $this->requirePost();
        $postId = (int) ($_POST['post_id'] ?? 0);
        $content = clean($_POST['content'] ?? '');

        if (!(new PostModel())->find($postId)) {
            $this->respond(false, 'Post not found.');
        }
        if ($content === '' || mb_strlen($content) > 500) {
            $this->respond(false, 'Comment cannot be empty or longer than 500 characters.');
        }

        $comments = new CommentModel();
        $id = $comments->create($postId, (int) $me['id'], $content);

        if (is_ajax()) {
            $comment = $comments->findWithUser($id);
            ob_start();
            require __DIR__ . '/../views/partials/comment.php';
            $html = ob_get_clean();
            json_out(['ok' => true, 'message' => 'Comment added.', 'html' => $html]);
        }
        $this->respond(true, 'Comment added.');
    }

    public function edit(): void
    {
        $me = $this->requireLogin();
        $comment = (new CommentModel())->find((int) ($_GET['id'] ?? 0));
        if (!$comment || (int) $comment['user_id'] !== (int) $me['id']) {
            flash('danger', 'You can only edit your own comments.');
            redirect(url());
        }
        $this->view('post/edit_comment', ['comment' => $comment, 'title' => 'Edit Comment']);
    }

    public function update(): void
    {
        $me = $this->requireLogin();
        $this->requirePost();
        $comments = new CommentModel();
        $comment = $comments->find((int) ($_POST['id'] ?? 0));
        if (!$comment || (int) $comment['user_id'] !== (int) $me['id']) {
            flash('danger', 'You can only edit your own comments.');
            redirect(url());
        }
        $content = clean($_POST['content'] ?? '');
        if ($content === '' || mb_strlen($content) > 500) {
            flash('danger', 'Comment cannot be empty or longer than 500 characters.');
            redirect(url('comment', 'edit', ['id' => $comment['id']]));
        }
        $comments->update((int) $comment['id'], $content);
        flash('success', 'Comment updated.');
        redirect(url());
    }

    public function delete(): void
    {
        $me = $this->requireLogin();
        $this->requirePost();
        $comments = new CommentModel();
        $comment = $comments->find((int) ($_POST['id'] ?? 0));
        if (!$comment || (int) $comment['user_id'] !== (int) $me['id']) {
            $this->respond(false, 'You can only delete your own comments.');
        }
        $comments->delete((int) $comment['id']);
        $this->respond(true, 'Comment deleted.');
    }
}
