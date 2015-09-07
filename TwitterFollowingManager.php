<?php

require_once('TwitterAPIClient.php');

class TwitterFollowingManager {
    private $client;

    public function __construct($oauth, $userName) {
        $this->client = new TwitterAPIClient($oauth, $userName);
    }

    public function backupFollowing($backupListId) {
        $ids = $this->client->getFollowingIds();

        $idChunks = array_chunk($ids, 100);
        foreach ($idChunks as $chunk) {
            $response = $this->twitterTool->addListMembers($backupListId, $chunk);
            var_dump($response);
        }
    }

    public function followingCount() {
        return count($this->client->getFollowingIds());
    }
}
