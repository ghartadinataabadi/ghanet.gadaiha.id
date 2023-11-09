<script>
let images = [];
//globals
const id = parseInt($('.form-input').find('[name="id"]').val());
const initForm = ()=>{
  return new Promise((reject, resolve)=>{
      var id = $('[name="id"]').val()
      if(id){
          $.ajax({
              type : 'GET',
              url : "<?php echo base_url('api/news/contents/show/');?>/"+id,
              dataType:"JSON",
              success : function(res){    
                $('[name="title"]').val(res.data.title);
                $('[name="description"]').val(res.data.description);
                $('[name="id"]').val(res.data.id);
                $('[name="summary"]').val(res.data.summary);
                $('[name="id_news_category"]').val(res.data.id_news_category);
                images = res.data.attachments;
              },
              error: function (jqXHR, textStatus, errorThrown){
                reject('error')
              },
              complete:function(){
                CKEDITOR.replace( 'description');
                buildImage();
              }
          });  
      }else{
          CKEDITOR.replace( 'description');
      }
  })
}

const fileHandler = (event)=>{
  let attachment = event.target.files[0];
  var formData = new FormData(); // Currently empty'
  formData.append('attachment', attachment)
  $.ajax({
            type : 'POST',
            url : "<?php echo base_url('api/news/contents/upload');?>",
            data :formData,
            processData: false,
            contentType: false,
            typeData:"JSON",
            success : function(res){    
              const data = JSON.parse(res).data;  
              images.push(data);
              buildImage();
            },
            error: function (jqXHR, textStatus, errorThrown){
           }
      });  
}

const buildImage = ()=>{
  $('[data-template="attachment-cloned"]').remove();
  images.forEach(image=>{
    let template = $('[data-template="attachment"]').clone();
    template.removeClass('d-none');
    template.attr('data-template','attachment-cloned');
    template.find('input').val(image.id);
    template.find('button').attr('onclick',`delete_attachment(${image.id})`);
    template.find('input').attr('name', `attachments[${image.id}]`);
    if(image.file_type === 'IMAGE'){
      template.find('img').attr('src', `<?php echo base_url('storage/news');?>/${image.file_name}`);
      template.find('a').attr('href', `<?php echo base_url('storage/news');?>/${image.file_name}`);
      template.find('a').text('download');
    }else{
       template.find('a').attr('href', `<?php echo base_url('storage/news');?>/${image.file_name}`);
       template.find('a').text(image.file_name);
       template.find('img').addClass('d-none');
    }
    $('.data-attachment').append(template);
  })
}

const delete_attachment = (id)=>{
  images = images.filter(image=>image.id != id);
  $.ajax({
        type : 'POST',
        url : "<?php echo base_url('api/news/contents/delete_attachment');?>/"+id,
        dataType :'JSON',
        success : function(res){  
          buildImage();
        },
        error: function (jqXHR, textStatus, errorThrown){
        }
  });  
}

document.addEventListener('DOMOnload', initForm() );

const submitHandler = (event)=>{
  event.preventDefault();
  let formData = new FormData(event.target);
  var data = CKEDITOR.instances.description.getData();
  var url = $('[name="id"]').val() ? 
  "<?php echo base_url('api/news/contents/update');?>"
  :"<?php echo base_url('api/news/contents/insert');?>";
  formData.append('description', data);
  $.ajax({
        type : 'POST',
        url : url,
        data :formData,
        processData: false,
        contentType: false,
        typeData:"JSON",
        success : function(res){    
          let response = JSON.parse(res);
          if(response.status === 200){
            location.href = '<?php echo base_url('datamaster/news');?>'
          }else{

          }
        },
        error: function (jqXHR, textStatus, errorThrown){
        }
  });  
}

</script>
