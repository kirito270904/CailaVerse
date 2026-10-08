<?php
class FollowController extends Controller
{
    public function toggle(): void
    {
        $me = $this->requireLogin();
        $this->requirePost();

        $targetId = (int) ($_POST['user_id'] ?? 0);
        if ($targetId <= 0 || $targetId === (int) $me['id']) {
            $this->respond(false, 'Invalid user.');
        }

        $target = (new UserModel())->findById($targetId);
        if (!$target) {
            $this->respond(false, 'User not found.');
        }

        $follows = new FollowModel();
        $following = $follows->toggle((int) $me['id'], $targetId);
        $count = $follows->followersCount($targetId);

        $msg = $following
            ? 'Connected with ' . $target['full_name'] . '!'
            : 'Disconnected from ' . $target['full_name'] . '.';

        $this->respond(true, $msg, [
            'following' => $following,
            'count'     => $count,
            'user_id'   => $targetId
        ]);
    }
}
