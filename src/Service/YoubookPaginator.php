<?php

namespace App\Service;

use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class YoubookPaginator
{

    /** @var int  */
    private $currentPage = 1;

    /** @var int  */
    private $itemsPerPage = 10;

    /** @var int  */
    private $totalItens = 0;

    /** @var int  */
    private $pagesCount = 0;

    /** @var Query */
    private $query = null;

    /** @var PaginatorInterface */
    private $paginator;

    /** @var PaginationInterface */
    private $pagination;

    /**
     * YoubookPaginator constructor.
     * @param PaginatorInterface $paginator
     */
    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @param int $currentPage
     */
    public function setCurrentPage(int $currentPage): void
    {
        $this->currentPage = $currentPage;
    }

    /**
     * @return int
     */
    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    /**
     * @param int $itemsPerPage
     */
    public function setItemsPerPage(int $itemsPerPage): void
    {
        $this->itemsPerPage = $itemsPerPage;
    }

    /**
     * @return int
     */
    public function getTotalItens(): int
    {
        return $this->totalItens;
    }

    /**
     * @param int $totalItens
     */
    public function setTotalItens(int $totalItens): void
    {
        $this->totalItens = $totalItens;
    }

    /**
     * @return int
     */
    public function getPagesCount(): int
    {
        return $this->pagesCount;
    }

    /**
     * @param int $pagesCount
     */
    public function setPagesCount(int $pagesCount): void
    {
        $this->pagesCount = $pagesCount;
    }

    /**
     * @return Query
     */
    public function getQuery(): Query
    {
        return $this->query;
    }

    /**
     * @param Query|null $query
     */
    public function setQuery($query = null): void
    {
        $this->query = $query;
        if (!is_null($query) && $query instanceof Query) {
            $this->pagination = $this->paginator->paginate($query->getResult(), $this->currentPage, $this->itemsPerPage);
            $this->setTotalItens($this->pagination->getTotalItemCount());
            $this->setPagesCount(ceil($this->totalItens / $this->itemsPerPage));
        }
    }

    public function paginate()
    {
        if (!$this->pagination instanceof PaginationInterface) {
            return null;
        }

        return [
            'totalItems' => $this->totalItens,
            'totalPages' => $this->pagesCount,
            'page' => $this->currentPage,
            'count' => count($this->pagination->getItems()),
            'items' => $this->pagination->getItems()
        ];
    }



}