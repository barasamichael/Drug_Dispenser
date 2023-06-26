<?php

function generatePagination($currentPage, $totalPages, $url)
{
    $pagination = '';

    if ($totalPages > 1) {
        $pagination .= '<ul class="pagination">';

        if ($currentPage > 1) {
            $pagination .= '<li><a href="' . $url . '?page=' . ($currentPage - 1) . '">Previous</a></li>';
        }

        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $currentPage) {
                $pagination .= '<li class="active"><span>' . $i . '</span></li>';
            } else {
                $pagination .= '<li><a href="' . $url . '?page=' . $i . '">' . $i . '</a></li>';
            }
        }

        if ($currentPage < $totalPages) {
            $pagination .= '<li><a href="' . $url . '?page=' . ($currentPage + 1) . '">Next</a></li>';
        }

        $pagination .= '</ul>';
    }

    return $pagination;
}
?>