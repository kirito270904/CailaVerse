<?php
class Controller
{
    protected function view(string $name, array $data = []): void
    {
        $data['me'] = $this->currentUser();

        $toasts = [];
        $flash = get_flash();
        if ($flash) {
            $toasts[] = $flash;
        }
        foreach (($data['errors'] ?? []) as $error) {
            $toasts[] = ['type' => 'danger', 'message' => $error];
        }
        $data['toasts'] = $toasts;

        extract($data);
        require __DIR__ . '/../views/layouts/header.php';
        require __DIR__ . '/../views/' . $name . '.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    protected function currentUser(): ?array
    {
        static $user = false;
        if ($user === false) {
            $user = null;
            if (!empty($_SESSION['user_id'])) {
                $user = (new UserModel())->findById((int) $_SESSION['user_id']);
                if (!$user) {
                    unset($_SESSION['user_id']);
                } else {
                    (new UserModel())->touchActive((int) $user['id']);
                }
            }
        }
        return $user;
    }

    protected function requireLogin(): array
    {
        $user = $this->currentUser();
        if (!$user) {
            if (is_ajax()) {
                json_out(['ok' => false, 'message' => 'Please log in first.'], 401);
            }
            flash('warning', 'Please log in first.');
            redirect(url('auth', 'login'));
        }
        return $user;
    }

    protected function requirePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(url());
        }
        csrf_check();
    }

    protected function respond(bool $ok, string $message, array $extra = [], ?string $fallback = null): void
    {
        if (is_ajax()) {
            json_out(array_merge(['ok' => $ok, 'message' => $message], $extra));
        }
        if ($message !== '') {
            flash($ok ? 'success' : 'danger', $message);
        }
        redirect_back($fallback ?? url());
    }

    protected function attachComments(array $posts): array
    {
        $ids = array_column($posts, 'id');
        $comments = (new CommentModel())->forPosts($ids);
        foreach ($posts as &$post) {
            $post['comments'] = $comments[$post['id']] ?? [];
        }
        unset($post);
        return $posts;
    }
}
