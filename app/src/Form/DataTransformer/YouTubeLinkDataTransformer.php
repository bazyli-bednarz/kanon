<?php
/**
 * Tags data transformer.
 */

namespace App\Form\DataTransformer;

use App\Entity\Tag;
use App\Service\PieceServiceInterface;
use App\Service\TagServiceInterface;
use Doctrine\Common\Collections\Collection;
use DoctrineExtensions\Query\Mysql\Pi;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class TagsDataTransformer.
 *
 * @implements DataTransformerInterface<mixed, mixed>
 */
class YouTubeLinkDataTransformer implements DataTransformerInterface
{
    /**
     * Transform YouTube link to normalized version.
     *
     * @param Collection<int, Tag> $value Tags entity collection
     *
     * @return string Result
     */
    public function transform($value): string
    {
        return 'https://youtu.be/'.$value;
    }

    /**
     * Reverse transform Youtube link.
     *
     * @param string $value String of tag names
     *
     * @return string Result
     */
    public function reverseTransform($value): string
    {
        if ($value === '') {
            return '';
        }
        $generalPattern = "/^(https?\:\/\/)?((www\.)?youtube\.com|youtu\.be)\/.+$/";
        if (!preg_match($generalPattern, $value)) {
            return '';
        }
        $url = $this->getYoutubeIdFromUrl($value);
        $match = [];
        preg_match('/t=([0-9]+)/', $value, $match);
        if (count($match) == 0) {
            $timestamp = '0';
        } else {
            $timestamp = substr($match[0], 2);
        }

        return $url.'?start='.$timestamp;
    }

    /**
     * Get Youtube video ID from URL
     *
     * @param string $url
     * @return mixed Youtube video ID or FALSE if not found
     */
    public function getYoutubeIdFromUrl(string $url): ?string {
        $parts = parse_url($url);
        if(isset($parts['query'])){
            parse_str($parts['query'], $qs);
            if(isset($qs['v'])){
                return $qs['v'];
            }else if(isset($qs['vi'])){
                return $qs['vi'];
            }
        }
        if(isset($parts['path'])){
            $path = explode('/', trim($parts['path'], '/'));
            return $path[count($path)-1];
        }
        return false;
    }
}
