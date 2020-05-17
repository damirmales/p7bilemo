<?php


namespace App\Manager;


class Paginate
{
    private $paginator;
    private $data;
    private $request;

    public function __construct($paginator, $data, $request)
    {
        $this->paginator = $paginator;
        $this->data = $data;
        $this->request = $request;
    }

    public function pagination($limitPerPage)
    {
        $pagineData = $this->paginator->paginate(
            $this->data,
            $this->request->query->getInt('page', 1),
            $limitPerPage/*limit per page*/
        );

        return $pagineData;
    }

}