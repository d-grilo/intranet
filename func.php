<?php

function paginate($dbc, $query, $page, $limit, $pageurl, $search_string = NULL, $category_id = NULL) {

    $data = mysqli_query($dbc, $query);
    $total_records = mysqli_num_rows($data); //$row['count'];
    $total_pages = ceil($total_records / $limit);

    $previous_page = $page - 1;
    if ($page == 1)
        $previous_page = 1;

    $next_page = $page + 1;
    if ($next_page == $total_pages + 1)
        $next_page = $page;

    # Só mostrar a paginação caso exista mais do que uma página
    if($total_pages > 1) {
        # Retroceder
        if(isset($search_string))
            $pagLink = '<ul class="pagination"><li><a href=' . $pageurl .'.php?page=' . $previous_page . '&search_string=' . $search_string . '>' . '&laquo;' . '</a></li>';
        else if(isset($category_id))
            $pagLink = '<ul class="pagination"><li><a href=' . $pageurl .'.php?page=' . $previous_page . '&category_id=' . $category_id . '>' . '&laquo;' . '</a></li>';
        else
            $pagLink = '<ul class="pagination"><li><a href=' . $pageurl .'.php?page=' . $previous_page . '>' . '&laquo;' . '</a></li>';

        # Lista as páginas
        for ($i = 1; $i <= $total_pages; $i++) {
            if ($page == $i)
                if(isset($search_string))
                    $pagLink .= '<li class="active"><a href=' . $pageurl .'.php?page=' . $i . '&search_string=' . $search_string . '>' . $i . '</a></li>';
                else if(isset($category_id))
                    $pagLink .= '<li class="active"><a href=' . $pageurl .'.php?page=' . $i . '&category_id=' . $category_id . '>' . $i . '</a></li>';
                else
                    $pagLink .= '<li class="active"><a href=' . $pageurl .'.php?page=' . $i . '>' . $i . '</a></li>';
            else
                if(isset($search_string))
                    $pagLink .= '<li><a href=' . $pageurl .'.php?page=' . $i . '&search_string=' . $search_string . '>' . $i . '</a></li>';
                else if(isset($category_id))
                    $pagLink .= '<li><a href=' . $pageurl .'.php?page=' . $i . '&category_id=' . $category_id . '>' . $i . '</a></li>';
                else
                    $pagLink .= '<li><a href=' . $pageurl .'.php?page=' . $i . '>' . $i . '</a></li>';
        }

        # Avançar
        if(isset($search_string))
            echo $pagLink . '<li><a href=' . $pageurl .'.php?page=' . $next_page . '&search_string=' . $search_string . '>' . '&raquo;' . '</a></li></ul>';
        else if(isset($category_id))
            echo $pagLink . '<li><a href=' . $pageurl .'.php?page=' . $next_page . '&category_id=' . $category_id . '>' . '&raquo;' . '</a></li></ul>';
        else
            echo $pagLink . '<li><a href=' . $pageurl .'.php?page=' . $next_page . '>' . '&raquo;' . '</a></li></ul>';
    }

}

