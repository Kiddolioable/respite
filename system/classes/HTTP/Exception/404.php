<?php defined('SYSPATH') OR die('No direct script access.');

class HTTP_Exception_404 extends Kohana_HTTP_Exception_404 {

    public function get_response()
    {
        $response = Response::factory()
            ->status(404)
            ->headers('Location', URL::site('errors/404'));

        return $response;
    }

}
