<?php
/**
 * Piece service interface.
 */

namespace App\Service;

use App\Entity\Piece;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface PieceServiceInterface.
 */
interface PieceServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page, array $filters): PaginationInterface;

    /**
     * Get a random piece from the database.
     *
     * @return Piece
     */
    public function getRandomPiece(): Piece;

    /**
     * Save entity.
     *
     * @param Piece $piece Piece entity
     */
    public function save(Piece $piece): void;

    /**
     * Delete entity.
     *
     * @param Piece $piece Piece entity
     */
    public function delete(Piece $piece): void;

    /**
     * Can Piece object be deleted?
     *
     * @param Piece $piece Piece entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Piece $piece): bool;

}
