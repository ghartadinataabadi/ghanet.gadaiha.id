<script>

function init(){
    const id = $('[name="id"]').val();
    $.ajax({
        type : 'GET',
        url : "<?php echo base_url("api/news/contents"); ?>/show/"+id,
        dataType : "json",
        success : function(res){  
            let data = res.data;
            const template = $('[data-template="news"]').clone();
            template.removeClass('d-none');
            template.attr('data-template','news-cloned');
            template.find('h3').text(data.title);
            template.find('.kt-widget19__text').html(data.description);
            if(data.cover){
                template.find('.kt-portlet-fit--sides').css('background-image', `url(<?php echo base_url();?>storage/news/${data.cover})`);
            }
           if(data.category){
                template.find('.category').text(data.category.name);
            }
            if(data.attachments){
                data.attachments.forEach(data=>{
                    if(data.extention === 'VND.OPENXMLFORMATS-OFFICEDOCUMENT.WORDPROCESSINGML.DOCUMENT'){
                           let file =  $('[data-template="word"]').clone();
                           file.find('p').text(data.file_name);
                           file.removeClass('d-none');
                           file.attr('data-template','file-cloned');
                           file.find('a').attr('href',`<?php echo base_url();?>storage/news/${data.file_name}`);
                           template.find('.attachments').append(file);
                    }else if(data.file_type === 'IMAGE'){
                           let file =  $('[data-template="image"]').clone();
                           file.removeClass('d-none');
                           file.find('p').text(data.file_name);
                           file.attr('data-template','file-cloned');
                           file.find('img').attr('src',`<?php echo base_url();?>storage/news/${data.file_name}`);
                           file.find('a').attr('href',`<?php echo base_url();?>storage/news/${data.file_name}`);
                           template.find('.attachments').append(file);
                    }else if(data.extention === 'VND.MS-EXCEL'){
                        let file =  $('[data-template="excel"]').clone();
                           file.removeClass('d-none');
                           file.find('p').text(data.file_name);
                           file.attr('data-template','file-cloned');
                           file.find('a').attr('href',`<?php echo base_url();?>storage/news/${data.file_name}`);
                           template.find('.attachments').append(file);
                    }else if(data.extention === 'PDF'){
                        let file =  $('[data-template="pdf"]').clone();
                           file.removeClass('d-none');
                           file.find('p').text(data.file_name);
                           file.attr('data-template','file-cloned');
                           file.find('a').attr('href',`<?php echo base_url();?>storage/news/${data.file_name}`);
                           template.find('.attachments').append(file);
                    }else if(data.extention === 'VND.OPENXMLFORMATS-OFFICEDOCUMENT.PRESENTATIONML.PRESENTATION'){
                        let file =  $('[data-template="powerpoint"]').clone();
                           file.removeClass('d-none');
                           file.find('p').text(data.file_name);
                           file.attr('data-template','file-cloned');
                           file.find('a').attr('href',`<?php echo base_url();?>storage/news/${data.file_name}`);
                           template.find('.attachments').append(file);
                    }else{
                           let file =  $('[data-template="file"]').clone();
                           file.removeClass('d-none');
                           file.find('p').text(data.file_name);
                           file.attr('data-template','file-cloned');
                           file.find('a').attr('href',`<?php echo base_url();?>storage/news/${data.file_name}`);
                           template.find('.attachments').append(file);
                    }
                    // switch(data.extention){
                    //     case 'VND.OPENXMLFORMATS-OFFICEDOCUMENT.WORDPROCESSINGML.DOCUMENT':{
                    //        let file =  $('[data-template="word"]').clone();
                    //        file.removeClass('d-none');
                    //        file.attr('data-template','file-cloned');
                    //        file.find('a').attr('href',`<?php echo base_url();?>storage/news/${data.file_name}`);
                    //        template.find('.attachments').append(file);
                    //     }
                    //     case 'PNG':{
                    //        let file =  $('[data-template="image"]').clone();
                    //        file.removeClass('d-none');
                    //        file.attr('data-template','file-cloned');
                    //        file.find('img').attr('src',`<?php echo base_url();?>storage/news/${data.file_name}`);
                    //        file.find('a').attr('href',`<?php echo base_url();?>storage/news/${data.file_name}`);
                    //        template.find('.attachments').append(file);
                    //     }
                    //     default:{
                    //         let file =  $('[data-template="file"]').clone();
                    //        file.removeClass('d-none');
                    //        file.attr('data-template','file-cloned');
                    //        file.find('a').attr('href',`<?php echo base_url();?>storage/news/${data.file_name}`);
                    //        template.find('.attachments').append(file);
                    //     }
                    // }
                })
            }
            $('.append-template').append(template);
        },
        error: function (jqXHR, textStatus, errorThrown){
       }
    });  
}


jQuery(document).ready(function() { 
    init()
});

</script>
