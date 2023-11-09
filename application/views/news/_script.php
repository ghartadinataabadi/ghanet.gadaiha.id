<script>

function init(){
    const title = $('[name="title"]').val();
    const id_news_category = $('[name="id_news_category"]').val();
    $.ajax({
        type : 'GET',
        url : "<?php echo base_url("api/news/contents"); ?>",
        dataType : "json",
        data:{limit:30, title, id_news_category},
        success : function(res){  
            $('[data-template="news-cloned"]').remove();
            if(res.data.length > 0){
                res.data.forEach(data=>{
                    const template = $('[data-template="news"]').clone();
                    template.removeClass('d-none');
                    template.attr('data-template','news-cloned');
                    template.find('h3').text(data.title);
                    template.find('.category').text(data.category);
                    template.find('.kt-widget19__text').text(data.summary);
                    if(data.cover){
                        template.find('.kt-portlet-fit--sides').css('background-image', `url(<?php echo base_url();?>storage/news/${data.cover})`);
                    }
                    template.find('a').attr('href',`<?php echo base_url('news/detail');?>/${data.id}`)
                    $('.append-template').append(template);
                })
            }        
        },
        error: function (jqXHR, textStatus, errorThrown){
       }
    });  
}


jQuery(document).ready(function() { 
    init()
});

</script>
