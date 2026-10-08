<?php
class ProfileController extends Controller
{
    public function show(): void
    {
        $me = $this->requireLogin();
        $id = (int) ($_GET['id'] ?? $me['id']);
        $user = (new UserModel())->findById($id);
        if (!$user) {
            flash('danger', 'User not found.');
            redirect(url());
        }
        $posts = $this->attachComments((new PostModel())->byUser($id, (int) $me['id']));
        $follows = new FollowModel();
        $isFollowing = $follows->isFollowing((int) $me['id'], $id);
        $followerCount = $follows->followersCount($id);
        $followingCount = $follows->followingCount($id);
        $this->view('profile/show', [
            'user'           => $user,
            'posts'          => $posts,
            'isFollowing'    => $isFollowing,
            'followerCount'  => $followerCount,
            'followingCount' => $followingCount,
            'title'          => $user['full_name']
        ]);
    }

    public function edit(): void
    {
        $this->requireLogin();
        $this->view('profile/edit', ['title' => 'Edit Profile']);
    }

    public function update(): void
    {
        $me = $this->requireLogin();
        $this->requirePost();
        $fullName = clean($_POST['full_name'] ?? '');
        $bio = clean($_POST['bio'] ?? '');
        if ($fullName === '' || mb_strlen($fullName) > 100 || mb_strlen($bio) > 255) {
            flash('danger', 'Full name is required (max 100) and bio can be up to 255 characters.');
            redirect(url('profile', 'edit'));
        }
        $error = null;
        $newImage = upload_image($_FILES['profile_image'] ?? [], $error);
        if ($error) {
            flash('danger', $error);
            redirect(url('profile', 'edit'));
        }
        $image = $me['profile_image'];
        if ($newImage) {
            delete_image($image);
            $image = $newImage;
        }
        (new UserModel())->update((int) $me['id'], $fullName, $bio, $image);
        flash('success', 'Profile updated.');
        redirect(url('profile', 'show', ['id' => $me['id']]));
    }
}
