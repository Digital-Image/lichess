<?php

namespace Application\ForumBundle;

use Ornicar\AkismetBundle\Akismet\AkismetInterface;
use Application\ForumBundle\Document\Post;
use Application\ForumBundle\Document\Topic;

class Akismet
{
    protected $akismet;

    public function __construct(AkismetInterface $akismet)
    {
        $this->akismet         = $akismet;
    }

    public function isPostSpam(Post $post)
    {
        return $this->isSpamLike($post) || $this->akismet->isSpam($this->getPostData($post));
    }

    public function isTopicSpam(Topic $topic)
    {
        return $this->isSpamLike($topic->getFirstPost()) || $this->akismet->isSpam($this->getTopicData($topic));
    }

    protected function isSpamLike(Post $post)
    {
        $simplified = str_replace(array("\r\n", "\n", " "), "", strtolower($post->getMessage()));
        $hasBr = strpos($simplified, '<br') !== false;
        $hasHref = strpos($simplified, '<ahref') !== false;

        return $hasBr || $hasHref;
    }

    protected function getPostData(Post $post)
    {
        return array(
            'comment_type'    => 'comment',
            'comment_author'  => $post->getAuthorName(),
            'comment_content' => $post->getMessage()
        );
    }

    protected function getTopicData(Topic $topic)
    {
        return array(
            'comment_type'    => 'comment',
            'comment_author'  => $topic->getFirstPost()->getAuthorName(),
            'comment_content' => $topic->getSubject().' '.$topic->getFirstPost()->getMessage()
        );
    }
}
