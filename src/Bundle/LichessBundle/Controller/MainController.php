<?php

namespace Bundle\LichessBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MainController extends Controller
{
    private function settings()
    {
        return $this->get('lichess_user.settings');
    }

    public function toggleSoundAction()
    {
        $value = $this->settings()->toggle('sound', true);
        $this->get('doctrine.odm.mongodb.document_manager')->flush();

        return new Response($value ? 'on' : 'off');
    }

    public function boardColorAction(Request $request)
    {
        if (!$color = $request->request->get('color', false)) {
            throw new HttpException(400);
        }
        $this->settings()->set('color', $color);
        $this->get('doctrine.odm.mongodb.document_manager')->flush();

        return new Response($color);
    }

    public function aboutAction()
    {
        return new RedirectResponse($this->generateUrl('lichess_wiki_show', array('slug' => 'Lichess-Wiki')));
    }
}
