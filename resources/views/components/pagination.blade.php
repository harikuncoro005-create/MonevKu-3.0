<style type="text/css">
    .page-link, .page-item {
        box-shadow: none !important;
    }
</style>

<?php if($total_records == 0 || count($records) == 0) {
    echo '<div class="container d-none">';
} else {
    echo '<div class="container">';
}
?>

    <nav>
        <div class="pagination justify-content-center my-3">
        <?php

            if($page == 1){
                echo '<li class="page-item disabled"><a class="page-link" href="#">First</a></li>';
                echo '<li class="page-item disabled"><a class="page-link" href="#"><span aria-hidden="true">&laquo;</span></a></li>';
            } else {
                $link_prev = ($page > 1) ? $page - 1 : 1;
                echo '<li class="page-item"><a class="page-link" number="1" href="?page=1">First</a></li>';
                echo '<li class="page-item"><a class="page-link" number="'.$link_prev.'" href="" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
            }

            for($i = $start_number; $i <= $end_number; $i++){
                $link_active = ($page == $i)? ' active' : '';
                echo '<li class="page-item '.$link_active.'"><a class="page-link" number="'.$i.'" href="">'.$i.'</a></li>';
              }

            if($page == $jumlah_page){
                echo '<li class="page-item disabled"><a class="page-link" href="#"><span aria-hidden="true">&raquo;</span></a></li>';
                echo '<li class="page-item disabled"><a class="page-link" href="#">Last</a></li>';
            } else {
                $link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
                echo '<li class="page-item"><a class="page-link" number="'.$link_next.'" href="" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
                echo '<li class="page-item"><a class="page-link" number="'.$jumlah_page.'" href="">Last</a></li>';
            }
        
        ?>
        </div>
    </nav>
</div>

<script>
    $('.page-link').off('click').on('click', function(e){
        e.preventDefault()
        var url = new URL($(location).attr('href'))
        var full_url = url.search

        var parameter = url.search
          .replace('?', '')
          .split('&')
          .map(param => param.split('='))
          .reduce((values, [ key, value ]) => {
                values[ key ] = decodeURIComponent((value + '').replace(/\+/g, '%20'))
                return values
            }, {})

        var search_params = url.searchParams;
        if (search_params.has('page')) {
            search_params.set('page', $(this).attr('number'));
            parameter['page'] = $(this).attr('number')
        } else {
            search_params.set('page', $(this).attr('number'));
            parameter['page'] = $(this).attr('number')
        }

        var new_url = url.toString();
        history.pushState({}, null, new_url);

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ $url_view }}",
            method: "POST",
            data: {parameter: parameter},
            async:true,
            dataType:"json",
            beforeSend: function() {
                $.ytLoad('start')
            },
            success:function(res) {
                $('#view-content').html(res.html)
                $('#pagination').html(res.pagination)
                $.ytLoad('complete')   
            }
        })
    })
</script>