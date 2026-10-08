<?php
class SearchController extends Controller
{
    public function index(): void
    {
        $me = $this->requireLogin();
        $q = clean($_GET['q'] ?? '');
        $users = [];
        $posts = [];
        if ($q !== '') {
            $users = (new UserModel())->search($q, (int) $me['id']);
            $posts = $this->attachComments((new PostModel())->search($q, (int) $me['id']));
        }
        $this->view('search/index', ['q' => $q, 'users' => $users, 'posts' => $posts, 'title' => 'Search']);
    }
}
