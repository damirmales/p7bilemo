<?php


namespace App\Manager;


class Paginate
{
    private $paginator;
    private $data;
    private $request;

    /**
     * Paginate constructor.
     * @param $paginator
     * @param $data
     * @param $request
     */
    public function __construct($paginator, $data, $request)
    {
        $this->paginator = $paginator;
        $this->data = $data;
        $this->request = $request;
    }

    /**
     * @param $limitPerPage
     * @return mixed
     */
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