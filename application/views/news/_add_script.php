<script>
let images = [];
//globals
const initForm = ()=>{
  $.ajax({
      type:"GET",
      url : "<?php echo base_url('api/news/contents');?>/",
      dataType :'JSON',
      success : function(res){  
        res.data.forEach(news=>{
          const template = $('[data-template="news"]').clone();
          template.removeClass('d-none');
          template.attr('data-template','news-cloned');
          template.find('a').text(news.title);
          template.find('a').attr('href', `<?php echo base_url('news/detail');?>/${news.id}`)
          template.find('.summary').text(news.summary);
          if(news.cover){
            template.find('img').attr('src',`<?php echo base_url('storage/news');?>/${news.cover}`)
          }else{
            template.find('img').addClass('d-remove');
          }
          $('.news-template').append(template);
        })
      },
  });  
}

document.addEventListener('DOMOnload', initForm());

</script>
